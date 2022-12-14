<?php 

namespace App\Controllers\Site\Changepassword;

use App\Controllers\BaseController;
use App\Models\Users;

class Index extends BaseController
{
	public function __construct()
	{
		$this->users = new Users();
	}
    
    public function index($id, $date)
    { 
		if ($this->request->getMethod()=='post')
        {
			$id = base64_decode($date);
			$password = $this->request->getPost('password');
			
            $result = $this->users->action(['password' => $password, 'actionid' => $id, 'user_id' => $id]); 
			if($result){
				$this->session->setFlashdata('success', 'Password is changed successfully.');
				return redirect()->to(base_url().'/login'); 
			}else{
				$this->session->setFlashdata('danger', 'Try Later');
				return redirect()->to(base_url().'/login'); 
			}
        }
		
		$data['expired'] = 0;
		if(base64_decode($date) < date('Y-m-d H:i:s')){
			$data['expired'] = 1;
		}
		
        return view('site/changepassword/index', $data);
    }
}
