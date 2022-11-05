<?php

namespace App\Controllers\Admin\Tax;

use App\Controllers\BaseController;

use App\Models\Tax;

class Index extends BaseController
{
	public function __construct()
	{  
		$this->tax  = new Tax();
    }
	
	public function index()
	{		
		if ($this->request->getMethod()=='post')
        {
			$requestdata = $this->request->getPost();
			$requestdata['userid'] = getAdminUserID();
			
            $result = $this->tax->delete($requestdata);
			
			if($result){
				$this->session->setFlashdata('success', 'Tax deleted successfully.');
				return redirect()->to(getAdminUrl().'/tax'); 
			}else{
				$this->session->setFlashdata('danger', 'Try Later');
				return redirect()->to(getAdminUrl().'/tax'); 
			}
        }
		
		return view('admin/tax/index');
	}
	
	public function DTtax()
	{
		$post 			= $this->request->getPost();
		$totalcount 	= $this->tax->getTax('count', ['tax'], ['status' => ['1']]+$post);
		$results 		= $this->tax->getTax('all', ['tax'], ['status' => ['1']]+$post);
		$currencysymbol = $this->config->currencysymbol;
		$totalrecord 	= [];
				
		if(count($results) > 0){
			$action = '';
			foreach($results as $key => $result){
				$action = 	'<a href="'.getAdminUrl().'/tax/action/'.$result['id'].'">Edit</a> / 
							<a href="javascript:void(0);" data-id="'.$result['id'].'" class="delete">Delete</a>';
				
				$totalrecord[] = 	[
										'fromtax' 			=> 	$result['from_tax'],
										'totax'           	=>  $result['to_tax'],
										'taxprice'          =>  $result['tax_price'],
										'action'			=> 	'
																	<div class="table-action">
																		'.$action.'
																	</div>
																'
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
	
	public function action($id='')
	{
		if($id!=''){
			$result = $this->tax->getTax('row', ['tax'], ['id' => $id, 'status' => ['1']]);
			if($result){
				$data['result'] = $result;
			}else{
				$this->session->setFlashdata('danger', 'No Record Found.');
				return redirect()->to(getAdminUrl().'/tax'); 
			}
		}
		
		if ($this->request->getMethod()=='post')
        {
			$requestdata = $this->request->getPost();
			$requestdata['status'] = '1';
			
            $result = $this->tax->action($requestdata);
			
			if($result){
				$this->session->setFlashdata('success', 'Tax saved successfully.');
				return redirect()->to(getAdminUrl().'/tax'); 
			}else{
				$this->session->setFlashdata('danger', 'Try Later.');
				return redirect()->to(getAdminUrl().'/tax'); 
			}
        }
		
		$data['paymentinterval'] 	= $this->config->paymentinterval;
		$data['paymentuser'] 		= $this->config->paymentuser;

		return view('admin/tax/action', $data);
	}	
}
