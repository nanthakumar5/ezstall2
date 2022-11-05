<?php
namespace App\Controllers\Site\Myaccount\PaymentInfo;

use App\Controllers\BaseController;
use App\Models\Payments;
use App\Models\Booking;
use App\Models\Event;

class Index extends BaseController
{
	public function __construct()
	{
		$this->event = new Event();
		$this->payments = new Payments();	
		$this->booking = new Booking();	
	}

	public function index()
    {
   		$pager = service('pager'); 
		$page = (int)(($this->request->getVar('page')!==null) ? $this->request->getVar('page') :1)-1;
		$perpage =  5; 
		$offset = $page * $perpage;

    	$userid = getSiteUserID();
		$allids = getStallManagerIDS($userid);
		array_push($allids, $userid);

		$paymentcount = $this->payments->getPayments('count', ['payment','event', 'users','booking'],['ninstatus' => ['0'], 'userid' => $allids]);
		$data['payments'] = $this->payments->getPayments('all', ['payment','event', 'users','booking'], ['ninstatus' => ['0'], 'userid' => $allids, 'start' => $offset, 'length' => $perpage], ['orderby' => 'p.id desc']);

	    $data['pager'] 			 = $pager->makeLinks($page, $perpage, $paymentcount);
		$data['paymentinterval'] = $this->config->paymentinterval;
		$data['paymenttype']     = $this->config->paymenttype;
		$data['paymentstatus']   = $this->config->paymentstatus;
		$data['usertype']        = $this->config->usertype;
		$data['currencysymbol']  = $this->config->currencysymbol;

    	return view('site/myaccount/paymentinfo/index',$data);

    }

	public function view($id)
	{
    	$userid = getSiteUserID();

		$result = $this->payments->getPayments('row', ['payment','event', 'users','booking'], ['ninstatus' => ['0'], 'userid' => [$userid],'id' => $id]);

		if($result){
			$data['result'] = $result;
		}else{
			$this->session->setFlashdata('danger', 'No Record Found.');
			return redirect()->to(base_url().'/myaccount/payments'); 
		}

		$data['usertype']        = $this->config->usertype;
		$data['paymenttype']     = $this->config->paymenttype;
		$data['currencysymbol']  = $this->config->currencysymbol;
		return view('site/myaccount/paymentinfo/view', $data);
	}	
}