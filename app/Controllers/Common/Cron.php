<?php

namespace App\Controllers\Common;

use App\Controllers\BaseController;
use App\Models\Booking;
use App\Models\Stripe;

class Cron extends BaseController
{
    public function __construct()
    {
		$this->db = db_connect();
		$this->booking = new Booking();	
		$this->stripe = new Stripe();	
    }
	
	public function cartremoval()
	{	
		$ip 		= 	$this->request->getIPAddress();
		$datetime 	= 	date("Y-m-d H:i:s");
		
		$cart 		= 	$this->db->table('cart')
						->select('max(datetime) as datetime, user_id')
						->groupBy('user_id', 'desc')
						->having('DATE_ADD(datetime, INTERVAL 30 MINUTE) <=', $datetime)
						->get()
						->getResultArray();
		
		if(count($cart) > 0){
			foreach($cart as $data){
				$this->db->table('cart')->delete(['user_id' => $data['user_id']]);
			}
		}
		
		die;
	}

	public function bookingenddate()
	{	
		$date			= date('Y-m-d');

		$booking = $this->db->table('booking_details bd')
						->join('booking b', 'b.id = bd.booking_id', 'left')
						->select('bd.stall_id, b.check_out checkout')
						->where(['b.check_out'=> $date])
						->get()
						->getResultArray();
						
		if(count($booking) > 0){		
			foreach($booking as $booking){  
				$result  = $this->booking->updatedata(['stallid' => $booking['stall_id'], 'lockunlock' => '0', 'dirtyclean' => '0' ]);
			}
		}
		die;
	}
	
	public function bookingsubscriptionstall()
	{	
		$datetime	= date('Y-m-d H:i:s');
		$fdatetime	= date('Y-m-d H:i:s', strtotime('+6 hours'));
		$subquery 	= "(select max(id) from payment p1 where p1.type='3' group by p1.stripe_payment_method_id)";
		
		$payments 	= 	$this->db->table('payment p')
						->where(['p.type'=> '3', 'p.plan_period_end <' => $datetime])
						->where('p.id in '.$subquery)
						->groupBy('p.stripe_payment_method_id')
						->get()
						->getResultArray();
		
		foreach($payments as $payment){
			if($payment['plan_period_end'] < $fdatetime && $payment['booking_details_id']!=''){
				$this->db->table('booking_details')->where(['id' => $payment['booking_details_id']])->update(['subscription_status' => '0']);
			}
		}
		
		die;
	}
}