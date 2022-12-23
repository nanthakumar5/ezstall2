<?php 

namespace App\Controllers\Api\Checkout;

use App\Controllers\BaseController;
use App\Models\Paymentmethod;
use App\Models\Event;
use App\Models\Cart;
use App\Models\Booking;

class Index extends BaseController
{
	public function __construct()
	{
		$this->paymentmethod    = new Paymentmethod(); 
		$this->event        	= new Event();
		$this->cart        		= new Cart();
		$this->booking        	= new Booking();
	}
	
	public function index()
	{	
		$post = $this->request->getPost();
		$validation = \Config\Services::validation();

        $validation->setRules(
            [
                'user_id'    => 'required',
            ],

            [
                'user_id' => [
                    'required' => 'User ID is required.',
                ],
            ]
        );

        if ($validation->withRequest($this->request)->run()) {
			$request 		= service('request');

			$paymentmethod	= $this->paymentmethod->getPaymentmethod('all', ['paymentmethod']);
		    $condition 			= ($post['user_id']!='') ? ['user_id' => $post['user_id'], 'ip' => $request->getIPAddress()] : ['user_id' => 0, 'ip' =>$request->getIPAddress()];

			$result         = $this->cart->getCart('all', ['cart', 'event', 'barn', 'stall', 'product', 'tax'], $condition);

		if($result){
			$setting 				= getSettings();
			$cartreservedtime		= $this->cart->getReserved($setting['cartreservedtime']);
			$timer					= $cartreservedtime ? $cartreservedtime : '';
			$count					= count($result);
			
			$event_id 				= array_unique(array_column($result, 'event_id'))[0];
			$event_name 			= array_unique(array_column($result, 'eventname'))[0];
			$event_location 		= array_unique(array_column($result, 'eventlocation'))[0];
			$event_description 		= array_unique(array_column($result, 'eventdescription'))[0];
			$cleaning_fee 			= array_unique(array_column($result, 'eventcleaningfee'))[0];
		    $check_in       		= formatdate(array_unique(array_column($result, 'check_in'))[0], 1);
		    $check_out      		= formatdate(array_unique(array_column($result, 'check_out'))[0], 1);
		    $start          		= strtotime(array_unique(array_column($result, 'check_in'))[0]);
			$end            		= strtotime(array_unique(array_column($result, 'check_out'))[0]);
			$daydiff           		= ceil(abs($start - $end) / 86400);
			$interval           	= $daydiff==0 ? 1 : $daydiff;
			$type          			= array_unique(array_column($result, 'type'))[0];
			$tax 					= isset($result[0]['tax']['tax_price']) ? $result[0]['tax']['tax_price'] :'0';

			$barnstall = $rvbarnstall = $feed =  $shaving = [];
			$price = 0;
			
			foreach ($result as $res) {
				$singleprice = $res['price'];
					
				if($res['flag']=='1' || $res['flag']=='2'){				
					$singlechargingid 	= $res['chargingid'];
					$singlepricetype 	= $res['price_type'];
					
					if($singlepricetype==0){
						$intervalday = $interval;
						$intervalday = ($intervalday%7==0) ? ($intervalday/7) : $intervalday;
						$intervalday = ($intervalday%30==0) ? ($intervalday/30) : $intervalday;
						if($singlechargingid=='4') $intervalday = 1;
							
						$singletotal = $singlechargingid=='4' ?  $singleprice : $singleprice * $intervalday;
					}else{
						$intervalday = $interval;
						if($singlepricetype=='2') 		$intervalday = ceil($intervalday/7);
						elseif($singlepricetype=='3') 	$intervalday = ceil($intervalday/30);
						elseif($singlepricetype=='4') 	$intervalday = 1;
						elseif($singlepricetype=='5') 	$intervalday = 1;
						
						if($singlepricetype=='1') 		$singletotal = $singleprice * $intervalday;
						elseif($singlepricetype=='2') 	$singletotal = $singleprice * $intervalday;
						elseif($singlepricetype=='3') 	$singletotal = $singleprice * $intervalday;
						elseif($singlepricetype=='4') 	$singletotal = $singleprice;
						elseif($singlepricetype=='5') 	$singletotal = $singleprice;
					}
					
					$barnrvdata = [
						'barn_id' 				=> $res['barn_id'], 
						'barn_name' 			=> $res['barnname'], 
						'stall_id' 				=> $res['stall_id'],
						'stall_name' 			=> $res['stallname'],
						'subscriptionprice' 	=> $res['subscription_price'],
						'price' 				=> $singleprice,
						'pricetype' 			=> $singlepricetype,
						'chargingid' 			=> $singlechargingid,
						'interval' 				=> $interval,
						'intervalday' 			=> $intervalday,
						'total' 				=> $singletotal
					];
					
					if($res['flag']=='1') 		$barnstall[] = $barnrvdata;
					elseif($res['flag']=='2')	$rvbarnstall[] = $barnrvdata;
					
					$price += $singletotal;
				}else if($res['flag']=='3' || $res['flag']=='4'){
					$singlequantity = $res['quantity'];
					$singletotal = $singleprice * $singlequantity;
					
					$feedshavingdata = [
						'product_id'	=> $res['product_id'], 
						'product_name'	=> $res['productname'], 
						'price' 		=> $singleprice,
						'quantity' 		=> $singlequantity,
						'total' 		=> $singletotal
					];
					
					if($res['flag']=='3') 		$feed[] = $feedshavingdata;
					elseif($res['flag']=='4')	$shaving[] = $feedshavingdata;
					
					$price += $singletotal;
				}
			}


			$transactionfee = number_format((($setting['transactionfee'] / 100) * $price), 2);
			$totaldue = $transactionfee+$count;
			$barnstallcolumn = array_column($barnstall, 'barn_id');
			array_multisort($barnstallcolumn, SORT_ASC, $barnstall);
			$rvbarnstallcolumn = array_column($rvbarnstall, 'barn_id');
			array_multisort($rvbarnstallcolumn, SORT_ASC, $rvbarnstall);
			$feedcolumn = array_column($feed, 'product_id');
			array_multisort($feedcolumn, SORT_ASC, $feed);
			$shavingcolumn = array_column($shaving, 'product_id');
			array_multisort($shavingcolumn, SORT_ASC, $shaving);
			$resultdata = [
				'event_id'			=> $event_id, 
				'event_name'		=> $event_name, 
				'event_tax'			=> $tax, 
				'event_location' 	=> $event_location, 
				'event_description' => $event_description, 
				'cleaning_fee' 		=> $cleaning_fee, 
				'transactionfee' 	=> $transactionfee,
				'totaldue' 			=> $totaldue,
				'barnstall'			=> $barnstall,
				'rvbarnstall'		=> $rvbarnstall, 
				'feed'				=> $feed, 
				'shaving'			=> $shaving, 
				'interval' 			=> $interval, 
				'check_in' 			=> $check_in,
				'check_out'			=> $check_out,
				'price' 			=> $price,
				'type' 				=> $type,
				'timer' 			=> $timer,
				'count' 			=> $count,
				'paymentmethod' 	=> $paymentmethod,
				'stripepublickey' 	=> $setting['stripepublickey'],
			];

			if(count($result)>0){
				$json = ['1', '1 Record(s) Found', $resultdata];
			} else {
	            $json = ['0', 'No Record(s) Found', []];
	        }
	    }
		}else {
	            $json = ['0', $validation->getErrors(), []];
	        }

		echo json_encode([
            'status'  => $json[0],
            'message' => $json[1],
            'result'  => $json[2],
        ]);

        die();
	}

