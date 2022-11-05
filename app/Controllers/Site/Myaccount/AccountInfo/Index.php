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
    	if ($this->request->getMethod()=='post')
		{ 
			$requestData = $this->request->getPost();
			$stripeemailId	= (isset($requestData['stripe_email'])) ? $requestData['stripe_email'] : '';

			if($stripeemailId!=''){
				$stripeconnect = $this->stripe->stripeconnect($requestData);
			}
			
			$requestData['stripe_account_id'] = $stripeconnect;

			$userid = $this->users->action($requestData); 
			if($userid){ 
				$this->session->setFlashdata('success', 'Your Account Updated Successfully'); 
			}else{ 
				$this->session->setFlashdata('danger', 'Try again Later.');
			}
		}

		$data['userdetail'] = getSiteUserDetails();
    	return view('site/myaccount/accountinfo/index',$data);
    }
}