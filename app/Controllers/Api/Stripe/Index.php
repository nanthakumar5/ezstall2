<?php

namespace App\Controllers\Api\Stripe;

use App\Controllers\BaseController;
use App\Models\Stripe;
use App\Models\Cart;

class Index extends BaseController
{
    public function __construct()
    {
		$this->stripe 	= new stripe();
		$this->cart 	= new Cart();

    }

    public function stripepayment(){ 
    	$requestData = $this->request->getPost(); 
    	if($requestData['type']=='1' || (isset($requestData['page']) && $requestData['page']=='checkout')){ 
			$result = $this->stripe->stripepayment($requestData);
			$json = ['1', 'Payment Success',$result['paymentintents']['id']];
		}elseif($requestData['type']=='2'){  
			$result = $this->stripe->striperecurringpayment($requestData);
			if($result['stripepay']){
				$this->cart->delete(['user_id' => $requestData['userid'], 'type' => $requestData['type']]);
				$json = ['1', 'Payment Success',[]];
			}else{
				$json = ['0', 'Your payment is not processed successfully',[]];
			}
		}else{
			$json = ['0', 'Try Later',[]];
		}

		 echo json_encode([
	            'status'  => $json[0],
	            'message' => $json[1],
	            'result'  => $json[2],
	        ]);
	        die();
    }

    public function stripekey(){

    }
}