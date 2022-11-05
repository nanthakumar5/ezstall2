<?php 

namespace App\Controllers\Site\Stripe;

use App\Controllers\BaseController;

class Index extends BaseController
{
    public function index()
    {  
		return view('site/stripe/index');
    }
}