	public function action(){
		$post = $this->request->getPost();
		$validation = \Config\Services::validation();

        $validation->setRules(
            [
                'firstname'    		=> 'required',
                'lastname'    		=> 'required',
                'mobile'   	 		=> 'required',
                'paymentmethodid'   => 'required',
                'tc'    			=> 'required',
                'userid'    		=> 'required',
                'email'    			=> 'required',
                'checkin'    		=> 'required',
                'checkout'    		=> 'required',
                'price'    			=> 'required',
                'transactionfee'    => 'required',
                'cleaningfee'    	=> 'required',

                'amount'    		=> 'required',
                'eventid'    		=> 'required',
                'eventuserid'    	=> 'required',
                'eventtax'    		=> 'required',
                'type'    			=> 'required',
                'barnstall'    		=> 'required',
                'rvbarnstall'    	=> 'required',
                'feed'    			=> 'required',
                'shaving'    		=> 'required',
                'page'    			=> 'required',
            ],

            [
                'firstname' => [
                    'required' => 'First Name is required.',
                ],
                'lastname' => [
                    'required' => 'required is required.',
                ],
                'mobile' => [
                    'required' => 'mobile is required.',
                ],
                'paymentmethodid' => [
                    'required' => 'paymentmethodid is required.',
                ],
                'tc' => [
                    'required' => 'tc is required.',
                ],
                'userid' => [
                    'required' => 'userid is required.',
                ],
                'email' => [
                    'required' => 'email is required.',
                ],
                'checkin' => [
                    'required' => 'checkin is required.',
                ],

                'checkout' => [
                    'required' => 'checkout is required.',
                ],
                'price' => [
                    'required' => 'price is required.',
                ],
                'mobile' => [
                    'required' => 'mobile is required.',
                ],
                'transactionfee' => [
                    'required' => 'transactionfee is required.',
                ],

                'cleaningfee' => [
                    'required' => 'cleaningfee is required.',
                ],
                'amount' => [
                    'required' => 'amount is required.',
                ],

                'eventid' => [
                    'required' => 'eventid is required.',
                ],
                'eventtax' => [
                    'required' => 'eventtax is required.',
                ],


                'type' => [
                    'required' => 'type is required.',
                ],
                'barnstall' => [
                    'required' => 'barnstall is required.',
                ],

                'rvbarnstall' => [
                    'required' => 'rvbarnstall is required.',
                ],
                'feed' => [
                    'required' => 'feed is required.',
                ],
                
                'shaving' => [
                    'required' => 'shaving is required.',
                ],
                'page' => [
                    'required' => 'page is required.',
                ],
            ]
        );

        if ($validation->withRequest($this->request)->run()) {
        	if(isset($post['paymentmethodid'])){
				$booking 	= $this->booking->action($post);
				if($booking){
					$this->cart->delete(['user_id' => $post['userid'], 'type' => $post['type']]);
					$json = ['0', 'Booking Successfully', []];
				}else{
					$json = ['0', 'Try Later', []];
				}
			}else{
					$json = ['0', 'Try Later', []];
				}
        }else {
            $json = ['0', $validation->getErrors(), []];
        }

		echo json_encode([
            'status'  => $json[0],
            'message' => $json[1],
            'result'  => $json[2],
        ]);

        die();
	}
}