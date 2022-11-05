<?php 

namespace App\Controllers\Site\Login;

use App\Controllers\BaseController;
use App\Models\Users;
use App\Models\Cart;

class Index extends BaseController
{
	public function __construct()
	{
		$this->users = new Users();
		$this->cart = new Cart();		
	}
    
    public function index()
    { 
		if ($this->request->getMethod()=='post')
        {
			$email = $this->request->getPost('email');
			$password = $this->request->getPost('password');
			
			$result = $this->users->getUsers('row', ['users'], ['email' => $email,'password' => $password, 'type' => ['2', '3', '4', '5', '6'],'status' => ['1','2']]);
			if($result){
				if($result['status']=='1'){ 
					if($result['email_status']=='0'){
						$this->session->setFlashdata('danger', 'Email is still not verified.');
						return redirect()->to(base_url().'/login'); 
					}else{
						$this->cart->action(['user_id' => $result['id'], 'ip' => $this->request->getIPAddress(), 'actionid' => 1]);
						$this->session->set('sitesession', ['userid' => $result['id']]);
						return redirect()->to(base_url().'/myaccount/dashboard'); 
					}
				}elseif($result['status']=='2'){
					$this->session->setFlashdata('danger', 'User is inactive, contact admin.');
					return redirect()->to(base_url().'/login'); 
				} else {
					$this->session->setFlashdata('danger', 'Invalid Credentials');
					return redirect()->to(base_url().'/login'); 
				}
			}else{
				$this->session->setFlashdata('danger', 'Invalid Credentials');
				return redirect()->to(base_url().'/login'); 
			}
        }
		
        return view('site/login/index');
    }
}
