<?php 

namespace App\Controllers\Site\Contactus;

use App\Controllers\BaseController;
use App\Models\Contactus;
use App\Models\Settings;


class Index extends BaseController
{
	public function __construct()
	{
		$this->contactus = new Contactus;
		$this->settings  = new Settings; 
	}
    
    public function index()
    {  
    	if($this->request->getMethod()=='post'){
    		$this->contactus->action($this->request->getPost());
			$this->session->setFlashdata('success', 'You have successfully contacted.');
			return redirect()->to(base_url().'/contactus'); 
    	}

		$data['googleapikey'] = $this->config->googleapikey;
		$data['settings'] = $this->settings->getSettings('row', ['settings']);

		return view('site/contactus/index',$data);
    }
}
