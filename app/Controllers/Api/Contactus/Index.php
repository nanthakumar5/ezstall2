<?php

namespace App\Controllers\Api\Contactus;

use App\Controllers\BaseController;

use App\Models\Contactus;
use App\Models\Settings;

class Index extends BaseController
{
    public function __construct()
    {
        $this->contactus = new Contactus;
		$this->settings  = new Settings;
    }

    public function index()
    {
		$datas = $this->settings->getSettings('row', ['settings']);
		if(count($datas) > 0) {
			$result[] = [
				        'phone'   => $datas['phone'],
						'email'   => $datas['email'],
						'address' => $datas['address'],
				];
            $json = ['1', '1 Record Found.', $result];		 
			
		} else {
			
			$json = ['0', 'No Records Found.', []];		 
			
		}
		
        echo json_encode([
            'status'      => $json[0],
            'message'     => $json[1],
            'result'     => $json[2],
        ]);

        die;
    }

    	
	public function add() 
	{
		$post       = $this->request->getPost(); 
        $validation = \Config\Services::validation();

        $validation->setRules(
            [
                'name'       => 'required',
				'email'      => 'required|valid_email',
				'subject'    => 'required',
				'message'    => 'required',
            ],

            [
                'name' => [
                    'required' => 'Name is required.',
                ],
				'email' => [
                    'required' => 'Email id is required.',
                ],
				'subject' => [
                    'required' => 'Subject is required.',
                ],
				'message' => [
                    'required' => 'Message is required.',
                ],
            ]
        );

        if ($validation->withRequest($this->request)->run()) {


            $datas = $this->contactus->action($post);
            if($datas) {
				$json = ['1', 'You have successfully contacted.', []];		 
			} else {
				$json = ['0', 'Try Later.', []];		 
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
