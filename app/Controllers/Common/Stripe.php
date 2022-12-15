<?php

namespace App\Controllers\Common;

use App\Controllers\BaseController;
use App\Models\Stripe as StripeModel;

class Stripe extends BaseController
{
    public function __construct()
    {
		$this->db = db_connect();
		$this->stripe = new StripeModel();	
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