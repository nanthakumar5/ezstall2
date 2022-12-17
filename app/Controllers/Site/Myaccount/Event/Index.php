<?php 
namespace App\Controllers\Site\Myaccount\Event;

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
		$userdetail 					= getSiteUserDetails();
		$userid 						= $userdetail['id'];
		$usertype						= $userdetail['type'];

		if($usertype == '4') $userid = $userdetail['parent_id'];
		
		if ($this->request->getMethod()=='post')
        {
			$requestData = $this->request->getPost();

			if(isset($requestData['stripepay'])){
				$this->session->setFlashdata('success', 'Your payment is processed successfully');				
				return redirect()->to(base_url().'/myaccount/events'); 
			}else{
				$result = $this->event->delete($requestData);
				
				if($result){
					$this->session->setFlashdata('success', 'Event deleted successfully.');
					return redirect()->to(base_url().'/myaccount/events'); 
				}else{
					$this->session->setFlashdata('danger', 'Try Later');
					return redirect()->to(base_url().'/myaccount/events'); 
				}
			}
        }
		
    	$pager = service('pager'); 
		$page = (int)(($this->request->getVar('page')!==null) ? $this->request->getVar('page') :1)-1;
		$perpage =  10; 
		$offset = $page * $perpage;
		
		if($this->request->getVar('q')!==null){
			$searchdata = ['search' => ['value' => $this->request->getVar('q')], 'page' => 'events'];
			$data['search'] = $this->request->getVar('q');
		}else{
			$searchdata = [];
			$data['search'] = '';
		}
		
		$eventcount = $this->event->getEvent('count', ['event'], $searchdata+['status' => ['1'], 'userid' => $userid, 'type' => '1']);
		$event = $this->event->getEvent('all', ['event', 'users', 'startingstallprice'], $searchdata+['status' => ['1'], 'userid' => $userid, 'type' => '1', 'start' => $offset, 'length' => $perpage], ['orderby' => 'e.id desc']);
		$settings = getSettings();
		
        $data['list'] = $event;
        $data['pager'] = $pager->makeLinks($page, $perpage, $eventcount);
		$data['userid'] = $userid;
		$data['usertype'] = $usertype;
		$data['eventcount'] = $eventcount;
		$data['currencysymbol'] = $this->config->currencysymbol;
    	$data['stripe'] = view('site/common/stripe/stripe1', ['pagetype' => '1']);
    	$data['settings'] = $settings;
		
		return view('site/myaccount/event/index', $data);
    }

    public function eventsaction($id='')
	{   
		$userdetails 					= getSiteUserDetails();
		$userid         				= $userdetails['id'];
		$usertype       				= $userdetails['type'];
		$checksubscription 				= checkSubscription();
		$checksubscriptiontype 			= $checksubscription['type'];
		$checksubscriptionproducer 		= $checksubscription['producer'];
		$checksubscriptionstallmanager 	= $checksubscription['stallmanager'];

		$eventcount = $this->event->getEvent('count', ['event'], ['status' => ['1'], 'userid' => $userid, 'type' => '1']);
		
		if($checksubscriptiontype=='3' && (($id=='' && $checksubscriptionproducer <= $eventcount) || ($id!='' && $checksubscriptionproducer < $eventcount))){
			$this->session->setFlashdata('danger', 'Please pay now for add more event');
			return redirect()->to(base_url().'/myaccount/events'); 
		}elseif($checksubscriptiontype=='4' && $checksubscriptionstallmanager!='4'){ 
			$this->session->setFlashdata('danger', 'Please subscribe the account.');
			return redirect()->to(base_url().'/myaccount/subscription'); 
		}
		
		if($id!=''){
			$result = $this->event->getEvent('row', ['event', 'barn', 'stall', 'rvbarn', 'rvstall', 'feed', 'shaving'],['id' => $id, 'status' => ['1'], 'userid' => $userid, 'type' => '1']);

			if($result){				
				$data['occupied'] 	= getOccupied($id);
				$data['reserved'] 	= getReserved($id);
				$data['result'] 	= $result;
			}else{
				$this->session->setFlashdata('danger', 'No Record Found.');
				return redirect()->to(base_url().'/myaccount/events'); 
			}
		}
		
		if ($this->request->getMethod()=='post'){
			if(isset($_FILES['import'])){
				$data['import'] 		= 1;
				$data['result'] 		= $this->importevents();
			}else{
				$requestData 			= $this->request->getPost();
				$requestData['type'] 	= '1';

				if(isset($requestData['start_date'])) $requestData['start_date'] 	= formatdate($requestData['start_date']);
				if(isset($requestData['end_date'])) $requestData['end_date'] 		= formatdate($requestData['end_date']);
				$result = $this->event->action($requestData);
				
				if($result){
					$this->session->setFlashdata('success', 'Event submitted successfully.');
					return redirect()->to(base_url().'/myaccount/events'); 
				}else{
					$this->session->setFlashdata('danger', 'Try Later.');
					return redirect()->to(base_url().'/myaccount/events'); 
				}
			}
        } 
		
		$data['barnstall'] 		= view('site/common/barnstall/barnstall1', ['yesno' => $this->config->yesno, 'pricelist' => $this->config->pricelist, 'chargingflag' => $this->config->chargingflag, 'usertype' => $usertype]+(isset($data) ? $data : []));		
		$data['userid'] 		= $userid;
		$data['googleapikey'] 	= $this->config->googleapikey;
		return view('site/myaccount/event/action', $data);
	}
	
	public function view($id)
    {  
		$event  			= $this->event->getEvent('row', ['event', 'barn', 'stall', 'rvbarn', 'rvstall', 'bookedstall', 'rvbookedstall', 'users', 'startingstallprice'], ['id' => $id, 'type' => '1']);
		$data['detail']  			= $event;
		$data['barnstall'] 			= view('site/common/barnstall/barnstall3', ['checkevent' => checkEvent($event), 'settings' => getSettings(), 'currencysymbol' => $this->config->currencysymbol, 'pricelists' => $this->config->pricelist]+$data);

		$data['currencysymbol'] = $this->config->currencysymbol;
		return view('site/myaccount/event/view',$data);
    }

    public function inventories($id)
    {  
		$data['product']  	= $this->product->getProduct('all', ['product'], ['event_id' => $id]);
		return view('site/myaccount/event/inventories',$data);
    }
	
    public function export($id)
    {
		$userdetail 	= getSiteUserDetails();
		$usertype 		= $userdetail['type'];
		$charging		= $this->config->chargingflag;
		$chargingvalue	= implode(',', array_values($charging));
		$yesno			= $this->config->yesno;
		$yesnovalue		= implode(',', array_values($yesno));
		
    	$data = $this->event->getEvent('row', ['event', 'barn', 'stall', 'rvbarn', 'rvstall', 'feed', 'shaving'], ['id' => $id, 'type' => '1']);

		$spreadsheet = new Spreadsheet();
		$sheet 		 = $spreadsheet->getActiveSheet();

		$row = 1;
		$sheet->setCellValue('A'.$row, 'Name');
		$sheet->setCellValue('B'.$row, 'Street');
		$sheet->setCellValue('C'.$row, 'City');
		$sheet->setCellValue('D'.$row, 'State');
		$sheet->setCellValue('E'.$row, 'Zipcode');
		$sheet->setCellValue('F'.$row, 'Mobile');
		$sheet->setCellValue('G'.$row, 'Start Date');
		$sheet->setCellValue('H'.$row, 'End Date');
		$sheet->setCellValue('I'.$row, 'Start Time');
		$sheet->setCellValue('J'.$row, 'End Time');
		$sheet->setCellValue('K'.$row, 'Description');
		$sheet->setCellValue('L'.$row, 'Will you be selling feed at this event?');
		$sheet->setCellValue('M'.$row, 'Will you be selling shavings at this event?');
		$sheet->setCellValue('N'.$row, 'Will you have RV Hookups at this event?');
		$sheet->setCellValue('O'.$row, 'Will you Collect the Cleaning fee from Horse owner?');
		$sheet->setCellValue('P'.$row, 'Send a text message to users when their stall is unlocked and ready for use?');
		if($usertype=='2'){
			$sheet->setCellValue('Q'.$row, 'Night Price');
			$sheet->setCellValue('R'.$row, 'Week Price');
			$sheet->setCellValue('S'.$row, 'Month Price');
			$sheet->setCellValue('T'.$row, 'Flat Price');
			$sheet->setCellValue('U'.$row, 'Subscription Initial Price');
			$sheet->setCellValue('V'.$row, 'Subscription Month Price');
			$sheet->setCellValue('W'.$row, 'Cleaning Price');
		}else{
			$sheet->setCellValue('Q'.$row, 'Cleaning Price');
		}
		
        $row++;
		$pricefee = explode(',', $data['price_fee']);
		$sheet->setCellValue('A'.$row, $data['name']);
		$sheet->setCellValue('B'.$row, $data['location']);
		$sheet->setCellValue('C'.$row, $data['city']);
		$sheet->setCellValue('D'.$row, $data['state']);
		$sheet->setCellValue('E'.$row, $data['zipcode']);
		$sheet->setCellValue('F'.$row, $data['mobile']);
		$sheet->setCellValue('G'.$row, formatdate($data['start_date'], 1));
		$sheet->setCellValue('H'.$row, formatdate($data['end_date'], 1));
		$sheet->setCellValue('I'.$row, $data['start_time']);
		$sheet->setCellValue('J'.$row, $data['end_time']);
		$sheet->setCellValue('K'.$row, $data['description']);
		$sheet->setCellValue('L'.$row, (isset($yesno[$data['feed_flag']]) ? $yesno[$data['feed_flag']] : ''));
		$sheet->setCellValue('M'.$row, (isset($yesno[$data['shaving_flag']]) ? $yesno[$data['shaving_flag']] : ''));
		$sheet->setCellValue('N'.$row, (isset($yesno[$data['rv_flag']]) ? $yesno[$data['rv_flag']] : ''));
		$sheet->setCellValue('O'.$row, (isset($yesno[$data['cleaning_flag']]) ? $yesno[$data['cleaning_flag']] : ''));
		$sheet->setCellValue('P'.$row, (isset($yesno[$data['notification_flag']]) ? $yesno[$data['notification_flag']] : ''));
		if($usertype=='2'){
			$sheet->setCellValue('Q'.$row, (isset($pricefee[0]) ? $pricefee[0] : 0));
			$sheet->setCellValue('R'.$row, (isset($pricefee[1]) ? $pricefee[1] : 0));
			$sheet->setCellValue('S'.$row, (isset($pricefee[2]) ? $pricefee[2] : 0));
			$sheet->setCellValue('T'.$row, (isset($pricefee[3]) ? $pricefee[3] : 0));
			$sheet->setCellValue('U'.$row, (isset($pricefee[4]) ? $pricefee[4] : 0));
			$sheet->setCellValue('V'.$row, (isset($pricefee[5]) ? $pricefee[5] : 0));
			$sheet->setCellValue('W'.$row, $data['cleaning_fee']);
		}else{
			$sheet->setCellValue('Q'.$row, $data['cleaning_fee']);
		}
		
		$dropdownlist = $sheet->getCell('L'.$row)->getDataValidation();
		$dropdownlist->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)->setAllowBlank(false)->setShowDropDown(true)->setPrompt('Select Yes or No')->setFormula1('"'.$yesnovalue.'"');
		$dropdownlist = $sheet->getCell('M'.$row)->getDataValidation();
		$dropdownlist->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)->setAllowBlank(false)->setShowDropDown(true)->setPrompt('Select Yes or No')->setFormula1('"'.$yesnovalue.'"');
		$dropdownlist = $sheet->getCell('N'.$row)->getDataValidation();
		$dropdownlist->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)->setAllowBlank(false)->setShowDropDown(true)->setPrompt('Select Yes or No')->setFormula1('"'.$yesnovalue.'"');
		$dropdownlist = $sheet->getCell('O'.$row)->getDataValidation();
		$dropdownlist->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)->setAllowBlank(false)->setShowDropDown(true)->setPrompt('Select Yes or No')->setFormula1('"'.$yesnovalue.'"');
		$dropdownlist = $sheet->getCell('P'.$row)->getDataValidation();
		$dropdownlist->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)->setAllowBlank(false)->setShowDropDown(true)->setPrompt('Select Yes or No')->setFormula1('"'.$yesnovalue.'"');
				
		$row = $row+2;
		$sheet->setCellValue('A'.$row, 'Barn & Stall');
		if($usertype=='2'){
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
			$sheet->setCellValue('S'.$row, 'Name');
			$sheet->setCellValue('T'.$row, 'Quantity');
			$sheet->setCellValue('U'.$row, 'Price');
			$sheet->setCellValue('X'.$row, 'Name');
			$sheet->setCellValue('Y'.$row, 'Quantity');
			$sheet->setCellValue('Z'.$row, 'Price');
		}else{
			$sheet->setCellValue('B'.$row, 'Charging');
			$sheet->setCellValue('C'.$row, 'Price');
			$sheet->setCellValue('F'.$row, 'RV Hookups');
			$sheet->setCellValue('G'.$row, 'Charging');
			$sheet->setCellValue('H'.$row, 'Price');
			$sheet->setCellValue('K'.$row, 'Name');
			$sheet->setCellValue('L'.$row, 'Quantity');
			$sheet->setCellValue('M'.$row, 'Price');
			$sheet->setCellValue('P'.$row, 'Name');
			$sheet->setCellValue('Q'.$row, 'Quantity');
			$sheet->setCellValue('R'.$row, 'Price');
		}
		
		$row++;
		$row1 = $row;
		foreach ($data['barn'] as $barn) { 
			$sheet->setCellValue('A'.$row, $barn['name']);
			$row++;
			
			foreach($barn['stall'] as $stall){  
				$sheet->setCellValue('A'.$row, $stall['name']);
				if($usertype=='2'){
					$sheet->setCellValue('B'.$row, $stall['night_price']);
					$sheet->setCellValue('C'.$row, $stall['week_price']);
					$sheet->setCellValue('D'.$row, $stall['month_price']);
					$sheet->setCellValue('E'.$row, $stall['flat_price']);
					$sheet->setCellValue('F'.$row, $stall['subscription_initial_price']);
					$sheet->setCellValue('G'.$row, $stall['subscription_month_price']);
					
					$dropdownlist = $sheet->getCell('F'.$row)->getDataValidation();
					$dropdownlist->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)->setAllowBlank(false)->setShowDropDown(true)->setPrompt('Select Charging ID')->setFormula1('"'.$chargingvalue.'"');
				}else{
					$sheet->setCellValue('B'.$row, (isset($charging[$stall['charging_id']]) ? $charging[$stall['charging_id']] : ''));
					$sheet->setCellValue('C'.$row, $stall['price']);
				}
				$row++;
			} 
			
			$row++;
		}
		
		$row = $row1;
		foreach ($data['rvbarn'] as $barn) { 
			if($usertype=='2'){
				$sheet->setCellValue('J'.$row, $barn['name']);
			}else{
				$sheet->setCellValue('F'.$row, $barn['name']);
			}
			$row++;
			
			foreach($barn['rvstall'] as $stall){  
				if($usertype=='2'){
					$sheet->setCellValue('J'.$row, $stall['name']);
					$sheet->setCellValue('K'.$row, $stall['night_price']);
					$sheet->setCellValue('L'.$row, $stall['week_price']);
					$sheet->setCellValue('M'.$row, $stall['month_price']);
					$sheet->setCellValue('N'.$row, $stall['flat_price']);
					$sheet->setCellValue('O'.$row, $stall['subscription_initial_price']);
					$sheet->setCellValue('P'.$row, $stall['subscription_month_price']);
				}else{
					$sheet->setCellValue('F'.$row, $stall['name']);
					$sheet->setCellValue('G'.$row, (isset($charging[$stall['charging_id']]) ? $charging[$stall['charging_id']] : ''));
					$sheet->setCellValue('H'.$row, $stall['price']);
					
					$dropdownlist = $sheet->getCell('F'.$row)->getDataValidation();
					$dropdownlist->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)->setAllowBlank(false)->setShowDropDown(true)->setPrompt('Select Charging ID')->setFormula1('"'.$chargingvalue.'"');
				}
				$row++;
			} 
			
			$row++;
		}
			
		$row = $row1;
		foreach ($data['feed'] as $product) { 
			if($usertype=='2'){
				$sheet->setCellValue('S'.$row, $product['name']);
				$sheet->setCellValue('T'.$row, $product['quantity']);
				$sheet->setCellValue('U'.$row, $product['price']);
			}else{
				$sheet->setCellValue('K'.$row, $product['name']);
				$sheet->setCellValue('L'.$row, $product['quantity']);
				$sheet->setCellValue('M'.$row, $product['price']);
			}
			$row++;
		}
			
		$row = $row1;
		foreach ($data['shaving'] as $product) { 
			if($usertype=='2'){
				$sheet->setCellValue('X'.$row, $product['name']);
				$sheet->setCellValue('Y'.$row, $product['quantity']);
				$sheet->setCellValue('Z'.$row, $product['price']);
			}else{
				$sheet->setCellValue('P'.$row, $product['name']);
				$sheet->setCellValue('Q'.$row, $product['quantity']);
				$sheet->setCellValue('R'.$row, $product['price']);
			}
			$row++;
		}
			
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$data['name'].'.xlsx"');
		header('Cache-Control: max-age=0');

		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
		die;
    }
	
	public function importevents()
	{
		$userdetail 	= getSiteUserDetails();
		$usertype 		= $userdetail['type'];
		$charging		= $this->config->chargingflag2;
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
					isset($data[16]) && $data[16]!='' && $data[16]!=0 ? 1 : 0,
					isset($data[17]) && $data[17]!='' && $data[17]!=0 ? 1 : 0,
					isset($data[18]) && $data[18]!='' && $data[18]!=0 ? 1 : 0,
					isset($data[19]) && $data[19]!='' && $data[19]!=0 ? 1 : 0,
					isset($data[20]) && $data[20]!='' && $data[20]!=0 ? 1 : 0
				];
				
				$pricefee = [
					isset($data[16]) ? $data[16] : 0,
					isset($data[17]) ? $data[17] : 0,
					isset($data[18]) ? $data[18] : 0,
					isset($data[19]) ? $data[19] : 0,
					isset($data[20]) ? $data[20] : 0,
					isset($data[21]) ? $data[21] : 0
				];
				
				$result = [
					'name'					=> isset($data[0]) ? $data[0] : '',
					'location'				=> isset($data[1]) ? $data[1] : '',
					'city'					=> isset($data[2]) ? $data[2] : '',
					'state'					=> isset($data[3]) ? $data[3] : '',
					'zipcode'				=> isset($data[4]) ? $data[4] : '',
					'mobile'				=> isset($data[5]) ? $data[5] : '',
					'start_date'			=> isset($data[6]) && $data[6]!='' ? formatdate($data[6]) : '',
					'end_date'				=> isset($data[7]) && $data[7]!='' ? formatdate($data[7]) : '',
					'start_time'			=> isset($data[8]) ? $data[8] : '',
					'end_time'				=> isset($data[9]) ? $data[9] : '',
					'description'			=> isset($data[10]) ? $data[10] : '',
					'feed_flag'				=> isset($data[11]) ? (isset($yesno[$data[11]]) ? $yesno[$data[11]] : '') : '',
					'shaving_flag'			=> isset($data[12]) ? (isset($yesno[$data[12]]) ? $yesno[$data[12]] : '') : '',
					'rv_flag'				=> isset($data[13]) ? (isset($yesno[$data[13]]) ? $yesno[$data[13]] : '') : '',
					'cleaning_flag'			=> isset($data[14]) ? (isset($yesno[$data[14]]) ? $yesno[$data[14]] : '') : '',
					'notification_flag'		=> isset($data[15]) ? (isset($yesno[$data[15]]) ? $yesno[$data[15]] : '') : '',
					'price_flag'			=> $usertype=='2' ? implode(',', $priceflag) : '',
					'price_fee'				=> $usertype=='2' ? implode(',', $pricefee) : '',
					'cleaning_fee'			=> $usertype=='2' ? (isset($data[22]) ? $data[22] : '') : (isset($data[16]) ? $data[16] : ''),
				];
			}
			
			if($key >= 4){				
				if($usertype=='2' && isset($data[0]) && $data[0]!=''){
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
				}elseif($usertype=='3' && isset($data[0]) && $data[0]!=''){
					if($typebarn==0){
						$result['barn'][$barnstallindex] = [
							'name' => $data[0],
							'type' => '1'
						];
						
						$typebarn++;
					}else{
						$result['barn'][$barnstallindex]['stall'][] = [
							'name' 			=> $data[0],
							'charging_id' 	=> isset($data[1]) ? (isset($charging[$data[1]]) ? $charging[$data[1]] : '') : '',
							'price' 		=> isset($data[2]) ? $data[2] : 0,
						];
					}
				}else{
					$typebarn = 0;
					$barnstallindex++;
				}
				
				if($usertype=='2' && isset($data[9]) && $data[9]!=''){
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
				}elseif($usertype=='3' && isset($data[5]) && $data[5]!=''){
					if($typervhookup==0){
						$result['rvbarn'][$rvhookupindex] = [
							'name' => $data[5],
							'type' => '1'
						];
						
						$typervhookup++;
					}else{
						$result['rvbarn'][$rvhookupindex]['rvstall'][] = [
							'name' 			=> $data[5],
							'charging_id' 	=> isset($data[6]) ? (isset($charging[$data[6]]) ? $charging[$data[6]] : '') : '',
							'price' 		=> isset($data[7]) ? $data[7] : 0,
						];
					}
				}else{
					$typervhookup = 0;
					$rvhookupindex++;
				}
				
				if($usertype=='2' && isset($data[18]) && $data[18]!=''){
					$result['feed'][$feedindex] = [
						'name' 			=> $data[18],
						'quantity' 		=> isset($data[19]) ? $data[19] : 0,
						'price' 		=> isset($data[20]) ? $data[20] : 0,
					];
					
					$feedindex++;
				}elseif($usertype=='3' && isset($data[10]) && $data[10]!=''){
					$result['feed'][$feedindex] = [
						'name' 			=> $data[10],
						'quantity' 		=> isset($data[11]) ? $data[11] : 0,
						'price' 		=> isset($data[12]) ? $data[12] : 0,
					];
					
					$feedindex++;
				}
				
				if($usertype=='2' && isset($data[23]) && $data[23]!=''){
					$result['shaving'][$shavingindex] = [
						'name' 			=> $data[23],
						'quantity' 		=> isset($data[24]) ? $data[24] : 0,
						'price' 		=> isset($data[25]) ? $data[25] : 0,
					];
					
					$shavingindex++;
				}elseif($usertype=='3' && isset($data[15]) && $data[15]!=''){
					$result['shaving'][$shavingindex] = [
						'name' 			=> $data[15],
						'quantity' 		=> isset($data[16]) ? $data[16] : 0,
						'price' 		=> isset($data[17]) ? $data[17] : 0,
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

   	public function eventreport($id)
   	{   		
		$mpdf 						= 	new \Mpdf\Mpdf();
		$currentdate 				= 	date("Y-m-d");
	    
    	$data['logo']               =   imagetobase64('./assets/images/ezstall_black.png');
    	$data['result'] 			=  	$this->event->getEvent('row', ['event','barn','stall','bookedstall'], ['id' => $id]);
    	
		$html =  view('site/common/pdf/eventreport', $data);
		$mpdf->WriteHTML($html);
		$this->response->setHeader('Content-Type', 'application/pdf');
		$mpdf->Output('Eventreport.pdf','D');
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
		$data['events']		    	= $this->report->getFinancialReport('all', ['booking', 'event', 'barn', 'stall', 'bookedstall', 'rvbarn', 'rvstall', 'rvbookedstall', 'feed', 'feedbooked', 'shaving', 'shavingbooked'], ['type' => '1']+$condition);
		
		$data['logo']               = imagetobase64('./assets/images/ezstall_black.png');
		$data['currencysymbol']  	= $this->config->currencysymbol;
		$data['type']		    	= '1';
		$data['usertype']		    = '2';
		
		if(empty($data['events'])){
			$this->session->setFlashdata('danger', 'Booking not found.');
			return redirect()->to(base_url().'/myaccount/events'); 
		}
		
		$html =  view('site/common/pdf/financialreport', $data);
		
		$mpdf = new \Mpdf\Mpdf();
		$mpdf->WriteHTML($html);
		$this->response->setHeader('Content-Type', 'application/pdf');
		$mpdf->Output('Invoice.pdf','D');
		die;
    }
	
    public function facilityaction($id='')
	{   
		$userdetails 					= getSiteUserDetails();
		$userid         				= $userdetails['id'];
		
		if($id!=''){
			$result = $this->event->getEvent('row', ['event', 'barn', 'stall', 'rvbarn', 'rvstall', 'feed', 'shaving'],['id' => $id, 'status' => ['1'], 'userid' => $userid, 'type' => '1']);

			if($result){				
				$data['occupied'] 	= getOccupied($id);
				$data['reserved'] 	= getReserved($id);
				$data['result'] 	= $result;
			}else{
				$this->session->setFlashdata('danger', 'No Record Found.');
				return redirect()->to(base_url().'/myaccount/events'); 
			}
		}
		
		if ($this->request->getMethod()=='post'){
			$requestData 			= $this->request->getPost();
			$requestData['type'] 	= '1';

			if(isset($requestData['start_date'])) $requestData['start_date'] 	= formatdate($requestData['start_date']);
    		if(isset($requestData['end_date'])) $requestData['end_date'] 		= formatdate($requestData['end_date']);
            $result = $this->event->action($requestData);
			
			if($result){
				$this->session->setFlashdata('success', 'Event submitted successfully.');
				return redirect()->to(base_url().'/myaccount/events'); 
			}else{
				$this->session->setFlashdata('danger', 'Try Later.');
				return redirect()->to(base_url().'/myaccount/events'); 
			}
        }
		
		$data['userid'] 		= $userid;
		$data['facilitylist'] 	= getEventsList(['type' => '2', 'userid' => $userid]);
		$data['googleapikey'] 	= $this->config->googleapikey;
		return view('site/myaccount/event/action', $data);
	}
}
