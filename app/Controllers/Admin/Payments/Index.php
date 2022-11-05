<?php

namespace App\Controllers\Admin\Payments;

use App\Controllers\BaseController;

use App\Models\Payments;

class Index extends BaseController
{
	public function __construct()
	{  
		$this->payments  = new Payments();
    }
	
	public function index()
	{		
		return view('admin/payments/index');
	}
	
	public function DTpayments()
	{		
		$post 			= $this->request->getPost();
		$totalcount 	= $this->payments->getPayments('count', ['payment'], ['ninstatus' => ['0']]+$post);
		$results 		= $this->payments->getPayments('all', ['payment'], ['ninstatus' => ['0']]+$post);
	
		$totalrecord 	= [];
				
		if(count($results) > 0){
			$action = '';
			foreach($results as $key => $result){
				$action = 	'<a href="'.getAdminUrl().'/payments/view/'.$result['id'].'" data-id="'.$result['id'].'" class="view">View</a>
							';
				
				$totalrecord[] = 	[
										'id' 			=> 	$result['id'],
										'name' 			=> 	$result['name'],
										'email'  		=>  $result['email'],
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
		$result = $this->payments->getPayments('row', ['payment','users'], ['ninstatus' => ['0'], 'id' => $id]);

		if($result){
			$data['result'] = $result;
		}else{
			$this->session->setFlashdata('danger', 'No Record Found.');
			return redirect()->to(getAdminUrl().'/payments'); 
		}

		$data['usertype']        = $this->config->usertype;
		$data['currencysymbol']	 =  $this->config->currencysymbol;
		return view('admin/payments/view', $data);
	}	
}
