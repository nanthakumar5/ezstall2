<?php

namespace App\Models;

use App\Models\BaseModel;

class Newsletter extends BaseModel
{
	public function getNewsletter($type, $querydata=[], $requestdata=[], $extras=[])
    {
    	$select 			= [];
		
		if(in_array('newsletter', $querydata)){
			$data		= 	['n.*'];							
			$select[] 	= 	implode(',', $data);
		}

		$query = $this->db->table('newsletter n');  
				
		if(isset($extras['select'])) 		$query->select($extras['select']);
		else								$query->select(implode(',', $select));
		
		if(isset($requestdata['id'])) 		$query->where('n.id', $requestdata['id']);
		
		
		if($type!=='count' && isset($requestdata['start']) && isset($requestdata['length'])){
			$query->limit($requestdata['length'], $requestdata['start']);
		}
		if(isset($requestdata['order']['0']['column']) && isset($requestdata['order']['0']['dir'])){
			if(isset($requestdata['page']) && $requestdata['page']=='newsletter'){
				$column = ['n.email', 'n.date'];
				$query->orderBy($column[$requestdata['order']['0']['column']], $requestdata['order']['0']['dir']);
			}
		}
		if(isset($requestdata['search']['value']) && $requestdata['search']['value']!=''){
			$searchvalue = $requestdata['search']['value'];
						
			if(isset($requestdata['page'])){
				$page = $requestdata['page'];
				
				$query->groupStart();
					if($page=='newsletter'){				
						$query->like('n.email', $searchvalue);
						$query->orLike('n.date', $searchvalue);
					}
				$query->groupEnd();
			}			
		}
		
		if(isset($extras['groupby'])) 	$query->groupBy($extras['groupby']);
		else $query->groupBy('n.id');
		
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
		
		if(isset($data['email']) && $data['email']!='')   $request['email'] 		= $data['email'];
		if(isset($request)){
			$request['date'] = $datetime;
			$this->db->table('newsletter')->insert($request);
			$newsletterinsertid = $this->db->insertID();
		}
		
		if(isset($newsletterinsertid) && $this->db->transStatus() === FALSE){
			$this->db->transRollback();
			return false;
		}else{
			$this->db->transCommit();
			return $newsletterinsertid;
		}

	}

}