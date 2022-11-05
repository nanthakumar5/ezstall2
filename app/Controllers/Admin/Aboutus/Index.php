<?php

namespace App\Controllers\Admin\Aboutus;

use App\Controllers\BaseController;

use App\Models\Cms;

class Index extends BaseController
{
	public function __construct()
	{  
		$this->cms  = new Cms();
    }
	
	public function index() 
	{		
		if ($this->request->getMethod()=='post')
        {
			$requestdata = $this->request->getPost();
			$requestdata['userid'] = getAdminUserID();
			
            $result = $this->cms->delete($requestdata);
			
			if($result){
				$this->session->setFlashdata('success', 'About Us deleted successfully.');
				return redirect()->to(getAdminUrl().'/aboutus'); 
			}else{
				$this->session->setFlashdata('danger', 'Try Later');
				return redirect()->to(getAdminUrl().'/aboutus'); 
			}
        }
		
		return view('admin/aboutus/index');
	}
	
	public function DTaboutus()
	{
		$post 			= $this->request->getPost();
		$totalcount 	= $this->cms->getCms('count', ['cms'], ['status' => ['1'], 'type' => ['1']]+$post);
		$results 		= $this->cms->getCms('all', ['cms'], ['status' => ['1'], 'type' => ['1']]+$post);
		
		$totalrecord 	= [];
				
		if(count($results) > 0){
			$action = '';
			foreach($results as $key => $result){
				$action = 	'<a href="'.getAdminUrl().'/aboutus/action/'.$result['id'].'">Edit</a> / 
							<a href="javascript:void(0);" data-id="'.$result['id'].'" class="delete">Delete</a>';
				
				$totalrecord[] = 	[
										'title' 			=> 	$result['title'],
										'content'           =>  $result['content'],
										'image'           	=>  '<img src="'.base_url().'/assets/uploads/aboutus/'.$result['image'].'" height="50" width="50">' ,
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
		$data = [];

		if($id!=''){
			$result = $this->cms->getCms('row', ['cms'], ['id' => $id, 'status' => ['1'], 'type' => ['1']]);
			if($result){
				$data['result'] = $result;
			}else{
				$this->session->setFlashdata('danger', 'No Record Found.');
				return redirect()->to(getAdminUrl().'/aboutus'); 
			}
		}
		
		if ($this->request->getMethod()=='post')
        {
			$requestdata = $this->request->getPost();
			$requestdata['userid'] 	= getAdminUserID();
			$requestdata['status'] 	= '1';
			$requestdata['type']  	= '1';
			
            $result = $this->cms->action($requestdata);
			
			if($result){
				$this->session->setFlashdata('success', 'About Us saved successfully.');
				return redirect()->to(getAdminUrl().'/aboutus'); 
			}else{
				$this->session->setFlashdata('danger', 'Try Later.');
				return redirect()->to(getAdminUrl().'/aboutus'); 
			}
        }
		
		return view('admin/aboutus/action', $data);
	}	
}
