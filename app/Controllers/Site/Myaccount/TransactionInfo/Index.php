<?php
namespace App\Controllers\Site\Myaccount\TransactionInfo;

use App\Controllers\BaseController;
use App\Models\StripePayments;

class Index extends BaseController
{
	public function __construct()
	{
		$this->transaction = new StripePayments();
	}

	public function index()
    {
   		$pager = service('pager'); 
		$page = (int)(($this->request->getVar('page')!==null) ? $this->request->getVar('page') :1)-1;
		$perpage =  5; 
		$offset = $page * $perpage;

		$userid     = getSiteUserID();

		$transactioncount = $this->transaction->getStripePayments('count', ['stripepayment','users'],['status' => ['1'],'userid' => $userid]);
		$data['transactions'] = $this->transaction->getStripePayments('all', ['stripepayment','users'], ['status' => ['1'],'userid' => $userid,'start' => $offset, 'length' => $perpage], ['orderby' => 's.id desc']);
	
	    $data['pager'] 			 = $pager->makeLinks($page, $perpage, $transactioncount);
		$data['usertype']        = $this->config->usertype;
		$data['currencysymbol']  = $this->config->currencysymbol;

    	return view('site/myaccount/transactioninfo/index',$data);

    }	
}