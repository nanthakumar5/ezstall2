<?php

namespace App\Controllers\Admin\Comments;

use App\Controllers\BaseController;
use App\Models\Comments;

class Index extends BaseController
{
	public function __construct()
	{  
		$this->comments  = new Comments();
	}
	
	public function index($id='')
	{	
		if($id!=''){
			$comments 	= $this->comments->getComments('all', ['comments','users','event','replycomments'],['commentid' => '0', 'eventid' => $id,'status'=> ['1']]);
		}

		if ($this->request->getMethod()=='post')
		{	
			$requestData 				= $this->request->getPost();
			$requestData['userid'] 		= getSiteUserID();
			$eventid 					= $id;

			$result = $this->comments->delete($requestData);
			
			if($result){
				$this->session->setFlashdata('success', 'Comment deleted successfully.');
				return redirect()->to(getAdminUrl().'/comments/'.$eventid); 
			}else{
				$this->session->setFlashdata('danger', 'Try Later');
				return redirect()->to(getAdminUrl().'/comments/'.$eventid); 
			}
		}

		$data['eventid'] 	= $id;
		$data['comments'] 	= $comments;
		return view('admin/comments/index',$data);
	}

	public function action($id='')
	{
		if($id!=''){
			$result = $this->comments->getComments('row', ['comments','users','event','replycomments'], ['id' => $id, 'status' => ['1']]);
			if($result){
				$data['result'] = $result;
			}else{
				$this->session->setFlashdata('danger', 'No Record Found.');
				return redirect()->to(getAdminUrl().'/comments/'.$id); 
			}
		}
		
		if ($this->request->getMethod()=='post')
		{ 
			$requestData = $this->request->getPost();

			$result = $this->comments->action($requestData);
			
			if($result){
				$this->session->setFlashdata('success', 'Comments saved successfully.');
				return redirect()->to(getAdminUrl().'/comments/'.$requestData['eventid']); 
			}else{
				$this->session->setFlashdata('danger', 'Try Later.');
				return redirect()->to(getAdminUrl().'/comments/'.$requestData['eventid']); 
			}
		}
		
		return view('admin/comments/action', $data);
	}
	
}
