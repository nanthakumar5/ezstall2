<?php

namespace App\Controllers\Admin\Contactus;

use App\Controllers\BaseController;

use App\Models\Contactus;

class Index extends BaseController
{
	public function __construct()
	{  
		$this->contactus  = new Contactus();
    }
	
	public function index()
	{		
		return view('admin/contactus/index');
	}
	
	public function DTcontactus()
	{		
		$post 			= $this->request->getPost(); 
		$totalcount 	= $this->contactus->getContactus('count', ['contactus'],['status' => ['1']]+$post);
		$results 		= $this->contactus->getContactus('all', ['contactus'],['status' => ['1']]+$post);

		$totalrecord 	= [];
				
		if(count($results) > 0){
			foreach($results as $key => $result){
				$totalrecord[] = 	[
										'name' 	    => 	$result['name'],
										'email'  	=>  $result['email'],
										'subject'  	=>  $result['subject'],
										'message'  	=>  $result['message'],
									];
			}
		}
		
		$json = array(
			"draw"            => intval($post['draw']),   
			"recordsTotal"    => intval($totalcount),  
			"recordsFiltered" => intval($totalcount),
			"data"            => $totalrecord
		);

		echo json_encode($json);
	}
	
}
