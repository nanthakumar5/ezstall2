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
        $searchdata = [];
        if($this->request->getGet()!=""){
            if($this->request->getGet('location')!="")   		$searchdata['llocation']    		= $this->request->getGet('location');
    		if($this->request->getGet('start_date')!="")   	 	$searchdata['btw_start_date']    	= formatdate($this->request->getGet('start_date'));
    		if($this->request->getGet('end_date')!="")   	 	$searchdata['btw_end_date']    		= formatdate($this->request->getGet('end_date'));
    		if($this->request->getGet('no_of_stalls')!="")   	$searchdata['no_of_stalls']    		= $this->request->getGet('no_of_stalls');
        }
		
		$eventcount = count($this->event->getEvent('all', ['event', 'users', 'startingstallprice'], $searchdata+['status'=> ['1'], 'type' => '2']));
		$data = $this->event->getEvent('all', ['event', 'users', 'startingstallprice'], $searchdata+['status'=> ['1'], 'type' => '2'], ['orderby' =>'e.id desc', 'groupby' => 'e.id']);
		
		if ($data && count($data) > 0){
			$result=[];
			foreach($data as $datas){
				$status = checkEvent($datas);
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
					'status'            => $status['status'],
					'btn'             	=> $status['btn']
				];
			}
			
			$json = ['1', count($data).' Record(s) Found', $result];		
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
    
    public function detail($id)
    {
		
		$data = $this->event->getEvent('row', ['event', 'barn', 'stall', 'rvbarn', 'rvstall', 'feed', 'shaving', 'users', 'startingstallprice'],['id' => $id, 'type' =>'2']);

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
