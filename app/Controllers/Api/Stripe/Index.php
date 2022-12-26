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
				$json = ['1', 'Payment Success',$result['paymentintents']['client_secret']];
			}elseif($requestData['type']=='2'){  
				$result = $this->stripe->striperecurringpayment($requestData);
				$json = ['1', 'Subscription is successfully',$result['paymentintents']['client_secret']];
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

    public function secretstripekey(){
        $requestData = $this->request->getPost();

        if($requestData['type']=='1' || (isset($requestData['page']) && $requestData['page']=='checkout')){
            $customerid = $this->stripe->customer(2,'muthulakshmi M','muthulakshmi@itflexsolutions.com','cus_N2VaqUup09Ixa3');
            $amount = 0.50*100;
            $result = $this->stripe->createPaymentIntents($customerid,$amount);
            $json = ['1', 'Secre key',$result['id']];
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
}