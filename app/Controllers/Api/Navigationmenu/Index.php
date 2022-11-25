<?php

namespace App\Controllers\Api\Navigationmenu;

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
            $data=[];
            if ($result) {
	
				if($result['type']=='2'){                         // Facility 
					
					$data = [
						'1'=> 'Dashboard',
						'2'=> 'Account Information',
						'3'=> 'Event',
						'4'=> 'Facility',
						'5'=> 'Facility Calendar',
						'6'=> 'Stall Manager',
						'7'=> 'Operators',
						'8'=> 'Current Reservation',
						'9'=> 'Past Reservation',
						'10'=> 'Payments',
						'11'=> 'Transactions',
						'12'=> 'Subscriptions',
						'13'=> 'Logout'
					];
						
				} elseif($result['type']=='3'){                       // Producer
					
					$data = [
						    '1'=> 'Dashboard',
							'2'=> 'Account Information',
							'3'=> 'Event',
							'6'=> 'Stall Manager',
							'7'=> 'Operators',
							'8'=> 'Current Reservation',
							'9'=> 'Past Reservation',
							'10'=> 'Payments',
							'11'=> 'Transactions',
							'13'=> 'Logout'
					];
					
				} elseif($result['type']=='4'){                       // Stall Manager
					
					$data = [
						'1'=> 'Dashboard',
						'2'=> 'Account Information',
						'3'=> 'Event',
						'4'=> 'Facility',
						'5'=> 'Facility Calendar',
						'8'=> 'Current Reservation',
						'9'=> 'Past Reservation',
						'10'=> 'Payments',
						'13'=> 'Logout'
					];
					
				} elseif($result['type']=='5'){                       //Horse Owner
					
					$data = [
    					'1'=> 'Dashboard',
						'2'=> 'Account Information',
						'8'=> 'Current Reservation',
						'9'=> 'Past Reservation',
						'10'=> 'Payments',
						'13'=> 'Logout'
					];
				} elseif($result['type']=='6'){                       //operator
					
					$data = [
    					'1'=> 'Dashboard',
						'2'=> 'Account Information',
						'8'=> 'Current Reservation',
						'13'=> 'Logout'
					];
				}
				
				
                 $json = ['1', '1 Record(s) Found', [$data]];
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
}
