<?php

namespace App\Controllers\Api\Stallmanager;

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
        $post       = $this->request->getPost();  //print_r($post);die;
        $validation = \Config\Services::validation();

        $validation->setRules(
            [
                'user_id'       => 'required',
            ],

            [
                'user_id' => [
                    'required' => 'User id is required.',
                ],
            ]
        );

        if ($validation->withRequest($this->request)->run()) {

            $result = $this->users->getUsers('row', ['users'], ['id' => $post['user_id'],'status' => ['1']]);
            if ($result) {
				  $datas =  $this->users->getUsers('all', ['users'], ['type'=>['4'],'status' => ['1']]);	
                  if(count($datas) > 0) {
					$result1=[];
                    foreach($datas as $data){
                       $result1[] = [
							'id'	  => $data['id'],
							'name'    => $data['name'],
							'email'   => $data['email'],
						];
					}

                    $json = ['1', count($datas).' Record(s) Found', $result1];				

			     } else {
			        $json = ['0', 'No Records Found.', []];	
			     }
            } else {
                $json = ['0', 'No Records Found.', []];
            }
        } else {
            $json = ['0', $validation->getErrors(), []];
        }

        echo json_encode([
            'status'         => $json[0],
            'message'       => $json[1],
            'result'         => $json[2],
        ]);

        die;
    } 
	
	public function add(){
		
		$post       = $this->request->getPost();  
        $validation = \Config\Services::validation();

        
        $validation->setRules(
            [
                'user_id'    => 'required',
				'name'       => 'required',
				'email'      => 'required|valid_email',
				'password'   => 'required',
            ],

            [
                'user_id' => [
                    'required' => 'User id is required.',
                ],
				'name' => [
                    'required' => 'Name is required.',
                ],
				'email' => [
                    'required' => 'Email Id is required.',
                ],
				'password' => [
                    'required' => 'Password is required.',
                ]
            ]
        );

        if ($validation->withRequest($this->request)->run()) {
			
			$check_email = $this->users->getUsers('count', ['users'], ['email' => $post['email']]);

            if ($check_email) {
    			$json = ['0', 'Email id already Exists.', []];
			} else {
				$post['userid']       = $post['user_id'];
				$post['name']         = $post['name'];
				$post['email']        = $post['email'];
				$post['password']     = md5($post['password']);
				$post['status']       = '1';
				$post['email_status'] = '1';
				$post['type']         = '4';                        

				$stallmanagerid = $this->users->action($post); 
                
				if($stallmanagerid){
                    $json = ['1', 'Stall Manager Created Successfully.', []];
                } else {
                    $json = ['0', 'Try Later.', []];
                }
            }
		} else {
            $json = ['0', $validation->getErrors(), []];
        }

        echo json_encode([
            'status'    => $json[0],
            'message'   => $json[1],
            'result'    => $json[2],
        ]);

        die;
	}
	
	public function edit(){
		
		$post       = $this->request->getPost();  
        $validation = \Config\Services::validation();

        
        $validation->setRules(
            [
                'user_id'        => 'required',
				'stallmanagerid' => 'required',
				'name'           => 'required',
				'email'          => 'required|valid_email',
            ],

            [
                'user_id' => [
                    'required' => 'User id is required.',
                ],
				'stallmanagerid' => [
                    'required' => 'User id is required.',
                ],
				'name' => [
                    'required' => 'Name is required.',
                ],
				'email' => [
                    'required' => 'Email Id is required.',
                ],
            ]
        );

        if ($validation->withRequest($this->request)->run()) {
			
				$post['userid']       = $post['user_id'];
				$post['actionid']     = $post['stallmanagerid'];
				$post['name']         = $post['name'];
				$post['email']        = $post['email'];
				if(isset($post['password'])) $post['password']     = md5($post['password']);

				$stallmanagerid = $this->users->action($post); 
                
				if($stallmanagerid){
                    $json = ['1', 'Stall Manager Updated Successfully.', []];
                } else {
                    $json = ['0', 'Try Later.', []];
                }
            
		} else {
            $json = ['0', $validation->getErrors(), []];
        }

        echo json_encode([
            'status'    => $json[0],
            'message'   => $json[1],
            'result'    => $json[2],
        ]);

        die;
	}
	
	public function delete(){
		
		$post       = $this->request->getPost();  
        $validation = \Config\Services::validation();

        
        $validation->setRules(
            [
                'user_id'        => 'required',
				'stallmanagerid' => 'required'
            ],

            [
                'user_id' => [
                    'required' => 'User id is required.',
                ],
				'stallmanagerid' => [
                    'required' => 'User id is required.',
                ]
            ]
        );

        if ($validation->withRequest($this->request)->run()) {
			
				$post['userid']       = $post['user_id'];
				$post['id']           = $post['stallmanagerid'];
			
				$stallmanagerid = $this->users->delete($post); 
                
				if($stallmanagerid){
                    $json = ['1', 'Stall Manager Deleted Successfully.', []];
                } else {
                    $json = ['0', 'Try Later.', []];
                }
            
		} else {
            $json = ['0', $validation->getErrors(), []];
        }

        echo json_encode([
            'status'    => $json[0],
            'message'   => $json[1],
            'result'    => $json[2],
        ]);

        die;
		
	}
}
