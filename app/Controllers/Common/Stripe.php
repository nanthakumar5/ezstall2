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
		$payload = @file_get_contents('php://input');
		$event = null;

		try {
			$event = \Stripe\Event::constructFrom(
				json_decode($payload, true)
			);
		} catch(\UnexpectedValueException $e) {
			http_response_code(400);
			exit();
		}

		switch ($event->type) {
			case 'payment_intent.succeeded':
				$paymentIntent = $event->data->object;
				
				$fp = fopen('./assets/stripe.txt', 'a');
				fwrite($fp, json_encode($paymentIntent));
				fclose($fp);
				
				break;
			case 'payment_method.attached':
				$paymentMethod = $event->data->object;
				
				$fp = fopen('./assets/stripe.txt', 'a');
				fwrite($fp, json_encode($paymentMethod));
				fclose($fp);
				
				break;
			default:
				echo 'Received unknown event type ' . $event->type;
		}

		http_response_code(200);
	}
}