<?php 
namespace App\Models;

use App\Models\BaseModel;

class Paymentmethod extends BaseModel
{
	public function getPaymentmethod($type, $querydata=[], $requestdata=[], $extras=[])
    {  
    	$select 			= [];
		
		if(in_array('paymentmethod', $querydata)){
			$data		= 	['pm.*'];							
			$select[] 	= 	implode(',', $data);
		}

		$query = $this->db->table('payment_method pm');

		if(isset($extras['select'])) 					$query->select($extras['select']);
		else											$query->select(implode(',', $select));
		
		if(isset($requestdata['id'])) 					$query->where('pm.id', $requestdata['id']);

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