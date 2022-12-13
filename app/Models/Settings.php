<?php

namespace App\Models;

use App\Models\BaseModel;

class Settings extends BaseModel
{	
	public function getsettings($type, $querydata=[], $requestdata=[], $extras=[])
    {  
    	$select 			= [];
		
		if(in_array('settings', $querydata)){
			$data		= 	['s.*'];							
			$select[] 	= 	implode(',', $data);
		}
			
		$query = $this->db->table('settings s');
				
		if(isset($extras['select'])) 					$query->select($extras['select']);
		else											$query->select(implode(',', $select));
		
		if(isset($requestdata['id'])) 					$query->where('s.id', $requestdata['id']);
		
		if(isset($extras['groupby'])) 	$query->groupBy($extras['groupby']);
		
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
		
		if(isset($data['name']) && $data['name']!='')      							$request['name'] 						= $data['name'];
		if(isset($data['description']) && $data['description']!='') 				$request['description'] 				= $data['description'];
		if(isset($data['address']) && $data['address']!='') 	  					$request['address'] 					= $data['address'];
		if(isset($data['email']) && $data['email']!='') 	  						$request['email'] 						= $data['email'];
		if(isset($data['phone']) && $data['phone']!='')      						$request['phone'] 						= $data['phone'];
		if(isset($data['facebook']) && $data['facebook']!='') 	 					$request['facebook'] 					= $data['facebook'];
		if(isset($data['google']) && $data['google']!='') 	  						$request['google'] 						= $data['google'];
		if(isset($data['twitter']) && $data['twitter']!='') 	  					$request['twitter'] 					= $data['twitter'];
		if(isset($data['instagram']) && $data['instagram']!='') 	  				$request['instagram']               	= $data['instagram'];

		if(isset($data['stripepublickey']) && $data['stripepublickey']!='') 		$request['stripepublickey'] 			= $data['stripepublickey'];
		if(isset($data['stripeprivatekey']) && $data['stripeprivatekey']!='') 		$request['stripeprivatekey'] 			= $data['stripeprivatekey'];

		if(isset($data['transactionfee']) && $data['transactionfee']!='') 			$request['transactionfee'] 				= $data['transactionfee'];
		if(isset($data['producereventfee']) && $data['producereventfee']!='') 	  	$request['producereventfee']            = $data['producereventfee'];
		if(isset($data['facilitystallfee']) && $data['facilitystallfee']!='') 	  	$request['facilitystallfee']            = $data['facilitystallfee'];
		if(isset($data['googleanalytics']) && $data['googleanalytics']!='') 	  	$request['googleanalytics']            = $data['googleanalytics'];
		
		if(isset($data['cartreservedtime']) && $data['cartreservedtime']!='') 	  	$request['cartreservedtime']            = $data['cartreservedtime'];
	
		if(isset($data['image']) && $data['image']!=''){
			$request['logo'] = $data['image'];		
			filemove($data['image'], './assets/uploads/settings');		
		}
		
		if(isset($request)){  
			$this->db->table('settings')->update($request, ['id' => $actionid]);
			$insertid = $actionid;
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
		
		$settings 			= $this->db->table('settings')->update(['updated_at' => $datetime, 'updated_by' => $userid, 'status' => '0'], ['id' => $id]);
		
		if($settings && $this->db->transStatus() === FALSE){
			$this->db->transRollback();
			return false;
		}else{
			$this->db->transCommit();
			return true;
		}
	}
}