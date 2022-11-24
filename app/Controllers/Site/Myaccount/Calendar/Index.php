<?php 
namespace App\Controllers\Site\Myaccount\Calendar;

use App\Controllers\BaseController;
use App\Models\Event;

class Index extends BaseController
{
	public function __construct()
	{	
		$this->event 	= new Event();
	}
    
    public function index()
    { 			
		$userdetail 		= getSiteUserDetails();
		$userid 			= $userdetail['id'];
		$events				= $this->event->getEvent('all', ['event', 'barn', 'stall'], ['status' => ['1'], 'userid' => $userid, 'type' => '2'], ['orderby' => 'e.id desc']);
		
		$eventresource = [];
		foreach($events as $key => $event){
			$eventresource[$key]['title'] 	= $event['name'];
			$startdate 						= [];
			$enddate 						= [];
			
			foreach($event['barn'] as $barn){
				foreach($barn['stall'] as $stall){
					$startdate[] 	= $stall['start_date'];
					$enddate[] 		= $stall['end_date'];
				}
			}
			
			$eventresource[$key]['start'] 	= min($startdate);
			$eventresource[$key]['end'] 	= max($enddate);
		}
		
		$data['eventresource'] = $eventresource;
		return view('site/myaccount/calendar/index', $data);
    }
}
