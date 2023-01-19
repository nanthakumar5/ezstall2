<?php

namespace App\Controllers\Api\Event;

use App\Controllers\BaseController;

use App\Models\Event;
use App\Models\Booking;
use App\Models\Comments;
use App\Models\Cart;

class Index extends BaseController
{
    public function __construct()
    {
		$this->event            = new Event();
		$this->comments         = new Comments();
		$this->booking 	        = new Booking();
		$this->cart 	        = new Cart();
    }
    
    public function listandsearch()
    {
		$post = $this->request->getPost();
		$eventcount = $this->event->getEvent('count', ['event'], ['status'=> ['1'], 'type' => '1']);
		$event = $this->event->getEvent('all', ['event', 'users', 'startingstallprice'], ['status'=> ['1'], 'type' => '1'], ['orderby' =>'e.id desc', 'groupby' => 'e.id']);
		
		if ($event && count($event) > 0){
			$result=[];
			foreach($event as $datas){ 
				$datas['api'] 		= 'api'; 
				$datas['userid'] 	= $post['userid']; 
				$status = checkEvent($datas); 
				$image = ($datas['image']!='') ? base_url().'/assets/uploads/event/'.$datas['image'] : '';
				
				$result[] = [
					'id'                	=> $datas['id'],
					'user_id'           	=> $datas['user_id'],
					'name'             	 	=> $datas['name'],
					'description'       	=> $datas['description'],
					'location'          	=> $datas['location'],
					'city'              	=> $datas['city'],
					'state'             	=> $datas['state'],
					'zipcode'           	=> $datas['zipcode'],
					'latitude'          	=> $datas['latitude'],
					'longitude'         	=> $datas['longitude'],
					'mobile'            	=> $datas['mobile'],
					'startingstallprice'    => $datas['startingstallprice'],
					'start_date'        	=> formatdate($datas['start_date'],1),
				    'end_date'          	=> formatdate($datas['end_date'],1),
					'image'             	=> $image,
					'status'				=> $status['status'],
					'btn'					=> $status['btn'] 
				];
			}
			
			$json = ['1', count($event).' Record(s) Found', $result];		
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
    
    public function detail($id){
        
        $userdetail 		= getSiteUserDetails() ? getSiteUserDetails() : [];
		$userid 			= (isset($userdetail['id'])) ? $userdetail['id'] : 0;
        $event 				= $this->event->getEvent('row', ['event', 'barn', 'stall', 'rvbarn', 'rvstall', 'feed', 'shaving', 'users', 'startingstallprice'],['id' => $id, 'type' =>'1']);

		$data['bookings'] 	= $this->booking->getBooking('row', ['booking', 'event'],['user_id' => $userid, 'eventid' => $id,'status'=> ['1']]);
		$data['comments'] 	= $this->comments->getComments('all', ['comments','users','replycomments'],['commentid' => '0', 'eventid' => $id,'status'=> ['1']]);
		
		if ($data && count($data) > 0){
			$data['event'] =[
				'id' 					=> $event['id'], 
				'user_id'				=> $event['user_id'], 
				'name' 					=> $event['name'], 
				'description' 			=> $event['description'], 
				'location' 				=> $event['location'], 
				'mobile' 				=> $event['mobile'], 
				'startingstallprice' 	=> $event['startingstallprice'], 
				'start_date'        	=> formatdate($event['start_date'],1),
				'end_date'          	=> formatdate($event['end_date'],1),
				'start_time' 			=> $event['start_time'], 
				'end_time' 				=> $event['end_time'],
				'image' 				=> ($event['image']!='' ? base_url().'/assets/uploads/event/'.$event['image'] : ''),
				'stallmap' 				=> ($event['stallmap']!='' ? $event['profile_image']: ''),
				'barn' 					=> $event['barn'],
				'rvbarn' 				=> $event['rvbarn'],
				'feed' 					=> $event['feed'],
				'shaving' 				=> $event['shaving']

			];

			$result[] = $data;

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
    
	
	public function viewallevents(){ 
		
		$datas = $this->event->getEvent('all', ['event', 'users', 'startingstallprice'], ['status'=> ['1'], 'type' => '1'], ['orderby' =>'e.id desc', 'groupby' => 'e.id']);

            if (count($datas) > 0) {
                $results = [];
				foreach ($datas as $data) {

					$startdate   = isset($data['start_date']) ? formatdate($data['start_date'],1) : '';
					$enddate     = isset($data['end_date'])  ? formatdate($data['end_date'],1) : '';
					$image       = isset($data['image'])  ? base_url().'/assets/uploads/event/'.$data['image'] : '';
				
					$results[] = [
						'id'          		=> $data['id'],
						'name'         		=> $data['name'],
						'start_date'   		=> $startdate,
						'end_date'     		=> $enddate,
						'location'     		=> $data['location'],
						'startingstallprice'=> $data['startingstallprice'],
						'image'				=> $image
					];
                }

                $json = ['1', count($datas) . ' Record(s) Found', $results];

            } else {
                $json = ['0', 'No Record(s) Found', []];
            }    

	        echo json_encode([
	            'status'  => $json[0],
	            'message' => $json[1],
	            'result'  => $json[2],
	        ]);
	        die();
		
	}
	
	function checkincheckout(){

		$post 		= $this->request->getPost();
		$eventid 	= $post['eventid'];
		$checkin 	= formatdate($post['checkin']);
		$checkout   = formatdate($post['checkout']); 
		$result['occupied']  	= getOccupied($eventid, ['checkin' => $checkin, 'checkout' => $checkout]);
		$result['reserved'] 	= getReserved($eventid,['checkin' => $checkin, 'checkout' => $checkout]);

		if(count($result)>0){
			$json = ['1', count($result) . ' Record(s) Found', $result];
		} else {
            $json = ['0', 'No Record(s) Found', []];
        }

		echo json_encode([
            'status'  => $json[0],
            'message' => $json[1],
            'result'  => $json[2],
        ]);
        die();
	}

	public function commentsaction()
    {  	
		if ($this->request->getMethod()=='post'){

			$requestData 	= $this->request->getPost();
        	$result 		= $this->comments->action($requestData);

        	if($result){
        	    $json = ['1','Your Comment Submitted Successfully', []];
        	}else {
        	    $json = ['0','Try Again', []];; 
			}
		} else {
    	    $json = ['0','Try Again', []];; 
		}
		echo json_encode([
            'status'      => $json[0],
            'message'     => $json[1],
            'result'     => $json[2],
        ]);

        die;
    }
}
