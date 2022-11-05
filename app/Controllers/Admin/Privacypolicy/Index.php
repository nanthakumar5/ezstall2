<?php

namespace App\Controllers\Admin\Privacypolicy;

use App\Controllers\BaseController;

use App\Models\Cms;

class Index extends BaseController
{
	public function __construct()
	{  
		$this->cms  = new Cms();
    }
	
	public function index()
	{		
		$data = [];

		$result = $this->cms->getCms('row', ['cms'], ['id' => '5', 'status' => ['1'], 'type' => ['4']]);
		if($result){
			$data['result'] = $result;
		}else{
			$this->session->setFlashdata('danger', 'Try Later.');
			return redirect()->to(getAdminUrl().'/privacypolicy'); 
		}
		
		if ($this->request->getMethod()=='post')
        {
			$requestdata = $this->request->getPost();
			$requestdata['userid'] = getAdminUserID();
			$requestdata['status'] = '1';
			$requestdata['type'] = '4';
			
            $result = $this->cms->action($requestdata);
			
			if($result){
				$this->session->setFlashdata('success', 'Privacy Policy content saved successfully.');
				return redirect()->to(getAdminUrl().'/privacypolicy'); 
			}else{
				$this->session->setFlashdata('danger', 'Try Later.');
				return redirect()->to(getAdminUrl().'/privacypolicy'); 
			}
        }
        
		return view('admin/privacypolicy/index', $data);
	}	
}
