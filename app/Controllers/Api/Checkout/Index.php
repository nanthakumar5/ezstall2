<?php 

namespace App\Controllers\Api\Checkout;

use App\Controllers\BaseController;
use App\Models\Paymentmethod;
use App\Models\Event;

class Index extends BaseController
{
	public function __construct()
	{
		$this->paymentmethod    = new Paymentmethod(); 
		$this->event        	= new Event();
	}
	
	public function index()
	{	
		$cartdetail  	= getCart();		
		$settings		= getSettings();
		$result['paymentmethod']	= $this->paymentmethod->getPaymentmethod('all', ['paymentmethod']);
		$result['event']			= $this->event->getEvent('row', ['event'], ['id' => $cartdetail['event_id'], 'status'=> ['1']]);

		if($result){
			$json = ['1', count($result).' Record(s) Found', $result];		
		} else {
			$json = ['0', 'No Records Found.', []];	
		}
		      
        echo json_encode([
            'status'      => $json[0],
            'message'     => $json[1],
            'result'     => $json[2],
        ]);

        die;
	}
}