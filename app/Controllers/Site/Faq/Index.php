<?php 

namespace App\Controllers\Site\Faq;

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
    	$data['result'] = $this->cms->getCms('all', ['cms'], ['status' => ['1'], 'type' => ['2']]);
		return view('site/faq/index',$data);
    }
}
