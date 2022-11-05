<?php 

namespace App\Controllers\Site\Register;

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
            $post = $this->request->getPost();  
            $result = $this->users->action($post); 
			
			if($result){ 
				$encryptid = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 10).$result.substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 5);
				$verificationurl= base_url()."/verification/".$encryptid;
				$email_subject = "Ezstall Registration";
				$email_message = "Hi ".$post['name'].","." \n\n Thank you for Registering in Ezstall.
				\n To activate your account please click below link.".' '.$verificationurl."";

				send_mail($post['email'],$email_subject,$email_message);
				
				$this->session->setFlashdata('success', 'Registered successfully. Check mail for verification.'); 
				return redirect()->to(base_url().'/login'); 
			}else{ 
				$this->session->setFlashdata('danger', 'Try Later.');
				return redirect()->to(base_url().'/register'); 
			}
       
        }
		
		return view('site/register/index');
    }
	
	public function verification($id)
	{
		$decryptid = (int) filter_var($id, FILTER_SANITIZE_NUMBER_INT);

		$post['actionid'] = $decryptid;
		$post['email_status'] = '1';

		$updateaction = $this->users->action($post);
		
		if($updateaction){
			$this->session->setFlashdata('success', 'Your Email is successfully verified.'); 
			return redirect()->to(base_url().'/login'); 
		}
	}
}
