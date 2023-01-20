<?php 
namespace App\Controllers\Api\Search;

use App\Controllers\BaseController;
use App\Models\Event;

class Index extends BaseController
{
	public function __construct()
	{
		$this->event = new Event();
	}
    
    public function index()
    {	
		$searchdata = [];
		if($this->request->getGet('location')!="")   		$searchdata['llocation']    		= $this->request->getGet('location');
		if($this->request->getGet('start_date')!="")   	 	$searchdata['btw_start_date']    	= formatdate($this->request->getGet('start_date'));
		if($this->request->getGet('end_date')!="")   	 	$searchdata['btw_end_date']    		= formatdate($this->request->getGet('end_date'));
		if($this->request->getGet('no_of_stalls')!="")   	$searchdata['no_of_stalls']    		= $this->request->getGet('no_of_stalls');
		
		$date = date('Y-m-d');
		$eventcount = count($this->event->getEvent('all', ['event', 'stallavailable'], $searchdata+['status'=> ['1'], 'lenddate' => $date]));
		$data['event'] = $this->event->getEvent('all', ['event', 'users', 'startingstallprice', 'stallavailable'], $searchdata+['status'=> ['1'], 'lenddate' => $date], ['orderby' =>'e.id desc', 'groupby' => 'e.id']);

		$data['searchdata'] = $searchdata;

		if($data){
			$json = ['1', count($data).' Record(s) Found', $data];		
		}else {
			$json = ['0', 'No Records Found.', []];	
		}
		
      
        echo json_encode([
            'status'      => $json[0],
            'message'     => $json[1],
            'result'     => $json[2],
        ]);

        die;
    }
}
