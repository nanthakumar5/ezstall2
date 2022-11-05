<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Siteauthentication1 implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
		$sitesession = session()->get('sitesession');

		if(isset($sitesession['userid'])){
			$users 	= new \App\Models\Users;
			$result = $users->getUsers('row', ['users'], ['id' => $sitesession['userid'], 'status' => ['1']]);
				
			if($result){
				return redirect()->to('/');
			}
		}
    }
	
	public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        
    }
}