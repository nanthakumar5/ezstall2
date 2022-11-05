<?php

namespace App\Controllers\Admin\Facility;

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
				$this->session->setFlashdata('success', 'Facility deleted successfully.');
				return redirect()->to(getAdminUrl().'/facility'); 
			}else{
				$this->session->setFlashdata('danger', 'Try Later');
				return redirect()->to(getAdminUrl().'/facility'); 
			}
        }
		
		return view('admin/facility/index');
	}
		
	public function DTfacility()
	{
		$post 			= $this->request->getPost();
		$totalcount 	= $this->event->getEvent('count', ['event'], ['status' => ['1', '2'], 'type' => '2']+$post);
		$results 		= $this->event->getEvent('all', ['event'], ['status' => ['1', '2'] ,'type' => '2']+$post);

		$totalrecord 	= [];
				
		if(count($results) > 0){
			$action = '';
			foreach($results as $key => $result){
				$action = 	'<a href="'.getAdminUrl().'/facility/action/'.$result['id'].'">Edit</a> / 
							<a href="javascript:void(0);" data-id="'.$result['id'].'" class="delete">Delete</a> /
							<a href="'.getAdminUrl().'/facility/view/'.$result['id'].'" data-id="'.$result['id'].'" class="view">View</a>
							';
				
				$totalrecord[] = 	[
										'name' 				=> 	$result['name'],
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
			$result = $this->event->getEvent('row', ['event', 'barn', 'stall', 'rvbarn', 'rvstall', 'feed', 'shaving'], ['id' => $id, 'status' => ['1', '2'], 'type' => '2']);
			if($result){
				$data['result'] = $result;
			}else{
				$this->session->setFlashdata('danger', 'No Record Found.');
				return redirect()->to(getAdminUrl().'/facility'); 
			}
		}
		
		if ($this->request->getMethod()=='post')
        { 
        	$requestData = $this->request->getPost();

        	if(isset($requestData['start_date'])) $requestData['start_date'] 	= formatdate($requestData['start_date']);
    		if(isset($requestData['end_date'])) $requestData['end_date'] 		= formatdate($requestData['end_date']);

            $result = $this->event->action($requestData);
			
			if($result){
				$this->session->setFlashdata('success', 'Facility saved successfully.');
				return redirect()->to(getAdminUrl().'/facility'); 
			}else{
				$this->session->setFlashdata('danger', 'Try Later.');
				return redirect()->to(getAdminUrl().'/facility'); 
			}
        }
		
		$data['statuslist'] 	= $this->config->status1;
		$data['chargingflag'] 	= $this->config->chargingflag;
		$data['yesno'] 			= $this->config->yesno;
		return view('admin/facility/action', $data);
	}	
	
	public function view($id)
	{
		$result = $this->event->getEvent('row', ['event', 'barn', 'stall', 'rvbarn', 'rvstall', 'bookedstall', 'rvbookedstall'], ['id' => $id, 'status' => ['1', '2'], 'type' => '2']);
		if($result){
			$data['result'] = $result;
		}else{
			$this->session->setFlashdata('danger', 'No Record Found.');
			return redirect()->to(getAdminUrl().'/facility'); 
		}
		
		$data['stallstatus'] = $this->config->status1;
		return view('admin/facility/view', $data);
	}
	public function importbarnstall()
    {	
		$phpspreadsheet = new Spreadsheet();

      	$reader 		= new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
      	$spreadsheet 	= $reader->load($_FILES['file']['tmp_name']);
		$sheetdata 		= $spreadsheet->getActiveSheet()->toArray();
		$array 			= [];
		
		foreach($sheetdata as $key1 => $data1){
			if($key1=='0') continue;
			
			foreach($data1 as $key2 => $data2){
				if($key1=='1' && ($key2%2)=='0'){
					$array[$key2]['name'] = $data2;
				}
				
				if($key1 > '1'  && ($key2%2)=='0'){
					$array[$key2]['stall'][] = ['name' => $data2, 'price' => (isset($data1[$key2+1]) ? $data1[$key2+1] : '')];
				}
			}
		}
		
		$array = array_values($array);
		echo json_encode($array);
    }
}
