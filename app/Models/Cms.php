<?php

namespace App\Models;

use App\Models\BaseModel;

class Cms extends BaseModel
{	
	public function getCms($type, $querydata=[], $requestdata=[], $extras=[])
    {  
    	$select 			= [];
		
		if(in_array('cms', $querydata)){
			$data		= 	['c.*'];							
			$select[] 	= 	implode(',', $data);
		}
			
		$query = $this->db->table('cms c');
				
		if(isset($extras['select'])) 					$query->select($extras['select']);
		else											$query->select(implode(',', $select));
		
		if(isset($requestdata['id'])) 					$query->where('c.id', $requestdata['id']);
		if(isset($requestdata['type'])) 				$query->whereIn('c.type', $requestdata['type']);
		if(isset($requestdata['status'])) 				$query->whereIn('c.status', $requestdata['status']);
		
		if($type!=='count' && isset($requestdata['start']) && isset($requestdata['length'])){
			$query->limit($requestdata['length'], $requestdata['start']);
		}
		if(isset($requestdata['order']['0']['column']) && isset($requestdata['order']['0']['dir'])){
			if(isset($requestdata['page']) && ($requestdata['page']=='adminfaq' || $requestdata['page']=='adminbanner')){
				$column = ['c.title', 'c.content'];
				$query->orderBy($column[$requestdata['order']['0']['column']], $requestdata['order']['0']['dir']);
			}
		}
		if(isset($requestdata['search']['value']) && $requestdata['search']['value']!=''){
			$searchvalue = $requestdata['search']['value'];
						
			if(isset($requestdata['page'])){
				$page = $requestdata['page'];
				
				$query->groupStart();
					if($page=='adminfaq' || $page=='adminbanner'){				
						$query->like('c.title', $searchvalue);
						$query->orLike('c.content', $searchvalue);
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
		$userid				= (isset($data['userid'])) ? $data['userid'] : '';		
		$actionid 			= (isset($data['actionid'])) ? $data['actionid'] : '';
		
		if(isset($data['title']) && $data['title']!='')      							$request['title'] 					= $data['title'];
		if(isset($data['content']) && $data['content']!='') 	 						$request['content'] 				= $data['content'];
		if(isset($data['type']) && $data['type']!='') 	  								$request['type'] 					= $data['type'];
		if(isset($data['status']) && $data['status']!='') 	  							$request['status'] 					= $data['status'];
		if($data['type']== '1'){ 
			if(isset($data['image']) && $data['image']!=''){
	 			$request['image'] = $data['image'];		
				filemove($data['image'], './assets/uploads/aboutus');		
			}
		}else{ 
			if(isset($data['image']) && $data['image']!=''){
	 			$request['image'] = $data['image'];		
				filemove($data['image'], './assets/uploads/banner');		
			}
		}
		
		

		if(isset($request)){				
			$request['updated_at'] 	= $datetime;
			$request['updated_by'] 	= $userid;						
			
			if($actionid==''){
				$request['created_at'] 		= 	$datetime;
				$request['created_by'] 		= 	$userid;
				
				$this->db->table('cms')->insert($request);
				$insertid = $this->db->insertID();
			}else{
				$this->db->table('cms')->update($request, ['id' => $actionid]);
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
		
		$cms 			= $this->db->table('cms')->update(['updated_at' => $datetime, 'updated_by' => $userid, 'status' => '0'], ['id' => $id]);
		
		if($cms && $this->db->transStatus() === FALSE){
			$this->db->transRollback();
			return false;
		}else{
			$this->db->transCommit();
			return true;
		}
	}
}