<?php 
namespace App\Controllers\Site\Search;

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
    	$pager = service('pager'); 
		$page = (int)(($this->request->getVar('page')!==null) ? $this->request->getVar('page') :1)-1;
		$perpage =  5; 
		$offset = $page * $perpage;
		$userdetail = getSiteUserDetails();
		
		$searchdata = [];
		if($this->request->getGet('location')!="")   		$searchdata['llocation']    		= $this->request->getGet('location');
		if($this->request->getGet('start_date')!="")   	 	$searchdata['btw_start_date']    	= formatdate($this->request->getGet('start_date'));
		if($this->request->getGet('end_date')!="")   	 	$searchdata['btw_end_date']    		= formatdate($this->request->getGet('end_date'));
		if($this->request->getGet('no_of_stalls')!="")   	$searchdata['no_of_stalls']    		= $this->request->getGet('no_of_stalls');
		
		$eventcount = $this->event->getEvent('count', ['event', 'stallavailable'], $searchdata+['status'=> ['1']]);
		$event = $this->event->getEvent('all', ['event', 'stallavailable'], $searchdata+['status'=> ['1'], 'start' => $offset, 'length' => $perpage], ['orderby' =>'e.id desc', 'groupby' => 'e.id']);

		$data['eventdetail'] = $userdetail;
		$data['userdetail'] = $userdetail;
		$data['usertype'] = $this->config->usertype;
		$data['list'] = $event;
		$data['searchdata'] = $searchdata;
        $data['pager'] = $pager->makeLinks($page, $perpage, $eventcount);
		
    	return view('site/search/index', $data);
    }
}
