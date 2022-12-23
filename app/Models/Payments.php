<?php 
namespace App\Models;

use App\Models\BaseModel;

class Payments extends BaseModel
{
	public function getPayments($type, $querydata=[], $requestdata=[], $extras=[])
    {  
    	$select 			= [];
		
		if(in_array('payment', $querydata)){
			$data		= 	['p.*'];							
			$select[] 	= 	implode(',', $data);
		}

		if(in_array('event', $querydata)){
			$data		= 	['e.name eventname'];							
			$select[] 	= 	implode(',', $data);
		}
		
		if(in_array('users', $querydata)){
			$data		= 	['u.name username','u.type usertype'];							
			$select[] 	= 	implode(',', $data);
		}
		
		if(in_array('booking', $querydata)){
			$data		= 	['b.payment_id, b.firstname, b.lastname, b.transaction_fee'];							
			$select[] 	= 	implode(',', $data);
		}
		
		if(in_array('plan', $querydata)){
			$data		= 	['pl.name as planname'];							
			$select[] 	= 	implode(',', $data);
		}
		
		$query = $this->db->table('payment p');
		if(in_array('users', $querydata)) $query->join('users u', 'u.id=p.user_id', 'left');
		if(in_array('booking', $querydata)) $query->join('booking b', 'b.payment_id=p.id', 'left');
		if(in_array('event', $querydata)) $query->join('event e', 'e.id=b.event_id', 'left');
		if(in_array('plan', $querydata)) $query->join('plan pl', 'pl.id=p.plan_id', 'left');

		if(isset($extras['select'])) 					$query->select($extras['select']);
		else											$query->select(implode(',', $select));
		
		if(isset($requestdata['id'])) 					$query->where('p.id', $requestdata['id']);
		if(isset($requestdata['paymenttype'])) 			$query->where('p.type', $requestdata['paymenttype']);
		if(isset($requestdata['ninstatus'])) 			$query->whereNotIn('p.status', $requestdata['ninstatus']);

		if(isset($requestdata['userid'])){
			$query->groupStart();
				$query->whereIn('p.user_id', $requestdata['userid']);
				$query->orWhereIn('e.user_id', $requestdata['userid']);
			$query->groupEnd();
		}
		
		if($type!=='count' && isset($requestdata['start']) && isset($requestdata['length'])){
			$query->limit($requestdata['length'], $requestdata['start']);
		}
		if(isset($requestdata['order']['0']['column']) && isset($requestdata['order']['0']['dir'])){
			if(isset($requestdata['page']) && $requestdata['page']=='adminpayments'){
				$column = ['p.id','p.name', 'p.email'];
				$query->orderBy($column[$requestdata['order']['0']['column']], $requestdata['order']['0']['dir']);
			}
		}
		if(isset($requestdata['search']['value']) && $requestdata['search']['value']!=''){
			$searchvalue = $requestdata['search']['value'];
						
			if(isset($requestdata['page'])){
				$page = $requestdata['page'];
				
				$query->groupStart();
					if($page=='adminpayments'){				
						$query->like('p.name', $searchvalue);
						$query->orLike('p.email', $searchvalue);
					}
				$query->groupEnd();
			}			
		}
		
		if(isset($extras['groupby'])) 	$query->groupBy($extras['groupby']);
		if(isset($extras['orderby'])) 	$query->orderBy($extras['orderby']);
		
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