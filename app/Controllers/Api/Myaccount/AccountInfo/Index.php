<?php

namespace App\Controllers\Api\Myaccount\AccountInfo;

use App\Controllers\BaseController;
use App\Models\Users;

class Index extends BaseController
{

	public function __construct()
    {
		$this->users = new Users();	
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
}