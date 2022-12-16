<?php

namespace App\Controllers\Common;

use App\Controllers\BaseController;
use App\Models\Stripe as StripeModel;
use App\Models\Event;
use App\Models\Booking;
use App\Models\Users;

class Stripe extends BaseController
{
    public function __construct()
    {
		$this->db 		= db_connect();
		$this->stripe 	= new StripeModel();	
		$this->event 	= new Event();
		$this->booking 	= new Booking();
		$this->users 	= new Users();
    }
	
	public function webhook()
	{	
		$payload = @file_get_contents('php://input');
		$event = null;

		try {
			$event = \Stripe\Event::constructFrom(
				json_decode($payload, true)
			);
		} catch(\UnexpectedValueException $e) {
			http_response_code(400);
			exit();
		}
		
		$eventtype = $event->type;
		if($eventtype = 'payment_intent.succeeded'){
			$paymentIntent = $event->data->object;
			
			$fp = fopen('./assets/uploads/stripe/stripe.txt', 'a');
			fwrite($fp, json_encode($paymentIntent).PHP_EOL);
			fclose($fp);
			
			$this->action('1', $paymentIntent->id);
		}

		http_response_code(200);
	}
	
	public function action($type, $id)
	{
		if($type=='1') $condition = ['stripe_paymentintent_id' =>  $id];
		$payment = $this->db->table('payment')->where($condition)->get()->getRowArray();
		
		if($payment){
			$paymentid 		= $payment['id'];
			$paymentuserid 	= $payment['user_id'];
			$paymenttype 	= $payment['type'];
			$data 			= $payment['stripe_data']!='' ? json_decode($payment['stripe_data'], true) : '';
			
			if($paymenttype=='1'){	
				if($data!=''){			
					if(isset($data['page']) && $data['page']=='checkout'){
						$booking = $this->booking->action($data+['paymentid' => $paymentid, 'paidunpaid' => 1]);
						$this->db->table('payment')->update(['booking_id' => $booking, 'status' => '1'], ['id' => $paymentid]);
						checkoutEmailSms($booking);
					}elseif(isset($data['page']) && $data['page']=='myaccountevent'){
						$userdetail 			= getSiteUserDetails($paymentuserid);
						$userid 				= $userdetail['id'];
						$usersubscriptioncount 	= $userdetail['producer_count'];
						
						$this->users->action(['user_id' => $userid, 'actionid' => $userid, 'producercount' => $usersubscriptioncount+1]);
						$this->db->table('payment')->update(['status' => '1'], ['id' => $paymentid]);
					}elseif(isset($data['page']) && $data['page']=='myaccountfacility'){
						$data['type'] 	= '2';
						$data['name'] 	= $data['facility_name'];
						
						$this->event->action($data);
						$this->db->table('payment')->update(['status' => '1'], ['id' => $paymentid]);
					}
				}
			}elseif($paymenttype=='2'){
				$this->db->table('payment')->update(['status' => '1'], ['id' => $paymentid]);
				$this->db->table('users')->where(['id' => $paymentuserid])->update(['subscription_id' => $paymentid]);
			}
		}
	}
}