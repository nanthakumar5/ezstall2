<?php 

namespace App\Controllers\Site\Privacypolicy;

use App\Controllers\BaseController;
use App\Models\Cms;

class Index extends BaseController
{
	public function __construct()
	{
		$this->cms = new Cms;
	}
    
    public function index()
    { 
    	$data['result'] = $this->cms->getCms('row', ['cms'], ['id' => '5', 'status' => ['1'], 'type' => ['4']]);
		return view('site/privacypolicy/index', $data);
    }
}
