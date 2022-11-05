<?php

namespace App\Controllers\Common;

use App\Controllers\BaseController;

use App\Models\Users;

class Validation extends BaseController
{
    public function __construct()
    {
        $this->users 	= new Users(); 
    }
	
	public function emailvalidation()
	{	
		$data				= $this->request->getPost();
		
		$id 				= isset($data['id']) ? $data['id'] : '';
		$email 				= $data['email'];
		
		$result = $this->users->getUsers('count', ['users'], ['email' => $email,'status' =>['1','2']]+($id!='' ? ['neqid' => $id] : []));
		
		if($result=='0'){
			echo 'true';
		}else{
			echo 'false';
		}
	}
}