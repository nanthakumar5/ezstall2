<?php
namespace App\Controllers\Site\Myaccount\Reservation;

use App\Controllers\BaseController;
use App\Models\Booking;
use App\Models\Stripe;

class Index extends BaseController
{
	public function __construct()
	{
		$this->booking = new Booking();	
		$this->stripe  = new Stripe();
	}

	public function index()
    { 
    	if($this->request->getMethod()=='post'){ 
    		$requestData 					= $this->request->getPost();

    		if(isset($requestData['lockunlock']) || isset($requestData['dirtyclean'])){
    		    $requestData['stallid'] 		= explode(',', $requestData['stallid']);
    			$result = $this->booking->updatedata($requestData);
    			$unlocksms = $this->booking->getBooking('row', ['booking', 'event', 'users', 'cleanbookingdetails', 'cleanstall'], ['stallid' => [$result]]);
    			if($unlocksms['notification_flag']=='1'){
	    			unlockedTemplate($unlocksms);
    			}
	    	}else{
				$unlocksms = $this->stripe->striperefunds($requestData);
			}
			
	    	return redirect()->to(base_url().'/myaccount/bookings');
        }

    	$pager = service('pager'); 
		$page = (int)(($this->request->getVar('page')!==null) ? $this->request->getVar('page') :1)-1;
		$perpage =  5; 
		$offset = $page * $perpage;
		$date	= date('Y-m-d');

    	$userdetail 	= getSiteUserDetails();
		$userid 		= ($userdetail['type']=='4' || $userdetail['type']=='6') ? $userdetail['parent_id'] : getSiteUserID();
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

		$result = $this->booking->getBooking('row', ['booking', 'event', 'users','barnstall', 'rvbarnstall', 'feed', 'shaving','payment','paymentmethod'], ['userid' => [$userid], 'id' => $id]);
		
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
		return view('site/myaccount/reservation/view', $data);
	}	

	public function bookeduser()
	{
		$requestData = $this->request->getPost(); 
		$result = array();

    	if (isset($requestData['search'])) {
			$result = $this->booking->getBooking('all', ['booking', 'cleanbookingdetails', 'cleanstall'], ['page' => 'reservations', 'search' => ['value' => $requestData['search']], 'lockunlock' => '0', 'dirtyclean' => '0']);
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
}