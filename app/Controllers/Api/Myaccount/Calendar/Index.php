<?php 
namespace App\Controllers\Api\Myaccount\Calendar;

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
    	$requestData = $this->request->getPost();
		$userdetail 			= getUserDetails($requestData['userid']);
		$userid 				= $userdetail['id'];
		$data['userid'] 		= $userid;

		if(!isset($requestData['id'])){
			$data['facilitylist'] 	= getEventsList(['type' => '2', 'userid' => $userid]);
		}else{ 
			$this->calendar();
		}

		if($data){
    		$json = ['1', 'Success', $data];
       	}else {
            $json = ['0', 'Try Later', []];
        }

		echo json_encode([
            'status'  => $json[0],
            'message' => $json[1],
            'result'  => $json[2],
        ]);

        die();
    }
    public function calendar()
	{
		$requestData = $this->request->getPost(); 

		$event		= $this->event->getEvent('row', ['event', 'barn', 'stall', 'bookedstall', 'rvbarn', 'rvstall', 'rvbookedstall'], ['id' => $requestData['id'], 'status' => ['1'], 'userid' => $requestData['userid'], 'type' => '2'], ['orderby' => 'e.id desc']);
		
		$barn 		= $this->getCalendarEventBarnStall($event, ['heading' => 'Barn', 'barnname' => 'barn', 'stallname' => 'stall', 'bookedstall' => 'bookedstall', 'key' => 0]);
		$rvhookup 	= $this->getCalendarEventBarnStall($event, ['heading' => 'RV Hookup', 'barnname' => 'rvbarn', 'stallname' => 'rvstall', 'bookedstall' => 'rvbookedstall', 'key' => 1]);
		
		$data['resourcedata'] 	= array_merge($barn[0], $rvhookup[0]);
		$data['eventdata'] 		= array_merge($barn[1], $rvhookup[1]);
		
		if($data){
    		$json = ['1', 'Success', $data];
       	}else {
            $json = ['0', 'Try Later', []];
        }

		echo json_encode([
            'status'  => $json[0],
            'message' => $json[1],
            'result'  => $json[2],
        ]);

        die();
	}
	
	public function getCalendarEventBarnStall($event, $extras)
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
				
				$reservedstall 	= $this->stall->getStall('row', ['stall', 'event'], ['stall_id' => $stall['id'], 'facilityid' => $event['id'], 'status' => ['1']]);
				
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
