<?php

namespace App\Controllers\Admin\StripePayments;

use App\Controllers\BaseController;

use App\Models\StripePayments;
use App\Models\Users;
use App\Models\Stripe;

class Index extends BaseController
{
	public function __construct()
	{  
		$this->stripepayments  = new StripePayments();
		$this->users  = new Users();
		$this->stripe  = new Stripe();
    }
	
	public function index()
	{
		return view('admin/stripepayments/index');
	}

	public function DTstripepayments()
	{
		$post 			= $this->request->getPost();
		$totalcount 	= $this->stripepayments->getStripePayments('count', ['stripepayment','users'], ['status' => ['1']]+$post);
		$results 		= $this->stripepayments->getStripePayments('all', ['stripepayment','users'], ['status' => ['1']]+$post);
		$currencysymbol = $this->config->currencysymbol;
		$totalrecord 	= [];
				
		if(count($results) > 0){
			foreach($results as $key => $result){
				$totalrecord[] = 	[
										'username' 			=> 	$result['username'],
										'amount' 			=> 	$currencysymbol.$result['amount'],
									];
			}
		}
		
		$json = array(
			"draw"            => intval($post['draw']),   
			"recordsTotal"    => intval($totalcount),  
			"recordsFiltered" => intval($totalcount),
			"data"            => $totalrecord
		);

		echo json_encode($json);
	}

	public function action()
	{	
		if ($this->request->getMethod()=='post')
        {
			$requestdata 		= $this->request->getPost();
			$userid 			= $requestdata['user_id'];
			$amount 			= $requestdata['amount'];
			$userdetail 		= getUserDetails($userid);
			$stripeaccountId 	= $userdetail['stripe_account_id'];
			if($stripeaccountId!=''){            	
				$transfer = $this->stripe->createTransfer($stripeaccountId, $amount); //echo $transfer;die;
			}else{
				$this->session->setFlashdata('danger', 'Please Connect your Email ID to Stripe account.');
				return redirect()->to(getAdminUrl().'/stripepayments/action'); 
			}
			
			if($transfer){
				$requestdata['userid'] = getAdminUserID();
				$requestdata['status'] = '1';
				
				$this->stripepayments->action($requestdata);
				
				$this->session->setFlashdata('success', 'Paid successfully.');
				return redirect()->to(getAdminUrl().'/stripepayments'); 
			}else{
				$this->session->setFlashdata('danger', 'Please add Connect Stripe Connected Account ID or updated your email id.');
				return redirect()->to(getAdminUrl().'/stripepayments'); 
			}
        }

		return view('admin/stripepayments/action');
	}		
}
