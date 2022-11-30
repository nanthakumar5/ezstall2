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
		$userdetail 		= getSiteUserDetails();
		$userid 			= $userdetail['id'];
		$event				= $this->event->getEvent('row', ['event', 'barn', 'stall', 'bookedstall', 'rvbarn', 'rvstall', 'rvbookedstall'], ['id' => '1', 'status' => ['1'], 'userid' => $userid, 'type' => '2'], ['orderby' => 'e.id desc']);
		
		$barn 		= $this->getEventBarnStall($event, ['heading' => 'Barn', 'barnname' => 'barn', 'stallname' => 'stall', 'bookedstall' => 'bookedstall', 'key' => 0]);
		$rvhookup 	= $this->getEventBarnStall($event, ['heading' => 'RV Hookup', 'barnname' => 'rvbarn', 'stallname' => 'rvstall', 'bookedstall' => 'rvbookedstall', 'key' => 1]);
		
		$data['resourcedata'] 	= array_merge($barn[0], $rvhookup[0]);
		$data['eventdata'] 		= array_merge($barn[1], $rvhookup[1]);
		
		return view('site/myaccount/calendar/index', $data);
    }
	
	public function getEventBarnStall($event, $extras)
    {
		$heading 		= $extras['heading'];
		$barnname 		= $extras['barnname'];
		$stallname 		= $extras['stallname'];
		$bookedstall 	= $extras['bookedstall'];
		$key 			= $extras['key'];
		
		$resourcedata 	= [];
		$eventdata 		= [];
		
		$resourcedata[$key] = [
			'id' 	=> $heading.$barnname.$event['id'],
			'title' => $heading
		];
		
		foreach($event[$barnname] as $barnkey => $barn){
			$resourcedata[$key]['children'][$barnkey] = [
				'id' 	=> 'barn'.$barn['id'],
				'title' => $barn['name']
			];
				
			foreach($barn[$stallname] as $stallkey => $stall){
				$resourcedata[$key]['children'][$barnkey]['children'][$stallkey] = [
					'id' 	=> 'stall'.$stall['id'],
					'title' => $stall['name']
				];
				
				$reservedstall = $this->stall->getStall('row', ['stall', 'event'], ['stall_id' => $stall['id'], 'facilityid' => $event['id'], 'status' => ['1']]);
				
				if($reservedstall){
					$eventdata[] = [
						'id' 			=> 'reservedstall'.$reservedstall['id'],
						'resourceId' 	=> 'stall'.$stall['id'],
						'title' 		=> $reservedstall['stallname'],
						'start' 		=> $reservedstall['estartdate'],
						'end' 			=> $reservedstall['eenddate'],
						'color' 		=> 'yellow'
					];
				}
				
				foreach($stall[$bookedstall] as $bookedstallkey => $bs){
					$eventdata[] = [
						'id' 			=> 'booking'.$bs['bdid'],
						'resourceId' 	=> 'stall'.$bs['bdstallid'],
						'title' 		=> $bs['name'],
						'start' 		=> $bs['check_in'],
						'end' 			=> $bs['check_out'],
						'color' 		=> 'red'
					];
				}
			}
		}
		
		return [$resourcedata, $eventdata];
    }
}
