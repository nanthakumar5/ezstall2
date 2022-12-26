<?php

namespace App\Models;

use App\Models\BaseModel;

class Bookingdetails extends BaseModel
{	
	public function getBookingdetails($type, $querydata=[], $requestdata=[], $extras=[])
    { 	
    	$select 			= [];
		
		if(in_array('bookingdetails', $querydata)){
			$data		= 	['bks.*'];							
			$select[] 	= 	implode(',', $data);
		}

		$query = $this->db->table('booking_details bks');  
				
		if(isset($extras['select'])) 					$query->select($extras['select']);
		else											$query->select(implode(',', $select));
		
		if(isset($requestdata['id'])) 					$query->where('bks.id', $requestdata['id']);
		if(isset($requestdata['stallid'])) 				$query->where('bks.stall_id', $requestdata['stallid']);
		if(isset($requestdata['booking_id'])) 			$query->where('bks.booking_id', $requestdata['booking_id']);

		$query = $query->get();
		
		if($type=='all') 		$result = $query->getResultArray();
		elseif($type=='row') 	$result = $query->getRowArray();
			
		return $result;
    }

    public function action($data)
	{ 		
		$this->db->transStart();
		
		if(isset($data['booking_id']) && $data['booking_id']!='')            		$request['booking_id']     		= $data['booking_id'];
		if(isset($data['barn_id']) && $data['barn_id']!='')              		$request['barn_id']      		= $data['barn_id'];
		if(isset($data['stall_id']) && $data['stall_id']!='')      	           		$request['stall_id'] 	      		= $data['stall_id'];
		if(isset($data['product_id']) && $data['product_id']!='')      	           		$request['product_id'] 	      		= $data['product_id'];
		if(isset($data['price']) && $data['price']!='')      	           		$request['price'] 	      		= $data['price'];
		if(isset($data['quantity']) && $data['quantity']!='')      	           		$request['quantity'] 	      		= $data['total'];
		if(isset($data['total']) && $data['total']!='')      	           		$request['total'] 	      		= $data['total'];
		if(isset($data['flag']) && $data['flag']!='')      	           		$request['flag'] 	      		= $data['flag'];	

		$request['status']  = '2';	

            $this->db->table('booking_details')->insert($request);
			$insertid = $this->db->insertID();

		if(isset($insertid) && $this->db->transStatus() === FALSE){
			$this->db->transRollback();
			return false;
		}else{
			$this->db->transCommit();
			return $insertid;
		}
	}

	public function updatestall($data)
	{
		$stalldata = $this->db->table('stall')->where('id', $data['stallid'])->get()->getRowArray();
		$bookingdata = $this->db->table('booking_details')->update(['barn_id' => $stalldata['barn_id'], 'stall_id' => $data['stallid']], ['id' => $data['id']]);	
		
		if($this->db->transStatus() === FALSE){ 
			$this->db->transRollback();
			return false;
		}else{
			$this->db->transCommit();
			return $bookingdata;
		}
	
	}
	
	public function cancelsubscription($data)
	{	
		$this->db->transStart();
		$this->db->table('booking_details')->update(['subscription_status' => 0], ['booking_details_id' => $data['booking_details_id']]);

		if($this->db->transStatus() === FALSE){ 
			$this->db->transRollback();
			return false;
		}else{
			$this->db->transCommit();
			return true;
		}
	}

}