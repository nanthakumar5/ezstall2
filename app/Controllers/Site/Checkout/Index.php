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
			$paymentmethodid			= $requestData['paymentmethodid'];
			$mpdf 						= new \Mpdf\Mpdf();

			if($paymentmethodid!='1') $payment = $this->stripe->action(['id' => $requestData['stripepayid']]);
			else $payment = 1;
			
			if($payment){
				$requestData['paymentid'] 	=  0;
				$requestData['paidunpaid']  =  0;
				
				if($paymentmethodid!='1'){
					$requestData['paymentid'] 	= $payment;
					$requestData['paidunpaid']  = 1;
				}
				
				$booking = $this->booking->action($requestData);
				if($booking){
					if($paymentmethodid!='1') $this->stripe->action(['bookingid' => $booking]);
					
					$this->cart->delete(['user_id' => $userid, 'type' => $requestData['type']]);
					$reservationpdf = $this->booking->getBooking('row', ['booking', 'event', 'users','barnstall', 'rvbarnstall', 'feed', 'shaving','payment','paymentmethod'], ['userid' => [$userid], 'id' => $booking]);
					
					$data['reservationpdf'] = $reservationpdf;
					$data['usertype'] 		= $this->config->usertype;
					$data['settings'] 		= getSettings();
					$data['currencysymbol'] = $this->config->currencysymbol;
					$html 					=  view('site/common/pdf/userreservation', $data);
					$mpdf->WriteHTML($html);
					$this->response->setHeader('Content-Type', 'application/pdf');
					$attachment = $mpdf->Output('Eventinvoice.pdf', 'S');
					
					send_emailsms_template('3', ['userid' => $reservationpdf['user_id'],'eventid' => $reservationpdf['event_id'], 'attachment' => $attachment]);
					send_emailsms_template('5', ['mobile' => $reservationpdf['mobile'], 'userid' => $reservationpdf['user_id'],'eventid' => $reservationpdf['event_id']]);
					
					return redirect()->to(base_url().'/paymentsuccess'); 
				}else{
					$this->session->setFlashdata('danger', 'Try Later.');
					return redirect()->to(base_url().'/checkout'); 
				}
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