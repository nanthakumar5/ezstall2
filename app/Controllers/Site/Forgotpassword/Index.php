<?php 

namespace App\Controllers\Site\Forgotpassword;

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
			$result = $this->users->getUsers('row', ['users'], ['email' => $email, 'type' => ['2', '3', '4', '5', '6'],'status' => ['1','2']]);
			if($result){
				if($result['status']=='1' && $result['email_status']=='1'){ 
					$this->session->setFlashdata('success', 'Check the email.');
					send_message_template('1', ['userid' => $result['id']]);
					return redirect()->to(base_url().'/login'); 
				} else {
					$this->session->setFlashdata('danger', 'Email not found');
					return redirect()->to(base_url().'/forgotpassword'); 
				}
			}else{
				$this->session->setFlashdata('danger', 'Email not found');
				return redirect()->to(base_url().'/forgotpassword'); 
			}
        }
		
        return view('site/forgotpassword/index');
    }
}
