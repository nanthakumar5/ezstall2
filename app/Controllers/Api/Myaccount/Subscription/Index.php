<?php

namespace App\Controllers\Api\Myaccount\Subscription;

use App\Controllers\BaseController;
use App\Models\Users;
use App\Models\Plan;
use App\Models\Payments;

class Index extends BaseController
{
    public function __construct()
    {
        $this->users = new Users();
        $this->plan 	= new Plan();
		$this->payments = new Payments();
    }

    public function index()
    {	
    	$post = $this->request->getPost();
        $validation 	= \Config\Services::validation();
        $validation->setRules(
            [
                'user_id'       => 'required',
            ],

            [
                'user_id' => [
                    'required' => 'user_id is required.',
                ],
            ]
        );

        if ($validation->withRequest($this->request)->run()) {
        	$users 					= getUserDetails($post['user_id']);
			$data['plans']          = $this->plan->getPlan('all', ['plan'], ['type' => [$users['type']]]);
			$data['subscriptions']  = $this->payments->getPayments('row', ['payment', 'plan'], ['ninstatus' => ['0'], 'id' => $users['subscription_id']]);
			$data['userdetail']     = $users;
			if($data){
				$json = ['0', count($data), $data];
			}else{
				$json = ['0', 'No Record Fount', $data];
			}
		} else {
            $json = ['0', $validation->getErrors(), []];
        } 

        echo json_encode([
            'status'         => $json[0],
            'message'        => $json[1],
            'result'         => $json[2],
        ]);

        die;
    }
}
