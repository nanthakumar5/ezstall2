<?php

namespace App\Controllers\Common;

use App\Controllers\BaseController;
use App\Models\Stripe;

class Cron extends BaseController
{
    public function __construct()
    {
		$this->db = db_connect();
		$this->stripe = new Stripe();	
    }
	
	public function webhook()
	{	
		$this->db->table('webhook')->insert(['content' => json_encode($_REQUEST)]);
	}
	
	public function createwebhook()
	{	
		$this->stripe->createWebhook();
	}
}