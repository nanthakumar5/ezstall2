<?php

namespace App\Controllers\Api\Myaccount\PaymentInfo;

use App\Controllers\BaseController;
use App\Models\Payments;

class Index extends BaseController
{

	public function __construct()
    {
		$this->payments = new Payments();	
    }

    public function index()
    { 
    	$post = $this->request->getPost();
    	$validation = \Config\Services::validation();
    	$validation->setRules(
	            [
	                'user_id'   => 'required',
	            ],

	            [
	                'user_id' => [
	                    'required' => 'User ID is required.',
	                ],
	            ]
	        );

    	if ($validation->withRequest($this->request)->run()) {

			$userid = $post['user_id']; 
			$allids = getStallManagerIDS($userid);
			array_push($allids, $userid);

			$payments = $this->payments->getPayments('all', ['payment','event', 'users','booking'], ['ninstatus' => ['0'], 'userid' => $allids], ['orderby' => 'p.id desc']);

			if($payments){
				$result = [];
				foreach ($payments as $data) {
					$result[] =[
						'id' 				=> $data['id'],
						'name' 				=> $data['name'],
						'amount' 			=> $data['amount'],
						'status' 			=> $data['status'],
						'created' 			=> $data['created'],
						'usertype' 			=> $data['usertype'],
					];
				}
				$json = ['1', count($result).' Record Found.', $result];		 
			}else{
				$json = ['0', 'Try Later.', []];		 
			}
		}else{
            $json = ['0', $validation->getErrors(), []];
		}

		echo json_encode([
			'status' => $json[0],
			'message' => $json[1],
			'result' => $json[2],
		]);

		die;
    }

    public function view(){
    	$post = $this->request->getPost();
    	$validation = \Config\Services::validation();

    	$validation->setRules(
    		[
                'user_id'   => 'required',
                'id'   => 'required',
            ],

            [
                'user_id' => [
                    'required' => 'User ID is required.',
                ],
                'id' => [
                    'required' => 'ID is required.',
                ],
            ]
	    );

	    if ($validation->withRequest($this->request)->run()) {

	    	$data = $this->payments->getPayments('row', ['payment','event', 'users','booking'], ['ninstatus' => ['0'], 'userid' => [$post['user_id']],'id' => $post['id']]);

	    	if($data){
	    		$result = [];
	    			$result[] =[
						'id' 					=> $data['id'],
						'name' 					=> $data['name'],
						'firstname' 			=> $data['firstname'],
						'lastname' 				=> $data['lastname'],
						'email' 				=> $data['email'],
						'type' 					=> $data['type'],
						'amount' 				=> $data['amount'],
						'created'				=> $data['created'],
						'usertype' 				=> $data['usertype']
					];

					$json = ['1', count($result).' Record Found.', $result];
	    	}else{
	    			$json = ['0', 'Try Later.', []];		 
	    	}
	    }

	    echo json_encode([
			'status' => $json[0],
			'message' => $json[1],
			'result' => $json[2],
		]);

		die;

    }
}