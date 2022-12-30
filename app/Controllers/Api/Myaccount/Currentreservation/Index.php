<?php

namespace App\Controllers\Api\Myaccount\Currentreservation;

use App\Controllers\BaseController;
use App\Models\Booking;
use App\Models\Payments;
use App\Models\Bookingdetails;
use App\Models\Stripe;

class Index extends BaseController
{

	public function __construct()
    {
    	$this->booking = new Booking();
    	$this->payments = new Payments();
		$this->bookingdetails = new Bookingdetails();	
		$this->stripe  = new Stripe();
    }

    public function index()
    { 
    	$post = $this->request->getPost();
    	$validation = \Config\Services::validation();
    	$validation->setRules(
	            [
	                'user_id'   => 'required',
	                'parent_id'   => 'required',
	            ],

	            [
	                'user_id' => [
	                    'required' => 'User ID is required.',
	                ],
	                'parent_id' => [
	                    'required' => 'Parent_id is required.',
	                ],
	            ]
	        );

    	if ($validation->withRequest($this->request)->run()) {

			$date	= date('Y-m-d');
			$userid = ($post['parent_id']!=0) ? $post['parent_id'] : $post['user_id']; 
			$allids = getStallManagerIDS($userid);
			array_push($allids, $userid);

			$bookings = $this->booking->getBooking('all', ['booking', 'event', 'users', 'barnstall', 'rvbarnstall', 'feed', 'shaving', 'payment','paymentmethod'], ['userid'=> $allids, 'gtenddate'=> $date], ['orderby' => 'b.id desc']);
			if($bookings){
				$result = [];
				foreach ($bookings as $data) {
					$result[] =[
						'id' 						=> $data['id'],
						'firstname' 				=> $data['firstname'],
						'lastname' 					=> $data['lastname'],
						'mobile' 					=> $data['mobile'],
						'check_in' 					=> $data['check_in'],
						'check_out' 				=> $data['check_out'],
						'amount' 					=> $data['amount'],
						'special_notice'			=> $data['special_notice'], 
						'usertype' 					=> $data['usertype'],
						'paymentmethod_name' 		=> $data['paymentmethod_name'],
						'stripe_paymentintent_id' 	=> $data['stripe_paymentintent_id'],
						'paymentid' 				=> $data['paymentid'],
						'created_at' 				=> $data['created_at'],
						'status' 					=> $data['status'],
						'eventname' 				=> $data['eventname'],
						'barn'   					=> $data['barnstall'],
						'rvstall' 					=> ($data['rvbarnstall']!='') ? $data['rvbarnstall']: [],
						'feed' 						=> ($data['feed']!='') ? $data['feed']: [],
						'shaving' 					=> ($data['shaving']!='') ? $data['shaving']: [],
					];
				}
				$json = ['1', count($result).' Record Found.', $result];		 
			}else{
				$json = ['0', 'Try Later.', []];		 
			}
		}else{
            $json = ['0', $validation->getErrors(), []];
		}

		echo json_encode([
			'status' => $json[0],
			'message' => $json[1],
			'result' => $json[2],
		]);

		die;
    }

    public function view(){
    	$post = $this->request->getPost();
    	$validation = \Config\Services::validation();

    	$validation->setRules(
    		[
                'user_id'   => 'required',
                'id'   => 'required',
            ],

            [
                'user_id' => [
                    'required' => 'User ID is required.',
                ],
                'id' => [
                    'required' => 'ID is required.',
                ],
            ]
	    );

	    if ($validation->withRequest($this->request)->run()) {
	    	$data = $this->booking->getBooking('row', ['booking', 'event', 'users','barnstall', 'rvbarnstall', 'feed', 'shaving', 'payment', 'paymentmethod'], ['userid' => [$post['user_id']], 'id' => $post['id']]);

	    	if($data){
	    		$result = [];
	    			$result[] =[
						'id' 					=> $data['id'],
						'firstname' 			=> $data['firstname'],
						'lastname' 				=> $data['lastname'],
						'mobile' 				=> $data['mobile'],
						'check_in' 				=> $data['check_in'],
						'check_out' 			=> $data['check_out'],
						'amount' 				=> $data['amount'],
						'special_notice'		=> $data['special_notice'],
						'usertype' 				=> $data['usertype'],
						'price' 				=> $data['price'],
						'transaction_fee' 		=> $data['transaction_fee'],
						'cleaning_fee' 			=> $data['cleaning_fee'],
						'event_tax' 			=> $data['event_tax'],
						'paymentmethod_name' 	=> $data['paymentmethod_name'],
						'created_at' 			=> $data['created_at'],
						'status' 				=> $data['status'],
						'eventname' 			=> $data['eventname'],
						'barn'   				=> $data['barnstall'],
						'rvstall' 				=> ($data['rvbarnstall']!='') ? $data['rvbarnstall']: [],
						'feed' 					=> ($data['feed']!='') ? $data['feed']: [],
						'shaving' 				=> ($data['shaving']!='') ? $data['shaving']: [],
					];

					$json = ['1', count($result).' Record Found.', $result];
	    	}else{
	    			$json = ['0', 'Try Later.', []];		 
	    	}
	    }else{
            $json = ['0', $validation->getErrors(), []];
		}

	    echo json_encode([
			'status' => $json[0],
			'message' => $json[1],
			'result' => $json[2],
		]);

		die;

    }

