<?php
namespace App\Controllers\Site\Myaccount\Reservation;

use App\Controllers\BaseController;
use App\Models\Booking;
use App\Models\Bookingdetails;
use App\Models\Stripe;
use App\Models\Payments;

class Index extends BaseController
{
	public function __construct()
	{
		$this->booking = new Booking();	
		$this->bookingdetails = new Bookingdetails();	
		$this->stripe  = new Stripe();
		$this->payments  = new Payments();
	}

	public function index()
    { 
    	if($this->request->getMethod()=='post'){ 
    		$requestData = $this->request->getPost();
			$this->stripe->striperefunds($requestData);
	    	return redirect()->to(base_url().'/myaccount/bookings');
        }

    	$pager = service('pager'); 
		$page = (int)(($this->request->getVar('page')!==null) ? $this->request->getVar('page') :1)-1;
		$perpage =  5; 
		$offset = $page * $perpage;
		$date	= date('Y-m-d');

    	$userdetail 	= getSiteUserDetails();
		$userid 		= ($userdetail['type']=='4' || $userdetail['type']=='6') ? $userdetail['parent_id'] : $userdetail['id'];
		$allids 		= getStallManagerIDS($userid);
		array_push($allids, $userid);

		$bookingcount = $this->booking->getBooking('count', ['booking', 'event', 'users'], ['userid'=> $allids, 'gtenddate'=> $date]);
		$data['bookings'] = $this->booking->getBooking('all', ['booking', 'event', 'users', 'barnstall', 'rvbarnstall', 'feed', 'shaving', 'payment','paymentmethod'], ['userid'=> $allids, 'gtenddate'=> $date, 'start' => $offset, 'length' => $perpage], ['orderby' => 'b.id desc']);

		$data['pager'] 			= $pager->makeLinks($page, $perpage, $bookingcount);
		$data['bookingstatus'] 	= $this->config->bookingstatus;
		$data['usertype'] 		= $this->config->usertype;
		$data['currencysymbol'] = $this->config->currencysymbol; 
		$data['userdetail']     = $userdetail;
		
    	return view('site/myaccount/reservation/index', $data);
    }


	public function view($id)
	{
    	$userid = getSiteUserID();
		
		$result = $this->booking->getBooking('row', ['booking', 'event', 'users','barnstall', 'rvbarnstall', 'feed', 'shaving', 'payment', 'paymentmethod'], ['userid' => [$userid], 'id' => $id]);
		
		if($result){
			$data['result'] = $result;
		}else{
			$this->session->setFlashdata('danger', 'No Record Found.');
			return redirect()->to(base_url().'/myaccount/bookings'); 
		}
		
		$data['usertype'] 		= $this->config->usertype;
		$data['bookingstatus'] 	= $this->config->bookingstatus;
		$data['currencysymbol'] = $this->config->currencysymbol; 
		$data['pricelists'] 	= $this->config->pricelist; 
		$data['userdetail'] 	= getSiteUserDetails();
		
		return view('site/myaccount/reservation/view', $data);
	}	

	public function bookeduser()
	{
		$requestData = $this->request->getPost(); 
		$result = array();

    	if (isset($requestData['search'])) {
			$result = $this->booking->getBooking('all', ['booking', 'lucdstall'], ['page' => 'reservations', 'search' => ['value' => $requestData['search']], 'lockunlock' => '0', 'dirtyclean' => '0', 'groupby' => 'b.id']);
		}

		$response['data'] = $result;
		return $this->response->setJSON($result);
	}

	public function paidunpaid()
	{
		if($this->request->getMethod()=='post'){ 
			$id = $this->booking->paiddata($this->request->getPost());
			return redirect()->to(base_url().'/myaccount/bookings/view/'.$id); 
		}
	}
	
	public function cancelsubscription()
	{
		$requestdata = $this->request->getPost();
		
		$payment = $this->payments->getPayments('row', ['payment'], ['id' => $requestdata['paymentid']]);
		if($payment){
			$this->stripe->cancelSchedule($payment['stripe_subscription_id']);
			$this->bookingdetails->cancelsubscription(['booking_details_id' => $payment['booking_details_id']]);
		}
		
		return redirect()->to(base_url().'/myaccount/bookings/view/'.$requestdata['bookingid']); 
	}
}