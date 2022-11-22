<?php

namespace App\Controllers\Admin\Event;

use App\Controllers\BaseController;

use App\Models\Event;

class Index extends BaseController
{
	public function __construct()
	{  
		$this->event  = new Event();
    }
	
	public function index()
	{		
		if ($this->request->getMethod()=='post')
        {	
        	$requestData 				= $this->request->getPost();
        	$requestData['userid'] 		= getSiteUserID();

            $result = $this->event->delete($requestData);
			
			if($result){
				$this->session->setFlashdata('success', 'Event deleted successfully.');
				return redirect()->to(getAdminUrl().'/event'); 
			}else{
				$this->session->setFlashdata('danger', 'Try Later');
				return redirect()->to(getAdminUrl().'/event'); 
			}
        }
		
		return view('admin/event/index');
	}
		
	public function DTevent()
	{
		$post 			= $this->request->getPost();
		$totalcount 	= $this->event->getEvent('count', ['event'], ['status' => ['1', '2'], 'type' => '1']+$post);
		$results 		= $this->event->getEvent('all', ['event'], ['status' => ['1', '2'], 'type' => '1']+$post);
		$totalrecord 	= [];
				
		if(count($results) > 0){
			$action = '';
			foreach($results as $key => $result){
				$action = 	'<a href="'.getAdminUrl().'/event/action/'.$result['id'].'">Edit</a> / 
							<a href="javascript:void(0);" data-id="'.$result['id'].'" class="delete">Delete</a> /
							<a href="'.getAdminUrl().'/event/view/'.$result['id'].'" data-id="'.$result['id'].'" class="view">View</a>/
							<a href="'.getAdminUrl().'/comments/'.$result['id'].'" data-id="'.$result['id'].'" class="commentview">View Comments</a>

							';
				
				$totalrecord[] = 	[
										'name' 				=> 	$result['name'],
										'location'          =>  $result['location'],
										'mobile'            =>  $result['mobile'],
										'action'			=> 	'
																	<div class="table-action">
																		'.$action.'
																	</div>
																'
									];
			}
		}
		
		$json = array(
			"draw"            => intval($post['draw']),   
			"recordsTotal"    => intval($totalcount),  
			"recordsFiltered" => intval($totalcount),
			"data"            => $totalrecord
		);

		echo json_encode($json);
	}
	
	public function action($id='')
	{
		if($id!=''){
			$result = $this->event->getEvent('row', ['event', 'barn', 'stall', 'rvbarn', 'rvstall', 'feed', 'shaving'], ['id' => $id, 'status' => ['1', '2'], 'type' => '1']);
			if($result){
				$data['result'] = $result;
			}else{
				$this->session->setFlashdata('danger', 'No Record Found.');
				return redirect()->to(getAdminUrl().'/event'); 
			}
		}
		
		if ($this->request->getMethod()=='post')
        { 
        	$requestData = $this->request->getPost();
        	
        	if(isset($requestData['start_date'])) $requestData['start_date'] 	= formatdate($requestData['start_date']);
    		if(isset($requestData['end_date'])) $requestData['end_date'] 		= formatdate($requestData['end_date']);

            $result = $this->event->action($requestData);
			
			if($result){
				$this->session->setFlashdata('success', 'Event saved successfully.');
				return redirect()->to(getAdminUrl().'/event'); 
			}else{
				$this->session->setFlashdata('danger', 'Try Later.');
				return redirect()->to(getAdminUrl().'/event'); 
			}
        }
		
		$data['barnstall'] 		= view('site/common/barnstall/barnstall1', ['yesno' => $this->config->yesno, 'pricelist' => $this->config->pricelist, 'usertype' => '']+(isset($data) ? $data : []));		
		$data['statuslist'] 	= $this->config->status1;
		$data['googleapikey'] 	= $this->config->googleapikey;
		
		return view('admin/event/action', $data);
	}	
	
	public function view($id)
	{
		$result = $this->event->getEvent('row', ['event', 'barn', 'stall', 'rvbarn', 'rvstall', 'bookedstall', 'rvbookedstall'], ['id' => $id, 'status' => ['1', '2'], 'type' => '1']);
		if($result){
			$data['result'] = $result;
		}else{
			$this->session->setFlashdata('danger', 'No Record Found.');
			return redirect()->to(getAdminUrl().'/event'); 
		}
		
		$data['stallstatus'] = $this->config->status1;
		return view('admin/event/view', $data);
	}
	
}
