<?php 

namespace App\Controllers\Site\Termsandconditions;

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
    	$data['result'] = $this->cms->getCms('row', ['cms'], ['id' => '4', 'status' => ['1'], 'type' => ['5']]);
		return view('site/termsandconditions/index', $data);
    }
}
