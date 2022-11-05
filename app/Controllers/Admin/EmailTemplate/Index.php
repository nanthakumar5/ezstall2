<?php

namespace App\Controllers\Admin\EmailTemplate;

use App\Controllers\BaseController;

use App\Models\Emailtemplate;

class Index extends BaseController
{
	public function __construct()
	{  
		$this->emailtemplate  = new Emailtemplate();
    }
	
	public function index() 
	{
		return view('admin/emailtemplate/index');
	}

	public function DTtemplates()
	{		
		$post 			= $this->request->getPost(); 
		$totalcount 	= $this->emailtemplate->getEmailTemplate('count', ['emailtemplate']+$post);
		$results 		= $this->emailtemplate->getEmailTemplate('all', ['emailtemplate']+$post);

		$totalrecord 	= [];
				
		if(count($results) > 0){
			$action = '';
			foreach($results as $key => $result){
				$action = 	'<a href="'.getAdminUrl().'/emailtemplate/action/'.$result['id'].'">Edit</a>'; 
				$totalrecord[] = 	[
										'name' 	    => 	$result['name'],
										'subject'  	=>  $result['subject'],
										'message'  	=>  $result['message'],
										'action'	=> 	'
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
			$result = $this->emailtemplate->getEmailTemplate('row', ['emailtemplate'], ['id' => $id]);
			if($result){
				$data['result'] = $result;
			}else{
				$this->session->setFlashdata('danger', 'No Record Found.');
				return redirect()->to(getAdminUrl().'/emailtemplate'); 
			}
		}
		
		if ($this->request->getMethod()=='post')
        {
			$requestdata 	= $this->request->getPost();
            $result 		= $this->emailtemplate->action($requestdata);
			
			if($result){
				$this->session->setFlashdata('success', 'Template Updated successfully.');
				return redirect()->to(getAdminUrl().'/emailtemplate'); 
			}else{
				$this->session->setFlashdata('danger', 'Try Later.');
				return redirect()->to(getAdminUrl().'/emailtemplate'); 
			}
        }
		
		return view('admin/emailtemplate/action', $data);
	}	
}
