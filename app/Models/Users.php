<?php

namespace App\Models;

use App\Models\BaseModel;

class Users extends BaseModel
{	
	public function getUsers($type, $querydata=[], $requestdata=[], $extras=[])
    {  
    	$select 			= [];
		
		if(in_array('users', $querydata)){
			$data		= 	['u.*'];							
			$select[] 	= 	implode(',', $data);
		}

		if(in_array('payment', $querydata)){
			$data		= 	['p.plan_period_end subscriptionenddate'];							
			$select[] 	= 	implode(',', $data);
		}
			
		$query = $this->db->table('users u');
		if(in_array('payment', $querydata)) $query->join('payment p', 'p.id =u.subscription_id' , 'left');

				
		if(isset($extras['select'])) 					$query->select($extras['select']);
		else											$query->select(implode(',', $select));
		
		if(isset($requestdata['id'])) 					$query->where('u.id', $requestdata['id']);
		if(isset($requestdata['neqid'])) 				$query->where('u.id !=', $requestdata['neqid']);
		if(isset($requestdata['email']))         		$query->where('u.email', $requestdata['email']);	
		if(isset($requestdata['password'])) 			$query->where('u.password', md5($requestdata['password']));
		if(isset($requestdata['type'])) 				$query->whereIn('u.type', $requestdata['type']);
		if(isset($requestdata['parentid'])) 			$query->where('u.parent_id', $requestdata['parentid']);
		if(isset($requestdata['status'])) 				$query->whereIn('u.status', $requestdata['status']);

		if($type!=='count' && isset($requestdata['start']) && isset($requestdata['length'])){
			$query->limit($requestdata['length'], $requestdata['start']);
		}
		if(isset($requestdata['order']['0']['column']) && isset($requestdata['order']['0']['dir'])){
			if(isset($requestdata['page']) && $requestdata['page']=='adminusers'){
				$column = ['u.name', 'u.email', 'u.created_at', 'u.type'];
				$query->orderBy($column[$requestdata['order']['0']['column']], $requestdata['order']['0']['dir']);
			}
		}
		if(isset($requestdata['search']['value']) && $requestdata['search']['value']!=''){
			$searchvalue = $requestdata['search']['value'];
						
			if(isset($requestdata['page'])){
				$page = $requestdata['page'];
				
				$query->groupStart();
					if($page=='adminusers'){				
						$query->like('u.name', $searchvalue);
						$query->orLike('u.email', $searchvalue);
						$query->orLike('u.type', $searchvalue);
						$query->orLike('u.created_at', $searchvalue);
					}
				$query->groupEnd();
			}			
		}
		
		if(isset($extras['groupby'])) 	$query->groupBy($extras['groupby']);
		else $query->groupBy('u.id');
		
		if($type=='count'){
			$result = $query->countAllResults();
		}else{
			$query = $query->get();
			
			if($type=='all') 		$result = $query->getResultArray();
			elseif($type=='row') 	$result = $query->getRowArray();
		}
		return $result;
    }
	
	public function action($data)
	{

		$this->db->transStart();
		
		$datetime		= date('Y-m-d H:i:s');
		$userid			= (isset($data['userid'])) ? $data['userid'] : '';		
		$actionid 		= (isset($data['actionid'])) ? $data['actionid'] : '';

		
		if(isset($data['name']) && $data['name']!='')      								$request['name'] 					= $data['name'];
		if(isset($data['email']) && $data['email']!='') 	 							$request['email'] 					= $data['email'];
		if(isset($data['password']) && $data['password']!='')							$request['password'] 				= md5($data['password']);
		if(isset($data['type']) && $data['type']!='') 	  								$request['type'] 					= $data['type'];
		if(isset($data['status']) && $data['status']!='') 	  							$request['status'] 					= $data['status'];
		if(isset($data['email_status']) && $data['email_status']!='') 	  				$request['email_status'] 			= $data['email_status'];
		if(isset($data['producercount']) && $data['producercount']!='') 	  			$request['producer_count'] 			= $data['producercount'];
		if(isset($data['parentid']) && $data['parentid']!='') 	  						$request['parent_id'] 				= $data['parentid'];
		if(isset($data['stripe_account_id']) && $data['stripe_account_id']!='') 	  	$request['stripe_account_id'] 		= $data['stripe_account_id'];

		if(isset($request)){				
			$request['updated_at'] 	= $datetime;
			$request['updated_by'] 	= $userid;						
			
			if($actionid==''){
				$request['created_at'] 		= 	$datetime;
				$request['created_by'] 		= 	$userid;
				
				$customer = $this->createCustomer($request['name'], $request['email']);
				if($customer) $request['stripe_customer_id'] = $customer->id;
				
				$users = $this->db->table('users')->insert($request);
				$usersinsertid = $this->db->insertID();
			}else{
				$users = $this->db->table('users')->update($request, ['id' => $actionid]);
				$usersinsertid = $actionid;
			}
		}
		
		if(isset($usersinsertid) && $this->db->transStatus() === FALSE){
			$this->db->transRollback();
			return false;
		}else{
			$this->db->transCommit();
			return $usersinsertid;
		}
	}

	public function delete($data)
	{
		$this->db->transStart();
		
		$datetime		= date('Y-m-d H:i:s');
		$userid			= (isset($data['userid'])) ? $data['userid'] : '';
		$id 			= $data['id'];
		
		$users 			= $this->db->table('users')->update(['updated_at' => $datetime, 'updated_by' => $userid, 'status' => '0'], ['id' => $id]);
		
		if($users && $this->db->transStatus() === FALSE){
			$this->db->transRollback();
			return false;
		}else{
			$this->db->transCommit();
			return true;
		}
	}
	
	public function createCustomer($name, $email)
    {
		try{
			$settings = getSettings();
			$stripe = new \Stripe\StripeClient($settings['stripeprivatekey']);
			
			$data = $stripe->customers->create([
				'name' 	=> $name,
				'email' => $email
			]);
			
			return $data;
		}catch(Exception $e){
			return false;
        }
    }
}