<?php
namespace App\Controllers\Site\Myaccount\AccountInfo;

use App\Controllers\BaseController;
use App\Models\Users;
use App\Models\Stripe;

class Index extends BaseController
{
	public function __construct()
	{
		$this->users = new Users();
		$this->stripe  = new Stripe();
	}

	public function index()
    {
		$userdetail 		= getSiteUserDetails();
		$stripeaccountid 	= $userdetail['stripe_account_id'];
		
    	if ($this->request->getMethod()=='post')
		{ 
			$requestData = $this->request->getPost();
			
			$userid = $this->users->action($requestData); 
			if($userid){ 
				$this->session->setFlashdata('success', 'Your Account Updated Successfully'); 
				return redirect()->to(base_url().'/myaccount/account'); 
			}else{ 
				$this->session->setFlashdata('danger', 'Try again Later.');
				return redirect()->to(base_url().'/myaccount/account'); 
			}
		}
		
		if($stripeaccountid!=''){
			$retrieveaccount = $this->stripe->retrieveAccount($stripeaccountid);
			if($retrieveaccount && $retrieveaccount['charges_enabled']!=''){
				$data['stripeaccountid'] = $retrieveaccount['id'];
			}
		}
		
		$data['userdetail'] = $userdetail;
		return view('site/myaccount/accountinfo/index',$data);
    }
	
	public function stripeconnect()
	{
		$url 			= base_url().'/myaccount/account';
		$userdetail 	= getSiteUserDetails();
		
		$stripeconnect 	= $this->stripe->createAccount();
		if($stripeconnect){
			$this->users->action(['actionid' => $userdetail['id'], 'userid' => $userdetail['id'], 'stripe_account_id' => $stripeconnect['id']]); 
			
			$accountlink = $this->stripe->createAccountLink($stripeconnect['id'], $url, $url);
			if($accountlink){
				return redirect()->to($accountlink['url']); 
			}else{
				$this->session->setFlashdata('danger', 'Try again Later.');
				return redirect()->to($url); 
			}
		}else{
			$this->session->setFlashdata('danger', 'Try again Later.');
			return redirect()->to($url); 
		}
	}
}