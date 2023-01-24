<?php

namespace App\Controllers\Api\Facility;

use App\Controllers\BaseController;

use App\Models\Event;

class Index extends BaseController
{
    public function __construct()
    {
		$this->event = new Event();
    }
    
    public function index()
    {

		$post = $this->request->getPost();
		

		$facilitycount = $this->event->getEvent('count', ['event'], ['status'=> ['1'], 'type' => '2']);
		$facility = $this->event->getEvent('all', ['event', 'users', 'startingstallprice'], ['status'=> ['1'], 'type' => '2']);
		
		if ($facility && count($facility) > 0){
			$result=[];
			foreach($facility as $datas){ 
				$image = ($datas['image']!='') ? base_url().'/assets/uploads/event/'.$datas['image'] : '';
				
				$result[] = [
					'id'                => $datas['id'],
					'user_id'           => $datas['user_id'],
					'name'              => $datas['name'],
					'description'       => $datas['description'],
					'location'          => $datas['location'],
					'city'              => $datas['city'],
					'state'             => $datas['state'],
					'zipcode'           => $datas['zipcode'],
					'latitude'          => $datas['latitude'],
					'longitude'         => $datas['longitude'],
					'mobile'            => $datas['mobile'],
					'startingstallprice'=> $datas['startingstallprice'],
					'start_date'        => dateformat($datas['start_date']),
				    'end_date'          => dateformat($datas['end_date']),
					'image'             => $image,
					'status'            => '1',
					'btn'             	=> 'Book Now'
				];
			}
			
			$json = ['1', count($facility).' Record(s) Found', $result];		
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

    public function updatereservation()
	{  
		$requestData = $this->request->getPost();
		if (isset($requestData['updatereservation'])){
			$updatereservation = json_decode($requestData['updatereservation'], true);
			
			foreach($updatereservation as $key => $value){
				$result = $this->bookingdetails->updatestall(['id' => $key, 'stallid' => $value]);
			}
			if($result){
        	    $json = ['1','Your Stall is Updated Successfully', []];
        	}else {
        	    $json = ['0','Try Again', []];; 
			}
			echo json_encode([
            'status'      => $json[0],
            'message'     => $json[1],
            'result'     => $json[2],
	        ]);

	        die;
		}

		$this->detail();
	}

	public function detail()
    {
    	$requestData = $this->request->getPost();
		if($requestData['bookingid']!=''){
			$result['booked'] = $this->booking->getBooking('row', ['booking', 'barnstall', 'rvbarnstall'], ['id' => $requestData['bookingid'], 'eventid' => $requestData['id'], 'status'=> ['1']]);
			if(!$result['booked']){
				$json = ['0', 'No Records Found.', []];	
			}
		}
		
		$event = $this->event->getEvent('row', ['event', 'barn', 'stall', 'rvbarn', 'rvstall', 'feed', 'shaving', 'users'],['id' => $requestData['id'], 'type' =>'2']);

		if ($data && count($data) > 0){
			$result =[];

			$result[] =[
				'id' 					=> $data['id'], 
				'user_id'				=> $data['user_id'], 
				'name' 					=> $data['name'], 
				'description' 			=> $data['description'], 
				'startingstallprice' 	=> $data['startingstallprice'], 
				'image' 				=> ($data['image']!='' ? base_url().'/assets/uploads/event/'.$data['image'] : ''),
				'profile_image' 		=> ($data['profile_image']!='' ? base_url().'/assets/uploads/profile/'.$data['profile_image']: ''),
				'stallmap' 				=> ($data['stallmap']!='' ? $data['profile_image']: ''),
				'barn' 					=> $data['barn'],
				'rvbarn' 				=> $data['rvbarn'],
				'feed' 					=> $data['feed'],
				'shaving' 				=> $data['shaving']

			];
			$json = ['1', '1'.' Record(s) Found', $result];		
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
	
}
