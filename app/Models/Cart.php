<?php

namespace App\Models;

use App\Models\BaseModel;

class Cart extends BaseModel
{	
	public function getCart($type, $querydata=[], $requestdata=[], $extras=[])
    {  
    	$select 			= [];
		
		if(in_array('cart', $querydata)){
			$data		= 	['c.*'];							
			$select[] 	= 	implode(',', $data);
		}

		if(in_array('event', $querydata)){
			$data		= 	['e.name eventname, e.location eventlocation, e.description eventdescription, e.cleaning_fee eventcleaningfee, e.zipcode eventzipcode'];							
			$select[] 	= 	implode(',', $data);
		}
		
		if(in_array('barn', $querydata)){
			$data		= 	['b.name barnname'];							
			$select[] 	= 	implode(',', $data);
		}
		
		if(in_array('stall', $querydata)){
			$data		= 	['s.name stallname, s.charging_id chargingid'];							
			$select[] 	= 	implode(',', $data);
		}
		
		if(in_array('product', $querydata)){
			$data		= 	['p.name productname'];							
			$select[] 	= 	implode(',', $data);
		}

		$query = $this->db->table('cart c');

		if(in_array('event', $querydata)) $query->join('event e', 'e.id=c.event_id', 'left');
		if(in_array('barn', $querydata)) $query->join('barn b', 'b.id=c.barn_id', 'left');
		if(in_array('stall', $querydata)) $query->join('stall s', 's.id=c.stall_id', 'left');
		if(in_array('product', $querydata)) $query->join('products p', 'p.id=c.product_id', 'left');

		if(isset($extras['select'])) 					        $query->select($extras['select']);
		else											        $query->select(implode(',', $select));
		
		if(isset($requestdata['user_id'])) 				        $query->where('c.user_id', $requestdata['user_id']);
		if(isset($requestdata['event_id'])) 				    $query->where('c.event_id', $requestdata['event_id']);
		if(isset($requestdata['barn_id'])) 				    	$query->where('c.barn_id', $requestdata['barn_id']);
		if(isset($requestdata['stall_id'])) 				    $query->where('c.stall_id', $requestdata['stall_id']);
		if(isset($requestdata['product_id'])) 				    $query->where('c.product_id', $requestdata['product_id']);
		if(isset($requestdata['ip'])) 				    		$query->where('c.ip', $requestdata['ip']);
		if(isset($requestdata['type'])) 				    	$query->where('c.type', $requestdata['type']);
		if(isset($requestdata['neq_user_id'])) 				    $query->where('c.user_id !=', $requestdata['neq_user_id']);
		if(isset($requestdata['neq_product_id'])) 				$query->where('c.product_id !=', $requestdata['neq_product_id']);
		
		if(isset($requestdata['checkin']) && isset($requestdata['checkout'])){
			$query->groupStart();
				$query->where("('".date('Y-m-d', strtotime($requestdata['checkin']))."' BETWEEN c.check_in AND DATE_ADD(c.check_out, INTERVAL -1 DAY))");
				$query->orWhere("('".date('Y-m-d', strtotime($requestdata['checkout']))."' BETWEEN c.check_in AND DATE_ADD(c.check_out, INTERVAL -1 DAY))");
				$query->orWhere("(c.check_in BETWEEN '".date('Y-m-d', strtotime($requestdata['checkin']))."' AND '".date('Y-m-d', strtotime($requestdata['checkout']))."')");
				$query->orWhere("(DATE_ADD(c.check_out, INTERVAL -1 DAY) BETWEEN '".date('Y-m-d', strtotime($requestdata['checkin']))."' AND '".date('Y-m-d', strtotime($requestdata['checkout']))."')");
			$query->groupEnd();
		}
		
		if($type=='count'){
			$result = $query->countAllResults();
		}else{
			$query = $query->get();
			if($type=='all') 		$result = $query->getResultArray();
			elseif($type=='row') 	$result = $query->getRowArray();
			
			if($type='all'){
				if(in_array('tax', $querydata)){ 
			    	foreach ($result as $key => $value) {
						if($value['eventzipcode']){
								$tax = 	$this->db->table('tax t')
								->join('event e', 't.from_tax = e.zipcode', 'left')
								->select('t.tax_price')
								->groupStart()->where("'".$value['eventzipcode']."' BETWEEN t.from_tax AND t.to_tax")->groupEnd()
								->get()
								->getRowArray();
								$result[$key]['tax'] = $tax;
						}
					}
				}
			}
		}
		return $result;
    }
	
