<?php

namespace App\Controllers\Api\Login;

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
                'email'          => 'required|valid_email',
                'password'      => 'required'
            ],

            [
                'email' => [
                    'required' => 'Email id is required.',
                ],
                'password'     => [
                    'required' => 'Password is required.',
                ]
            ]
        );

        if ($validation->withRequest($this->request)->run()) {

            $result = $this->users->getUsers('row', ['users'], ['email' => $post['email'], 'password' => $post['password'], 'status' => ['1']]);

            if ($result) {
				   $data = [
						'user_id' 	=> $result['id'],
						'name' 		=> $result['name'],
						'email' 	=> $result['email'],
						'type' 		=> $result['type'],
					];
                if ($result['email_status'] == '0') $json = ['0', 'Mail is not verified.', []];
                else $json = ['1', 'Login successfully.', [$data]]; 
            } else {
                $json = ['0', 'Invalid User.', []];
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
}
