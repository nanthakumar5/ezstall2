<?php

namespace App\Models;

use App\Models\BaseModel;

class Stall extends BaseModel
{	
	public function getStall($type, $querydata=[], $requestdata=[], $extras=[])
    { 
    	$select 			= [];
		
		if(in_array('stall', $querydata)){
			$data		= 	['s.*', 's.name stallname'];							
			$select[] 	= 	implode(',', $data);
		}
		
		if(in_array('event', $querydata)){
			$data		= 	['e.description','e.user_id','e.name','e.start_date estartdate','e.end_date eenddate'];							
			$select[] 	= 	implode(',', $data);
		}

		$query = $this->db->table('stall s');  
		if(in_array('event', $querydata))	$query->join('event e','e.id=s.event_id', 'LEFT');
				
		if(isset($extras['select'])) 					$query->select($extras['select']);
		else											$query->select(implode(',', $select));
		
		if(isset($requestdata['stall_id'])) 			$query->where('s.stall_id', $requestdata['stall_id']);
		if(isset($requestdata['event_id'])) 			$query->where('s.event_id', $requestdata['event_id']); 
		if(isset($requestdata['block_unblock'])) 		$query->where('s.block_unblock', $requestdata['block_unblock']);
		if(isset($requestdata['status'])) 				$query->whereIn('s.status', $requestdata['status']);
		if(isset($requestdata['nqeventid']) && $requestdata['nqeventid']!='') 	$query->where('e.id !=', $requestdata['nqeventid']);
		if(isset($requestdata['facilityid'])) 			$query->where('e.facility_id', $requestdata['facilityid']);
		if(isset($requestdata['gtenddate'])) 			$query->where('e.end_date >=', $requestdata['gtenddate']);
		if(isset($requestdata['type'])) 				$query->where('e.type', $requestdata['type']);
		
		if(isset($requestdata['checkin']) && isset($requestdata['checkout'])){
			$query->groupStart();
				$query->where("('".date('Y-m-d', strtotime($requestdata['checkin']))."' BETWEEN e.start_date AND DATE_ADD(e.end_date, INTERVAL -1 DAY))");
				$query->orWhere("('".date('Y-m-d', strtotime($requestdata['checkout']))."' BETWEEN e.start_date AND DATE_ADD(e.end_date, INTERVAL -1 DAY))");
				$query->orWhere("(e.start_date BETWEEN '".date('Y-m-d', strtotime($requestdata['checkin']))."' AND '".date('Y-m-d', strtotime($requestdata['checkout']))."')");
				$query->orWhere("(DATE_ADD(e.end_date, INTERVAL -1 DAY) BETWEEN '".date('Y-m-d', strtotime($requestdata['checkin']))."' AND '".date('Y-m-d', strtotime($requestdata['checkout']))."')");
			$query->groupEnd();
		}
		
		$query->groupBy('s.id');
		
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