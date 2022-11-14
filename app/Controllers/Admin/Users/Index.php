<?php

namespace App\Controllers\Admin\Users;

use App\Controllers\BaseController;

use App\Models\Users;
use App\Models\Stripe;

class Index extends BaseController
{
	public function __construct()
	{  
		$this->users  = new Users();
		$this->stripe  = new Stripe();
    }
	
	public function index()
	{		
		if ($this->request->getMethod()=='post')
        {
			$requestdata = $this->request->getPost();
			$requestdata['userid'] = getAdminUserID();
			
            $result = $this->users->delete($requestdata);
			
			if($result){
				$this->session->setFlashdata('success', 'User deleted successfully.');
				return redirect()->to(getAdminUrl().'/users'); 
			}else{
				$this->session->setFlashdata('danger', 'Try Later');
				return redirect()->to(getAdminUrl().'/users'); 
			}
        }
		
		return view('admin/users/index');
	}
	
	public function DTusers()
	{
		$post 			= $this->request->getPost();
		$totalcount 	= $this->users->getUsers('count', ['users'], ['status' => ['1', '2'], 'type' => ['2','3','4','5']]+$post);
		$results 		= $this->users->getUsers('all', ['users'], ['status' => ['1','2'], 'type' => ['2','3','4','5']]+$post);
		
		$totalrecord 	= [];
				
		if(count($results) > 0){
			$action = '';
			foreach($results as $key => $result){
				$action = 	'<a href="'.getAdminUrl().'/users/action/'.$result['id'].'">Edit</a> / 
							<a href="javascript:void(0);" data-id="'.$result['id'].'" class="delete">Delete</a>';
				
				$totalrecord[] = 	[
										'name' 				=> 	$result['name'],
										'email'             =>  $result['email'],
										'type'              =>  $this->config->usertype[$result['type']],
										'created_at'        =>  date('m-d-Y H:i:s', strtotime($result['created_at'])),
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
			$result = $this->users->getUsers('row', ['users'], ['id' => $id, 'status' => ['1', '2']]);
			if($result){
				$data['result'] = $result;
			}else{
				$this->session->setFlashdata('danger', 'No Record Found.');
				return redirect()->to(getAdminUrl().'/users'); 
			}
		}
		
		if ($this->request->getMethod()=='post')
        { 
			$requestdata = $this->request->getPost();
			$requestdata['userid'] = getAdminUserID();
            $result = $this->users->action($requestdata);
  
			if($result){
				$this->session->setFlashdata('success', 'Users saved successfully.');
				return redirect()->to(getAdminUrl().'/users'); 
			}else{
				$this->session->setFlashdata('danger', 'Try Later.');
				return redirect()->to(getAdminUrl().'/users'); 
			}
        }
			
		$data['usertype']   = $this->config->usertype;
		$data['userstatus'] = $this->config->status1;
		
		return view('admin/users/action', $data);
	}	
}
