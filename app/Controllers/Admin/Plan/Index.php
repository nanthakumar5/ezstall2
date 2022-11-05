<?php

namespace App\Controllers\Admin\Plan;

use App\Controllers\BaseController;

use App\Models\Plan;

class Index extends BaseController
{
	public function __construct()
	{  
		$this->plans  = new Plan();
    }
	
	public function index()
	{		
		if ($this->request->getMethod()=='post')
        {
			$requestdata = $this->request->getPost();
			$requestdata['userid'] = getAdminUserID();
			
            $result = $this->plans->delete($requestdata);
			
			if($result){
				$this->session->setFlashdata('success', 'Plan deleted successfully.');
				return redirect()->to(getAdminUrl().'/plan'); 
			}else{
				$this->session->setFlashdata('danger', 'Try Later');
				return redirect()->to(getAdminUrl().'/plan'); 
			}
        }
		
		return view('admin/plan/index');
	}
	
	public function DTplan()
	{
		$post 			= $this->request->getPost();
		$totalcount 	= $this->plans->getPlan('count', ['plan'], ['status' => ['1']]+$post);
		$results 		= $this->plans->getPlan('all', ['plan'], ['status' => ['1']]+$post);
		$currencysymbol = $this->config->currencysymbol;
		$totalrecord 	= [];
				
		if(count($results) > 0){
			$action = '';
			foreach($results as $key => $result){
				$action = 	'<a href="'.getAdminUrl().'/plan/action/'.$result['id'].'">Edit</a> / 
							<a href="javascript:void(0);" data-id="'.$result['id'].'" class="delete">Delete</a>';
				
				$totalrecord[] = 	[
										'name' 				=> 	$result['name'],
										'price'           	=>  $currencysymbol.$result['price'],
										'interval'          =>  $this->config->paymentinterval[$result['interval']],
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
			$result = $this->plans->getPlan('row', ['plan'], ['id' => $id, 'status' => ['1']]);
			if($result){
				$data['result'] = $result;
			}else{
				$this->session->setFlashdata('danger', 'No Record Found.');
				return redirect()->to(getAdminUrl().'/plan'); 
			}
		}
		
		if ($this->request->getMethod()=='post')
        {
			$requestdata = $this->request->getPost();
			$requestdata['userid'] = getAdminUserID();
			$requestdata['status'] = '1';
			
            $result = $this->plans->action($requestdata);
			
			if($result){
				$this->session->setFlashdata('success', 'Plan saved successfully.');
				return redirect()->to(getAdminUrl().'/plan'); 
			}else{
				$this->session->setFlashdata('danger', 'Try Later.');
				return redirect()->to(getAdminUrl().'/plan'); 
			}
        }
		
		$data['paymentinterval'] 	= $this->config->paymentinterval;
		$data['paymentuser'] 		= $this->config->paymentuser;

		return view('admin/plan/action', $data);
	}	
}
