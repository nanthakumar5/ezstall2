<?php

namespace App\Controllers\Api\Myaccount\TransactionInfo;

use App\Controllers\BaseController;

use App\Models\StripePayments;

class Index extends BaseController
{

	public function __construct()
    {
		$this->transaction = new StripePayments();
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
			$transactions = $this->transaction->getStripePayments('all', ['stripepayment','users'], ['status' => ['1'],'userid' => $post['user_id']], ['orderby' => 's.id desc']);

			if($transactions){
				$result = [];
				foreach ($transactions as $data) { 
					$result[] =[
						'username' 				=> $data['username'],
						'amount' 				=> $data['amount'],
						'transferusertype' 		=> $data['transferusertype'],
						'created_at' 			=> formatdate($data['created_at'],1),
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
}