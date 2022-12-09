<?php

namespace App\Controllers\Api\Myaccount\Facility;

use App\Controllers\BaseController;

use App\Models\Users;
use App\Models\Event;
use App\Models\Booking;
use App\Models\Stripe;
use App\Models\Products;
use App\Models\Report;

class Index extends BaseController
{
    public function __construct()
    {
		$this->users 	= new Users();
		$this->event 	= new Event();
		$this->booking 	= new Booking();
		$this->stripe 	= new Stripe();
		$this->product 	= new Products();
		$this->report 	= new Report();
    }

    public function index()
    {
        $post       = $this->request->getPost(); 

        $validation = \Config\Services::validation();

        $validation->setRules(
            [
                'user_id'       => 'required',
				'length'        => 'required',
            ],

            [
                'user_id' => [
                    'required' => 'User id is required.',
                ],
				'length' => [
                    'required' => 'Length is required.',
                ],
            ]
        );

        if ($validation->withRequest($this->request)->run()) {
			
			$perpage = 10;
            if ($post['length'] == '' || $post['length'] == 0) {
                $offset = 0;
            }else{
                $offset = $post['length'];
            }  

            //facility
			$datas = $this->event->getEvent('all', ['event'], ['status' => ['1'], 'userid' => $post['user_id'], 'type' => '2', 'start' => $offset, 'length' => $perpage], ['orderby' => 'e.id desc']);

			if(count($datas) > 0){
				$result1=[];
				foreach($datas as $data){
				  $result1[] = [
						'id'	       =>  $data['id'],
						'name'	       =>  $data['name'],
						'image'        => ($data['image']!='') ? base_url().'/assets/uploads/event/'.$data['image'] : '',
						'description'  =>  $data['description'],
					];
				}

				$json = ['1', count($datas).' Record(s) Found', $result1];				

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
			
			$data	= $this->event->getEvent('row', ['event', 'barn', 'stall', 'rvbarn', 'rvstall', 'bookedstall', 'rvbookedstall'],['id' => $post['event_id'], 'type' => '2']);
			if(count($data) > 0){
				$result1=[];
				   $result1[] = [
						'id'	       =>  $data['id'],
						'name'	       =>  $data['name'],
						'image'        => ($data['image']!='') ? base_url().'/assets/uploads/event/'.$data['image'] : '',
						'description'  =>  $data['description'],
						'barndata'     => $data['barn'],
						'rvbarndata'   => $data['rvbarn'],
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
}
