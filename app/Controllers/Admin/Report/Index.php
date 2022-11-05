<?php

namespace App\Controllers\Admin\Report;

use App\Controllers\BaseController;

use App\Models\Event;
use App\Models\Booking;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Index extends BaseController
{
	public function __construct()
	{  
		$this->event  	= new Event();
		$this->booking 	= new Booking();
    }
	
	public function eventreport()
    {	
		if ($this->request->getMethod()=='post')
        {
			$requestdata 		= $this->request->getPost();
			$condition 			= ($requestdata['eventid']=='all') ? ['status' => ['1'], 'type' => '1'] : ['id' => $requestdata['eventid'], ['status' => ['1'], 'type' => '1']]; 
			$bookingcondition 	= ($requestdata['eventid']=='all') ? ['status' => ['1'], 'type' => '1'] : ['eventid' => $requestdata['eventid'], ['status' => ['1'], 'type' => '1']];
		
        	$data['logo']               = imagetobase64('./assets/images/ezstall_black.png');
			$data['events']		        = $this->event->getEvent('all', ['event', 'barn', 'stall', 'bookedstall'], $condition);	 
		    $data['currencysymbol']  	= $this->config->currencysymbol;
			$bookingtotalamount	= $this->booking->getBooking('all', ['booking'], $bookingcondition); 
			
			$totalamount = 0;
	    	foreach($bookingtotalamount as $bkam){
				$totalamount +=  $bkam['amount'];
	    	}
        	$data['totalamount']    = $totalamount;
        	
    		$html =  view('site/common/pdf/invoice', $data);
    		
	    	$mpdf = new \Mpdf\Mpdf();
    		$mpdf->WriteHTML($html);
    		$this->response->setHeader('Content-Type', 'application/pdf');
    		$mpdf->Output('Invoice.pdf','D');
			die;
		}
		
		$data['event'] = $this->event->getEvent('all', ['event'], ['status' => ['1'], 'type' => '1']);
		return view('admin/report/eventreport', $data);
    }
    
	public function eventreportexcel()
    {	
		if ($this->request->getMethod()=='post')
        {
			$requestdata 		= $this->request->getPost();
			$condition 			= ($requestdata['eventid']=='all') ? ['status' => ['1'], 'type' => '1'] : ['id' => $requestdata['eventid'], ['status' => ['1'], 'type' => '1']]; 
			$bookingcondition 	= ($requestdata['eventid']=='all') ? ['status' => ['1'], 'type' => '1'] : ['eventid' => $requestdata['eventid'], ['status' => ['1'], 'type' => '1']]; 
			$data			= $this->event->getEvent('all', ['event', 'barn', 'stall', 'bookedstall'], $condition);

			$bookingtotalamount	= $this->booking->getBooking('all', ['booking'], $bookingcondition); 
			$totalamount = 0;
	    	foreach($bookingtotalamount as $bkam){
				$totalamount +=  $bkam['amount'];
	    	}
			$spreadsheet 	= new Spreadsheet();
			$sheet 		 	= $spreadsheet->getActiveSheet();

			$sheet->setCellValue('A1', 'Event Name');
			$sheet->setCellValue('B1', 'Description');
			$sheet->setCellValue('C1', 'Location');
			$sheet->setCellValue('D1', 'Mobile');
			$sheet->setCellValue('E1', 'start_date');
			$sheet->setCellValue('F1', 'end_date');
			$sheet->setCellValue('G1', 'start_time');
			$sheet->setCellValue('H1', 'end_time');
			$sheet->setCellValue('I1', 'stalls_price');
			$sheet->setCellValue('J1', 'Total Amount');
			
			$row = 2;
			$sheet->setCellValue('J' . $row, $totalamount);
			foreach($data as $data){
				$sheet->setCellValue('A' . $row, $data['name']);
				$sheet->setCellValue('B' . $row, $data['description']);
				$sheet->setCellValue('C' . $row, $data['location']);
				$sheet->setCellValue('D' . $row, $data['mobile']);
				$sheet->setCellValue('E' . $row, formatdate($data['start_date']));
				$sheet->setCellValue('F' . $row, formatdate($data['end_date']));
				$sheet->setCellValue('G' . $row, formattime($data['start_time']));
				$sheet->setCellValue('H' . $row, formattime($data['end_time']));
				$sheet->setCellValue('I' . $row, $data['stalls_price']);

				
				$row++;
				foreach ($data['barn'] as $key => $barn) { 
					$sheet->setCellValue('A'.$row, $barn['name']);
					$row++;
					
					foreach($barn['stall'] as $key=> $stall){  
						$stallname  = $stall['name'];
						
						$bookedstall = '';
						foreach($stall['bookedstall'] as $keys=> $booking){
							$bookedstall	.=   "\nName : ".$booking['name']."\nDate  : ".formatdate($booking['check_in'])." to ".formatdate($booking['check_out'])."\nPayment_Method : ".$booking['paymentmethod']."\nAmount : ".$booking['amount'];
						}
						
						$sheet->setCellValue('A'.$row, $stallname.$bookedstall);
						$sheet->getCell('A'.$row)->getStyle()->getAlignment()->setWrapText(true);
						$sheet->getCell('A'.$row)->getStyle()->getFont()->setBold(true);
						$row++;
					} 
				}
				
				$row++;
			}

			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="Event Report.xlsx"');
			header('Cache-Control: max-age=0');

			$writer = new Xlsx($spreadsheet);
			$writer->save('php://output');
			die;
		}
		
		$data['event'] = $this->event->getEvent('all', ['event'], ['status' => ['1'], 'type' => '1']);
		return view('admin/report/eventreport', $data);
    }
}
