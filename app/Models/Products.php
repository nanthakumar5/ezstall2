<?php

namespace App\Models;

use App\Models\BaseModel;

class Products extends BaseModel
{	
	public function getProduct($type, $querydata=[], $requestdata=[], $extras=[])
    { 
    	$select 			= [];
		
		if(in_array('product', $querydata)){
			$data		= 	['p.*'];							
			$select[] 	= 	implode(',', $data);
		}

		$query = $this->db->table('products p');  
				
		if(isset($extras['select'])) 					$query->select($extras['select']);
		else											$query->select(implode(',', $select));
		
		if(isset($requestdata['id'])) 					$query->where('p.id', $requestdata['id']);
		if(isset($requestdata['event_id'])) 			$query->where('event_id', $requestdata['event_id']);
		if(isset($requestdata['type'])) 				$query->where('type', $requestdata['type']);
		
		if($type=='count'){
			$result = $query->countAllResults();
		}else{
			$query = $query->get();
			if($type=='all') 		$result = $query->getResultArray();
			elseif($type=='row') 	$result = $query->getRowArray();
		}
	
		return $result;
    }
}