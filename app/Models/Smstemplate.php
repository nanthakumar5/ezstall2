<?php

namespace App\Models;

use App\Models\BaseModel;

class Smstemplate extends BaseModel
{
	public function getSmstemplate($type, $querydata=[], $requestdata=[], $extras=[])
    {
    	$select 			= [];
		
		if(in_array('smstemplate', $querydata)){
			$data		= 	['s.*'];							
			$select[] 	= 	implode(',', $data);
		}

		$query = $this->db->table('sms_template s');  
				
		if(isset($extras['select'])) 		$query->select($extras['select']);
		else								$query->select(implode(',', $select));
		
		if(isset($requestdata['id'])) 		$query->where('s.id', $requestdata['id']);
		
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