<?php

namespace App\Controllers\Api\Myaccount\Event;

use App\Controllers\BaseController;

use App\Models\Event;

use App\Models\Booking;
use App\Models\Products;

class Index extends BaseController
{
    public function __construct()
    {
		$this->event = new Event();
		$this->booking = new Booking();
		$this->product = new Products();
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
			$datas = $this->event->getEvent('all', ['event'],['status' => ['1'], 'userid' => $post['user_id'], 'type' => '1'], ['orderby' => 'e.id desc']);
			if(count($datas) > 0){
				$result=[];
				foreach($datas as $data){
				   $result[] = [
						'id'	       =>  $data['id'],
						'name'	       =>  $data['name'],
						'image'        => ($data['image']!='') ? base_url().'/assets/uploads/event/'.$data['image'] : '',
						'location'     =>  $data['location'],
						'start_date'   => ($data['start_date']!='' && $data['start_date']!='0000-00-00') ? date('m-d-Y', strtotime($data['start_date'])) : '',
						'end_date'     => ($data['end_date']!='' && $data['end_date']!='0000-00-00') ? date('m-d-Y', strtotime($data['end_date'])) : '',
						'stalls_price' => $data['stalls_price']
					];
				}

				$json = ['1', count($datas).' Record(s) Found', $result];				

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
	
	public function view()
	{
		$post       = $this->request->getPost(); 

        $validation = \Config\Services::validation();

        $validation->setRules(
            [
                'event_id'       => 'required',
            ],

            [
                'event_id' => [
                    'required' => 'Event id is required.',
                ],
            ]
        );

        if ($validation->withRequest($this->request)->run()) {
			
		    $data = $this->event->getEvent('row', ['event', 'barn', 'stall', 'rvbarn', 'rvstall', 'bookedstall', 'rvbookedstall'], ['id' => $post['event_id'], 'type' => '1']);
			
			if(count($data) > 0){
				$result1=[];
				   $result1[] = [
						'id'	       =>  $data['id'],
						'name'	       =>  $data['name'],
						'image'        => ($data['image']!='') ? base_url().'/assets/uploads/event/'.$data['image'] : '',
						'location'     =>  $data['location'],
						'mobile'       =>  $data['mobile'],
						'start_date'   => ($data['start_date']!='' && $data['start_date']!='0000-00-00') ? formatdate($data['start_date'], 1) : '',
						'end_date'     => ($data['end_date']!='' && $data['end_date']!='0000-00-00') ? formatdate($data['end_date'], 1) : '',
						'start_time'   => ($data['start_time']!='') ? formattime($data['start_time']) : '',
						'end_time'     => ($data['end_time']!='') ? formattime($data['end_time']) : '',
						'stalls_price' => $data['stalls_price'],
						'barndata'     => $data['barn'],
						'rvbarndata'   => $data['rvbarn']
					];
				$json = ['1', '1 Record(s) Found', $result1];	
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

	function inventories($id)
	{
		if($id!=''){
			$datas  	= $this->product->getProduct('all', ['product'], ['event_id' => $id]);

			if(count($datas) > 0){
				$result=[];
				foreach($datas as $data){
				   $result[] = [
						'id'	       =>  $data['id'],
						'name'	       =>  $data['name'],
						'quantity'     =>  $data['quantity'],
						'price'        =>  $data['price'],
						'type'        =>  $data['type']
					];
				}
				$json = ['1', count($datas).' Record(s) Found', $result];
			} else {
                $json = ['0', 'No Records Found.', []];	
			}
		}
		 echo json_encode([
            'status'         => $json[0],
            'message'       => $json[1],
            'result'         => $json[2],
        ]);

        die;

	}

	function inventories($id)
	{
		if($id!=''){
			$datas  	= $this->product->getProduct('all', ['product'], ['event_id' => $id]);

			if(count($datas) > 0){
				$result=[];
				foreach($datas as $data){
				   $result[] = [
						'id'	       =>  $data['id'],
						'name'	       =>  $data['name'],
						'quantity'     =>  $data['quantity'],
						'price'        =>  $data['price'],
						'type'        =>  $data['type']
					];
				}
				$json = ['1', count($datas).' Record(s) Found', $result];
			} else {
                $json = ['0', 'No Records Found.', []];	
			}
		}
		 echo json_encode([
            'status'         => $json[0],
            'message'       => $json[1],
            'result'         => $json[2],
        ]);

        die;

	}
}
