<?php

namespace App\Models;

use App\Models\BaseModel;

class StripePayments extends BaseModel
{	
	public function getStripePayments($type, $querydata=[], $requestdata=[], $extras=[])
    {  
    	$select 			= [];
		
		if(in_array('stripepayment', $querydata)){
			$data		= 	['s.*'];							
			$select[] 	= 	implode(',', $data);
		}

		if(in_array('users', $querydata)){
			$data		= 	['u.name username','u.stripe_account_id','u1.type transferusertype',];							
			$select[] 	= 	implode(',', $data);
		}

		$query = $this->db->table('stripe_payment s');

  		if(in_array('users', $querydata))      		$query->join('users u',  'u.id = s.user_id' , 'left')->join('users u1',  'u1.id = s.created_by' , 'left');

		if(isset($extras['select'])) 					$query->select($extras['select']);
		else											$query->select(implode(',', $select));
		
		if(isset($requestdata['id'])) 					$query->where('s.id', $requestdata['id']);
		if(isset($requestdata['userid'])) 				$query->where('s.user_id', $requestdata['userid']);
		if(isset($requestdata['status'])) 				$query->whereIn('s.status', $requestdata['status']);

		if($type!=='count' && isset($requestdata['start']) && isset($requestdata['length'])){
			$query->limit($requestdata['length'], $requestdata['start']);
		}
		if(isset($requestdata['order']['0']['column']) && isset($requestdata['order']['0']['dir'])){
			if(isset($requestdata['page']) && $requestdata['page']=='stripepayments'){
				$column = ['u.name','s.name'];
				$query->orderBy($column[$requestdata['order']['0']['column']], $requestdata['order']['0']['dir']);
			}
		}
		if(isset($requestdata['search']['value']) && $requestdata['search']['value']!=''){
			$searchvalue = $requestdata['search']['value'];
						
			if(isset($requestdata['page'])){ 
				$page = $requestdata['page'];
				$query->groupStart();
					if($page=='stripepayments'){ 		
						$query->like('u.name', $searchvalue);
						$query->orLike('s.amount', $searchvalue);
					}
				$query->groupEnd();
			}			
		}
		
		if(isset($extras['groupby'])) 	$query->groupBy($extras['groupby']);
		if(isset($extras['orderby'])) 	$query->orderBy($extras['orderby']);
		
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
		
		$datetime			= date('Y-m-d H:i:s');
		$userid				= (isset($data['userid'])) ? $data['userid'] : '';		
		
		if(isset($data['user_id']) && $data['user_id']!='')      						$request['user_id'] 				= $data['user_id'];
		if(isset($data['amount']) && $data['amount']!='') 	 							$request['amount'] 					= $data['amount'];
		if(isset($data['status']) && $data['status']!='') 	  							$request['status'] 					= $data['status'];
		
		if(isset($request)){				
			$request['updated_at'] 	= $datetime;
			$request['updated_by'] 	= $userid;						
			$request['created_at'] 	= $datetime;
			$request['created_by'] 	= $userid;
				
			$this->db->table('stripe_payment')->insert($request);
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
}