<?php 

namespace App\Controllers\Site\Facility;

use App\Controllers\BaseController;
use App\Models\Event;
use App\Models\Users;
use App\Models\Stall;
use App\Models\Bookingdetails;
use App\Models\Booking;

class Index extends BaseController
{
	public function __construct()
	{
		$this->event   			= new Event();
		$this->users 			= new Users();
		$this->stall    		= new Stall();
		$this->bookingdetails 	= new Bookingdetails();	
		$this->booking 			= new Booking();
	}
    
    public function lists()
    {	
    	$pager = service('pager'); 
		$page = (int)(($this->request->getVar('page')!==null) ? $this->request->getVar('page') :1)-1;
		$perpage =  5; 
		$offset = $page * $perpage;
		$userdetail = getSiteUserDetails();

		if($this->request->getVar('q')!==null){
			$searchdata = ['search' => ['value' => $this->request->getVar('q')], 'page' => 'events'];
			$data['search'] = $this->request->getVar('q');
		}else{
			$searchdata = [];
			$data['search'] = '';
		}

		if($this->request->getGet('name')!="")   			$searchdata['lname']    			= $this->request->getGet('name');
		if($this->request->getGet('no_of_stalls')!="")   	$searchdata['no_of_stalls']    		= $this->request->getGet('no_of_stalls');
		
		$facilitycount = count($this->event->getEvent('all', ['event', 'stallavailable'], $searchdata+['status'=> ['1'], 'type' => '2']));
		$facility = $this->event->getEvent('all', ['event', 'barn', 'stallavailable'], $searchdata+['status'=> ['1'], 'start' => $offset, 'length' => $perpage, 'type' => '2']);
	
		$data['eventdetail'] = $userdetail;
		$data['userdetail'] = $userdetail;
		$data['usertype'] = $this->config->usertype;
		$data['list'] = $facility;
		$data['searchdata'] = $searchdata;
        $data['pager'] = $pager->makeLinks($page, $perpage, $facilitycount);
		
    	return view('site/facility/list', $data);
    }
	
	public function detail($id)
    {  
		$currentdate = date("Y-m-d");
		$event = $this->event->getEvent('row', ['event', 'barn', 'stall', 'rvbarn', 'rvstall', 'feed', 'shaving', 'users'],['id' => $id, 'type' =>'2']);
		$data['detail'] 			= $event;
		$data['barnstall'] 			= view('site/common/barnstall/barnstall2', ['settings' => getSettings(), 'currencysymbol' => $this->config->currencysymbol, 'pricelists' => $this->config->pricelist]+$data);		
		
    	return view('site/facility/detail',$data);
    }
	
	public function download($filename)
	{  
		$filepath   = base_url().'/assets/uploads/stallmap/'.$filename;		
		header("Content-Type: application/octet-stream"); 
        header("Content-Disposition: attachment; filename=\"". basename($filepath) ."\"");
        readfile ($filepath);
        exit();
	}
	
	public function updatereservation($id='')
	{ 
		$userdetail 		= getSiteUserDetails() ? getSiteUserDetails() : [];
		$userid 			= (isset($userdetail['id'])) ? $userdetail['id'] : 0;
		$usertype 			= (isset($userdetail['type'])) ? $userdetail['type'] : 0;
		$uri 				= current_url(true);
		$bookingid 			= $uri->getSegment(4);

		if ($this->request->getMethod()=='post'){ 
			$requestData 	= $this->request->getPost();
			$datauncheckedstallid = $requestData['uncheckedstallid'];
			$updatedbookingstall = $requestData['updatedbookingstall'];

			foreach($requestData['uncheckedstallid'] as $akey => $bkids){
				foreach($requestData['updatedbookingstall'] as $bkey => $ubstallid){
					
					if($akey==$bkey){
						$bk = $this->bookingdetails->getBookingdetails('row', ['bookingdetails'],['booking_id' => $requestData['bookingid'],'id' => $bkids['bkid']]);
						$result = $this->bookingdetails->action($bk);

						
						$this->bookingdetails->updatedbkstall(['bdid' => $bkids['bkid'], 'updastallid' => $ubstallid['stallid']]);
						}
				}
			}

			if($result){
				$this->session->setFlashdata('success', 'Your Stall is Updated Successfully');
				return redirect()->to(base_url().'/myaccount/bookings'); 
        	}

		}

		$event 		= $this->event->getEvent('row', ['event', 'barn', 'stall', 'rvbarn', 'rvstall', 'feed', 'shaving'],['id' => $id, 'type' =>'2']);

		$bookings 	= $this->booking->getBooking('row', ['booking', 'event', 'users','barnstall', 'rvbarnstall', 'feed', 'shaving','payment','paymentmethod'],['id' => $bookingid,'status'=> ['1']]);

		$data['checkevent'] 		= checkEvent($event);
		$data['detail']  			= $event;
		$data['bookings']  			= $bookings;
		$data['settings']  			= getSettings();
		$data['currencysymbol']  	= $this->config->currencysymbol;
		$data['usertype']			= $usertype;
		
		return view('site/facility/updatereservation',$data);
	}
}
