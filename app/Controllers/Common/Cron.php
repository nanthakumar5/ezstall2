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
	
	public function stripestallsubscription()
	{	
	$x = $this->stripe->retrieveSchedule('sub_sched_1MEtDCSBPAfrS2b07Vw3sp85');
	echo '<pre>';print_r($x);die;
		$date = date('Y-m-d');

		$payments 	= 	$this->db->table('payment p')
						->where(['DATE(p.plan_period_start) <=' => $date, 'DATE(p.plan_period_end) >=' => $date, 'p.type' => '3'])
						->get()
						->getResultArray();
				
		if(count($payments) > 0){		
			foreach($payments as $payment){
			}
		}
		die;
	}

}