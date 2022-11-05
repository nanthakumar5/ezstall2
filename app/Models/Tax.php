<?php

namespace App\Models;

use App\Models\BaseModel;

class Tax extends BaseModel
{	
	public function getTax($type, $querydata=[], $requestdata=[], $extras=[])
    {  
    	$select 			= [];
		
		if(in_array('tax', $querydata)){
			$data		= 	['t.*'];							
			$select[] 	= 	implode(',', $data);
		}
			
		$query = $this->db->table('tax t');
				
		if(isset($extras['select'])) 					$query->select($extras['select']);
		else											$query->select(implode(',', $select));
		
		if(isset($requestdata['id'])) 					$query->where('t.id', $requestdata['id']);
		if(isset($requestdata['status'])) 				$query->whereIn('t.status', $requestdata['status']);
		
		if($type!=='count' && isset($requestdata['start']) && isset($requestdata['length'])){
			$query->limit($requestdata['length'], $requestdata['start']);
		}
		if(isset($requestdata['order']['0']['column']) && isset($requestdata['order']['0']['dir'])){
			if(isset($requestdata['page']) && $requestdata['page']=='tax'){
				$column = ['t.from_tax', 't.to_tax', 't.tax_price'];
				$query->orderBy($column[$requestdata['order']['0']['column']], $requestdata['order']['0']['dir']);
			}
		}
		if(isset($requestdata['search']['value']) && $requestdata['search']['value']!=''){
			$searchvalue = $requestdata['search']['value'];
						
			if(isset($requestdata['page'])){ 
				$page = $requestdata['page'];
				$query->groupStart();
					if($page=='tax'){ 		
						$query->like('t.from_tax', $searchvalue);
						$query->orLike('t.to_tax', $searchvalue);
						$query->orLike('t.tax_price', $searchvalue);
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
		$actionid 			= (isset($data['actionid'])) ? $data['actionid'] : '';
		
		if(isset($data['fromtax']) && $data['fromtax']!='')      						$request['from_tax'] 					= $data['fromtax'];
		if(isset($data['totax']) && $data['totax']!='') 	 							$request['to_tax'] 					= $data['totax'];
		if(isset($data['taxprice']) && $data['taxprice']!='')							$request['tax_price'] 				= $data['taxprice'];
		if(isset($data['status']) && $data['status']!='') 	  							$request['status'] 					= $data['status'];
		
		if(isset($request)){
			if($actionid==''){
				$this->db->table('tax')->insert($request);
				$insertid = $this->db->insertID();
			}else{
				$this->db->table('tax')->update($request, ['id' => $actionid]);
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
		$id 			= $data['id'];
		
		$tax 			= $this->db->table('tax')->update(['status' => '0'], ['id' => $id]);
		
		if($tax && $this->db->transStatus() === FALSE){
			$this->db->transRollback();
			return false;
		}else{
			$this->db->transCommit();
			return true;
		}
	}
}