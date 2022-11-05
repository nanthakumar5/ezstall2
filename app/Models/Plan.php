<?php

namespace App\Models;

use App\Models\BaseModel;

class Plan extends BaseModel
{	
	public function getPlan($type, $querydata=[], $requestdata=[], $extras=[])
    {  
    	$select 			= [];
		
		if(in_array('plan', $querydata)){
			$data		= 	['p.*'];							
			$select[] 	= 	implode(',', $data);
		}
			
		$query = $this->db->table('plan p');
				
		if(isset($extras['select'])) 					$query->select($extras['select']);
		else											$query->select(implode(',', $select));
		
		if(isset($requestdata['id'])) 					$query->where('p.id', $requestdata['id']);
		if(isset($requestdata['type'])) 				$query->whereIn('p.type', $requestdata['type']);
		if(isset($requestdata['status'])) 				$query->whereIn('p.status', $requestdata['status']);
		
		if($type!=='count' && isset($requestdata['start']) && isset($requestdata['length'])){
			$query->limit($requestdata['length'], $requestdata['start']);
		}
		if(isset($requestdata['order']['0']['column']) && isset($requestdata['order']['0']['dir'])){
			if(isset($requestdata['page']) && $requestdata['page']=='plan'){
				$column = ['p.name', 'p.price', 'p.interval'];
				$query->orderBy($column[$requestdata['order']['0']['column']], $requestdata['order']['0']['dir']);
			}
		}
		if(isset($requestdata['search']['value']) && $requestdata['search']['value']!=''){
			$searchvalue = $requestdata['search']['value'];
						
			if(isset($requestdata['page'])){ 
				$page = $requestdata['page'];
				$query->groupStart();
					if($page=='plan'){ 		
						$query->like('p.name', $searchvalue);
						$query->orLike('p.price', $searchvalue);
						$query->orLike('p.interval', $searchvalue);
					}
				$query->groupEnd();
			}			
		}
		
		if(isset($extras['groupby'])) 	$query->groupBy($extras['groupby']);
		if(isset($extras['orderby'])) 	$query->orderBy($extras['orderby'], $extras['sort']);
		
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
		$actionid 			= (isset($data['actionid'])) ? $data['actionid'] : '';
		
		if(isset($data['name']) && $data['name']!='')      								$request['name'] 					= $data['name'];
		if(isset($data['price']) && $data['price']!='') 	 							$request['price'] 					= $data['price'];
		if(isset($data['interval']) && $data['interval']!='')							$request['interval'] 				= $data['interval'];	
		if(isset($data['type']) && $data['type']!='') 	  								$request['type'] 					= $data['type'];
		if(isset($data['status']) && $data['status']!='') 	  							$request['status'] 					= $data['status'];
		
		if(isset($request)){				
			$request['updated_at'] 	= $datetime;
			$request['updated_by'] 	= $userid;						
			
			if($actionid==''){
				$request['created_at'] 		= 	$datetime;
				$request['created_by'] 		= 	$userid;
				
				$this->db->table('plan')->insert($request);
				$insertid = $this->db->insertID();
			}else{
				$this->db->table('plan')->update($request, ['id' => $actionid]);
				$insertid = $actionid;
			}
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
		
		$datetime		= date('Y-m-d H:i:s');
		$userid			= (isset($data['userid'])) ? $data['userid'] : '';
		$id 			= $data['id'];
		
		$cms 			= $this->db->table('plan')->update(['updated_at' => $datetime, 'updated_by' => $userid, 'status' => '0'], ['id' => $id]);
		
		if($cms && $this->db->transStatus() === FALSE){
			$this->db->transRollback();
			return false;
		}else{
			$this->db->transCommit();
			return true;
		}
	}
}