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
			
			$stripeemailid	= (isset($requestData['stripe_email'])) ? $requestData['stripe_email'] : '';
			if($stripeemailid!=''){
				$stripeRequestData['stripe_email'] = $requestData['stripe_email'];
				$stripeRequestData['stripe_account_id'] = $stripeaccountid;
				$stripeconnect = $this->stripe->stripeconnect($stripeRequestData);
				if($stripeconnect) $requestData['stripe_account_id'] = $stripeconnect;
			}
			
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
			$data['stripelink'] = '';
			$retrieveaccount = $this->stripe->retrieveAccount($stripeaccountid);
			if($retrieveaccount && $retrieveaccount['charges_enabled']==''){
				$url = base_url().'/myaccount/account';
				$accountlink = $this->stripe->createAccountLink($stripeaccountid, $url, $url);
				if($accountlink){
					$data['stripelink'] = $accountlink['url'];
				}
			}
		}
		
		$data['userdetail'] = $userdetail;
		return view('site/myaccount/accountinfo/index',$data);
    }
}