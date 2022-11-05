<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Siteauthentication2 implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
		helper('custom');
		
		$userid = getSiteUserID();
		$uri = service('uri');
		$segment2 = $uri->getSegment(2);
		
		if($userid){
			$users 	= new \App\Models\Users;
			$result = $users->getUsers('row', ['users'], ['id' => $userid, 'status' => ['1']]);		
			
			if(!$result){
				return redirect()->to('/login');
			}else{
				if($segment2=='events' && in_array($result['type'], ['5'])){
					return redirect()->to('/myaccount/dashboard');
				}
				if($segment2=='subscription' && in_array($result['type'], ['3', '4'])){
					return redirect()->to('/myaccount/dashboard');
				}
			}
		}else{
			return redirect()->to('/login');
		}			
    }
	
	public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}