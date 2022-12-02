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
		$price 					= isset($requestData['amount']) ? $requestData['amount'] * 100 : $requestData['price'] * 100;
        $currency 				= "inr";
		
		$customer = $this->customer();
		if($customer){
			$paymentintents = $this->createPaymentIntents($customer, $price, $currency);				
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
				
				if(isset($requestData['barnstall'])){
					foreach(json_decode($requestData['barnstall'], true) as $barnstall){
						if($barnstall['subscriptionprice']!='' && $barnstall['subscriptionprice']!='0'){
							$productandprice = $this->productandprice('2', '', ['name' => $barnstall['stall_name'], 'price' => $barnstall['subscriptionprice'], 'interval' => 'week']);
							$this->createSchedule($customer, strtotime($requestData['checkin']), strtotime($requestData['checkout']), $productandprice);
						}
					}
				}
				
				return ['paymentintents' => $paymentintents, 'id' => $paymentinsertid];
			}else{
				return false;
			}
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
		$planid 				= $requestData['plan_id'];
		
		$customer 			= $this->customer();
		$productandprice 	= $this->productandprice('1', $planid);
		
		if ($customer && $productandprice){
			$subscription = $this->createSubscription($customer, $productandprice);
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
			$name 				= $extras['name'];
			$price 				= $extras['price'];
			$interval 			= $extras['interval'];
			$stripeproductid 	= '';
			$stripepriceid 		= '';
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
	
    function createProduct($type, $id, $name)
    {
		try{
			$settings = getSettings();
			$stripe = new \Stripe\StripeClient($settings['stripeprivatekey']);
			
			$data = $stripe->products->create([
				'name' => $name
			]);
			
			if($type=='1') $this->db->table('plan')->where('id', $id)->update(['stripe_product_id' => $data->id]);
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
				'recurring' => ['interval' => $planinterval],
				'product' => $productid
			]);
			
			if($type=='1') $this->db->table('plan')->where('id', $id)->update(['stripe_price_id' => $data->id]);
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
	
    function createSchedule($customerid, $startdate, $endate, $price)
	{
		try{
			$settings = getSettings();
			$stripe = new \Stripe\StripeClient($settings['stripeprivatekey']);
			
            $data = $stripe->subscriptionSchedules->create([
                "customer" 		=> $customerid,
                "start_date" 	=> $startdate,
				"end_behavior" 	=> "release",
				"phases" 		=> [["items" => [["price" => $price, "quantity" => 1]], "end_date" => $endate]],
            ]);
		
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

