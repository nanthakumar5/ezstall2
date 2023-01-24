<?php

namespace App\Controllers\Api\Myaccount\AccountInfo;

use App\Controllers\BaseController;
use App\Models\Users;
use App\Models\Stripe;

class Index extends BaseController
{

	public function __construct()
    {
		$this->users = new Users();	
		$this->stripe = new Stripe();	
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
			$datas = getUserDetails($post['user_id']);
			if($datas){
				$result = [];
					$result[] =[
						'id' 				=> $datas['id'],
						'name' 				=> $datas['name'],
						'email' 			=> $datas['email'],
					];

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

    public function action(){
    	$post = $this->request->getPost();
    	$validation = \Config\Services::validation();
    	$validation->setRules(
    		[
                'id'   => 'required',
                'name'   => 'required',
                'email'  => 'required',
                'actionid'  => 'required',

            ],

            [
                'id' => [
                    'required' => 'ID is required.',
                ],
                'name' => [
                    'required' => 'Name is required.',
                ],
                'email' => [
                    'required' => 'Email is required.',
                ],
                'actionid' => [
                    'required' => 'Action ID is required.',
                ],
            ]
	    );

	    if ($validation->withRequest($this->request)->run()) {  

	    	$datas = $this->users->action($post); 
	    	if($datas){
				$json = ['1',' Record Inserted.', $datas];
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

    public function stripeconnect()
	{	
		$post 			= $this->request->getPost();
		$url 			= base_url().'/api/stripeconnect'; //print_r($url);die;
		$userdetail 	= getUserDetails($post['user_id']);
		
		$stripeconnect 	= $this->stripe->createAccount();
		if($stripeconnect){
			$this->users->action(['actionid' => $userdetail['id'], 'userid' => $userdetail['id'], 'stripe_account_id' => $stripeconnect['id']]); 
			
			$accountlink = $this->stripe->createAccountLink($stripeconnect['id'], $url, $url);
			if($accountlink){
				$json = ['1','Connected Stripe.', $accountlink['url']];
				//return redirect()->to($accountlink['url']); 
			}else{
				$json = ['0', 'Try again Later.', []];		 
			}
		}else{
			$json = ['0', 'Try again Later.', []];		
		}

		echo json_encode([
			'status' => $json[0],
			'message' => $json[1],
			'result' => $json[2],
		]);

		die;
	}
}