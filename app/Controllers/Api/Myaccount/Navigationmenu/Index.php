<?php

namespace App\Controllers\Api\Myaccount\Navigationmenu;

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

            $result = $this->users->getUsers('row', ['users'], ['id' => $post['user_id'],'status' => ['1']]);

            if ($result) {
               $data=[];	
				if($result['type']=='2'){                         // Facility 
					
					$data = array(
					        array('id' => '1','screen' => 'Account Information'),
							array('id' => '2','screen' => 'Event'),
							array('id' => '3','screen' => 'Facility'),
							array('id' => '4','screen' => 'Calendar'),
							array('id' => '5','screen' => 'Stall Manager'),
							array('id' => '6','screen' => 'Operators'),
					        array('id' => '7','screen' => 'Current Reservation'),
							array('id' => '8','screen' => 'Past Reservation'),
							array('id' => '9','screen' => 'Payments'),
							array('id' => '10','screen' => 'Transactions'),
							array('id' => '11','screen' => 'Subscriptions'),
							
					      );
						
				} elseif($result['type']=='3'){                       // Producer
					
					$data = array(
					        array('id' => '1','screen' => 'Account Information'),
							array('id' => '2','screen' => 'Event'),
							array('id' => '4','screen' => 'Stall Manager'),
							array('id' => '5','screen' => 'Operators'),
					        array('id' => '6','screen' => 'Current Reservation'),
							array('id' => '7','screen' => 'Past Reservation'),
							array('id' => '9','screen' => 'Payments'),
							array('id' => '10','screen' => 'Transactions'),
					      );
					
				} elseif($result['type']=='4'){                       // Stall Manager
					
					$data = array(
					        array('id' => '1','screen' => 'Account Information'),
							array('id' => '2','screen' => 'Event'),
							array('id' => '3','screen' => 'Facility'),
					        array('id' => '6','screen' => 'Current Reservation'),
							array('id' => '7','screen' => 'Past Reservation'),
							array('id' => '9','screen' => 'Payments'),
					      );
					
				} elseif($result['type']=='5'){                       //Horse Owner
					
					$data = array(
					        array('id' => '1','screen' => 'Account Information'),
					        array('id' => '6','screen' => 'Current Reservation'),
							array('id' => '7','screen' => 'Past Reservation'),
							array('id' => '9','screen' => 'Payments'),
					      );

				} elseif($result['type']=='6'){                       //Operator
					
					$data = array(
					        array('id' => '1','screen' => 'Account Information'),
					        array('id' => '6','screen' => 'Current Reservation'),
					      );
				} 
				 $type = isset($result['type']) ? $result['type'] : []; 
				 if(!empty($data)) $json = ['1', count($data).' Record(s) Found', $type, $data];
                 else             $json = ['0', 'No Records Found.',[] ,[]];
            } else {
                $json = ['0', 'No Records Found.', [],[]];
            }
        } else {
            $json = ['0', $validation->getErrors(), [],[]];
        }

        echo json_encode([
            'status'         => $json[0],
            'message'       => $json[1],
			'type'          => $json[2],
            'result'         => $json[3],
        ]);

        die;
    }
}
