<?php

namespace App\Controllers\Api\Cart;

use App\Controllers\BaseController;

use App\Models\Event;
use App\Models\Booking;
use App\Models\Comments;
use App\Models\Cart;

class Index extends BaseController
{
    public function __construct()
    {
		$this->event            = new Event();
		$this->comments         = new Comments();
		$this->booking 	        = new Booking();
		$this->cart 	        = new Cart();
    }

	function index(){ 

		$post = $this->request->getPost();
        $validation = \Config\Services::validation();

        $validation->setRules(
            [
                'user_id'    => 'required',
				'type'       => 'required',
            ],

            [
                'user_id' => [
                    'required' => 'User ID is required.',
                ],
				'type' => [
                    'required' => 'Type id is required.',
                ],
            ]
        );

        if ($validation->withRequest($this->request)->run()) {
			$request 			= service('request');

	    	$condition 			= ($post['user_id']!='') ? ['user_id' => $post['user_id'], 'ip' => $request->getIPAddress()] : ['user_id' => 0, 'ip' =>$request->getIPAddress()];

			if($post['type']) $condition['type'] = $post['type'];

			$result         = $this->cart->getCart('all', ['cart', 'event', 'barn', 'stall', 'product', 'tax'], $condition);

			if($result){
			$setting 				= getSettings();
			$cartreservedtime		= $this->cart->getReserved($setting['cartreservedtime']);
			$timer					= $cartreservedtime ? strtotime($cartreservedtime) : '';
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
						$intervalday 	= $interval;					
						$intervalcalc 	= $interval;	
						
						if(in_array($singlepricetype, ['1', '2', '3'])){
							$singleprice 	= 0;
							$singletotal 	= 0;
							$mwnpricelist	= explode(',', $res['mwn_price']);
							
							$mwnprice = $mwninterval = $mwntotal = [0, 0, 0];
							
							$monthcalc = intdiv($intervalcalc, 30);						
							if($monthcalc > 0){
								$mwnprice[0] = $mwnpricelist[0];
								$mwninterval[0] = $monthcalc;
								$mwntotal[0] = $mwnprice[0] * $mwninterval[0];
								$singletotal += $mwntotal[0];
								$intervalcalc = $intervalcalc - (30 * $monthcalc);
							}
								
							$weekcalc = intdiv($intervalcalc, 7);
							if($weekcalc > 0){
								$mwnprice[1] = $mwnpricelist[1];
								$mwninterval[1] = $weekcalc;
								$mwntotal[1] = $mwnprice[1] * $mwninterval[1];
								$singletotal += $mwntotal[1];
								$intervalcalc = $intervalcalc - (7 * $weekcalc);
							}
							
							if($intervalcalc > 0){
								$mwnprice[2] = $mwnpricelist[2];
								$mwninterval[2] = $intervalcalc;
								$mwntotal[2] = $mwnprice[2] * $mwninterval[2];
								$singletotal += $mwntotal[2];
							}
						}else{
							$intervalday = 1;
							$singletotal = $singleprice;
						}
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
						'total' 				=> $singletotal,
						'mwn_price' 			=> isset($mwnprice) ? $mwnprice : '',
						'mwn_interval' 			=> isset($mwninterval) ? $mwninterval : '0',
						'mwn_total' 			=> isset($mwntotal) ? $mwntotal : '0',
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

				$transactionfee  = number_format((($setting['transactionfee'] / 100) * $price), 2);
				$barnstallcolumn = array_column($barnstall, 'barn_id');
				array_multisort($barnstallcolumn, SORT_ASC, $barnstall);
				$rvbarnstallcolumn = array_column($rvbarnstall, 'barn_id');
				array_multisort($rvbarnstallcolumn, SORT_ASC, $rvbarnstall);
				$feedcolumn = array_column($feed, 'product_id');
				array_multisort($feedcolumn, SORT_ASC, $feed);
				$shavingcolumn = array_column($shaving, 'product_id');
				array_multisort($shavingcolumn, SORT_ASC, $shaving);

				$total ='';
				$totaldue = (number_format($price,2)+ number_format($transactionfee,2)+ number_format($cleaning_fee,2));

				$resultdata = [
					'event_id'			=> $event_id, 
					'event_name'		=> $event_name, 
					'event_tax'			=> $tax, 
					'event_location' 	=> $event_location, 
					'event_description' => $event_description, 
					'cleaning_fee' 		=> number_format($cleaning_fee,2), 
					'totaldue' 			=> number_format($totaldue,2), 
					'transactionfee' 	=> number_format($transactionfee,2), 
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
					'count' 			=> $count
				];

				if(count($result)>0){
					$json = ['1', count($result) . ' Record(s) Found', $resultdata];
				} else {
		            $json = ['0', 'No Record(s) Found', []];
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

	public function stallcartinsert(){ 
		$post = $this->request->getPost();
        $validation = \Config\Services::validation();

        $validation->setRules(
            [
                'event_id'    		=> 'required',
				'barn_id'       	=> 'required',
				'stall_id'       	=> 'required',
				'price'       		=> 'required',/*
				'subscriptionprice' => 'required',*/
				'pricetype'       	=> 'required',
				'quantity'       	=> 'required',
				'startdate'       	=> 'required',
				'enddate'       	=> 'required',
				'type'       		=> 'required',
				'checked'       	=> 'required',
				'flag'       		=> 'required',
				//'mwn_price'       	=> 'required',
            ],

            [
                'event_id' => [
                    'required' => 'Event ID is required.',
                ], 
                'barn_id' => [
                    'required' => 'Barn ID is required.',
                ], 
                'stall_id' => [
                    'required' => 'Stall ID is required.',
                ], 
                'price' => [
                    'required' => 'Price is required.',
                ], 
                /*'subscriptionprice' => [
                    'required' => 'Subscription Price is required.',
                ], */
                'pricetype' => [
                    'required' => 'Price Type is required.',
                ], 
                'quantity' => [
                    'required' => 'Quantity is required.',
                ], 
                'startdate' => [
                    'required' => 'Start Date is required.',
                ], 
                'enddate' => [
                    'required' => 'End Date is required.',
                ], 
                'checked' => [
                    'required' => 'Checked is required.',
                ],
				'type' => [
                    'required' => 'Type is required.',
                ], 
				'flag' => [
                    'required' => 'Flag is required.',
                ],
                'flag' => [
                    'required' => 'Flag is required.',
                ],
                /*'mwn_price' => [
                    'required' => 'mwn_price is required.',
                ],*/
            ]
        );

        if ($validation->withRequest($this->request)->run()) {
			$post['ip']  	= $this->request->getIPAddress();
			$post['user_id'] = $post['user_id'] ? $post['user_id'] : 0;
			$post['actionid'] = ($post['actionid']!='') ? $post['actionid'] : '';
			if($post['checked']==1){
    			$post['startdate'] 		= formatdate($post['startdate']);
				$post['enddate'] 		= formatdate($post['enddate']);

               	$result = $this->cart->action($post); 
               	if($result){
            		$json = ['1', 'Record inserted', $result];
               	}else{
            		$json = ['0', 'Try Later', []]; 
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

	public function stallcartdelete(){ 
		$post = $this->request->getPost();
        $validation = \Config\Services::validation();

        $validation->setRules(
            [
                
				'stall_id'       	=> 'required',
				'type'       		=> 'required',
				'checked'       	=> 'required',
				'user_id'       	=> 'required',
				'flag'       		=> 'required',
            ],

            [ 
                'stall_id' => [
                    'required' => 'Stall ID is required.',
                ], 
                'checked' => [
                    'required' => 'Checked is required.',
                ], 
                'user_id' => [
                    'required' => 'User Id Type is required.',
                ], 
				'type' => [
                    'required' => 'Type is required.',
                ],
				'flag' => [
                    'required' => 'Flag is required.',
                ],
            ]
        );

        if ($validation->withRequest($this->request)->run()) {
			$post['ip']  	= $this->request->getIPAddress();
			$post['user_id'] = $post['user_id'] ? $post['user_id'] : 0;
			$post['actionid'] = ($post['actionid']!='') ? $post['actionid'] : '';
			if($post['checked']==0){
            	$result = $this->cart->delete($post); 
            	if($result){
           			$json = ['1', 'Record Deleted', $result]; 
            	}else{
            		$json = ['0', 'Try Later', []];
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
}
