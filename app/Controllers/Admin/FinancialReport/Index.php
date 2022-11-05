<?php

namespace App\Controllers\Admin\FinancialReport;

use App\Controllers\BaseController;

use App\Models\Event;
use App\Models\Booking;
use App\Models\Report;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Index extends BaseController
{
	public function __construct()
	{  
		$this->event  	= new Event();
		$this->booking 	= new Booking();
		$this->report 	= new Report();
    }
	
	public function financialreport()
    {	
		if ($this->request->getMethod()=='post')
        {
			$requestdata 	= $this->request->getPost(); 
			$check_out  	= explode('-', $requestdata['financialcheckout']);
			$check_in  		= explode('-', $requestdata['financialcheckin']);
			$checkin  		= date('Y-m-d h:i:s',strtotime($check_in[1].'-'.$check_in[0].'-'.$check_in[2]));
			$checkout  		= date('Y-m-d h:i:s',strtotime($check_out[1].'-'.$check_out[0].'-'.$check_out[2]));

			$data['finacialamount']			= $this->report->getReport('all', ['booking','event','barn', 'stall','rvbarn', 'rvstall', 'bookedstall', 'rvbookedstall','feed', 'shaving'], ['financialcheckin' => $checkin, 'financialcheckout' => $checkout],['groupby' => 'bk.event_id', 'select' => 'SUM(bk.amount) as bookingamount, (e.name) AS eventname, (bk.event_id) AS eventid,(bk.id) AS id']);

			$spreadsheet 	= new Spreadsheet();
			$sheet 		 	= $spreadsheet->getActiveSheet();

			$sheet->setCellValue('A1', 'Event Name');
			$sheet->setCellValue('B1', 'Total Stalls Rented & Revenue');
			$sheet->setCellValue('C1', 'Total Available Stalls');
			$sheet->setCellValue('D1', 'Total COD Stall');
			$sheet->setCellValue('E1', 'Total Stall Paid & Unpaid');
			$sheet->setCellValue('F1', 'Total Stall Stripe');

			$sheet->setCellValue('G1', 'Total Lots Rented & Revenue');
			$sheet->setCellValue('H1', 'Total Available Lots');
			$sheet->setCellValue('I1', 'Total COD Lots');
			$sheet->setCellValue('J1', 'Total Lots Paid & Unpaid');
			$sheet->setCellValue('K1', 'Total Lots Stripe');
			$sheet->setCellValue('L1', 'Total Feed Revenue');
			$sheet->setCellValue('M1', 'Remaining Feed Inventory');
			$sheet->setCellValue('N1', 'Total shavings Revenue');
			$sheet->setCellValue('O1', 'Remaining shavings Inventory');

      		$totalamount 			= 0;
			$row = 2;
			if($data['finacialamount']){
				foreach ($data['finacialamount'] as $event) {
					$testbkslstrcountcs = [];
					$testbkslstrcount = [];
					$totalstallcount = [];
					$bookingstallcount = [];
					$availablestallcount = [];
					$testbkslstrcountcsrv = [];
					$testbkslstrcountrv = [];
					$totalrvstallcount = [];
					$bookingrvstallcount = [];
					$availablervstallcount = [];
					$bookingstallprice = [];
					$bookingrvstallprice = [];
					$paid = [];
					$unpaid = [];
					$rvpaid = [];
					$rvunpaid = [];
					$productfeedtotal =[];
					$productshavingstotal = [];
					$remainingfeed = []; 
					$remainingshavings = [];

					foreach ($event['barn'] as $barn) { 
						foreach ($barn['stall'] as $stall) {
							$totalstallcount[] = $stall['id'];
							if(!empty($stall['bookedstall'])){ 
								$bookingstallcount[] = $stall['id'];
								$bookingstallprice[] = (count($stall['bookedstall']) * $stall['price']);
							}else if(empty($stall['bookedstall'])){
								$availablestallcount[] = $stall['id'];
							}
							foreach ($stall['bookedstall'] as $bksl) {
								if($bksl['paymentid']==1){
									$testbkslstrcountcs[] = $bksl['paymentid'];
								}else if($bksl['paymentid']==2){
									$testbkslstrcount[]=$bksl['paymentid'];
								}
								if($bksl['paymentmethod']=='Cash on Delivery' && $bksl['paidunpaid']=='1'){
									$paid[] = $bksl['paymentid'];
								}else if($bksl['paidunpaid']==0 || $bksl['paymentmethod']=='Cash on Delivery'){
									$unpaid[]= $bksl['paymentid'];
								}
							}
						}
					}

					foreach ($event['rvbarn'] as $rvbarn) { 
						foreach ($rvbarn['rvstall'] as $rvstall) { 
							$totalrvstallcount[] = $rvstall['id'];
							if(!empty($rvstall['rvbookedstall'])){
								$bookingrvstallcount[] = $rvstall['id'];
								$bookingrvstallprice[] = (count($rvstall['rvbookedstall']) * $rvstall['price']);
							}else{
								$availablestallcount[] = $rvstall['id'];
							}
							foreach ($rvstall['rvbookedstall'] as $rvbksl) {
								if($rvbksl['paymentid']==1){
									$testbkslstrcountcsrv[] = $rvbksl['paymentid'];
								}else if($bksl['paymentid']==2){
									$testbkslstrcountrv[]=$rvbksl['paymentid'];
								}
								if($rvbksl['paymentmethod']=='Cash on Delivery' && $rvbksl['paidunpaid']=='1'){
									$rvpaid[] = $rvbksl['paymentid'];
								}else if($rvbksl['paidunpaid']==0 || $rvbksl['paymentmethod']=='Cash on Delivery'){
									$rvunpaid[]= $rvbksl['paymentid'];
								}
							}
						}
					}

					foreach($event['feed'] as $feed){
						$productfeedtotal[] = $feed['total']; 
						$remainingfeed[] = $feed['totalquantity'];
					}

					foreach($event['shaving'] as $shaving){
						$productshavingstotal[] = $shaving['total'];
						$remainingshavings[] = $shaving['totalquantity'];
					}

					$sheet->setCellValue('A' . $row, $event['eventname']);

					$sheet->setCellValue('B' . $row, count($bookingstallcount).'&'.array_sum($bookingstallprice));
					$sheet->setCellValue('C' . $row, count($totalstallcount)-count($bookingstallcount));
					$sheet->setCellValue('D' . $row, count($testbkslstrcountcs));
					$sheet->setCellValue('E' . $row, count($paid).'&'.count($unpaid));
					$sheet->setCellValue('F' . $row, count($testbkslstrcount));

					$sheet->setCellValue('G' . $row, count($bookingrvstallcount).'&'.array_sum($bookingrvstallprice));
					$sheet->setCellValue('H' . $row, count($totalrvstallcount)-count($bookingrvstallcount));

					$sheet->setCellValue('I' . $row, count($testbkslstrcountcsrv));
					$sheet->setCellValue('J' . $row, count($rvpaid).'&'.count($rvunpaid));
					$sheet->setCellValue('K' . $row, count($testbkslstrcountrv));
					$sheet->setCellValue('L' . $row, array_sum($productfeedtotal));
					$sheet->setCellValue('M' . $row, array_sum($remainingfeed));
					$sheet->setCellValue('N' . $row, array_sum($productshavingstotal));
					$sheet->setCellValue('O' . $row, array_sum($remainingshavings));
					
					$row++;
				}
			}else {
				$result = 'No Data';
				$sheet->setCellValue('A' . $row, $result);
			}
			
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="Event Report.xlsx"');
			header('Cache-Control: max-age=0');

			$writer = new Xlsx($spreadsheet);
			$writer->save('php://output');

			
		}
		return view('admin/financialreport/financialreport');
	}
}
