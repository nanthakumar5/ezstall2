<?php

namespace App\Controllers\Api\Faq;

use App\Controllers\BaseController;


class Index extends BaseController
{
    public function __construct()
    {

    }

    public function index()
    {
        
	    $json = ['1', 'If you still have questions, please contact us. Send us a message and we will answer your valuable questions.', []];
		
        echo json_encode([
            'status'      => $json[0],
            'message'     => $json[1],
            'result'     => $json[2],
        ]);

        die;
    }

	
}
