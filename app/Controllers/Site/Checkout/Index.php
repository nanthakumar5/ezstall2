<?php 

namespace App\Controllers\Site\Checkout;

use App\Controllers\BaseController;
use App\Models\Booking;
use App\Models\Stripe;
use App\Models\Cart;
use App\Models\Paymentmethod;
use App\Models\Event;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Index extends BaseController
{
	public function __construct()
	{
		$this->booking 			= new Booking();	
		$this->stripe  			= new Stripe();
		$this->cart    			= new Cart(); 
		$this->paymentmethod    = new Paymentmethod(); 
		$this->event        	= new Event();
	}
	
	public function index()
	{
		if(!getCart()){
			return redirect()->to(base_url().'/'); 
		}
		
		$userdetail  	= getSiteUserDetails();
		$cartdetail  	= getCart();		
		$settings		= getSettings();
		$paymentmethod	= $this->paymentmethod->getPaymentmethod('all', ['paymentmethod']);
		$event			= $this->event->getEvent('row', ['event'], ['id' => $cartdetail['event_id'], 'status'=> ['1']]);
		
		if($this->request->getMethod()=='post')
		{    
			$requestData 				= $this->request->getPost();
			$userid             		= $userdetail['id'];

			if(isset($requestData['paymentmethodid'])){
				$booking 	= $this->booking->action($requestData);
				if($booking){
					$this->cart->delete(['user_id' => $userid, 'type' => $requestData['type']]);
					checkoutEmailSms($booking);
					
					return redirect()->to(base_url().'/paymentsuccess'); 
				}else{
					$this->session->setFlashdata('danger', 'Try Later.');
					return redirect()->to(base_url().'/checkout'); 
				}
			}elseif(isset($requestData['stripepay'])){
				$this->cart->delete(['user_id' => $userid, 'type' => $requestData['type']]);				
				return redirect()->to(base_url().'/paymentsuccess'); 
			}else{
				$this->session->setFlashdata('danger', 'Your payment is not processed successfully.');
				return redirect()->to(base_url().'/checkout'); 
			}
		}
		
		return view('site/checkout/index', [
			'currencysymbol' 	=> $this->config->currencysymbol, 
			'pricelists' 		=> $this->config->pricelist, 
			'settings' 			=> $settings, 
			'userdetail' 		=> $userdetail, 
			'cartdetail' 		=> $cartdetail,
			'paymentmethod' 	=> $paymentmethod,
			'event' 			=> $event,
			'stripe'			=> view('site/common/stripe/stripe1')
		]);
	}

	public function success(){
		return view('site/checkout/success');
	}
}