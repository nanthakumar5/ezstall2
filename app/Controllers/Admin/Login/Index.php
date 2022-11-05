<?php

namespace App\Controllers\Admin\Login;

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
			$email = $this->request->getPost('email');
			$password = $this->request->getPost('password');
			
			$result = $this->users->getUsers('row', ['users'], ['email' => $email, 'type' => ['1']]);
			
			if($result){
				if($result['status']=='1'){
					$this->session->set('adminsession', ['userid' => $result['id']]);
					return redirect()->to(getAdminUrl().'/users'); 
				}elseif($result['status']=='0'){
					$this->session->setFlashdata('danger', 'User is inactive, contact admin.');
					return redirect()->to(getAdminUrl()); 
				}else {
					$this->session->setFlashdata('danger', 'Invalid Credentials');
					return redirect()->to(getAdminUrl()); 
				}
			}else{
				$this->session->setFlashdata('danger', 'Invalid Credentials');
				return redirect()->to(getAdminUrl()); 
			}
        }
		
        return view('admin/login/index');
    }
}
