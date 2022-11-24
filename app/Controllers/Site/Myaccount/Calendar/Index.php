<?php 
namespace App\Controllers\Site\Myaccount\Calendar;

use App\Controllers\BaseController;

class Index extends BaseController
{
	public function __construct()
	{	
		
	}
    
    public function index()
    { 			
		return view('site/myaccount/calendar/index');
    }
}
