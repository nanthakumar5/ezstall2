<?php
namespace App\Controllers\Admin\Profile;

use App\Controllers\BaseController;
use App\Models\Users;

class Index extends BaseController
{
	public function __construct()
	{
		$this->users = new Users();	
	}

	public function index()
    {
    	if ($this->request->getMethod()=='post')
		{ 
			$requestData = $this->request->getPost();
			$requestData['userid'] = getAdminUserID();
			$result = $this->users->action($requestData); 
			if($result){ 
				$this->session->setFlashdata('success', 'Your Account Updated Successfully'); 
			}else{ 
				$this->session->setFlashdata('danger', 'Try again Later.');
			}
		}
		
		$data['userdetail'] 	= getAdminUserDetails();
    	return view('admin/profile/index',$data);
    }
}