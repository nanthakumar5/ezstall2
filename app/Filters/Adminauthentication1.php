<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Adminauthentication1 implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        helper('custom_helper');
		$adminsession = session()->get('adminsession');

		if(isset($adminsession['userid'])){
			$users 	= new \App\Models\Users;
			$result = $users->getUsers('row', ['users'], ['id' => $adminsession['userid'], 'type' => ['1']]);
				
			if($result){
				return redirect()->to(getAdminUrl().'/users');
			}
		}
    }
	
	public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        
    }
}