	public function action($data)
	{   
		$this->db->transStart();

		$ip = $data['ip'];
		$request['ip'] = $ip;
		$request['user_id'] = (isset($data['user_id'])&& $data['user_id']!='') ? $data['user_id'] : 0;
		
		if(isset($data['event_id'])&& $data['event_id']!='')           			$request['event_id'] 	    		= $data['event_id'];
		if(isset($data['barn_id'])&& $data['barn_id']!='')           			$request['barn_id'] 	    		= $data['barn_id'];
		if(isset($data['stall_id'])&& $data['stall_id']!='')           			$request['stall_id'] 	    		= $data['stall_id'];
		if(isset($data['product_id'])&& $data['product_id']!='')        		$request['product_id'] 	    		= $data['product_id'];
		if(isset($data['mwn_price'])&& $data['mwn_price']!='')     				$request['mwn_price'] 	        	= (isset($data['mwn_price']) ? implode(',', $data['mwn_price']) : '');
		if(isset($data['price'])&& $data['price']!='')     						$request['price'] 	        		= (isset($data['pricetype']) && in_array($data['pricetype'], ['1', '2','3'])) ? '0' : $data['price'];
		if(isset($data['subscriptionprice'])&& $data['subscriptionprice']!='')	$request['subscription_price'] 	    = $data['subscriptionprice'];
		if(isset($data['pricetype'])&& $data['pricetype']!='')     				$request['price_type'] 	    		= $data['pricetype'];
		if(isset($data['quantity'])&& $data['quantity']!='')           			$request['quantity'] 	    		= $data['quantity'];
		if(isset($data['total'])&& $data['total']!='')           				$request['total'] 	   				= $data['total'];
		if(isset($data['startdate'])&& $data['startdate']!='')         			$request['check_in'] 	    		= date('Y-m-d', strtotime($data['startdate']));
		if(isset($data['enddate'])&& $data['enddate']!='')             			$request['check_out'] 	    		= date('Y-m-d', strtotime($data['enddate']));
		if(isset($data['type'])&& $data['type']!='')             				$request['type'] 	    			= $data['type'];
		if(isset($data['flag'])&& $data['flag']!='')             				$request['flag'] 	    			= $data['flag'];

		if($data['actionid']==""){
			if($request['type']=='1')	$this->db->table('cart')->delete(['ip' => $ip, 'type' => '2']);
			if($request['type']=='2') 	$this->db->table('cart')->delete(['ip' => $ip, 'type' => '1']);
			
			if($request['flag']=='3' || $request['flag']=='4'){
				$checkproduct = $this->db->table('cart')->where(['ip' => $request['ip'], 'user_id' => $request['user_id'], 'event_id' => $request['event_id'], 'product_id' => $request['product_id']])->get()->getRowArray();
			}
			
			$request['datetime'] = date('Y-m-d H:i:s');
			
			if(isset($checkproduct)){
				$cart = $this->db->table('cart')->where('id', $checkproduct['id'])->update($request);
				$insertid = $this->db->insertID();
			}else{
				$cart = $this->db->table('cart')->insert($request);
				$insertid = $this->db->insertID();
			}
		}else{
			$this->db->table('cart')->update($request, ['ip' => $ip, 'user_id' => 0]);
			$insertid = $this->db->insertID();
		}
		
		if(isset($insertid) && $this->db->transStatus() === FALSE){
			$this->db->transRollback();
			return false;
		}else{
			$this->db->transCommit();
			return $insertid;
		}
	}

	public function delete($data)
	{
		$this->db->transStart();
		
		$request = [];
		$request['type'] = $data['type'];
		if(isset($data['ip']))            	 	$request['ip']    		= $data['ip'];
		if(isset($data['user_id']))            	$request['user_id']    	= $data['user_id'];
		if(isset($data['stall_id']))            $request['stall_id'] 	= $data['stall_id'];
		if(isset($data['product_id']))          $request['product_id'] 	= $data['product_id'];
		
		if(count($request)){
			$cart = $this->db->table('cart')->delete($request);
		}

		if(isset($cart) && $this->db->transStatus() === FALSE){
			$this->db->transRollback();
			return false;
		}else{
			$this->db->transCommit();
			return true;
		}
	}
	
	public function removeReserved($time)
	{	
		$request 	= 	service('request');
		$ip 		= 	$request->getIPAddress();
		$datetime 	= 	date("Y-m-d H:i:s");
		
		$cart 		= 	$this->db->table('cart')
						->select('max(datetime) as datetime, user_id')
						->groupBy('user_id', 'desc')
						->where('ip', $ip)
						->having('DATE_ADD(datetime, INTERVAL '.$time.' MINUTE) <=', $datetime)
						->get()
						->getResultArray();
		
		if(count($cart) > 0){
			foreach($cart as $data){
				$this->db->table('cart')->delete(['user_id' => $data['user_id']]);
			}
		}
	}
	
	public function getReserved($time) 
	{	
		$request 	= 	service('request');
		$ip 		= 	$request->getIPAddress();
		$datetime 	= 	date("Y-m-d H:i:s");
		
		$cart 		= 	$this->db->table('cart')
						->select('max(datetime) as datetime')
						->groupBy('user_id', 'desc')
						->where('ip', $ip)
						->having('DATE_ADD(datetime, INTERVAL '.$time.' MINUTE) >=', $datetime)
						->get()
						->getRowArray();
		
		if($cart){
			return date('Y-m-d H:i:s', strtotime('+'.$time.' minutes', strtotime($cart['datetime'])));
		}else{
			return false;
		}
	}
}