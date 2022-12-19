<?php

namespace App\Controllers\Api\Event;

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
    
    public function listandsearch()
    { 
    	$todaydate = date('Y_m_d');

        $searchdata = [];
        if($this->request->getGet()!=""){
            if($this->request->getGet('location')!="")   		$searchdata['llocation']    		= $this->request->getGet('location');
    		if($this->request->getGet('start_date')!="")   	 	$searchdata['btw_start_date']    	= formatdate($this->request->getGet('start_date'));
    		if($this->request->getGet('end_date')!="")   	 	$searchdata['btw_end_date']    		= formatdate($this->request->getGet('end_date'));
    		if($this->request->getGet('no_of_stalls')!="")   	$searchdata['no_of_stalls']    		= $this->request->getGet('no_of_stalls');
        }
		
		$eventcount = count($this->event->getEvent('all', ['event', 'users', 'startingstallprice'], $searchdata+['status'=> ['1'], 'type' => '1']));
		$data = $this->event->getEvent('all', ['event', 'users', 'startingstallprice'], $searchdata+['status'=> ['1'], 'type' => '1'], ['orderby' =>'e.id desc', 'groupby' => 'e.id']);
		
		if ($data && count($data) > 0){
			$result=[];
			foreach($data as $datas){  
				$status = checkEvent($datas); 
				$image = ($datas['image']!='') ? base_url().'/assets/uploads/event/'.$datas['image'] : '';
				
				$result[] = [
					'id'                	=> $datas['id'],
					'user_id'           	=> $datas['user_id'],
					'name'             	 	=> $datas['name'],
					'description'       	=> $datas['description'],
					'location'          	=> $datas['location'],
					'city'              	=> $datas['city'],
					'state'             	=> $datas['state'],
					'zipcode'           	=> $datas['zipcode'],
					'latitude'          	=> $datas['latitude'],
					'longitude'         	=> $datas['longitude'],
					'mobile'            	=> $datas['mobile'],
					'startingstallprice'    => $datas['startingstallprice'],
					'start_date'        	=> formatdate($datas['start_date'],1),
				    'end_date'          	=> formatdate($datas['end_date'],1),
					'image'             	=> $image,
					'status'				=> $status['status'],
					'btn'					=> $status['btn'] 
				];
			}
			
			$json = ['1', count($data).' Record(s) Found', $result];		
		} else {
			$json = ['0', 'No Records Found.', []];	
		}
		
      
        echo json_encode([
            'status'      => $json[0],
            'message'     => $json[1],
            'result'     => $json[2],
        ]);

        die;
		
    }
    
    public function detail($id){
        
        $userdetail 		= getSiteUserDetails() ? getSiteUserDetails() : [];
		$userid 			= (isset($userdetail['id'])) ? $userdetail['id'] : 0;
        $event 				= $this->event->getEvent('row', ['event', 'barn', 'stall', 'rvbarn', 'rvstall', 'feed', 'shaving', 'users', 'startingstallprice'],['id' => $id, 'type' =>'1']);

		$data['bookings'] 	= $this->booking->getBooking('row', ['booking', 'event'],['user_id' => $userid, 'eventid' => $id,'status'=> ['1']]);
		$data['comments'] 	= $this->comments->getComments('all', ['comments','users','replycomments'],['commentid' => '0', 'eventid' => $id,'status'=> ['1']]);
		
		if ($data && count($data) > 0){
			$data['event'] =[
				'id' 					=> $event['id'], 
				'user_id'				=> $event['user_id'], 
				'name' 					=> $event['name'], 
				'description' 			=> $event['description'], 
				'location' 				=> $event['location'], 
				'mobile' 				=> $event['mobile'], 
				'startingstallprice' 	=> $event['startingstallprice'], 
				'start_date'        	=> formatdate($event['start_date'],1),
				'end_date'          	=> formatdate($event['end_date'],1),
				'start_time' 			=> $event['start_time'], 
				'end_time' 				=> $event['end_time'],
				'image' 				=> ($event['image']!='' ? base_url().'/assets/uploads/event/'.$event['image'] : ''),
				'stallmap' 				=> ($event['stallmap']!='' ? $event['profile_image']: ''),
				'barn' 					=> $event['barn'],
				'rvbarn' 				=> $event['rvbarn'],
				'feed' 					=> $event['feed'],
				'shaving' 				=> $event['shaving']

			];

			$result[] = $data;

			$json = ['1', count($data).' Record(s) Found', $result];		
		} else {
			$json = ['0', 'No Records Found.', []];	
		}
		
      
        echo json_encode([
            'status'      => $json[0],
            'message'     => $json[1],
            'result'     => $json[2],
        ]);

        die;
    }
    
	
	public function viewallevents(){ 
		
		$datas = $this->event->getEvent('all', ['event', 'users', 'startingstallprice'], ['status'=> ['1'], 'type' => '1'], ['orderby' =>'e.id desc', 'groupby' => 'e.id']);

            if (count($datas) > 0) {
                $results = [];
				foreach ($datas as $data) {

					$startdate   = isset($data['start_date']) ? formatdate($data['start_date'],1) : '';
					$enddate     = isset($data['end_date'])  ? formatdate($data['end_date'],1) : '';
					$image       = isset($data['image'])  ? base_url().'/assets/uploads/event/'.$data['image'] : '';
				
					$results[] = [
						'id'          		=> $data['id'],
						'name'         		=> $data['name'],
						'start_date'   		=> $startdate,
						'end_date'     		=> $enddate,
						'location'     		=> $data['location'],
						'startingstallprice'=> $data['startingstallprice'],
						'image'				=> $image
					];
                }

                $json = ['1', count($datas) . ' Record(s) Found', $results];

            } else {
                $json = ['0', 'No Record(s) Found', []];
            }    

	        echo json_encode([
	            'status'  => $json[0],
	            'message' => $json[1],
	            'result'  => $json[2],
	        ]);
	        die();
		
	}
	
	function checkincheckout(){

		$post 		= $this->request->getPost();
		$eventid 	= $post['eventid'];
		$checkin 	= formatdate($post['checkin']);
		$checkout   = formatdate($post['checkout']); 
		$result['occupied']  	= getOccupied($eventid, ['checkin' => $checkin, 'checkout' => $checkout]);
		$result['reserved'] 	= getReserved($eventid,['checkin' => $checkin, 'checkout' => $checkout]);

		if(count($result)>0){
			$json = ['1', count($result) . ' Record(s) Found', $result];
		} else {
            $json = ['0', 'No Record(s) Found', []];
        }

		echo json_encode([
            'status'  => $json[0],
            'message' => $json[1],
            'result'  => $json[2],
        ]);
        die();
	}

	function cartdetails(){

		$request 			= service('request');
		$type  = '1';
    	$condition 			= '2' ? ['user_id' => '2', 'ip' => $request->getIPAddress()] : ['user_id' => 0, 'ip' =>$request->getIPAddress()] ;
	if($type!='') $condition['type'] = $type;

	$cart 		    = new \App\Models\Cart;
	$result         = $this->cart->getCart('all', ['cart', 'event', 'barn', 'stall', 'product', 'tax'], $condition);

		if($result){
			$setting 				= getSettings();
			$cartreservedtime		= $cart->getReserved($setting['cartreservedtime']);
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


			$barnstallcolumn = array_column($barnstall, 'barn_id');
			array_multisort($barnstallcolumn, SORT_ASC, $barnstall);
			$rvbarnstallcolumn = array_column($rvbarnstall, 'barn_id');
			array_multisort($rvbarnstallcolumn, SORT_ASC, $rvbarnstall);
			$feedcolumn = array_column($feed, 'product_id');
			array_multisort($feedcolumn, SORT_ASC, $feed);
			$shavingcolumn = array_column($shaving, 'product_id');
			array_multisort($shavingcolumn, SORT_ASC, $shaving);
			$return = [
				'event_id'			=> $event_id, 
				'event_name'		=> $event_name, 
				'event_tax'			=> $tax, 
				'event_location' 	=> $event_location, 
				'event_description' => $event_description, 
				'cleaning_fee' 		=> $cleaning_fee, 
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
			];

			if(count($result)>0){
				$json = ['1', count($return) . ' Record(s) Found', $return];
			} else {
	            $json = ['0', 'No Record(s) Found', []];
	        }

			echo json_encode([
	            'status'  => $json[0],
	            'message' => $json[1],
	            'result'  => $json[2],
	        ]);
	        die();
		}
	}
}
