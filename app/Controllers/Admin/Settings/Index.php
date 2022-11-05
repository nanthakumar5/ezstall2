<?php

namespace App\Controllers\Admin\Settings;

use App\Controllers\BaseController;

use App\Models\Settings;

class Index extends BaseController
{
	public function __construct()
	{  
		$this->settings  = new Settings();
    }
	
	public function index()
	{		
		$result = $this->settings->getsettings('row', ['settings'], ['id' => '1']);
		
		if($result){
			$data['result'] = $result;
		}else{
			$this->session->setFlashdata('danger', 'Try Later.');
			return redirect()->to(getAdminUrl().'/'); 
		}
		
		if ($this->request->getMethod()=='post')
        {
			$requestdata = $this->request->getPost();  
            $result = $this->settings->action($requestdata);
			
			if($result){
				$this->session->setFlashdata('success', 'Settings saved successfully.');
				return redirect()->to(getAdminUrl().'/settings'); 
			}else{
				$this->session->setFlashdata('danger', 'Try Later.');
				return redirect()->to(getAdminUrl().'/settings'); 
			}
        }
		
		return view('admin/settings/index', $data);
	}	
}