    public function paidunpaid(){ 
    	$post = $this->request->getPost();
    	$validation = \Config\Services::validation();

    	$validation->setRules(
    		[
                'bookingid'   => 'required',
                'paid_unpaid'   => 'required',
            ],

            [
                'bookingid' => [
                    'required' => 'Booking Id is required.',
                ],
                'paid_unpaid' => [
                    'required' => 'Paid Unpaid status is required.',
                ],
            ]
	    );

	    if ($validation->withRequest($this->request)->run()) {
			$id = $this->booking->paiddata($post);
			if($id){ 
				$json = ['1', ' Updated successfully.', $id];
			}else{ 
				$json = ['0', ' Try Later.', []];
			}
	    }else{ 
            $json = ['0', $validation->getErrors(), []];
		}

		echo json_encode([
			'status' => $json[0],
			'message' => $json[1],
			'result' => $json[2],
		]);

		die;
    }

    public function striperefunds(){  
    	$post = $this->request->getPost(); 
    	$validation = \Config\Services::validation();

    	$validation->setRules(
    		[
                'paymentintentid'   => 'required',
                'paymentid'   		=> 'required',
                'id'   				=> 'required',
                'amount'   			=> 'required',
            ],

            [
                'paymentintentid' => [
                    'required' => 'paymentintentid Id is required.',
                ],
                'paymentid' => [
                    'required' => 'Payment Id status is required.',
                ],
                'id' => [
                    'required' => 'Id status is required.',
                ],
                'amount' => [
                    'required' => 'amount status is required.',
                ],
            ]
	    );

	    if ($validation->withRequest($this->request)->run()) {
			$data = $this->stripe->striperefunds($post);
			if($data){
				$json = ['1', ' Reservation Cancelled.', $data];
			}else{ 
				$json = ['0', ' Try Later.', []];
			}
	    }else{ 
            $json = ['0', $validation->getErrors(), []];
		}

		echo json_encode([
			'status' => $json[0],
			'message' => $json[1],
			'result' => $json[2],
		]);

		die;
    }

     public function cancelsubscription(){  
    	$requestdata = $this->request->getPost(); 
    	$validation = \Config\Services::validation();

    	$validation->setRules(
    		[
                'bookingid'   => 'required',
                'paymentid'   => 'required',
            ],

            [
                'bookingid' => [
                    'required' => 'paymentintentid Id is required.',
                ],
                'paymentid' => [
                    'required' => 'Payment Id status is required.',
                ],
            ]
	    );

	    if ($validation->withRequest($this->request)->run()) {
	    	$payment = $this->payments->getPayments('row', ['payment'], ['id' => $requestdata['paymentid']]);
			if($payment){
				$data = $this->stripe->cancelSchedule($payment['stripe_scheduled_id']);
				$data = $this->bookingdetails->cancelsubscription(['booking_details_id' => $payment['booking_details_id']]);
				if($data){
					$json = ['1', ' Subscription Cancelled.', $data];
				}else{ 
					$json = ['0', ' Try Later.', []];
				}
			}else{ 
				$json = ['0', ' Try Later.', []];
			}
	    }else{ 
            $json = ['0', $validation->getErrors(), []];
		}

		echo json_encode([
			'status' => $json[0],
			'message' => $json[1],
			'result' => $json[2],
		]);

		die;
    }

    public function updatedstall(){

    }
}