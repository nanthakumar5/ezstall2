<?php

namespace App\Controllers\Api\Myaccount\Dashboard;

use App\Controllers\BaseController;

use App\Models\Event;

use App\Models\Booking;

class Index extends BaseController
{
    public function __construct()
    {
		$this->event = new Event();
		$this->booking = new Booking();
    }

    public function index()
    {
        $post       = $this->request->getPost();  

		
    }
}
