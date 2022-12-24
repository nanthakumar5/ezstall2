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
    	$validation = \Config\Services::validation();

        $validation->setRules(
            [
                'page'    			=> 'required',
                'api'    			=> 'required',
                'userid'    		=> 'required',
                'type'    			=> 'required',
            ],

            [
               
                'userid' => [
                    'required' => 'userid is required.',
                ],

                'type' => [
                    'required' => 'type is required.',
                ],
                'page' => [
                    'required' => 'page is required.',
                ],
                'api' => [
                    'required' => 'api is required.',
                ],
            ]
        );

        if ($validation->withRequest($this->request)->run()) {
	    	if($requestData['type']=='1' || (isset($requestData['page']) && $requestData['page']=='checkout')){
				$result = $this->stripe->stripepayment($requestData);
				$json = ['1', 'Payment Success',$result['paymentintents']['id']];
			}elseif($requestData['type']=='2'){  
				$result = $this->stripe->striperecurringpayment($requestData);
				$json = ['1', 'Subscription is successfully',$result['paymentintents']['id']];
			}else{
				$json = ['0', 'Try Later',[]];
			}
		}else {
            $json = ['0', $validation->getErrors(), []];
        }

		 echo json_encode([
	            'status'  => $json[0],
	            'message' => $json[1],
	            'result'  => $json[2],
	        ]);
	        die();
    }
}