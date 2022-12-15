<?php

namespace App\Models;

use App\Models\BaseModel;

class Emailsms extends BaseModel
{	
	public function action($data)
	{
		$this->db->transStart();
		
		if(isset($data['userid']) && $data['userid']!='')      				$request['user_id'] 					= $data['userid'];
		if(isset($data['templateid']) && $data['templateid']!='')      		$request['template_id'] 				= $data['templateid'];
		if(isset($data['email']) && $data['email']!='')      				$request['email'] 						= $data['email'];
		if(isset($data['mobile']) && $data['mobile']!='')      				$request['mobile'] 						= $data['mobile'];
		if(isset($data['subject']) && $data['subject']!='') 	 			$request['subject'] 					= $data['subject'];
		if(isset($data['message']) && $data['message']!='') 	 			$request['message'] 					= $data['message'];

		if(isset($request)){
			$this->db->table('email_sms')->insert($request);
			$insertid = $this->db->insertID();
		}
		
		if($this->db->transStatus() === FALSE){
			$this->db->transRollback();
			return false;
		}else{
			$this->db->transCommit();
			return $insertid;
		}
	}
}