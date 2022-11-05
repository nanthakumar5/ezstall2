<?php

namespace App\Controllers\Common;

use App\Controllers\BaseController;
use App\Models\Booking;

class Cron extends BaseController
{
    public function __construct()
    {
		$this->db = db_connect();
		$this->booking = new Booking();	
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

}