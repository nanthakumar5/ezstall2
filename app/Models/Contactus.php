<?php

namespace App\Models;

use App\Models\BaseModel;

class Contactus extends BaseModel
{	
	public function getContactus($type, $querydata=[], $requestdata=[], $extras=[])
    {
    	$select 			= [];
		
		if(in_array('contactus', $querydata)){
			$data		= 	['c.*'];							
			$select[] 	= 	implode(',', $data);
		}

		$query = $this->db->table('contactus c');  
				
		if(isset($extras['select'])) 					$query->select($extras['select']);
		else											$query->select(implode(',', $select));
		
		if(isset($requestdata['id'])) 					$query->where('c.id', $requestdata['id']);
		if(isset($requestdata['status'])) 				$query->whereIn('c.status', $requestdata['status']);
		
		if($type!=='count' && isset($requestdata['start']) && isset($requestdata['length'])){
			$query->limit($requestdata['length'], $requestdata['start']);
		}
		if(isset($requestdata['order']['0']['column']) && isset($requestdata['order']['0']['dir'])){
			if(isset($requestdata['page']) && $requestdata['page']=='contactus'){
				$column = ['c.id','c.name', 'c.email', 'c.subject', 'c.message'];
				$query->orderBy($column[$requestdata['order']['0']['column']], $requestdata['order']['0']['dir']);
			}
		}
		if(isset($requestdata['search']['value']) && $requestdata['search']['value']!=''){
			$searchvalue = $requestdata['search']['value'];
						
			if(isset($requestdata['page'])){
				$page = $requestdata['page'];
				
				$query->groupStart();
					if($page=='contactus'){				
						$query->like('c.name', $searchvalue);
						$query->orLike('c.email', $searchvalue);
						$query->orLike('c.subject', $searchvalue);
						$query->orLike('c.message', $searchvalue);
					}
				$query->groupEnd();
			}			
		}
		
		if(isset($extras['groupby'])) 	$query->groupBy($extras['groupby']);
		else $query->groupBy('c.id');
		
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
		$data['userid']     = getSiteUserID();
		$userid				= (isset($data['userid'])) ? $data['userid'] : '';		
		$actionid 			= (isset($data['actionid'])) ? $data['actionid'] : '';
		
		if(isset($data['name']) && $data['name']!='')      		$request['name'] 		= $data['name'];
		if(isset($data['email']) && $data['email']!='') 	 	$request['email'] 		= $data['email'];
		if(isset($data['subject']) && $data['subject']!='') 	$request['subject']     = $data['subject'];
		if(isset($data['message']) && $data['message']!='') 	$request['message'] 	= $data['message'];

		if(isset($request)){
			$request['status'] 		= '1';
			$request['updated_at'] 	= $datetime;
			$request['updated_by'] 	= $userid;
			$request['created_at'] 	= $datetime;
			$request['created_by'] 	= $userid;
				
			$this->db->table('contactus')->insert($request);
			$contactusinsertid = $this->db->insertID();
		}
		
		if(isset($contactusinsertid) && $this->db->transStatus() === FALSE){
			$this->db->transRollback();
			return false;
		}else{
			$this->db->transCommit();
			return $contactusinsertid;
		}
	}
}