<?php
namespace App\Controllers\Api\Myaccount\Dashboard;

use App\Controllers\BaseController;

use App\Models\Event;

use App\Models\Booking;

use App\Models\Users;

class Index extends BaseController
{
    public function __construct()
    {
		$this->event = new Event();
		$this->booking = new Booking();
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
            if($result){
				
		$countcurrentstall 			= 0;
      	$countcurrentbookingstalls 	= 0;
      	$countcurrentbookingrvlots 	= 0;
      	$countpastevent 			= [];
      	$countpaststall 			= 0;
      	$countpastamount 			= 0;
      	$countpayedamount			= 0;
      	$countcurrentrvlots 		= 0;
      	$countcurrentevent  		= [];
		
		$yesterday 	=  date("Y-m-d", strtotime("yesterday")); 
		$tday 		=  date("Y-m-d", strtotime("today"));


     	$date				= date('Y-m-d');
    	$userdetail 		= getSiteUserDetails($post['user_id']);
    	$usertype 			= $userdetail['type'];
    	$parentid 			= $userdetail['parent_id'];
		$parentdetails 		= getSiteUserDetails($parentid);
		$parenttype   		= $parentdetails ? $parentdetails['type'] : '';
    	$userid 			= ($usertype=='4' || $usertype=='6') ? $parentdetails['id'] : $userdetail['id'];
		$allids 			= getStallManagerIDS($userid);
		array_push($allids, $userid);
        
      	if($usertype=='3' || ($usertype=='4' && $parenttype == '3')){ 
      		$currentreservation = $this->event->getEvent('all', ['event', 'barn', 'stall','rvbarn','rvstall'],['status' => ['1'], 'userids' => $allids, 'type' => '1', 'gtenddate' => $date]);
      	}
      	
      	if($usertype=='2' || ($usertype=='4' && $parenttype == '2')){
      		$currentreservation = $this->event->getEvent('all', ['event', 'barn', 'stall','rvbarn','rvstall'],['status' => ['1'], 'userids' => $allids, 'fenddate' => $date]);
      	}
      	
      	if($usertype=='2' || $usertype =='3' || ($usertype=='4' && $parenttype == '2') || ($usertype=='4' && $parenttype == '3')){
	  		foreach ($currentreservation as $event) {  
	  			foreach ($event['barn'] as $barn) {
					$countcurrentstall += count(array_column($barn['stall'], 'id'));
				}
				if($event['rvbarn']!=''){
					foreach ($event['rvbarn'] as $rvbarn) {
						$countcurrentrvlots += count(array_column($rvbarn['rvstall'], 'id'));
					}
				}


				$bookedevents = $this->booking->getBooking('all', ['users','booking','event','barnstall','rvbarnstall'],['eventid'=> $event['id'], 'status' => '1']);

				if(count($bookedevents) > 0){
					
					foreach($bookedevents as $bookedevent){
						$barnstall = $bookedevent['barnstall'];
						$rvbarnstall = $bookedevent['rvbarnstall'];
						if(count($barnstall) > 0) $countcurrentbookingstalls += count(array_column($barnstall, 'stall_id'));
						if(count($rvbarnstall) > 0) $countcurrentbookingrvlots += count(array_column($rvbarnstall, 'stall_id'));
					}
				}
	      	}
      	
	      	$pastevent = $this->booking->getBooking('all', ['booking','event','payment','barnstall','rvbarnstall'],['userid'=> $allids, 'ltenddate' => $date, 'status' => '1']);

			foreach ($pastevent as $event) {  
	  			$countpastevent[] = $event['event_id'];
	  			$barnstall = $event['barnstall'];
	  			$rvbarnstall = $event['rvbarnstall'];
	  			if(count($barnstall) > 0) $countpaststall += count(array_column($barnstall, 'stall_id'));
	  			if(count($rvbarnstall) > 0) $countpaststall += count(array_column($rvbarnstall, 'stall_id'));
	  			$countpastamount += $event['amount'];
	      	}
      	}
		

		$data['monthlyincome'] = $this->booking->getBooking('all', ['booking', 'event', 'payment'],['userid'=> $allids, 'status' => '1'], ['groupby' => 'DATE_FORMAT(b.created_at, "%M %Y")', 'select' => 'SUM(p.amount) as paymentamount, DATE_FORMAT(b.created_at, "%M %Y") AS month']);
		
		if($usertype=='2' || ($usertype=='4' && $parenttype == '2')){
			$data['upcomingevents'] = $this->event->getEvent('all', ['event'],['userids' => $allids, 'fenddate'=> $date, 'status' => ['1']]);
		}
		
		if($usertype=='3' || ($usertype=='4' && $parenttype == '3')){
			$data['upcomingevents'] = $this->event->getEvent('all', ['event'],['userids' => $allids, 'start_date' => $date, 'status' => ['1'], 'type' => '1']);
		}
    	
    	if($usertype=='5'){

    		$horseevent = $this->booking->getBooking('all', ['booking','event','payment','barnstall','rvbarnstall'],['userid'=> $allids,'ltcheck_out' => $date, 'status' => '1']);

    		foreach ($horseevent as $event) {  
	  			$countpastevent[] = $event['event_id'];
	  			$barnstall = $event['barnstall'];
	  			$rvbarnstall = $event['rvbarnstall'];
	  			if(count($barnstall) > 0) $countpaststall += count(array_column($barnstall, 'stall_id'));
	  			if(count($rvbarnstall) > 0) $countpaststall += count(array_column($rvbarnstall, 'stall_id'));
	  			$countpastamount += $event['amount'];
	      	}

    		$currentreservation = $this->booking->getBooking('all', ['booking','event','payment','barnstall','rvbarnstall'],['userid'=> $allids,'gtcheck_in' => $date, 'status' => '1']);
    		foreach ($currentreservation as $event) {  
	  			$countcurrentevent[] = $event['event_id'];
	  			$barnstall = $event['barnstall'];
	  			$rvbarnstall = $event['rvbarnstall'];
	  			if(count($barnstall) > 0) $countcurrentstall += count(array_column($barnstall, 'stall_id'));
	  			if(count($rvbarnstall) > 0) $countcurrentrvlots += count(array_column($rvbarnstall, 'stall_id'));
	  			$countpayedamount += $event['amount'];
	      	}

      		$data['countcurrentevent'] 	= count(array_unique($countcurrentevent));
    		$data['countpayedamount'] 	= $countpayedamount;
    		$data['countcurrentstall'] 	= $countcurrentstall;
    	}

		if($usertype=='6' || ($usertype=='4' && $parenttype == '2') || ($usertype=='4' && $parenttype == '3')){
    		$data['checkinstall'] = $this->booking->getBooking('all', ['users','booking','event','barnstall','rvbarnstall'],['userid'=> $allids, 'stallcheck_in'=>[$tday]], ['orderby' =>'e.id asc', 'groupby' => 'e.id']);
    		$data['checkoutstall'] = $this->booking->getBooking('all', ['users','booking','event','barnstall','rvbarnstall'],['userid'=> $allids, 'stallcheck_out'=>[$tday]], ['orderby' =>'e.id asc', 'groupby' => 'e.id']);
      	}

        $data[] =[
		        'userdetail' => $userdetail,
                'countcurrentstall' => $countcurrentstall,
                'countcurrentrvlots' => $countcurrentrvlots,
				'countcurrentbookingstalls' => $countcurrentbookingstalls,
                'countcurrentbookingrvlots' => $countcurrentbookingrvlots,
                'countcurrentavailablestalls' => ($countcurrentstall - $countcurrentbookingstalls),
                'countcurrentavailablervlots' => ($countcurrentrvlots - $countcurrentbookingrvlots),
                'pastevent' => count(array_unique($countpastevent)),
                'countpaststall' => $countpaststall,
				'countpastamount' => $countpastamount,
		];
      	
     		$json = ['1', '1 Record(s) Found', $usertype ,$data];
    
        } else {
                $json = ['0', 'No Records Found.', [],[]];
            }
        } else {
            $json = ['0', $validation->getErrors(), [],[]];
        } 
		echo json_encode([
            'status'         	=> $json[0],
            'message'       	=> $json[1],
            'usertype'      	=> $json[2],
			'result'         	=> $json[3],
			
        ]);

        die;		
    }

    public function lockunlock(){
    	$post 		= $this->request->getPost();
    	$validation = \Config\Services::validation();
    	$validation->setRules(
	            [
	                'stallid'   => 'required',
	            ],

	            [
	                'stallid
	                ' => [
	                    'required' => 'User ID is required.',
	                ],
	            ]
	        );

    	if ($validation->withRequest($this->request)->run()) {
    		$post['stallid'] 		= explode(',', $post['stallid']);

    		if(isset($post['lockunlock']) || isset($post['dirtyclean'])){
    			$result = $this->booking->updatedata($post);
    			if($result){
    				$json = ['1', 'Stall Updated successfully', $result];
    			}
	    	}else {
                $json = ['0', 'Try Later.', []];
            }
        }else{
            $json = ['0', $validation->getErrors(), []];
		}
		
		echo json_encode([
            'status'      => $json[0],
            'message'     => $json[1],
            'result'      => $json[2],
        ]);

        die;
    }
}
