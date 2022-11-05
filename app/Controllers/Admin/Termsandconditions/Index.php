<?php

namespace App\Controllers\Admin\Termsandconditions;

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

		$result = $this->cms->getCms('row', ['cms'], ['id'=> '4','status' => ['1'], 'type' => ['5']]);
		
		if($result){
			$data['result'] = $result;
		}else{
			$this->session->setFlashdata('danger', 'Try Later.');
			return redirect()->to(getAdminUrl().'/termsandconditions'); 
		}

		if ($this->request->getMethod()=='post')
        {
			$requestdata = $this->request->getPost();
			$requestdata['userid'] = getAdminUserID();
			$requestdata['status'] = '1';
			$requestdata['type'] = '5';
			
            $result = $this->cms->action($requestdata); 
			
			if($result){
				$this->session->setFlashdata('success', 'Terms and conditions content saved successfully.');
				return redirect()->to(getAdminUrl().'/termsandconditions'); 
			}else{
				$this->session->setFlashdata('danger', 'Try Later.');
				return redirect()->to(getAdminUrl().'/termsandconditions'); 
			}
        }
		
		return view('admin/termsandconditions/index', $data);
	}
	
	}
