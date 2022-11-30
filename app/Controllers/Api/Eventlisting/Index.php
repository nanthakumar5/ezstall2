<?php

namespace App\Controllers\Api\Eventlisting;

use App\Controllers\BaseController;

use App\Models\Event;

class Index extends BaseController
{
    public function __construct()
    {
		$this->event = new Event();
    }

    public function upcomingevents()
    {
        $post       = $this->request->getPost();  

		$date = date('Y-m-d');
    	$result = $this->event->getEvent('all', ['event'],['status' => ['1'], 'start_date' => $date], ['orderby' => 'e.id desc', 'limit' => '5', 'type' => '1']);         
		if ($result && count($result) > 0){
			$event=[];
			foreach($result as $data){
				
				$start_on   = (isset($data['start_date']) && $data['start_date']!='0000-00-00') ? date("M d, Y", strtotime($data['start_date'])) : '';
				$end_on     = (isset($data['end_date']) && $data['end_date']!='0000-00-00') ? date("M d, Y", strtotime($data['end_date'])) : '';
				$event_on   = ($start_on!='' && $end_on!='') ? $start_on.' - '.$end_on : '';
				
				$event[] = [
					'id'           => $data['id'],
					'name'         => $data['name'],
					'start_date'   => $start_on,
					'end_date'     => $end_on,
					'time'         => $event_on,
					'location'     => $data['location'],
					'stalls_price' => $data['stalls_price'],
				];
			}
			
			$json = ['1', count($result).' Record(s) Found', $event];		
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

    public function pastevents()
    {
        $post       = $this->request->getPost();  

		$date = date('Y-m-d');
    	$result = $this->event->getEvent('all', ['event'],['status' => ['1'], 'end_date' => $date], ['orderby' => 'e.id desc', 'limit' => '5', 'type' => '1']);
		if ($result && count($result) > 0){
			$event=[];
			foreach($result as $data){
				
				$start_on   = (isset($data['start_date']) && $data['start_date']!='0000-00-00') ? date("M d, Y", strtotime($data['start_date'])) : '';
				$end_on     = (isset($data['end_date']) && $data['end_date']!='0000-00-00') ? date("M d, Y", strtotime($data['end_date'])) : '';
				$event_on   = ($start_on!='' && $end_on!='') ? $start_on.' - '.$end_on : '';
				
				$event[] = [
					'id'           => $data['id'],
					'name'         => $data['name'],
					'start_date'   => $start_on,
					'end_date'     => $end_on,
					'time'         => $event_on,
					'location'     => $data['location'],
					'stalls_price' => $data['stalls_price'],
				];
			}
			
			$json = ['1', count($result).' Record(s) Found', $event];		
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
	
	public function viewallevents(){
		
		$post = $this->request->getPost();

        $validation = \Config\Services::validation();

        $validation->setRules([
                'length'         => 'required'
            ],
            [ 
                'length' => [
                    'required'  => 'Length is required.',
                ]
            ]
        );

        if($validation->withRequest($this->request)->run()){            

            $perpage = 10;
            if ($post['length'] == '' || $post['length'] == 0) {
                $offset = 0;
            }else{
                $offset = $post['length'];
            }  

			$datas = $this->event->getEvent('all', ['event', 'stallavailable'], ['status'=> ['1'], 'start' => $offset, 'length' => $perpage, 'type' => '1'], ['orderby' =>'e.id desc', 'groupby' => 'e.id']);

            if (count($datas) > 0) {
                $results = [];
				foreach ($datas as $data) {

					$start_on   = (isset($data['start_date']) && $data['start_date']!='0000-00-00') ? date("M d, Y", strtotime($data['start_date'])) : '';
					$end_on     = (isset($data['end_date']) && $data['end_date']!='0000-00-00') ? date("M d, Y", strtotime($data['end_date'])) : '';
					$event_on   = ($start_on!='' && $end_on!='') ? $start_on.' - '.$end_on : '';
				
					$results[] = [
						'id'           => $data['id'],
						'name'         => $data['name'],
						'start_date'   => $start_on,
						'end_date'     => $end_on,
						'time'         => $event_on,
						'location'     => $data['location'],
						'stalls_price' => $data['stalls_price'],
					];
                }

                $json = ['1', count($datas) . ' Record(s) Found', $results];

            } else {
                $json = ['0', 'No Record(s) Found', []];
            }       

        }else{
            $json = ['0', $validation->getErrors(), []];
        }

        echo json_encode([
            'status'  => $json[0],
            'message' => $json[1],
            'result'  => $json[2],
        ]);
        die();
		
		
	}
	
}
