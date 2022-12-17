<?php 
namespace App\Controllers\Site\Event;

use App\Controllers\BaseController;
use App\Models\Event;
use App\Models\Users;
use App\Models\Comments;
use App\Models\Booking;
use App\Models\Cart;
use App\Models\Bookingdetails;

class Index extends BaseController
{
	public function __construct()
	{
		$this->event        	= new Event();
		$this->users        	= new Users();
		$this->comments         = new Comments();
		$this->booking 	        = new Booking();	
		$this->cart         	= new Cart();	
		$this->bookingdetails 	= new Bookingdetails();		
	}
    
    public function lists()
    {	
    	$pager = service('pager'); 
		$page = (int)(($this->request->getVar('page')!==null) ? $this->request->getVar('page') :1)-1;
		$perpage =  5; 
		$offset = $page * $perpage;
		$userdetail = getSiteUserDetails();

		$eventcount = $this->event->getEvent('count', ['event'], ['status'=> ['1'], 'type' => '1']);
		$event = $this->event->getEvent('all', ['event', 'users', 'startingstallprice'], ['status'=> ['1'], 'start' => $offset, 'length' => $perpage, 'type' => '1'], ['orderby' =>'e.id desc', 'groupby' => 'e.id']);

		$data['eventdetail'] = $userdetail;
		$data['userdetail'] = $userdetail;
		$data['usertype'] = $this->config->usertype;
		$data['list'] = $event;
        $data['pager'] = $pager->makeLinks($page, $perpage, $eventcount);
		
    	return view('site/events/list', $data);
    }
	
	public function detail($id)
    {  	
		if ($this->request->getMethod()=='post'){

			$requestData 	= $this->request->getPost();
        	$result 		= $this->comments->action($requestData);

        	if($result){
				$this->session->setFlashdata('success', 'Your Comment Submitted Successfully');
				return redirect()->to(base_url().'/events/detail/'.$id); 
        	}else {
				$this->session->setFlashdata('danger', 'Try Again');
				return redirect()->to(base_url().'/events/detail/'.$id); 
			}
		}

		return $this->eventdetail($id);
    }
	
	public function updatereservation($id, $bookingid)
	{  
		if ($this->request->getMethod()=='post'){ 
			$requestData = $this->request->getPost();
			
			if(isset($requestData['updatereservation'])){ 
				$updatereservation = json_decode($requestData['updatereservation'], true);
				
				foreach($updatereservation as $key => $value){
					$this->bookingdetails->updatestall(['id' => $key, 'stallid' => $value]);
				}
			}
			$this->session->setFlashdata('success', 'Your Stall is Updated Successfully');
			return redirect()->to(base_url().'/myaccount/bookings');
		}
		
		return $this->eventdetail($id, $bookingid);
	}
	
	public function eventdetail($id, $bookingid='')
    {  	
		$userdetail 		= getSiteUserDetails() ? getSiteUserDetails() : [];
		$userid 			= (isset($userdetail['id'])) ? $userdetail['id'] : 0;
		$usertype 			= (isset($userdetail['type'])) ? $userdetail['type'] : 0;
		
		if($bookingid!=''){
			$booked = $this->booking->getBooking('row', ['booking', 'barnstall', 'rvbarnstall'], ['id' => $bookingid, 'eventid' => $id, 'status'=> ['1']]);
			if(!$booked){
				$this->session->setFlashdata('danger', 'No Record Found');
				return redirect()->to(base_url().'/myaccount/dashboard'); 
			}
		}
		
		$event 		= $this->event->getEvent('row', ['event', 'barn', 'stall', 'rvbarn', 'rvstall', 'feed', 'shaving', 'users', 'startingstallprice'],['id' => $id, 'type' =>'1']);

		$bookings 	= $this->booking->getBooking('row', ['booking', 'event'],['user_id' => $userid, 'eventid' => $id,'status'=> ['1']]);
		$comments 	= $this->comments->getComments('all', ['comments','users','replycomments'],['commentid' => '0', 'eventid' => $id,'status'=> ['1']]);

		$data['detail']  			= $event;
		$data['barnstall'] 			= view('site/common/barnstall/barnstall2', ['checkevent' => checkEvent($event), 'settings' => getSettings(), 'currencysymbol' => $this->config->currencysymbol, 'pricelists' => $this->config->pricelist, 'booked' => (isset($booked) ? $booked : '')]+$data);		
		$data['usertype']			= $usertype;
		$data['bookings']  			= $bookings;
		$data['comments']  			= $comments;
		
		return view('site/events/detail',$data);
    }
	
	public function downloadeventflyer($filename)
	{  
		$filepath   = base_url().'/assets/uploads/eventflyer/'.$filename;		
		header("Content-Type: application/octet-stream"); 
        header("Content-Disposition: attachment; filename=\"". basename($filepath) ."\"");
        readfile ($filepath);
        exit();
	}
	
	public function downloadstallmap($filename)
	{ 
		$filepath   = base_url().'/assets/uploads/stallmap/'.$filename;		
		header("Content-Type: application/octet-stream"); 
        header("Content-Disposition: attachment; filename=\"". basename($filepath) ."\"");
        readfile ($filepath);
        exit();
	}

	public function latlong()
	{
		$data 	= $this->request->getPost(); 
		$lat 	= $data['latitude'];
		$long 	= $data['longitude'];
		$radius = 50; 
		$latlongs 	= $this->event->getEvent('all', ['event', 'latlong'], ['radius' => $radius, 'latitude' => $lat, 'longitude' => $long, 'status'=> ['1'], 'type' => '1'], ['orderby' =>'e.id desc', 'groupby' => 'e.id']);
		
		$bookingbtn = [];
		foreach ($latlongs as $key =>  $latlong) {
			$bookingbtn[] = checkEvent($latlong);
		}
		
		echo json_encode(['latlongs' => $latlongs, 'bookingbtn' => $bookingbtn]);
	}
}
