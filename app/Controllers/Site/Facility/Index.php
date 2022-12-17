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

		$facilitycount = $this->event->getEvent('count', ['event'], ['status'=> ['1'], 'type' => '2']);
		$facility = $this->event->getEvent('all', ['event', 'users', 'startingstallprice'], ['status'=> ['1'], 'start' => $offset, 'length' => $perpage, 'type' => '2']);
	
		$data['eventdetail'] = $userdetail;
		$data['userdetail'] = $userdetail;
		$data['usertype'] = $this->config->usertype;
		$data['list'] = $facility;
        $data['pager'] = $pager->makeLinks($page, $perpage, $facilitycount);
		
    	return view('site/facility/list', $data);
    }
	
	public function detail($id)
	{
		return $this->facilitydetail($id);
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
		
		return $this->facilitydetail($id, $bookingid);
	}
	
	public function facilitydetail($id, $bookingid='')
    {  
		$userdetail 		= getSiteUserDetails() ? getSiteUserDetails() : [];
		$userid 			= (isset($userdetail['id'])) ? $userdetail['id'] : 0;
		
		if($bookingid!=''){
			$booked = $this->booking->getBooking('row', ['booking', 'barnstall', 'rvbarnstall'], ['id' => $bookingid, 'eventid' => $id, 'user_id' => $userid, 'status'=> ['1']]);
			if(!$booked){
				$this->session->setFlashdata('danger', 'No Record Found');
				return redirect()->to(base_url().'/myaccount/dashboard'); 
			}
		}
		
		$currentdate = date("Y-m-d");
		$event = $this->event->getEvent('row', ['event', 'barn', 'stall', 'rvbarn', 'rvstall', 'feed', 'shaving', 'users'],['id' => $id, 'type' =>'2']);
		$data['detail'] 			= $event;
		$data['barnstall'] 			= view('site/common/barnstall/barnstall2', ['settings' => getSettings(), 'currencysymbol' => $this->config->currencysymbol, 'pricelists' => $this->config->pricelist, 'booked' => (isset($booked) ? $booked : '')]+$data);		
		
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
}
