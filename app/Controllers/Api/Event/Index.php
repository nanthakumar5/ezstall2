<?php

namespace App\Controllers\Api\Event;

use App\Controllers\BaseController;

use App\Models\Event;
use App\Models\Booking;
use App\Models\Comments;

class Index extends BaseController
{
    public function __construct()
    {
		$this->event            = new Event();
		$this->comments         = new Comments();
		$this->booking 	        = new Booking();
    }
    
    public function listandsearch()
    {
    	$todaydate = date('Y_m_d');

        $searchdata = [];
        if($this->request->getGet()!=""){
            if($this->request->getGet('location')!="")   		$searchdata['llocation']    		= $this->request->getGet('location');
    		if($this->request->getGet('start_date')!="")   	 	$searchdata['btw_start_date']    	= formatdate($this->request->getGet('start_date'));
    		if($this->request->getGet('end_date')!="")   	 	$searchdata['btw_end_date']    		= formatdate($this->request->getGet('end_date'));
    		if($this->request->getGet('no_of_stalls')!="")   	$searchdata['no_of_stalls']    		= $this->request->getGet('no_of_stalls');
        }
		
		$eventcount = count($this->event->getEvent('all', ['event', 'stallavailable'], $searchdata+['status'=> ['1'], 'type' => '1']));
		$data = $this->event->getEvent('all', ['event', 'stallavailable'], $searchdata+['status'=> ['1'], 'type' => '1'], ['orderby' =>'e.id desc', 'groupby' => 'e.id']);
		
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
					'stalls_price'      => $datas['stalls_price'],
					'start_date'        => formatdate($datas['start_date'],1),
				    'end_date'          => formatdate($datas['end_date'],1),
					'image'             => $image,
					'status'			=> $status['status'],
					'btn'				=> $status['btn'] 
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
    
    public function detail($id){
        
        $userdetail 		= getSiteUserDetails() ? getSiteUserDetails() : [];
		$userid 			= (isset($userdetail['id'])) ? $userdetail['id'] : 0;
        $data['event'] 		= $this->event->getEvent('row', ['event', 'barn', 'stall', 'rvbarn', 'rvstall', 'feed', 'shaving', 'users'],['id' => $id, 'type' =>'1']);
		$data['bookings'] 	= $this->booking->getBooking('row', ['booking', 'event'],['user_id' => $userid, 'eventid' => $id,'status'=> ['1']]);
		$data['comments'] 	= $this->comments->getComments('all', ['comments','users','replycomments'],['commentid' => '0', 'eventid' => $id,'status'=> ['1']]);
		
		if ($data && count($data) > 0){
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
		
		$datas = $this->event->getEvent('all', ['event'], ['status'=> ['1'], 'type' => '1'], ['orderby' =>'e.id desc', 'groupby' => 'e.id']);

            if (count($datas) > 0) {
                $results = [];
				foreach ($datas as $data) {

					$startdate   = isset($data['start_date']) ? formatdate($data['start_date'],1) : '';
					$enddate     = isset($data['end_date'])  ? formatdate($data['end_date'],1) : '';
				
					$results[] = [
						'id'           => $data['id'],
						'name'         => $data['name'],
						'start_date'   => $startdate,
						'end_date'     => $enddate,
						'location'     => $data['location'],
						'stalls_price' => $data['stalls_price']
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
	
	function checkinchecout(){
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
}
