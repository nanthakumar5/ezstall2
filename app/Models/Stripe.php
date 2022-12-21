<?php
namespace App\Models;
use App\Models\BaseModel;

class Stripe extends BaseModel
{	
	function stripepayment($requestData)
	{
		$this->stripewebhook();
		
		$userdetails			= getSiteUserDetails();
		$userid 				= $userdetails['id'];
		$name 					= $userdetails['name'];
		$email 					= $userdetails['email'];
		$price 					= isset($requestData['amount']) ? $requestData['amount'] * 100 : $requestData['price'] * 100;
		$transactionfee 		= isset($requestData['transactionfee']) ? $requestData['transactionfee'] * 100 : 0;
        $currency 				= "inr";
		
		$customer = $this->customer();
		if($customer){
			if(isset($requestData['eventuserid'])){
				$user = $this->db->table('users')->where('id', $requestData['eventuserid'])->get()->getRowArray();
				if($user['stripe_account_id']!=''){
					$retrieveaccount = $this->retrieveAccount($user['stripe_account_id']);
					if($retrieveaccount && $retrieveaccount['charges_enabled']!=''){
						$stripeaccountid = $retrieveaccount['id'];
					}
				}
			}
		
			$paymentintents = $this->createPaymentIntents($customer, $price, $currency, $transactionfee, (isset($stripeaccountid) ? $stripeaccountid : ''));				
			if($paymentintents){
				$paymentData = array(
					'user_id' 					=> $userid,
					'name' 						=> $name,
					'email' 					=> $email,
					'amount' 					=> $price/100,
					'currency' 					=> $currency,
					'stripe_paymentintent_id' 	=> $paymentintents->id,
					'transfer' 					=> (isset($stripeaccountid) ? 1 : 0),
					'type' 						=> '1',
					'status' 					=> '0',
					'created' 					=> date("Y-m-d H:i:s"),
					'stripe_data'				=> json_encode($requestData)
				);
				
				$this->db->table('payment')->insert($paymentData);
				$paymentinsertid = $this->db->insertID();
				
				$this->stripescheduledpayment($requestData, ['paymentid' => $paymentinsertid]);
				
				return ['paymentintents' => $paymentintents];
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	
	function striperecurringpayment($requestData)
	{
		$this->stripewebhook();
		
		$userdetails			= getSiteUserDetails();
		$userid 				= $userdetails['id'];
		$name 					= $userdetails['name'];
		$email 					= $userdetails['email'];
		$planid 				= $requestData['plan_id'];
		$stripepaymentmethodid 	= $requestData['stripe_payment_method_id'];
		
		$customer 			= $this->customer();
		$productandprice 	= $this->productandprice('1', $planid);
		
		if ($customer && $productandprice){
			$this->attachPaymentMethod($stripepaymentmethodid, $customer);
			$subscription = $this->createSubscription($customer, $productandprice, $stripepaymentmethodid);
			if($subscription){
				$paymentintents = $subscription->latest_invoice->payment_intent;
				
				$paymentData = array(
					'user_id' 					=> $userid,
					'name' 						=> $name,
					'email' 					=> $email,
					'amount' 					=> $subscription->plan->amount/100,
					'currency' 					=> $subscription->plan->currency,
					'stripe_paymentintent_id' 	=> $paymentintents->id,
					'stripe_subscription_id' 	=> $subscription->id,
					'stripe_payment_method_id' 	=> $stripepaymentmethodid,
					'plan_id'					=> $planid,
					'plan_interval' 			=> $subscription->plan->interval,
					'plan_period_start' 		=> date("Y-m-d H:i:s", $subscription->current_period_start),
					'plan_period_end' 			=> date("Y-m-d H:i:s", $subscription->current_period_end),
					'type' 						=> '2',
					'status' 					=> '0',
					'created' 					=> date("Y-m-d H:i:s"),
					'stripe_data'				=> json_encode($requestData)
				);

				$this->db->table('payment')->insert($paymentData);
				
				return ['paymentintents' => $paymentintents];
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	function stripescheduledpayment($requestData, $extras=[])
	{
		$userdetails			= getSiteUserDetails();
		$userid 				= $userdetails['id'];
		$name 					= $userdetails['name'];
		$email 					= $userdetails['email'];
		
		$customer 				= $this->customer();
		$interval				= 'day';
		$attachcard				= 1;
		
		for($i=0; $i<2; $i++){
			$barnstallname 		= $i=='0' ? 'barnstall' : 'rvbarnstall';
				
			if(isset($requestData[$barnstallname])){
				foreach(json_decode($requestData[$barnstallname], true) as $barnstall){
					$planid = $barnstall['stall_id'];
					$subscriptionprice = $barnstall['subscriptionprice'];
					
					if($barnstall['pricetype']=='5' && $subscriptionprice!='' && $subscriptionprice!='0'){
						$stripepaymentmethodid = $barnstall['stripe_payment_method_id'];
						if($attachcard==1){
							$this->attachPaymentMethod($stripepaymentmethodid, $customer);
							$attachcard = 0;
						}
						
						$productandprice = $this->productandprice('2', $planid, ['interval' => $interval]);
						$scheduled = $this->createSchedule($customer, strtotime($requestData['checkin']), strtotime($requestData['checkout']), $productandprice, $stripepaymentmethodid);
						
						$paymentData = array(
							'payment_id' 				=> (isset($extras['paymentid']) ? $extras['paymentid'] : ''),
							'user_id' 					=> $userid,
							'name' 						=> $name,
							'email' 					=> $email,
							'amount' 					=> $subscriptionprice,
							'currency' 					=> $scheduled->phases[0]->currency,
							'stripe_scheduled_id' 	    => $scheduled->id,
							'stripe_payment_method_id' 	=> $stripepaymentmethodid,
							'plan_id'					=> $planid,
							'plan_interval' 			=> $interval,
							'plan_period_start' 		=> date("Y-m-d H:i:s", $scheduled->phases[0]->start_date),
							'plan_period_end' 			=> date("Y-m-d H:i:s", $scheduled->phases[0]->end_date),
							'type' 						=> '3',
							'status' 					=> '0',
							'created' 					=> date("Y-m-d H:i:s")
						);

						$this->db->table('payment')->insert($paymentData);
					}
				}
			}
		}
		return true;
	}

	function stripewebhook()
	{
		$webhook	= $this->db->table('webhook')->where('id', '1')->get()->getRowArray();
		
		if($webhook){
			$stripewebhookid = $webhook['stripe_webhook_id'];
			
			$retrievewebhook 	= $this->retrieveWebhook($stripewebhookid);
			if(!$retrievewebhook){
				$hook 		= $this->createWebhook();
				$webhookid 	= $hook->id;
			}else{
				$webhookid 	= $retrievewebhook->id;
			}
		}else{
			$hook 		= $this->createWebhook();
			$webhookid 	= $hook->id;
		}
		
		if(isset($webhookid)){
			return $webhookid;
		}else{
			return false;
		}
	}
	
	function customer()
	{
		$userdetails			= getSiteUserDetails();
		
		$userid 				= $userdetails['id'];
		$name 					= $userdetails['name'];
		$email 					= $userdetails['email'];
		$stripecustomerid 		= $userdetails['stripe_customer_id'];
		
		if($stripecustomerid==''){
			$customer 			= $this->createCustomer($userid, $name, $email);
			$customerid 		= $customer->id;
		}else{
			$retrievecustomer 	= $this->retrieveCustomer($stripecustomerid);
			if(!$retrievecustomer){
				$customer 		= $this->createCustomer($userid, $name, $email);
				$customerid 	= $customer->id;
			}else{
				if($retrievecustomer->deleted){
					$customer 		= $this->createCustomer($userid, $name, $email);
					$customerid 	= $customer->id;
				}else{
					$customerid 	= $retrievecustomer->id;
				}
			}
		}
		
		if(isset($customerid)){
			return $customerid;
		}else{
			return false;
		}
	}
	
	function productandprice($type, $id, $extras=[])
	{
		if($type=='1'){
			$plan				= $this->db->table('plan')->where('id', $id)->get()->getRowArray();
			
			$name 				= $plan['name'];
			$price 				= $plan['price'];
			$interval 			= $plan['interval'];
			$stripeproductid 	= $plan['stripe_product_id'];
			$stripepriceid 		= $plan['stripe_price_id'];
		}elseif($type=='2'){
			$plan				= $this->db->table('stall')->where('id', $id)->get()->getRowArray();
			
			$name 				= $plan['name'];
			$price 				= $plan['subscription_month_price'];
			$interval 			= $extras['interval'];
			$stripeproductid 	= $plan['stripe_product_id'];
			$stripepriceid 		= $plan['stripe_price_id'];
		}
		
		if($stripeproductid==''){
			$product 	= $this->createProduct($type, $id, $name);
			$productid 	= $product->id;
		}else{
			$retrieveproduct = $this->retrieveProduct($stripeproductid);
			
			if(!$retrieveproduct){
				$product 	= $this->createProduct($type, $id, $name);
				$productid 	= $product->id;
			}else{
				if($retrieveproduct->active==''){
					$product 	= $this->createProduct($type, $id, $name);
					$productid 	= $product->id;
				}else{
					if($retrieveproduct->name!=$name){
						$this->updateProduct($retrieveproduct->id, $name);
					}
					
					$productid 	= $retrieveproduct->id;
				}
			}
		}
		
		if(isset($productid)){
			if($stripepriceid==''){
				$price 		= $this->createPrice($type, $id, $productid, $price, $interval);
				$priceid 	= $price->id;
			}else{
				$retrieveprice = $this->retrievePrice($stripepriceid);
				
				if(!$retrieveprice){
					$price 		= $this->createPrice($type, $id, $productid, $price, $interval);
					$priceid 	= $price->id;
				}else{
					if($retrieveprice->product!=$productid || ($retrieveprice->unit_amount/100)!=$price || $retrieveprice->recurring->interval!=$interval){
						$price 		= $this->createPrice($type, $id, $productid, $price, $interval);
						$priceid 	= $price->id;
					}else{
						$priceid 	= $retrieveprice->id;
					}
				}
			}
			
			if(isset($priceid)){
				return $priceid;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
    function createCustomer($id, $name, $email)
    {
		try{
			$settings = getSettings();
			$stripe = new \Stripe\StripeClient($settings['stripeprivatekey']);
			
			$data = $stripe->customers->create([
				'name' 				=> $name,
				'email' 			=> $email
			]);
			
			$this->db->table('users')->update(['stripe_customer_id' => $data->id], ['id' => $id]);			
			return $data;
		}catch(Exception $e){
			return false;
        }
    }
	
    function retrieveCustomer($customerid)
    {
        try{
			$settings = getSettings();
			$stripe = new \Stripe\StripeClient($settings['stripeprivatekey']);
			
			$data = $stripe->customers->retrieve(
				$customerid,
				[]
			);
			
			return $data;
        }catch(\Stripe\Exception\InvalidRequestException $e){
            return false;
        }catch(Exception $e){
            return false;
        }
    } 
	
    function attachPaymentMethod($paymentmethodid, $customerid)
    {
		try{
			$settings = getSettings();
			$stripe = new \Stripe\StripeClient($settings['stripeprivatekey']);
			
			$data = $stripe->paymentMethods->attach(
				$paymentmethodid,
				['customer' => $customerid]
			);
			
			return $data;
		}catch(Exception $e){
			return false;
        }
    }
	
    function detachPaymentMethod($paymentmethodid)
    {
		try{
			$settings = getSettings();
			$stripe = new \Stripe\StripeClient($settings['stripeprivatekey']);
			
			$data = $stripe->paymentMethods->detach(
				$paymentmethodid,
				[]
			);
			
			return $data;
		}catch(Exception $e){
			return false;
        }
    }
	
	function createPaymentIntents($customerid, $price, $currency, $transactionfee=0, $accountid='')
    {
		try{
			$settings = getSettings();
			$stripe = new \Stripe\StripeClient($settings['stripeprivatekey']);
			
			$createdata = [
				"customer" => $customerid,
				'amount' => $price,
				'currency' => $currency,
				'payment_method_types' => ['card'],
			];
			
			if($accountid!=''){
				$createdata['transfer_data'] = 	[
					'destination' 	=> $accountid,
					'amount' 		=> $price - $transactionfee,
				];
			}
			
			$data = $stripe->paymentIntents->create($createdata);
            return $data;
        }catch(Exception $e){
            return false;
        }
    }
	
    function retrievePaymentIntents($paymentintentsid)
    {
        try{
			$settings = getSettings();
			$stripe = new \Stripe\StripeClient($settings['stripeprivatekey']);
			
			$data = $stripe->paymentIntents->retrieve(
				$paymentintentsid,
				[]
			);
			
			return $data;
        }catch(Exception $e){
            return false;
        }
    } 
	
    function createProduct($type, $id, $name)
    {
		try{
			$settings = getSettings();
			$stripe = new \Stripe\StripeClient($settings['stripeprivatekey']);
			
			$data = $stripe->products->create([
				'name' => $name
			]);
			
			if($type=='1') 		$this->db->table('plan')->where('id', $id)->update(['stripe_product_id' => $data->id]);
			elseif($type=='2') 	$this->db->table('stall')->where('id', $id)->update(['stripe_product_id' => $data->id]);
			return $data;
		}catch(Exception $e){
            return false;
        }
    }

    function updateProduct($productid, $name)
    {
		try{
			$settings = getSettings();
			$stripe = new \Stripe\StripeClient($settings['stripeprivatekey']);
			
			$data = $stripe->products->update(
				$productid,
				['name' => $name]
			);
			
			return $data;
		}catch(Exception $e){
            return false;
        }
    }

    function retrieveProduct($productid)
    {
		try{
			$settings = getSettings();
			$stripe = new \Stripe\StripeClient($settings['stripeprivatekey']);
			
			$data = $stripe->products->retrieve(
				$productid,
				[]
			);
			
			return $data;
		}catch(\Stripe\Exception\InvalidRequestException $e){
            return false;
        }catch(Exception $e){
            return false;
        }
    }

    function createPrice($type, $id, $productid, $planprice, $planinterval)
    {
		try{
			$settings = getSettings();
			$stripe = new \Stripe\StripeClient($settings['stripeprivatekey']);
			
			$amount = ($planprice * 100);
			$currency = "inr";
			
			$data = $stripe->prices->create([
				'unit_amount' => $amount,
				'currency' => $currency,
				//'recurring' => ['interval' => $planinterval],
				'recurring' => ['interval' => 'day'],
				'product' => $productid
			]);
			
			if($type=='1') 		$this->db->table('plan')->where('id', $id)->update(['stripe_price_id' => $data->id]);
			elseif($type=='2') 	$this->db->table('stall')->where('id', $id)->update(['stripe_price_id' => $data->id]);
			return $data;
		}catch(Exception $e){
            return false;
        }
    }

    function retrievePrice($priceid)
    {
		try{
			$settings = getSettings();
			$stripe = new \Stripe\StripeClient($settings['stripeprivatekey']);
			
			$data = $stripe->prices->retrieve(
				$priceid,
				[]
			);
			
			return $data;
		}catch(\Stripe\Exception\InvalidRequestException $e){
            return false;
        }catch(Exception $e){
            return false;
        }
    }

    function createSubscription($customerid, $priceid, $stripepaymentmethodid)
    {
        try{
			$settings = getSettings();
			$stripe = new \Stripe\StripeClient($settings['stripeprivatekey']);
			
			$startdate = strtotime(date('Y-m-d 00:00:00'));
            $data = $stripe->subscriptions->create([
                "customer" => $customerid,
				"items" => [
					["price" => $priceid]
				],
				'default_payment_method' => $stripepaymentmethodid,
				'payment_behavior' => 'default_incomplete', 
				'backdate_start_date' => $startdate,
                'expand' => ['latest_invoice.payment_intent']
            ]);
			
			return $data;
        }catch(Exception $e){
            return false;
        }
    } 
	
    function retrieveSubscription($subscriptionid)
    {
        try{
			$settings = getSettings();
			$stripe = new \Stripe\StripeClient($settings['stripeprivatekey']);
			
			$data = $stripe->subscriptions->retrieve(
				$subscriptionid,
				[]
			);
			
			return $data;
        }catch(Exception $e){
            return false;
        }
    } 
	
    function createSchedule($customerid, $startdate, $endate, $price, $paymentmethodid)
	{
		try{
			$settings = getSettings();
			$stripe = new \Stripe\StripeClient($settings['stripeprivatekey']);
			
            $data = $stripe->subscriptionSchedules->create([
                "customer" 			=> $customerid,
                "start_date" 		=> $startdate,
				"end_behavior" 		=> "release",
				"phases" 			=> [["items" => [["price" => $price, "quantity" => 1]], "end_date" => $endate]],
				"default_settings" 	=> ["default_payment_method" => $paymentmethodid]
            ]);
		
			return $data;
        }catch(Exception $e){
            return false;
        }
	}
	
	function retrieveSchedule($scheduleid)
	{
		try{
			$settings = getSettings();
			$stripe = new \Stripe\StripeClient($settings['stripeprivatekey']);
			
            $data = $stripe->subscriptionSchedules->retrieve(
				$scheduleid,
				[]
			);
		
			return $data;
        }catch(Exception $e){
            return false;
        }
	}
	
    function cancelSchedule($subscriptionid)
	{
		try{
			$settings = getSettings();
			$stripe = new \Stripe\StripeClient($settings['stripeprivatekey']);
			
            $data = $stripe->subscriptionSchedules->cancel(
                $subscriptionid
            );
		
			return $data;
        }catch(Exception $e){
            return false;
        }
	}
	
	function striperefunds($data)
	{
		$this->db->table('booking')->update(['status' => '2'], ['id' => $data['id']]);
		return true;
	}
	
    function createRefunds($paymentintentid, $amount)
    {
        try{
			$settings = getSettings();
			$stripe = new \Stripe\StripeClient($settings['stripeprivatekey']);
			
            $data = $stripe->refunds->create([
                'payment_intent' => $paymentintentid,
                'amount' => $amount
            ]);

			return $data;
        }catch(Exception $e){
            return false;
        }
    }

	function createAccount()
	{
		try{ 
			$settings = getSettings();
			$stripe = new \Stripe\StripeClient($settings['stripeprivatekey']); 

			$data = $stripe->accounts->create([
				'type' 	=> 'standard'
			]);
			
			return $data;	
		}catch(\Stripe\Exception\InvalidRequestException $e){
		    return false;
        }catch(Exception $e){ 
			return false;
		}
	}

    function retrieveAccount($accountid)
    {
		try{			
			$settings = getSettings();
			$stripe = new \Stripe\StripeClient($settings['stripeprivatekey']);

			$data = $stripe->accounts->retrieve($accountid, []);

			return $data;
		}catch(\Stripe\Exception\InvalidRequestException $e){
		    return false;
        }catch(\Stripe\Exception\PermissionException $e){
		    return false;
        }catch(Exception $e){
			return false;
		}
    }
	
    function createAccountLink($accountid, $refreshurl, $returnurl)
    {
		try{			
			$settings = getSettings();
			$stripe = new \Stripe\StripeClient($settings['stripeprivatekey']);

			$data = $stripe->accountLinks->create([
						'account' => $accountid,
						'refresh_url' => $refreshurl,
						'return_url' => $returnurl,
						'type' => 'account_onboarding',
					]);

			return $data;
		}catch(Exception $e){
			return false;
		}catch (\Stripe\Exception\InvalidRequestException $e) {
		    return false;
        }
    }

    function createTransfer($accountid, $amount)
    {
        try{

			$settings = getSettings();
			$stripe = new \Stripe\StripeClient($settings['stripeprivatekey']);
				$currency = "inr";
	            $data = $stripe->transfers->create([
	  				'amount' 			=> $amount * 100,
	  				'currency' 			=> $currency,
	  				'destination' 		=> $accountid
				]);

			return ['status' => '1', 'message' => '', 'result' => $data];
        }catch(Exception $e){
            return ['status' => '0', 'message' => $e->getMessage(),  'result' => []];
        }catch (\Stripe\Exception\InvalidRequestException $e) {
            return ['status' => '0', 'message' => $e->getMessage(),  'result' => []];
        }
    }
	
    function retrieveBalance()
    {
        try{
			$settings = getSettings();
			$stripe = new \Stripe\StripeClient($settings['stripeprivatekey']);
	        $data = $stripe->balance->retrieve([]);

			return array_sum(array_column($data['available'], 'amount'));
        }catch(Exception $e){
            return false;
        }
    }
	
    function createWebhook()
    {
        try{			
			$settings = getSettings();
			$stripe = new \Stripe\StripeClient($settings['stripeprivatekey']);

			$data = $stripe->webhookEndpoints->create([
						'url' => base_url().'/stripe/webhook',
						'enabled_events' => [
							'payment_intent.succeeded',
							'invoice.paid',
							'customer.subscription.updated'
						]
					]);
			
			$webhook = $this->db->table('webhook')->where(['id' => '1'])->get()->getRowArray();
			if($webhook){
				$this->db->table('webhook')->update(['stripe_webhook_id' => $data->id], ['id' => '1']);	
			}else{
				$this->db->table('webhook')->insert(['id' => '1', 'stripe_webhook_id' => $data->id]);	
			}
			
			return $data;
		}catch(Exception $e){
			return false;
		}catch (\Stripe\Exception\InvalidRequestException $e) {
		    return false;
        }
    }
	
    function retrieveWebhook($webhookid)
    {
        try{			
			$settings = getSettings();
			$stripe = new \Stripe\StripeClient($settings['stripeprivatekey']);

			$data = $stripe->webhookEndpoints->retrieve(
						$webhookid,
						[]
					);

			return $data;
		}catch(Exception $e){
			return false;
		}catch (\Stripe\Exception\InvalidRequestException $e) {
		    return false;
        }
    }
}

