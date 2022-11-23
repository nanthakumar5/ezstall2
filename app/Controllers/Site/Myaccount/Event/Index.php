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
				$payment = $this->stripe->action(['id' => $requestData['stripepayid']]);
				if($payment){
					$usersubscriptioncount = $userdetail['producer_count'];
					$this->users->action(['user_id' => $userid, 'actionid' => $userid, 'producercount' => $usersubscriptioncount+1]);
					$this->session->setFlashdata('success', 'Your payment is processed successfully');
				}else{
					$this->session->setFlashdata('danger', 'Your payment is not processed successfully.');
				}
				
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
		$event = $this->event->getEvent('all', ['event'], $searchdata+['status' => ['1'], 'userid' => $userid, 'type' => '1', 'start' => $offset, 'length' => $perpage], ['orderby' => 'e.id desc']);
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
		return $this->action($id);
	}
	
    public function facilityeventsaction($id='')
	{   
		return $this->action($id);
	}
	
    public function action($id)
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
		
		$data['barnstall'] 		= view('site/common/barnstall/barnstall1', ['yesno' => $this->config->yesno, 'pricelist' => $this->config->pricelist, 'chargingflag' => $this->config->chargingflag, 'usertype' => $usertype]+$data);		
		$data['userid'] 		= $userid;
		$data['googleapikey'] 	= $this->config->googleapikey;
		return view('site/myaccount/event/action', $data);
	}
	
	public function view($id)
    {  
		$data['detail']  	= $this->event->getEvent('row', ['event', 'barn', 'stall', 'rvbarn', 'rvstall', 'bookedstall', 'rvbookedstall'], ['id' => $id, 'type' => '1']);
		return view('site/myaccount/event/view',$data);
    }

    public function inventories($id)
    {  
		$data['product']  	= $this->product->getProduct('all', ['product'], ['event_id' => $id]);
		return view('site/myaccount/event/inventories',$data);
    }
	
    public function export($id)
    {	
    	$data 		= $this->event->getEvent('row', ['event', 'barn', 'stall', 'bookedstall'],['id' => $id, 'type' => '1']);

    	$bookingtotalamount	= $this->booking->getBooking('all', ['booking'],['eventid' => $id]);
		$totalamount = 0;
    	foreach($bookingtotalamount as $bkam){
			$totalamount +=  $bkam['amount'];
    	}

		$spreadsheet = new Spreadsheet();
		$sheet 		 = $spreadsheet->getActiveSheet();

		$sheet->setCellValue('A1', 'Event Name');
		$sheet->setCellValue('B1', 'Description');
		$sheet->setCellValue('C1', 'Location');
		$sheet->setCellValue('D1', 'Mobile');
		$sheet->setCellValue('E1', 'start_date');
		$sheet->setCellValue('F1', 'end_date');
		$sheet->setCellValue('G1', 'start_time');
		$sheet->setCellValue('H1', 'end_time');
		$sheet->setCellValue('I1', 'stalls_price');
		$sheet->setCellValue('J1', 'rvspots_price');
		$sheet->setCellValue('K1', 'Total Amount');

     	$row = 2;
		$sheet->setCellValue('A' . $row, $data['name']);
		$sheet->setCellValue('B' . $row, $data['description']);
		$sheet->setCellValue('C' . $row, $data['location']);
		$sheet->setCellValue('D' . $row, $data['mobile']);
		$sheet->setCellValue('E' . $row, formatdate($data['start_date']));
		$sheet->setCellValue('F' . $row, formatdate($data['end_date']));
		$sheet->setCellValue('G' . $row, formattime($data['start_time']));
		$sheet->setCellValue('H' . $row, formattime($data['end_time']));
		$sheet->setCellValue('I' . $row, $data['stalls_price']);
		
		foreach ($data['barn'] as $key => $barn) { 
			$sheet->setCellValue('A'.$row, $barn['name']);
			$row++;
			
			foreach($barn['stall'] as $key=> $stall){  
				$stallname  = $stall['name'];
				$bookedstall = '';
				foreach($stall['bookedstall'] as $keys=> $booking){

					$bookedstall	.=   "\nName : ".$booking['name']."\nDate  : ".formatdate($booking['check_in'])." to ".formatdate($booking['check_out'])."\nPayment Method : ".$booking['paymentmethod'];
				}
				
				$sheet->setCellValue('A'.$row, $stallname.$bookedstall);
				$sheet->getCell('A'.$row)->getStyle()->getAlignment()->setWrapText(true);
				$sheet->getCell('A'.$row)->getStyle()->getFont()->setBold(true);
				$row++;
			} 
		}
		
		$row++;
		$sheet->setCellValue('K' . $row, $totalamount);
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$data['name'].'.xlsx"');
		header('Cache-Control: max-age=0');

		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
		die;
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
}
