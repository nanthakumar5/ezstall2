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

            if ($result) {
               $data=[];	
				if($result['type']=='2'){                         // Facility 
					
					$data = array(
					        array('id' => '1','screen' => 'Dashboard'),
					        array('id' => '2','screen' => 'Account Information'),
							array('id' => '3','screen' => 'Event'),
							array('id' => '4','screen' => 'Facility'),
							array('id' => '5','screen' => 'Facility Calendar'),
							array('id' => '6','screen' => 'Stall Manager'),
							array('id' => '7','screen' => 'Operators'),
					        array('id' => '8','screen' => 'Current Reservation'),
							array('id' => '9','screen' => 'Past Reservation'),
							array('id' => '10','screen' => 'Payments'),
							array('id' => '11','screen' => 'Transactions'),
							array('id' => '12','screen' => 'Subscriptions'),
							array('id' => '13','screen'=> 'Logout'),
					      );
						
				} elseif($result['type']=='3'){                       // Producer
					
					$data = array(
					        array('id' => '1','screen' => 'Dashboard'),
					        array('id' => '2','screen' => 'Account Information'),
							array('id' => '3','screen' => 'Event'),
							array('id' => '6','screen' => 'Stall Manager'),
							array('id' => '7','screen' => 'Operators'),
					        array('id' => '8','screen' => 'Current Reservation'),
							array('id' => '9','screen' => 'Past Reservation'),
							array('id' => '10','screen' => 'Payments'),
							array('id' => '11','screen' => 'Transactions'),
							array('id' => '13','screen'=> 'Logout'),
					      );
					
				} elseif($result['type']=='4'){                       // Stall Manager
					
					$data = array(
					        array('id' => '1','screen' => 'Dashboard'),
					        array('id' => '2','screen' => 'Account Information'),
							array('id' => '3','screen' => 'Event'),
							array('id' => '4','screen' => 'Facility'),
							array('id' => '5','screen' => 'Facility Calendar'),
					        array('id' => '8','screen' => 'Current Reservation'),
							array('id' => '9','screen' => 'Past Reservation'),
							array('id' => '10','screen' => 'Payments'),
							array('id' => '13','screen'=> 'Logout'),
					      );
					
				} elseif($result['type']=='5'){                       //Horse Owner
					
					$data = array(
					        array('id' => '1','screen' => 'Dashboard'),
					        array('id' => '2','screen' => 'Account Information'),
					        array('id' => '8','screen' => 'Current Reservation'),
							array('id' => '9','screen' => 'Past Reservation'),
							array('id' => '10','screen' => 'Payments'),
							array('id' => '13','screen'=> 'Logout'),
					      );

				} elseif($result['type']=='6'){                       //Operator
					
					$data = array(
					        array('id' => '1','screen' => 'Dashboard'),
					        array('id' => '2','screen' => 'Account Information'),
					        array('id' => '8','screen' => 'Current Reservation'),
							array('id' => '13','screen'=> 'Logout'),
					      );
				}
				
				 if(!empty($data)) $json = ['1', '1 Record(s) Found', [$data]];
                 else             $json = ['0', 'No Records Found.', []];

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
