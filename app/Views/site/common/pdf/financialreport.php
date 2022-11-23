<style>
table{
	width : 100%;
}

table tr th, 
table tr td {
	padding : 10px;
	text-align : left;
}
</style>


<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Invoice Report</title>
	</head>
	<body>
	    <div> 
		    <div style="text-align:center;padding:0;"><img src="<?php echo $logo; ?>" width="500"></div>
		    <?php foreach($events as $key => $data){ ?>
                <?php
                    $eventname  			= $data['eventname'];
                    $eventtype  			= $data['eventtype'];
                    $startdate 				= isset($checkin) && $checkin!='' ? formatdate($checkin, 1) : formatdate($data['startdate'], 1);
                 	$enddate 				= isset($checkout) && $checkout!='' ? formatdate($checkout, 1) : formatdate($data['enddate'], 1);
					$totalamount 			= $data['totalamount'];
					$totalprice 			= $data['totalprice'];
					$totaltransactionfee 	= $data['totaltransactionfee'];
					$totalcleaningfee 		= $data['totalcleaningfee'];
					$totaltax 				= $data['totaltax'];
					$totalamountadmin		= $totalprice+$totaltransactionfee+$totalcleaningfee+$totaltax;
					$totalamountuser		= $totalprice+$totalcleaningfee+$totaltax;
					
					if($eventtype==1){
						$eventtext = 'EVENT';
					}else{
						$eventtext = 'FACILITY';
					}
					
					$totalstallavailable = 0;
					$totalstallrented = 0;
					$totalstallnotrented = 0;
					$totalstallamount = 0;
					$totalstallcodamount = 0;
					$totalstallstripeamount = 0;
					foreach($data['barn'] as $barn){
						if(!empty($barn['stall'])){
							foreach($barn['stall'] as $stall){
								$totalstallavailable++;
								
								if(!empty($stall['bookedstall'])){
									$totalstallrented++;
									
									foreach($stall['bookedstall'] as $bookedstall){
										$totalstallamount += $bookedstall['total'];
										
										if($bookedstall['paymentmethodid']=='1'){
											$totalstallcodamount += $bookedstall['total'];
										}elseif($bookedstall['paymentmethodid']=='2'){
											$totalstallstripeamount += $bookedstall['total'];
										}
									}
								}else{
									$totalstallnotrented++;
								}
							}
						}
					}
					
					$totallotsavailable = 0;
					$totallotsrented = 0;
					$totallotsnotrented = 0;
					$totallotsamount = 0;
					$totallotscodamount = 0;
					$totallotsstripeamount = 0;
					foreach($data['rvbarn'] as $barn){
						if(!empty($barn['rvstall'])){
							foreach($barn['rvstall'] as $stall){
								$totallotsavailable++;
								
								if(!empty($stall['rvbookedstall'])){
									$totallotsrented++;
									
									foreach($stall['rvbookedstall'] as $bookedstall){
										$totallotsamount += $bookedstall['total'];
										
										if($bookedstall['paymentmethodid']=='1'){
											$totallotscodamount += $bookedstall['total'];
										}elseif($bookedstall['paymentmethodid']=='2'){
											$totallotsstripeamount += $bookedstall['total'];
										}
									}
								}else{
									$totallotsnotrented++;
								}
							}
						}
					}
					
					$feedshaving = count($data['shaving']) > count($data['feed']) ? count($data['shaving']) : count($data['feed']);
					$feedshavingdatas = '';
					$totalfeedamount = 0;
					$totalfeedscodamount = 0;
					$totalfeedstripeamount = 0;
					$totalshavingamount = 0;
					$totalshavingcodamount = 0;
					$totalshavingstripeamount = 0;
					for($i=0; $i<$feedshaving; $i++){
						$feedendinventory = '';
						$shavingendinventory = '';
						$feedsold = 0;
						$shavingsold = 0;
						
						if(isset($data['feed'][$i])){
							$feedendinventory = $data['feed'][$i]['productname'].'<br> ENDING INVENTORY: '.$data['feed'][$i]['productquantity'];
							
							if(!empty($data['feed'][$i]['feedbooked'])){
								foreach($data['feed'][$i]['feedbooked'] as $bookedstall){
									$feedsold += $bookedstall['quantity'];
									$totalfeedamount += $bookedstall['total'];
									
									if($bookedstall['paymentmethodid']=='1'){
										$totalfeedscodamount += $bookedstall['total'];
									}elseif($bookedstall['paymentmethodid']=='2'){
										$totalfeedstripeamount += $bookedstall['total'];
									}
								}
							}
						}
						
						if(isset($data['shaving'][$i])){
							$shavingendinventory = $data['shaving'][$i]['productname'].'<br> ENDING INVENTORY: '.$data['shaving'][$i]['productquantity'];
							
							if(!empty($data['shaving'][$i]['shavingbooked'])){
								foreach($data['shaving'][$i]['shavingbooked'] as $bookedstall){
									$shavingsold += $bookedstall['quantity'];
									$totalshavingamount += $bookedstall['total'];
									
									if($bookedstall['paymentmethodid']=='1'){
										$totalshavingcodamount += $bookedstall['total'];
									}elseif($bookedstall['paymentmethodid']=='2'){
										$totalshavingstripeamount += $bookedstall['total'];
									}
								}
							}
						}
						
						$feedshavingdatas 	.=  '<tr>
													<td style="padding:0px;">'.$feedendinventory.'</td>
													<td style="padding:0px;">'.$shavingendinventory.'</td>
												</tr>
												<tr>
													<td style="padding:0px;">'.($feedendinventory!='' ? "QUANTITY SOLD: $feedsold" : "").'</td>
													<td style="padding:0px;">'.($shavingendinventory!='' ? "QUANTITY SOLD: $shavingsold" : "").'</td>
												</tr>';
					}
                ?>
    	        <h2 style="font-size:14px"><?php echo $eventname;?></h2>
    	        <table style="border-bottom:2px solid #000;margin-bottom:10px;">
    	            <tr>
						<?php if($eventtype=='1'){ ?>
							<td style="padding-left:0px;"><?php echo 'Start Date: '.$startdate; ?></td>
							<td><?php echo 'End Date: '.$enddate; ?></td>
							<td><?php echo 'TOTAL '.$eventtext.' REVENUE: '.$currencysymbol.($usertype==1 ? $totalamountadmin : $totalamountuser); ?></td>
						<?php }elseif($eventtype=='2'){ ?>
							<td style="padding-left:0px;"><?php echo 'TOTAL '.$eventtext.' REVENUE: '.$currencysymbol.($usertype==1 ? $totalamountadmin : $totalamountuser); ?></td>
						<?php } ?>
    	            </tr>
    	        </table>
    	        <table style="border-bottom:2px solid #000;padding-bottom:5px;">
    	            <tr>
    	                <td width="50%" style="padding-left:0;"><h2 style="font-size:30px;">STALLS</h2></td>
    	                <td width="50%" style="padding-left:0;"><h2 style="font-size:30px;">RV HOOKUPS</h2></td>
    	            </tr>
    	            <tr>
    	                <td style="padding:0px;">Total Stalls Available: <?php echo $totalstallavailable; ?></td>
    	                <td style="padding:0px;">Total Lots Available: <?php echo $totallotsavailable; ?></td>
    	            </tr>
    	            <tr>
    	                <td style="padding:0px;">Total Stalls Rented: <?php echo $totalstallrented; ?></td>
    	                <td style="padding:0px;">Total Lots Rented: <?php echo $totallotsrented; ?></td>
    	            </tr>
    	            <tr>
    	                <td style="padding:0px;">Total Stalls Not Rented: <?php echo $totalstallnotrented; ?></td>
    	                <td style="padding:0px;">Total Lots Not Rented: <?php echo $totallotsnotrented; ?></td>
    	            </tr>
    	            <tr>
    	                <td style="padding:0px;">Percentage Occupied: <?php echo $totalstallrented==0 ? 0 : number_format((($totalstallrented/$totalstallavailable)*100), '2').'%'; ?></td>
    	                <td style="padding:0px;">Percentage Occupied: <?php echo $totallotsrented==0 ? 0 : number_format((($totallotsrented/$totallotsavailable)*100), '2').'%'; ?></td>
    	            </tr>
				</table>
				<table style="margin-bottom:15px;">
    	            <tr>
    	                <td width="50%" style="font-weight:bold;padding:0px;padding-bottom:15px;padding-top:5px;">TOTAL STALL REVENUE: <?php echo $currencysymbol.$totalstallamount; ?></td>
    	                <td width="50%" style="font-weight:bold;padding:0px;padding-bottom:15px;padding-top:5px;">TOTAL LOT REVENUE: <?php echo $currencysymbol.$totallotsamount; ?></td>
    	            </tr>
    	            <tr>
    	                <td style="padding:0px;">STRIPE TRANSACTIONS: <?php echo $currencysymbol.$totalstallstripeamount; ?></td>
    	                <td style="padding:0px;">STRIPE TRANSACTIONS: <?php echo $currencysymbol.$totallotsstripeamount; ?></td>
    	            </tr>
    	            <tr>
    	                <td style="padding:0px;">CASH ON DELIVERY: <?php echo $currencysymbol.$totalstallcodamount; ?></td>
    	                <td style="padding:0px;">CASH ON DELIVERY: <?php echo $currencysymbol.$totallotscodamount; ?></td>
    	            </tr>
    	        </table>
    	        <table style="border-bottom:2px solid #000;padding-bottom:5px;">
    	            <tr>
    	                <td width="50%" style="padding-left:0;"><h2 style="font-size:30px;">FEED</h2></td>
    	                <td width="50%" style="padding-left:0;"><h2 style="font-size:30px;">SHAVINGS</h2></td>
    	            </tr>
					<?php echo $feedshavingdatas; ?>
				</table>
				<table style="margin-bottom:15px;">
    	            <tr>
    	                <td width="50%" style="font-weight:bold;padding:0px;padding-bottom:15px;padding-top:5px;">TOTAL FEED REVENUE: <?php echo $currencysymbol.$totalfeedamount; ?></td>
    	                <td width="50%" style="font-weight:bold;padding:0px;padding-bottom:15px;padding-top:5px;">TOTAL SHAVINGS REVENUE: <?php echo $currencysymbol.$totalshavingamount; ?></td>
    	            </tr>
    	            <tr>
    	                <td style="padding:0px;">STRIPE TRANSACTIONS: <?php echo $currencysymbol.$totalfeedstripeamount; ?></td>
    	                <td style="padding:0px;">STRIPE TRANSACTIONS: <?php echo $currencysymbol.$totalshavingstripeamount; ?></td>
    	            </tr>
    	            <tr>
    	                <td style="padding:0px;">CASH ON DELIVERY: <?php echo $currencysymbol.$totalfeedscodamount; ?></td>
    	                <td style="padding:0px;">CASH ON DELIVERY: <?php echo $currencysymbol.$totalshavingcodamount; ?></td>
    	            </tr>
    	        </table>
    	        <table style="border-bottom:2px solid #000;padding-bottom:5px;">
    	            <tr>
    	                <td width="<?php echo $usertype=='1' ? '33%' : '50%'; ?>" style="padding-left:0;padding-bottom:0px;"><h2 style="font-size:30px;">CLEANING</h2></td>
    	                <td width="<?php echo $usertype=='1' ? '33%' : '50%'; ?>" style="padding-left:0;padding-bottom:0px;"><h2 style="font-size:30px;">TAX</h2></td>
						<?php if($usertype=='1'){ ?>
							<td width="34%" style="padding-left:0;padding-bottom:0px;"><h2 style="font-size:30px;">TRANSACTION</h2></td>
						<?php } ?>
    	            </tr>
				</table>
				<table>
    	            <tr>
    	                <td width="<?php echo $usertype=='1' ? '33%' : '50%'; ?>" style="padding:0px;">TOTAL: <?php echo $currencysymbol.$totalcleaningfee; ?></td>
    	                <td width="<?php echo $usertype=='1' ? '33%' : '50%'; ?>" style="padding:0px;">TOTAL: <?php echo $currencysymbol.$totaltax; ?></td>
						<?php if($usertype=='1'){ ?>
							<td width="34%" style="padding:0px;">TOTAL: <?php echo $currencysymbol.$totaltransactionfee; ?></td>
						<?php } ?>
    	            </tr>
    	        </table>
    	        <h2 style="font-size:18px;padding-top:20px;padding-bottom:10px;"><?php echo 'TOTAL '.$eventtext.' REVENUE: '.$currencysymbol.($usertype==1 ? $totalamountadmin : $totalamountuser); ?></h2>
				<?php if($key!=count($events)-1){ ?>
					<pagebreak></pagebreak>
				<?php } ?>
	        <?php } ?>
			<p style="text-align:center;font-style: italic;">Thank You for Choosing EZ Stall!</p>
	    </div>
	</body>
</html>