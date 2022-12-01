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

        $json = ['0', 'No Records Found.', []];	
        
		echo json_encode([
            'status'         => $json[0],
            'message'       => $json[1],
            'result'         => $json[2],
        ]);

        die;		
    }
}
