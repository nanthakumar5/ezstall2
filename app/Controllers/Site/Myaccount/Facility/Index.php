<?php 
namespace App\Controllers\Site\Myaccount\Facility;

use App\Controllers\BaseController;
use App\Models\Users;
use App\Models\Event;
use App\Models\Booking;
use App\Models\Stripe;
use App\Models\Products;
use App\Models\Report;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Index extends BaseController
{
	public function __construct()
	{	
		$this->users 	= new Users();
		$this->event 	= new Event();
		$this->booking 	= new Booking();
		$this->stripe 	= new Stripe();
		$this->product 	= new Products();
		$this->report 	= new Report();
	}
    
    public function index()
    { 			
		$userdetail 	= getSiteUserDetails();
		$userid 		= $userdetail['id'];
		$usertype 		= $userdetail['type'];

		if($usertype == '4') $userid = $userdetail['parent_id'];
		
		if ($this->request->getMethod()=='post')
        {
			$result = $this->event->delete($this->request->getPost());
			if($result){
				$this->session->setFlashdata('success', 'Facility deleted successfully.');
				return redirect()->to(base_url().'/myaccount/facility'); 
			}else{
				$this->session->setFlashdata('danger', 'Try Later');
				return redirect()->to(base_url().'/myaccount/facility'); 
			}
        }
		
    	$pager = service('pager'); 
		$page = (int)(($this->request->getVar('page')!==null) ? $this->request->getVar('page') :1)-1;
		$perpage =  10; 
		$offset = $page * $perpage;
		
		$eventcount = $this->event->getEvent('count', ['event'], ['status' => ['1'], 'userid' => $userid, 'type' => '2']);
		$event = $this->event->getEvent('all', ['event'], ['status' => ['1'], 'userid' => $userid, 'type' => '2', 'start' => $offset, 'length' => $perpage], ['orderby' => 'e.id desc']);

        $data['list'] 		= $event;
        $data['pager'] 		= $pager->makeLinks($page, $perpage, $eventcount);
		$data['userid'] 	= $userid;
		$data['usertype'] 	= $usertype;
		
		return view('site/myaccount/facility/index', $data);
    }

    public function action($id='')
	{   
		$settings 						= getSettings();
		$userdetails 					= getSiteUserDetails();
		$userid         				= $userdetails['id'];
		$usertype       				= $userdetails['type'];
		$checksubscription 				= checkSubscription();
		$checksubscriptiontype 			= $checksubscription['type'];
		$checksubscriptionfacility 		= $checksubscription['facility'];
		$checksubscriptionstallmanager 	= $checksubscription['stallmanager'];

		$eventcount = $this->event->getEvent('count', ['event'], ['status' => ['1'], 'userid' => $userid, 'type' => '2']);
		
		if($checksubscriptiontype=='2' && $checksubscriptionfacility!='1'){
			$this->session->setFlashdata('danger', 'Please subscribe the account.');
			return redirect()->to(base_url().'/myaccount/subscription'); 
		}
		elseif($checksubscriptiontype=='4' && $checksubscriptionstallmanager!='4'){ 
			$this->session->setFlashdata('danger', 'Please subscribe the account.');
			return redirect()->to(base_url().'/myaccount/subscription'); 
		}
		
		if($id!=''){
			$result = $this->event->getEvent('row',  ['event', 'barn', 'stall', 'rvbarn', 'rvstall', 'feed', 'shaving'],['id' => $id, 'status' => ['1'], 'userid' => $userid, 'type' => '2']);
			if($result){				
				$data['occupied'] 		= getOccupied($id);
				$data['reserved'] 		= getReserved($id);
				$data['blockunblock'] 	= getBlockunblock($id, ['type' => '1']);
				$data['result'] 		= $result;
			}else{
				$this->session->setFlashdata('danger', 'No Record Found.');
				return redirect()->to(base_url().'/myaccount/facility'); 
			}
		}
		
		if ($this->request->getMethod()=='post')
		{
			if(isset($_FILES['import'])){
				$data['import'] 		= 1;
				$data['result'] 		= $this->importfacility();
			}else{
				$requestData 			= $this->request->getPost();
				
				if(isset($requestData['stripepay'])){
					$this->session->setFlashdata('success', 'Facility submitted successfully.');				
					return redirect()->to(base_url().'/myaccount/facility'); 
				}else{
					$requestData['type'] 	= '2';
					$requestData['name'] 	= $requestData['facility_name'];
					
					$result = $this->event->action($requestData);			
					if($result){
						$this->session->setFlashdata('success', 'Facility submitted successfully.');
						return redirect()->to(base_url().'/myaccount/facility'); 
					}else{
						$this->session->setFlashdata('danger', 'Try Later.');
						return redirect()->to(base_url().'/myaccount/facility'); 
					}
				}
			}
        } 
		
		$data['barnstall'] 		= view('site/common/barnstall/barnstall1', ['yesno' => $this->config->yesno, 'pricelist' => $this->config->pricelist, 'usertype' => $usertype]+(isset($data) ? $data : []));		
		$data['userid'] 		= $userid;
		$data['settings'] 		= $settings;
		$data['statuslist'] 	= $this->config->status1;
		$data['currencysymbol'] = $this->config->currencysymbol;
		$data['googleapikey'] 	= $this->config->googleapikey;
		$data['stripe'] 		= view('site/common/stripe/stripe1');
		
		return view('site/myaccount/facility/action', $data);
	}
	
	public function view($id)
    {  
		$event  	= $this->event->getEvent('row', ['event', 'barn', 'stall', 'rvbarn', 'rvstall', 'bookedstall', 'rvbookedstall'],['id' => $id, 'type' => '2']);

		$data['detail']  			= $event;
		$data['barnstall'] 			= view('site/common/barnstall/barnstall3', ['checkevent' => checkEvent($event), 'settings' => getSettings(), 'currencysymbol' => $this->config->currencysymbol, 'pricelists' => $this->config->pricelist]+$data);
		$data['currencysymbol'] = $this->config->currencysymbol;
		
		return view('site/myaccount/facility/view',$data);
    }

    public function inventories($id)
    {  
		$data['product']  	= $this->product->getProduct('all', ['product'], ['event_id' => $id]);
		return view('site/myaccount/facility/inventories',$data);
    }
	
    public function export($id)
    {	
		$yesno			= $this->config->yesno;
		$yesnovalue		= implode(',', array_values($yesno));
		
    	$data 			= $this->event->getEvent('row', ['event', 'barn', 'stall', 'rvbarn', 'rvstall', 'feed', 'shaving'],['id' => $id, 'type' => '2']); 
		
		$spreadsheet = new Spreadsheet();
		$sheet 		 = $spreadsheet->getActiveSheet();

		$row = 1;
		$sheet->setCellValue('A'.$row, 'Name');
		$sheet->setCellValue('B'.$row, 'Street');
		$sheet->setCellValue('C'.$row, 'City');
		$sheet->setCellValue('D'.$row, 'State');
		$sheet->setCellValue('E'.$row, 'Zipcode');
		$sheet->setCellValue('F'.$row, 'Description');
		$sheet->setCellValue('G'.$row, 'Will you be selling feed at this event?');
		$sheet->setCellValue('H'.$row, 'Will you be selling shavings at this event?');
		$sheet->setCellValue('I'.$row, 'Will you have RV Hookups at this event?');
		$sheet->setCellValue('J'.$row, 'Will you Collect the Cleaning fee from Horse owner?');
		$sheet->setCellValue('K'.$row, 'Send a text message to users when their stall is unlocked and ready for use?');
		$sheet->setCellValue('L'.$row, 'Night Price');
		$sheet->setCellValue('M'.$row, 'Week Price');
		$sheet->setCellValue('N'.$row, 'Month Price');
		$sheet->setCellValue('O'.$row, 'Flat Price');
		$sheet->setCellValue('P'.$row, 'Subscription Initial Price');
		$sheet->setCellValue('Q'.$row, 'Subscription Month Price');
		$sheet->setCellValue('R'.$row, 'Cleaning Price');
		
        $row++;
		$pricefee = explode(',', $data['price_fee']);
		$sheet->setCellValue('A'.$row, $data['name']);
		$sheet->setCellValue('B'.$row, $data['location']);
		$sheet->setCellValue('C'.$row, $data['city']);
		$sheet->setCellValue('D'.$row, $data['state']);
		$sheet->setCellValue('E'.$row, $data['zipcode']);
		$sheet->setCellValue('F'.$row, strip_tags($data['description']));
		$sheet->setCellValue('G'.$row, (isset($yesno[$data['feed_flag']]) ? $yesno[$data['feed_flag']] : ''));
		$sheet->setCellValue('H'.$row, (isset($yesno[$data['shaving_flag']]) ? $yesno[$data['shaving_flag']] : ''));
		$sheet->setCellValue('I'.$row, (isset($yesno[$data['rv_flag']]) ? $yesno[$data['rv_flag']] : ''));
		$sheet->setCellValue('J'.$row, (isset($yesno[$data['cleaning_flag']]) ? $yesno[$data['cleaning_flag']] : ''));
		$sheet->setCellValue('K'.$row, (isset($yesno[$data['notification_flag']]) ? $yesno[$data['notification_flag']] : ''));
		$sheet->setCellValue('L'.$row, (isset($pricefee[0]) ? $pricefee[0] : 0));
		$sheet->setCellValue('M'.$row, (isset($pricefee[1]) ? $pricefee[1] : 0));
		$sheet->setCellValue('N'.$row, (isset($pricefee[2]) ? $pricefee[2] : 0));
		$sheet->setCellValue('O'.$row, (isset($pricefee[3]) ? $pricefee[3] : 0));
		$sheet->setCellValue('P'.$row, (isset($pricefee[4]) ? $pricefee[4] : 0));
		$sheet->setCellValue('Q'.$row, (isset($pricefee[5]) ? $pricefee[5] : 0));
		$sheet->setCellValue('R'.$row, $data['cleaning_fee']);
		
		$dropdownlist = $sheet->getCell('G'.$row)->getDataValidation();
		$dropdownlist->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)->setAllowBlank(false)->setShowDropDown(true)->setPrompt('Select Yes or No')->setFormula1('"'.$yesnovalue.'"');
		$dropdownlist = $sheet->getCell('H'.$row)->getDataValidation();
		$dropdownlist->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)->setAllowBlank(false)->setShowDropDown(true)->setPrompt('Select Yes or No')->setFormula1('"'.$yesnovalue.'"');
		$dropdownlist = $sheet->getCell('I'.$row)->getDataValidation();
		$dropdownlist->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)->setAllowBlank(false)->setShowDropDown(true)->setPrompt('Select Yes or No')->setFormula1('"'.$yesnovalue.'"');
		$dropdownlist = $sheet->getCell('J'.$row)->getDataValidation();
		$dropdownlist->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)->setAllowBlank(false)->setShowDropDown(true)->setPrompt('Select Yes or No')->setFormula1('"'.$yesnovalue.'"');
		$dropdownlist = $sheet->getCell('K'.$row)->getDataValidation();
		$dropdownlist->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)->setAllowBlank(false)->setShowDropDown(true)->setPrompt('Select Yes or No')->setFormula1('"'.$yesnovalue.'"');
		
		$row = $row+2;
		$sheet->setCellValue('A'.$row, 'Barn & Stall');
		$sheet->setCellValue('B'.$row, 'Night Price');
		$sheet->setCellValue('C'.$row, 'Week Price');
		$sheet->setCellValue('D'.$row, 'Month Price');
		$sheet->setCellValue('E'.$row, 'Flat Price');
		$sheet->setCellValue('F'.$row, 'Subscription Initial Price');
		$sheet->setCellValue('G'.$row, 'Subscription Month Price');
		$sheet->setCellValue('J'.$row, 'RV Hookups');
		$sheet->setCellValue('K'.$row, 'Night Price');
		$sheet->setCellValue('L'.$row, 'Week Price');
		$sheet->setCellValue('M'.$row, 'Month Price');
		$sheet->setCellValue('N'.$row, 'Flat Price');
		$sheet->setCellValue('O'.$row, 'Subscription Initial Price');
		$sheet->setCellValue('P'.$row, 'Subscription Month Price');
		$sheet->setCellValue('R'.$row, 'Name');
		$sheet->setCellValue('S'.$row, 'Quantity');
		$sheet->setCellValue('T'.$row, 'Price');
		$sheet->setCellValue('V'.$row, 'Name');
		$sheet->setCellValue('W'.$row, 'Quantity');
		$sheet->setCellValue('X'.$row, 'Price');
		
		$row++;
		$row1 = $row;
		foreach ($data['barn'] as $barn) { 
			$sheet->setCellValue('A'.$row, $barn['name']);
			$row++;
			
			foreach($barn['stall'] as $stall){  
				$sheet->setCellValue('A'.$row, $stall['name']);
				$sheet->setCellValue('B'.$row, $stall['night_price']);
				$sheet->setCellValue('C'.$row, $stall['week_price']);
				$sheet->setCellValue('D'.$row, $stall['month_price']);
				$sheet->setCellValue('E'.$row, $stall['flat_price']);
				$sheet->setCellValue('F'.$row, $stall['subscription_initial_price']);
				$sheet->setCellValue('G'.$row, $stall['subscription_month_price']);
				$row++;
			} 
			
			$row++;
		}
		
		$row = $row1;
		foreach ($data['rvbarn'] as $barn) { 
			$sheet->setCellValue('J'.$row, $barn['name']);
			$row++;
			
			foreach($barn['rvstall'] as $stall){  
				$sheet->setCellValue('J'.$row, $stall['name']);
				$sheet->setCellValue('K'.$row, $stall['night_price']);
				$sheet->setCellValue('L'.$row, $stall['week_price']);
				$sheet->setCellValue('M'.$row, $stall['month_price']);
				$sheet->setCellValue('N'.$row, $stall['flat_price']);
				$sheet->setCellValue('O'.$row, $stall['subscription_initial_price']);
				$sheet->setCellValue('P'.$row, $stall['subscription_month_price']);
				$row++;
			} 
			
			$row++;
		}
			
		$row = $row1;
		foreach ($data['feed'] as $product) { 
			$sheet->setCellValue('R'.$row, $product['name']);
			$sheet->setCellValue('S'.$row, $product['quantity']);
			$sheet->setCellValue('T'.$row, $product['price']);
			$row++;
		}
			
		$row = $row1;
		foreach ($data['shaving'] as $product) { 
			$sheet->setCellValue('V'.$row, $product['name']);
			$sheet->setCellValue('W'.$row, $product['quantity']);
			$sheet->setCellValue('X'.$row, $product['price']);
			$row++;
		}
			
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$data['name'].'.xlsx"');
		header('Cache-Control: max-age=0');

		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
		die;
    }
	
	public function importfacility()
	{
		$yesno			= $this->config->yesno2;
		
		$phpspreadsheet = new Spreadsheet();

      	$reader 		= new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
      	$spreadsheet 	= $reader->load($_FILES['import']['tmp_name']);
		$sheetdata 		= $spreadsheet->getActiveSheet()->toArray();
		
		$result = [];
		$barnstallindex = $rvhookupindex = $feedindex = $shavingindex = 0;
		$typebarn = $typervhookup = 0;
		
		foreach($sheetdata as $key => $data){
			if(in_array($key, [0, 2, 3])) continue;
			
			if($key==1){
				$priceflag = [
					isset($data[11]) && $data[11]!='' && $data[11]!=0 ? 1 : 0,
					isset($data[12]) && $data[12]!='' && $data[12]!=0 ? 1 : 0,
					isset($data[13]) && $data[13]!='' && $data[13]!=0 ? 1 : 0,
					isset($data[14]) && $data[14]!='' && $data[14]!=0 ? 1 : 0,
					isset($data[15]) && $data[15]!='' && $data[15]!=0 ? 1 : 0
				];
				
				$pricefee = [
					isset($data[11]) ? $data[11] : 0,
					isset($data[12]) ? $data[12] : 0,
					isset($data[13]) ? $data[13] : 0,
					isset($data[14]) ? $data[14] : 0,
					isset($data[15]) ? $data[15] : 0,
					isset($data[16]) ? $data[16] : 0
				];
				
				$result = [
					'name'					=> isset($data[0]) ? $data[0] : '',
					'location'				=> isset($data[1]) ? $data[1] : '',
					'city'					=> isset($data[2]) ? $data[2] : '',
					'state'					=> isset($data[3]) ? $data[3] : '',
					'zipcode'				=> isset($data[4]) ? $data[4] : '',
					'description'			=> isset($data[5]) ? $data[5] : '',
					'feed_flag'				=> isset($data[6]) ? (isset($yesno[$data[6]]) ? $yesno[$data[6]] : '') : '',
					'shaving_flag'			=> isset($data[7]) ? (isset($yesno[$data[7]]) ? $yesno[$data[7]] : '') : '',
					'rv_flag'				=> isset($data[8]) ? (isset($yesno[$data[8]]) ? $yesno[$data[8]] : '') : '',
					'cleaning_flag'			=> isset($data[9]) ? (isset($yesno[$data[9]]) ? $yesno[$data[9]] : '') : '',
					'notification_flag'		=> isset($data[10]) ? (isset($yesno[$data[10]]) ? $yesno[$data[10]] : '') : '',
					'price_flag'			=> implode(',', $priceflag),
					'price_fee'				=> implode(',', $pricefee),
					'cleaning_fee'			=> isset($data[17]) ? $data[17] : '',
				];
			}
			
			if($key >= 4){				
				if(isset($data[0]) && $data[0]!=''){
					if($typebarn==0){
						$result['barn'][$barnstallindex] = [
							'name' => $data[0],
							'type' => '1'
						];
						
						$typebarn++;
					}else{
						$result['barn'][$barnstallindex]['stall'][] = [
							'name' 							=> $data[0],
							'night_price' 					=> isset($data[1]) ? $data[1] : 0,
							'week_price' 					=> isset($data[2]) ? $data[2] : 0,
							'month_price' 					=> isset($data[3]) ? $data[3] : 0,
							'flat_price' 					=> isset($data[4]) ? $data[4] : 0,
							'subscription_initial_price' 	=> isset($data[5]) ? $data[5] : 0,
							'subscription_month_price' 		=> isset($data[6]) ? $data[6] : 0,
						];
					}
				}else{
					$typebarn = 0;
					$barnstallindex++;
				}
				
				if(isset($data[9]) && $data[9]!=''){
					if($typervhookup==0){
						$result['rvbarn'][$rvhookupindex] = [
							'name' => $data[9],
							'type' => '1'
						];
						
						$typervhookup++;
					}else{
						$result['rvbarn'][$rvhookupindex]['rvstall'][] = [
							'name' 							=> $data[9],
							'night_price' 					=> isset($data[10]) ? $data[10] : 0,
							'week_price' 					=> isset($data[11]) ? $data[11] : 0,
							'month_price' 					=> isset($data[12]) ? $data[12] : 0,
							'flat_price' 					=> isset($data[13]) ? $data[13] : 0,
							'subscription_initial_price' 	=> isset($data[14]) ? $data[14] : 0,
							'subscription_month_price' 		=> isset($data[15]) ? $data[15] : 0,
						];
					}
				}else{
					$typervhookup = 0;
					$rvhookupindex++;
				}
				
				if(isset($data[17]) && $data[17]!=''){
					$result['feed'][$feedindex] = [
						'name' 			=> $data[17],
						'quantity' 		=> isset($data[18]) ? $data[18] : 0,
						'price' 		=> isset($data[19]) ? $data[19] : 0,
					];
					
					$feedindex++;
				}
				
				if(isset($data[21]) && $data[21]!=''){
					$result['shaving'][$shavingindex] = [
						'name' 			=> $data[21],
						'quantity' 		=> isset($data[22]) ? $data[22] : 0,
						'price' 		=> isset($data[23]) ? $data[23] : 0,
					];
					
					$shavingindex++;
				}
			}
		}
		
		return $result;
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
	
   	public function financialreport()
   	{
		$requestData 	= $this->request->getPost();
		
		$checkin  		= $requestData['checkin']!='' ? formatdate($requestData['checkin']) : '';
		$checkout  		= $requestData['checkout']!='' ? formatdate($requestData['checkout']) : '';
		$eventid  		= $requestData['event_id']!='' ? $requestData['event_id'] : '';

		$condition = [];
		if($checkin!='') $condition['checkin'] = $checkin;
		if($checkout!='') $condition['checkout'] = $checkout;
		if($eventid!='') $condition['eventid'] = $eventid;
		$data['events']		    	= $this->report->getFinancialReport('all', ['booking', 'event', 'barn', 'stall', 'bookedstall', 'rvbarn', 'rvstall', 'rvbookedstall', 'feed', 'feedbooked', 'shaving', 'shavingbooked'], ['type' => '2']+$condition);
		
		$data['logo']               = imagetobase64('./assets/images/ezstall_black.png');
		$data['currencysymbol']  	= $this->config->currencysymbol;
		$data['type']		    	= '2';
		$data['usertype']		    = '2';
		
		if(empty($data['events'])){
			$this->session->setFlashdata('danger', 'Booking not found.');
			return redirect()->to(base_url().'/myaccount/facility'); 
		}
		
		$html =  view('site/common/pdf/financialreport', $data);
		
		$mpdf = new \Mpdf\Mpdf();
		$mpdf->WriteHTML($html);
		$this->response->setHeader('Content-Type', 'application/pdf');
		$mpdf->Output('Invoice.pdf','D');
		die;
    }
}
