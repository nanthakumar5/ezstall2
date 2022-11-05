<?php

namespace App\Controllers\Admin\Banner;

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
				$this->session->setFlashdata('success', 'Banner deleted successfully.');
				return redirect()->to(getAdminUrl().'/banner'); 
			}else{
				$this->session->setFlashdata('danger', 'Try Later');
				return redirect()->to(getAdminUrl().'/banner'); 
			}
        }
		
		return view('admin/banner/index');
	}
	
	public function DTbanner()
	{
		$post 			= $this->request->getPost();
		$totalcount 	= $this->cms->getCms('count', ['cms'], ['status' => ['1'], 'type' => ['3']]+$post);
		$results 		= $this->cms->getCms('all', ['cms'], ['status' => ['1'], 'type' => ['3']]+$post);
		
		$totalrecord 	= [];
				
		if(count($results) > 0){
			$action = '';
			foreach($results as $key => $result){
				$action = 	'<a href="'.getAdminUrl().'/banner/action/'.$result['id'].'">Edit</a> / 
							<a href="javascript:void(0);" data-id="'.$result['id'].'" class="delete">Delete</a>';
				
				$totalrecord[] = 	[
										'title' 			=> 	$result['title'],
										'content'           =>  $result['content'],
										'image'           	=>  $result['image'],
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
			$result = $this->cms->getCms('row', ['cms'], ['id' => $id, 'status' => ['1'], 'type' => ['3']]);
			if($result){
				$data['result'] = $result;
			}else{
				$this->session->setFlashdata('danger', 'No Record Found.');
				return redirect()->to(getAdminUrl().'/banner'); 
			}
		}
		
		if ($this->request->getMethod()=='post')
        {
			$requestdata = $this->request->getPost();
			$requestdata['userid'] 	= getAdminUserID();
			$requestdata['status'] 	= '1';
			$requestdata['type']  	= '3';
			
            $result = $this->cms->action($requestdata);
			
			if($result){
				$this->session->setFlashdata('success', 'Banner saved successfully.');
				return redirect()->to(getAdminUrl().'/banner'); 
			}else{
				$this->session->setFlashdata('danger', 'Try Later.');
				return redirect()->to(getAdminUrl().'/banner'); 
			}
        }
		
		return view('admin/banner/action', $data);
	}	
}
