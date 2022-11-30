<?php 
namespace App\Controllers\Site\Myaccount\Calendar;

use App\Controllers\BaseController;
use App\Models\Event;
use App\Models\Stall;

class Index extends BaseController
{
	public function __construct()
	{	
		$this->event 	= new Event();
		$this->stall 	= new Stall();
	}
    
    public function index()
    { 			
		$userdetail 			= getSiteUserDetails();
		$userid 				= $userdetail['id'];
		
		$data['userid'] 		= $userid;
		$data['facilitylist'] 	= getEventsList(['type' => '2', 'userid' => $userid]);
		
		return view('site/myaccount/calendar/index', $data);
    }
}
