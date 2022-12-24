<?php
namespace App\Controllers\Site\Myaccount\PastActivity;

use App\Controllers\BaseController;
use App\Models\Booking;

class Index extends BaseController
{
	public function __construct()
	{
		$this->booking = new Booking();	
	}

	public function index()
    {
    	$pager = service('pager'); 
		$page = (int)(($this->request->getVar('page')!==null) ? $this->request->getVar('page') :1)-1;
		$perpage =  5; 
		$offset = $page * $perpage;
     	$date	= date('Y-m-d');

    	$userdetail = getSiteUserDetails();
    	$userid     = $userdetail['id'];
		$allids 	= getStallManagerIDS($userid);
		array_push($allids, $userid);

		$bookingcount = $this->booking->getBooking('count', ['booking', 'event', 'users','barnstall','rvbarnstall', 'feed', 'shaving','payment','paymentmethod'], ['userid' => $allids, 'ltenddate' => $date]);
		$data['bookings'] = $this->booking->getBooking('all', ['booking', 'event', 'users','barnstall','rvbarnstall', 'feed', 'shaving','payment','paymentmethod'], ['userid' => $allids, 'ltenddate' => $date, 'start' => $offset, 'length' => $perpage], ['orderby' => 'b.id desc']);

		$data['pager'] 			= $pager->makeLinks($page, $perpage, $bookingcount);
		$data['usertype'] 		= $this->config->usertype;
		$data['bookingstatus'] 	= $this->config->bookingstatus;
		$data['pricelists'] 	= $this->config->pricelist; 
		$data['userdetail']     = $userdetail;
		$data['currencysymbol'] = $this->config->currencysymbol;
		
    	return view('site/myaccount/pastactivity/index',$data);

    }
	
	public function view($id)
	{		
    	$userid = getSiteUserID();

		$result = $this->booking->getBooking('row', ['booking', 'event', 'users','barnstall', 'rvbarnstall', 'feed', 'shaving', 'payment','paymentmethod'], ['userid' => [$userid], 'id' => $id]);

		if($result){
			$data['result'] = $result;
			$data['bookingstatus'] 	= $this->config->bookingstatus;
			$data['pricelists'] 	= $this->config->pricelist;
			$data['currencysymbol'] = $this->config->currencysymbol;
		}else{
			$this->session->setFlashdata('danger', 'No Record Found.');
			return redirect()->to(base_url().'/myaccount/pastactivity'); 
		}
		$data['usertype'] 	= $this->config->usertype;
		$data['userdetail']	= getSiteUserDetails();
		return view('site/myaccount/pastactivity/view', $data);
	}
}