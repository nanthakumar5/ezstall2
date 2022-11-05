<?php

namespace App\Controllers\Admin\Reservations;

use App\Controllers\BaseController;

use App\Models\Booking;
use App\Models\Stripe;

class Index extends BaseController
{
	public function __construct()
	{  
		$this->payments  = new Booking();
		$this->stripe    = new Stripe();
    }
	
	public function index()
	{	
		if($this->request->getMethod()=='post'){ 
			$this->stripe->striperefunds($this->request->getPost());
			return redirect()->to(getAdminUrl().'/reservations'); 
        }
		return view('admin/reservations/index');
	}
	
	public function DTreservations()
	{		
		$post 			= $this->request->getPost();
		$totalcount 	= $this->payments->getBooking('count', ['booking'], $post);
		$results 		= $this->payments->getBooking('all', ['booking', 'event','stall','payment','paymentmethod'], $post);
		$data 			= $this->config->bookingstatus;
		$totalrecord 	= [];
				
		if(count($results) > 0){
			$action = '';
			
			foreach($results as $key => $result){

			$month 			= date('m',strtotime($result['check_out'])) == date('m');
			$statuscolor 	= ($result['status']=='2') ? "cancelcolor" : "activecolor"; 

			$action = 	'<a href="'.getAdminUrl().'/reservations/view/'.$result['id'].'" data-id="'.$result['id'].'" class="view btn btn-primary">View</a>';

			if($result['status']=='1' && $month){
			 	$action = 	'<a href="'.getAdminUrl().'/reservations/view/'.$result['id'].'" data-id="'.$result['id'].'" class="view btn btn-primary">View</a><a href="javascript:void(0);" data-id="'.$result['id'].'"" data-paymentid="'.$result['payment_id'].'" data-paymentintentid="'.$result['stripe_paymentintent_id'].'" data-amount="'.$result['amount'].'" class="striperefunds btn btn-danger">
					Cancel</a>';
			}
				
				
				$totalrecord[] = 	[
										'id' 			=> 	$result['id'],
										'paymentmethod' => 	$result['paymentmethod_name'],
										'firstname' 	=> 	$result['firstname'],
										'lastname'  	=>  $result['lastname'],
										'mobile'  		=>  $result['mobile'],
										'status'  		=>  '<div class='.$statuscolor.'>'.$data[$result['status']].'</div>',
										'action'		=> 	'<div class="table-action">
																'.$action.
															'</div>'
									];
			}
		}
		
		$json = array(
			"draw"            => intval($post['draw']),   
			"recordsTotal"    => intval($totalcount),  
			"recordsFiltered" => intval($totalcount),
			"data"            => $totalrecord
		);

		echo json_encode($json);
	}
	
	public function view($id)
	{
		$result = $this->payments->getBooking('row', ['booking', 'event','barnstall','users','paymentmethod'], ['id' => $id]);
		if($result){
			$data['result'] = $result;
		}else{
			$this->session->setFlashdata('danger', 'No Record Found.');
			return redirect()->to(getAdminUrl().'/reservations'); 
		}
		$data['usertype'] 			= $this->config->usertype;
		$data['bookingstatus'] 		= $this->config->bookingstatus;

		return view('admin/reservations/view', $data);
	}
}
