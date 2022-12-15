<?php

namespace App\Controllers\Api\Operator;

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
        $post       = $this->request->getPost(); 
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

            $result = $this->users->getUsers('row', ['users'], ['id' => $post['user_id'],'type'=>['2','3'],'status' => ['1']]); 
            if ($result) {
				  $datas =  $this->users->getUsers('all', ['users'], ['parentid' => $post['user_id'],'type'=>['6'],'status' => ['1']]);	
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
				
				$result = $this->users->getUsers('row', ['users'], ['id' => $post['user_id'],'type'=>['2','3'],'status' => ['1']]); 
				
				if($result){
					
					$post['userid']       = $post['user_id'];
					$post['parentid']     = $post['user_id'];
					$post['name']         = $post['name'];
					$post['email']        = $post['email'];
					$post['password']     = md5($post['password']);
					$post['status']       = '1';
					$post['email_status'] = '1';
					$post['type']         = '6';                        

					$operatorid = $this->users->action($post); 
					
					if($operatorid){
						
						$result=[];
						
						$data = $this->users->getUsers('row', ['users'], ['id' => $operatorid]); 
						if($data){
							$result = [
							'user_id' 	=> $data['id'],
							'name' 		=> $data['name'],
							'email' 	=> $data['email'],
							'type' 		=> $data['type'],
						];
					   }
						
						$json = ['1', 'Operator Created Successfully.', $result];
					} else {
						$json = ['0', 'Try Later.', []];
					}
					
				} else {
					$json = ['0', 'Not Authorized to Create Operator.', []];
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
				'operatorid' => 'required',
				'name'           => 'required',
				'email'          => 'required|valid_email',
            ],

            [
                'user_id' => [
                    'required' => 'User id is required.',
                ],
				'operatorid' => [
                    'required' => 'Operator id is required.',
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
				$post['parentid']     = $post['user_id'];
				$post['actionid']     = $post['operatorid'];
				$post['name']         = $post['name'];
				$post['email']        = $post['email'];
				if(isset($post['password'])) $post['password']     = md5($post['password']);

				$operatorid = $this->users->action($post); 
                
				if($operatorid){

					$result=[];
						
					$data = $this->users->getUsers('row', ['users'], ['id' => $operatorid]); 
					if($data){
						$result = [
						'user_id' 	=> $data['id'],
						'name' 		=> $data['name'],
						'email' 	=> $data['email'],
						'type' 		=> $data['type'],
					];
				   }
				   
                    $json = ['1', 'Operator Updated Successfully.', $result];
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
				'operatorid' => 'required'
            ],

            [
                'user_id' => [
                    'required' => 'User id is required.',
                ],
				'operatorid' => [
                    'required' => 'User id is required.',
                ]
            ]
        );

        if ($validation->withRequest($this->request)->run()) {
			
				$post['userid']       = $post['user_id'];
				$post['id']           = $post['operatorid'];
			
				$operatorid = $this->users->delete($post); 
                
				if($operatorid){
                    $json = ['1', 'Operator Deleted Successfully.', []];
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
