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
		
		createDirectory('./assets/uploads/stripe');
		$fp = fopen('./assets/uploads/stripe/stripe.txt', 'a');
		fwrite($fp, date('d-m-Y H:i:s').'---'.$eventtype.PHP_EOL);
		fwrite($fp, json_encode($event).PHP_EOL);
		fclose($fp);
		
		if($eventtype == 'payment_intent.succeeded'){
			$paymentintent = $event->data->object;
			$this->action('1', $paymentintent->id, ($paymentintent->subscription ? $paymentintent->subscription : ''));
		}elseif($eventtype == 'invoice.paid'){
			$invoicepaid = $event->data->object;
			$this->action('2', '', '', $invoicepaid);
		}elseif($eventtype == 'customer.subscription.updated'){
			$subscriptionupdated = $event->data->object;
			$this->action('3', '', '', $subscriptionupdated);
		}

		http_response_code(200);
	}
	
	public function action($type, $paymentintentid='', $subscriptionid='', $result='')
	{
	    if($type=='1'){
    		$condition = ['stripe_paymentintent_id' =>  $paymentintentid]+($subscriptionid!='' ? ['stripe_subscription_id' =>  $subscriptionid] : []);
    		
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
    						
    						$schedulepayments = $this->db->table('payment')->where(['payment_id' => $paymentid, 'type' => '3'])->get()->getResultArray();
    						if(!empty($schedulepayments)){
    							foreach($schedulepayments as $schedulepayment){
    								$bookingdetails = $this->db->table('booking_details')->where(['booking_id' => $booking, 'stall_id' => $schedulepayment['plan_id']])->get()->getRowArray();
    								$this->db->table('payment')->update(['booking_id' => $booking, 'booking_details_id' => $bookingdetails['id'], 'status' => '1', 'stripe_data' => ''], ['id' => $schedulepayment['id']]);
    								$this->db->table('booking_details')->update(['payment_id' => $schedulepayment['id'], 'subscription_status' => '1'], ['booking_id' => $booking, 'stall_id' => $schedulepayment['plan_id']]);
    							}
    						}
    						
    						$this->db->table('payment')->update(['booking_id' => $booking, 'status' => '1', 'stripe_data' => ''], ['id' => $paymentid]);
    						checkoutEmailSms($booking);
    					}elseif(isset($data['page']) && $data['page']=='myaccountevent'){
    						$userdetail 			= getSiteUserDetails($paymentuserid);
    						$userid 				= $userdetail['id'];
    						$usersubscriptioncount 	= $userdetail['producer_count'];
    						
    						$this->users->action(['user_id' => $userid, 'actionid' => $userid, 'producercount' => $usersubscriptioncount+1]);
    						$this->db->table('payment')->update(['status' => '1', 'stripe_data' => ''], ['id' => $paymentid]);
    					}elseif(isset($data['page']) && $data['page']=='myaccountfacility'){
    						$data['type'] 	= '2';
    						$data['name'] 	= $data['facility_name'];
    						
    						$this->event->action($data);
    						$this->db->table('payment')->update(['status' => '1', 'stripe_data' => ''], ['id' => $paymentid]);
    					}
    				}
    			}elseif($paymenttype=='2'){
    				$this->db->table('users')->where(['id' => $paymentuserid])->update(['subscription_id' => $paymentid]);
    				$this->db->table('payment')->update(['status' => '1', 'stripe_data' => ''], ['id' => $paymentid]);
    			}
    		}
	    }elseif($type=='2'){
	        if($result!=''){
				$start = date("Y-m-d H:i:s", $result->lines->data[0]->period->start);
				$end = date("Y-m-d H:i:s", $result->lines->data[0]->period->end);
				
	            $subscription = $this->db->table('payment')->where(['stripe_subscription_id' => $result->subscription])->get()->getRowArray();
				
	            if($subscription){					
	                $paymentData = array(
	                    'payment_id' 				=> $subscription['payment_id'],
	                    'booking_id' 				=> $subscription['booking_id'],
	                    'booking_details_id' 		=> $subscription['booking_details_id'],
    					'user_id' 					=> $subscription['user_id'],
    					'name' 						=> $subscription['name'],
    					'email' 					=> $subscription['email'],
    					'amount' 					=> ($result->amount_paid)/100,
    					'currency' 					=> $result->currency,
    					'stripe_paymentintent_id' 	=> $result->payment_intent,
    					'stripe_subscription_id' 	=> $result->subscription,
    					'stripe_scheduled_id' 	    => $subscription['stripe_scheduled_id'],
    					'stripe_payment_method_id' 	=> $subscription['stripe_payment_method_id'],
    					'plan_id'					=> $subscription['plan_id'],
    					'plan_interval' 			=> $subscription['plan_interval'],
    					'plan_period_start' 		=> $start,
    					'plan_period_end' 			=> $end,
    					'type' 						=> $subscription['type'],
    					'status' 					=> '1',
    					'created' 					=> date("Y-m-d H:i:s")
    				);
					
    				if($subscription['type']=='2' && $start!=$subscription['plan_period_start'] && $end!=$subscription['plan_period_end']){
						$this->db->table('payment')->insert($paymentData);
						$paymentinsertid = $this->db->insertID();
						
    				    $this->db->table('users')->where(['id' => $subscription['user_id']])->update(['subscription_id' => $paymentinsertid]);
    				}elseif($subscription['type']=='3'){
						$this->db->table('payment')->insert($paymentData);
						$paymentinsertid = $this->db->insertID();
						
    				    $this->db->table('booking_details')->where(['id' => $subscription['booking_details_id']])->update(['subscription_status' => '1', 'payment_id' => $paymentinsertid]);
    				}
	            }
	        }
	    }elseif($type=='3'){
	        $scheduledsubscription = $this->db->table('payment')->where(['stripe_scheduled_id' => $result->schedule, 'type' => '3', 'status' => '1'])->get()->getRowArray();
            if($scheduledsubscription){
                $this->db->table('payment')->where(['id' => $scheduledsubscription['id']])->update(['stripe_subscription_id' => $result->id]);
            }
	    }
	}
}