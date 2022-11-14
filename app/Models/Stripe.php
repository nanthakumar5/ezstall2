<?php
namespace App\Models;
use App\Models\BaseModel;

class Stripe extends BaseModel
{	
	public function action($data)
	{ 
		$this->db->transStart();
		
		$id = $data['id'];
		
		$payment = $this->db->table('payment')->where('id', $id)->get()->getRowArray();

		if($payment['type']=='1'){
			$data = $this->retrievePaymentIntents($payment['stripe_paymentintent_id']);

			if($data->status=='succeeded'){
				$this->db->table('payment')->update(['status' => '1'], ['id' => $id]);
				$insertid = $id;
			}
		}elseif($payment['type']=='2'){
			$data = $this->retrieveSubscription($payment['stripe_subscription_id']);
			if($data->status=='active'){
				$this->db->table('payment')->update(['status' => '1'], ['id' => $id]);
				$this->db->table('users')->where(['id' => $payment['user_id']])->update(['subscription_id' => $id]);
				$insertid = $id;
			}
		}
			
		if(!isset($insertid) || $this->db->transStatus() === FALSE){
			$this->db->transRollback();
			return false;
		}else{
			$this->db->transCommit();
			return $id;
		}
	}

	function stripepayment($requestData)
	{
		$userdetails			= getSiteUserDetails();
		
		$userid 				= $userdetails['id'];
		$name 					= $userdetails['name'];
		$email 					= $userdetails['email'];
		$stripecustomerid 		= $userdetails['stripe_customer_id'];
		$price 					= isset($requestData['amount']) ? $requestData['amount'] * 100 : $requestData['price'] * 100;
        $currency 				= "inr";
		
		if($stripecustomerid==''){
			$customer 			= $this->createCustomer($userid, $name, $email);
			$stripecustomerid 	= $customer->id;
		}else{
			$retrievecustomer 	= $this->retrieveCustomer($stripecustomerid);
			
			if(!$retrievecustomer){
				$customer 			= $this->createCustomer($userid, $name, $email);
				$stripecustomerid 	= $customer->id; 
			}
		}
		
		$paymentintents = $this->createPaymentIntents($stripecustomerid, $price, $currency);				
		if($paymentintents){
			$paymentData = array(
				'user_id' 					=> $userid,
				'name' 						=> $name,
				'email' 					=> $email,
				'amount' 					=> $price/100,
				'currency' 					=> $currency,
				'stripe_paymentintent_id' 	=> $paymentintents->id,
				'type' 						=> '1',
				'status' 					=> '0',
				'created' 					=> date("Y-m-d H:i:s")
			);
			
			$this->db->table('payment')->insert($paymentData);
			$paymentinsertid = $this->db->insertID();
			
			return ['paymentintents' => $paymentintents, 'id' => $paymentinsertid];
		}else{
			return false;
		}
	}
	
	
	function striperecurringpayment($requestData)
	{
		$userdetails			= getSiteUserDetails();
		
		$userid 				= $userdetails['id'];
		$name 					= $userdetails['name'];
		$email 					= $userdetails['email'];
		$stripecustomerid 		= $userdetails['stripe_customer_id'];
		$planid 				= $requestData['plan_id'];
		$planname 				= $requestData['plan_name'];
		$planprice 				= $requestData['plan_price'];
		$planinterval 			= $requestData['plan_interval'];
		
		if($stripecustomerid==''){
			$customer 			= $this->createCustomer($userid, $name, $email);
			$stripecustomerid 	= $customer->id;
		}else{
			$retrievecustomer = $this->retrieveCustomer($stripecustomerid);
			
			if(!$retrievecustomer){
				$customer 			= $this->createCustomer($userid, $name, $email);
				$stripecustomerid 	= $customer->id;
			}
		}
		
		$product = $this->createProduct($planname);
		if($product){
			$price = $this->createPrice($product->id, $planprice, $planinterval);
			if ($price){
				$subscription = $this->createSubscription($stripecustomerid, $price->id);
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
						'stripe_plan_id' 			=> $subscription->plan->id,
						'plan_id'					=> $planid,
						'plan_interval' 			=> $subscription->plan->interval,
						'plan_period_start' 		=> date("Y-m-d H:i:s", $subscription->current_period_start),
						'plan_period_end' 			=> date("Y-m-d H:i:s", $subscription->current_period_end),
						'type' 						=> '2',
						'status' 					=> '0',
						'created' 					=> date("Y-m-d H:i:s")
					);

					$this->db->table('payment')->insert($paymentData);
					$paymentinsertid = $this->db->insertID();
					
					return ['paymentintents' => $paymentintents, 'id' => $paymentinsertid];
				}else{
					return false;
				}
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	function createPaymentIntents($customerid, $price, $currency)
    {
		try{
			$settings = getSettings();
			$stripe = new \Stripe\StripeClient($settings['stripeprivatekey']);
			
			$data = $stripe->paymentIntents->create([
				"customer" => $customerid,
				'amount' => $price,
				'currency' => $currency,
				'payment_method_types' => ['card'],
			]);

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
	
    function createProduct($planname)
    {
		try{
			$settings = getSettings();
			$stripe = new \Stripe\StripeClient($settings['stripeprivatekey']);
			
			$data = $stripe->products->create([
				'name' => $planname
			]);
			
			return $data;
		}catch(Exception $e){
            return false;
        }
    }

    function createPrice($productid, $planprice, $planinterval)
    {
		try{
			$settings = getSettings();
			$stripe = new \Stripe\StripeClient($settings['stripeprivatekey']);
			
			$amount = ($planprice * 100);
			$currency = "inr";
			
			$data = $stripe->prices->create([
				'unit_amount' => $amount,
				'currency' => $currency,
				'recurring' => ['interval' => $planinterval],
				'product' => $productid
			]);
			
			return $data;
		}catch(Exception $e){
            return false;
        }
    }

    function createSubscription($customerid, $priceid)
    {
        try{
			$settings = getSettings();
			$stripe = new \Stripe\StripeClient($settings['stripeprivatekey']);
			
            $data = $stripe->subscriptions->create([
                "customer" => $customerid,
				"items" => [
					["price" => $priceid]
				],
				'payment_behavior' => 'default_incomplete', 
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

    function stripeconnect($requestData)
    {
		$stripeemailid		= $requestData['stripe_email'];
		$stripeaccountid 	= $requestData['stripe_account_id'];
		
		if($stripeaccountid == ''){
			$connectedaccount = $this->createAccount($stripeemailid);
			return $connectedaccount ? $connectedaccount['id'] : false;			
		}else{ 
			$retrieveaccount = $this->retrieveAccount($stripeaccountid);
			
			if(!$retrieveaccount){
				$connectedaccount = $this->createAccount($stripeemailid);
				return $connectedaccount ? $connectedaccount['id'] : false;
			}else{
				return $retrieveaccount ? $retrieveaccount['id'] : false;
			}
		}
    }

	function createAccount($stripeemail)
	{
		try{ 

			$settings = getSettings();
			$stripe = new \Stripe\StripeClient($settings['stripeprivatekey']); 

			$data = $stripe->accounts->create([
				'type' 	=> 'standard',
				'email' => $stripeemail
			]);
			
			return $data;	
		}catch(Exception $e){ 
			return false;
		}catch (\Stripe\Exception\InvalidRequestException $e) {
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
}

