<?php

namespace App\Controllers\Common;

use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Ajax extends BaseController
{
    public function __construct()
    {
        $this->db = db_connect();
	}
	
	public function fileupload()
	{ 
		$file 		= $this->request->getFile('file'); 
		$name 		= $file->getRandomName(); 
		$postData 	= $this->request->getPost();

		$file->move($this->request->getPost('path'), $name);
		$this->db->table('fileupload')->insert(['name' => $name, 'date' => date('Y-m-d')]);

		if($postData['resize']!=''){
			if($postData['resize']=='1'){
				$imageresize = array(['120','90'],['559','371']);
				$path = 'assets/uploads/event';
			}else if($postData['resize']=='2'){
				$imageresize = array(['120','90'],['1200','600']);
				$path = 'assets/uploads/event';
			}else if($postData['resize']=='aboutus'){
				$imageresize = array(['680','440'],['486','300']);
				$path = 'assets/uploads/aboutus';
			}else if($postData['resize']=='banner'){
				$imageresize = array(['1200','800']);
				$path = 'assets/uploads/banner';
			}

			foreach($imageresize as $imageresize){ 
				\Config\Services::image()->withFile('assets/uploads/temp/' . $name)
	                        ->resize($imageresize[0],$imageresize[1])
	         				->save($path .'/' . $imageresize[0].'x'.$imageresize[1].'_'.$name);
			}
		}

		echo json_encode(['success' => $name]);
	}
	
	public function ajaxoccupied()
	{
		$eventid = $this->request->getPost('eventid');
		$checkin = formatdate($this->request->getPost('checkin'));
		$checkout = formatdate($this->request->getPost('checkout'));
		$result = getOccupied($eventid, ['checkin' => $checkin, 'checkout' => $checkout]);
		
		echo json_encode(['success' => $result, 'totalstallcount' => count($result)]);
	}
	
	public function ajaxreserved()
	{
		$eventid = $this->request->getPost('eventid');
		$checkin = formatdate($this->request->getPost('checkin'));
		$checkout = formatdate($this->request->getPost('checkout'));
		$result = getReserved($eventid, ['checkin' => $checkin, 'checkout' => $checkout]);
		echo json_encode(['success' => $result]);
	}

	public function ajaxblockunblock()
	{  
		$eventid 	= $this->request->getPost('eventid'); 
		$result 	= getBlockunblock($eventid);
		echo json_encode(['success' => $result]); 
	}
	
	public function ajaxproductquantity()
	{
		$eventid = $this->request->getPost('eventid');
		$productid = $this->request->getPost('productid');
		$result = getProductQuantity($eventid, ['product_id' => $productid]);
		
		echo json_encode(['success' => $result]);
	}
	
	public function ajaxstripepayment()
	{
		$requestData = $this->request->getPost();		
		$stripeModel = new \App\Models\Stripe();
		
		if($requestData['type']=='1' || (isset($requestData['page']) && $requestData['page']=='checkout')){
			$result = $stripeModel->stripepayment($requestData);
		}elseif($requestData['type']=='2'){
			$result = $stripeModel->striperecurringpayment($requestData);
		}
		
		echo json_encode(['success' => $result]);
	}

	public function ajaxsearchevents()
	{ 
		$requestData = $this->request->getPost(); 
		$event = new \App\Models\Event();
		$result = array();
		
		if (isset($requestData['search'])) {
			$result = $event->getEvent('all', ['event'], ['status'=> ['1'], 'page' => 'events', 'search' => ['value' => $requestData['search']], 'type' =>'1']);
		}

		return $this->response->setJSON($result);
	}

	public function ajaxsearchfacility()
	{
		$requestData = $this->request->getPost(); 
		$event = new \App\Models\Event();
		$result = array();
		
		if (isset($requestData['search'])) {
			$result = $event->getEvent('all', ['event'], ['status'=> ['1'], 'page' => 'events', 'search' => ['value' => $requestData['search']], 'type' =>'2']);
		}

		return $this->response->setJSON($result);
	}

	public function importbarnstall()
    {	
		$phpspreadsheet = new Spreadsheet();
      	$reader 		= new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
      	$spreadsheet 	= $reader->load($_FILES['file']['tmp_name']);
		$sheetdata 		= $spreadsheet->getActiveSheet()->toArray();
		$array 			= [];
		
		foreach($sheetdata as $key1 => $data1){
			if($key1=='0') continue;
			
			foreach($data1 as $key2 => $data2){
				if($key1=='1' && ($key2%3)=='0'){
					$array[$key2]['name'] = $data2;
				}
				
				if($key1 > '1'  && ($key2%3)=='0'){
					$array[$key2]['stall'][] = ['name' => $data2, 'price' => (isset($data1[$key2+1]) ? $data1[$key2+1] : ''), 'charging_id' => (isset($data1[$key2+2]) ? $data1[$key2+2] : '')];
				}
			}
		}
		
		$array = array_values($array);
		echo json_encode($array);
    }
	
	public function barnstall1()
	{
		$requestData 	= $this->request->getPost(); 
		$id				= $requestData['id'];
		$userid			= $requestData['userid'];
		
		$event = new \App\Models\Event();
		$result = $event->getEvent('row',  ['event', 'barn', 'stall', 'rvbarn', 'rvstall', 'feed', 'shaving'], ['id' => $id, 'status' => ['1'], 'userid' => $userid, 'type' => '2']);
		
		if($result){				
			$data['occupied'] 	= getOccupied($id);
			$data['reserved'] 	= getReserved($id);
			$data['result'] 	= $result;
			$data['nobtn'] 		= '1';
			
			echo view('site/common/barnstall/barnstall1', ['yesno' => $this->config->yesno, 'pricelist' => $this->config->pricelist, 'usertype' => '2']+$data);		
		}else{
			echo '';
		}
	}
}
