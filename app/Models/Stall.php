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
		
		if(isset($requestdata['id'])) 					$query->where('s.id', $requestdata['id']);
		if(isset($requestdata['name'])) 				$query->where('s.name', $requestdata['name']);
		if(isset($requestdata['event_id'])) 			$query->where('s.event_id', $requestdata['event_id']); 
		if(isset($requestdata['barn_id'])) 			    $query->where('s.barn_id', $requestdata['barn_id']);
		if(isset($requestdata['stall_id'])) 			$query->where('s.stall_id', $requestdata['stall_id']);
		if(isset($requestdata['block_unblock'])) 		$query->where('s.block_unblock', $requestdata['block_unblock']);
		if(isset($requestdata['e.id'])) 				$query->where('e.id', $requestdata['e.id']);
		if(isset($requestdata['ids'])) 					$query->whereIn('e.id', $requestdata['ids']);
		if(isset($requestdata['facilityid'])) 			$query->where('e.facility_id', $requestdata['facilityid']);
		if(isset($requestdata['nqeventid']) && $requestdata['nqeventid']!='') 	$query->where('e.id !=', $requestdata['nqeventid']);
		if(isset($requestdata['start_date'])) 			$query->where('e.start_date', $requestdata['start_date']);
		if(isset($requestdata['end_date'])) 			$query->where('e.end_date', $requestdata['end_date']);
		if(isset($requestdata['gtenddate'])) 			$query->where('e.end_date >=', $requestdata['gtenddate']);
		if(isset($requestdata['btw_start_date']) && isset($requestdata['btw_end_date'])) $query->groupStart()->where("'".$requestdata['btw_start_date']."' BETWEEN e.start_date AND e.end_date")->orWhere("'".$requestdata['btw_end_date']."' BETWEEN e.start_date AND e.end_date")->groupEnd();
		if(isset($requestdata['type'])) 				$query->where('e.type', $requestdata['type']);
		if(isset($requestdata['status'])) 				$query->whereIn('s.status', $requestdata['status']);
		
		if($type!=='count' && isset($requestdata['start']) && isset($requestdata['length'])){
			$query->limit($requestdata['length'], $requestdata['start']);
		}
		if(isset($requestdata['order']['0']['column']) && isset($requestdata['order']['0']['dir'])){
			if(isset($requestdata['page']) && $requestdata['page']=='stalls'){
				$column = ['s.name', 's.image'];
				$query->orderBy($column[$requestdata['order']['0']['column']], $requestdata['order']['0']['dir']);
			}
		}
		if(isset($requestdata['search']['value']) && $requestdata['search']['value']!=''){
			$searchvalue = $requestdata['search']['value'];
						
			if(isset($requestdata['page'])){ 
				$page = $requestdata['page'];
				$query->groupStart();
					if($page=='facility'){				
						$query->like('s.name', $searchvalue);
					}
				$query->groupEnd();
			}			
		}
		
		if(isset($extras['groupby'])) 	$query->groupBy($extras['groupby']);
		else $query->groupBy('s.id');
		
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
}