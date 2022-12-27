<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Event Ticket</title>
</head>
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ;?>/assets/site/css/bootstrap.min.css">
<body>
	<style type="text/css">
		
		@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap');
		
		*, td, th, p{
			font-family: 'Montserrat', sans-serif;
		}
		
		.reservation-sect p{
			font-size:26px;
			font-weight:700;
		}

		::-webkit-scrollbar{
			display: none;
		}
		
		.ticket_title_tag {
			font-size: 11px;
			margin-bottom: 0;
		}

		.ticket_values {
			font-weight: 700;
			font-size: 15px;
			margin-bottom: 0;
		}

		.ticket_status {
			background-color: #5ACC5F;
			color: #fff;
			text-transform: uppercase;
			width: 100%;
			padding: 10px;
			border-radius: 5px;
			text-align: center;
		}

		.ticket_row{
			height: fit-content;
			position: relative;
		}

		.base_stripe {
			/* padding: 15px; */
			border-radius: 5px;
		}

		.base_stripe:nth-child(odd) {
			background-color: #F9F9F9;
		}

		@media(max-width: 767px){
			.res_mt_3{
				margin-top: 10px;
			}
		}

		.fw-bold{
			font-weight: bold;
		}
		.pdf-logo{
		    text-align:center;
		}
		.table tr{
		    padding:10px;
		}
		.table tr, .table td{
		    padding:5px;
		}

        .ticket_title_tag {
            font-size: 11px;
            margin-bottom: 0;
        }

        .ticket_values {
            font-weight: 600;
            font-size: 13px;
            margin-bottom: 0;
        }
        
        .ticket_title_tag, .ticket_values{
            padding-left:30px;
        }

        .ticket_status {
            background-color: #5ACC5F;
            color: #fff;
            text-transform: uppercase;
            width: 100%;
            padding: 0.6rem;
            border-radius: 5px;
            text-align: center;
        }

        .ticket_row{
            height: fit-content;
            position: relative;
        }

        .base_stripe {
            padding: 15px;
            border-radius: 5px;
        }

        .base_stripe:nth-child(odd) {
            background-color: #F9F9F9;
        }
        .summary-total {
    padding: 0;
}

.summary-sec {
    border: 1px solid #f0f0f0;
    padding-top: 10px;
}

.summary-sec h5 {
    padding-left: 20px;
}

.totalbg{
    background-color: #ffffff;
    /* width: fit-content; */
    /* float: right; */
    padding: 8px 0px;
    color: #e00000;
    margin: 0;
}

.stall-summary-list .table th{
    background-color: transparent;
}

.stall-summary-list .table th:last-child {
    /*padding-right: 0;*/
}

.stall-summary-list .table th:first-child {
    vertical-align: middle;
}

.summaryprcy {
    background-color: #e00000;
    color: #fff !important;
    padding: 10px 20px !important;
    margin-top: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.stall-summary-list .table-striped>tbody>tr:nth-of-type(odd)>*, .stall-summary-list .table-striped>tbody>tr:nth-of-type(even)>* {
    background-color: transparent !important;
    box-shadow: none;
    border-bottom: none;
}

.summaryprcy p:first-child{
    float:left;
    width:40%;
}

.summaryprcy p:last-child{
    float:right;
    width:40%;
}

.summaryprcy p, .summaryprcy p b {
    margin: 0;
    color: #fff;
    font-weight: 600;
}

.cart-summary-section {
    border: 1px solid #f0f0f0;
}

.cart-summary-section .col-md-7, .cart-summary-section .col-md-5 {
    padding: 20px;
}

.cart-summary-section .col-md-5 {
    background-color: #f0f0f0;
}

.summaryprc p {
    margin: 0;
}

.stall-summary-list .table tr th {
    border-bottom: none;
    padding: 10PX 15px !important;
}

.stall-summary-list .table {
    width: 100%;
    border: 1px solid #f0f0f0;
    margin-top: 20px;
    border-left: 3px solid #e00000;
}

.summary-sec h5 {
    margin-bottom: 20px;
    border-bottom: 1px solid #f0f0f0;
    padding-bottom: 12px;
    color: #000 !important;
    font-size: 24px;
}

.summaryprc {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.summary-total .summary-sec .summaryprc td {
    height: auto;
    padding: 10px 20px;
    background: #fff;
    margin-bottom: 12px;
}

.col-md-5.summary-total {
    padding: 0;
}

.summary-sec {
    border: 1px solid #f0f0f0;
    padding-top: 10px;
}


.stall-summary-list .table tr th {
    border-bottom: none;
    padding-bottom: 0;
    padding: 10PX 15px !important;
}

	</style>
	<?php 
	$bookingid          = isset($reservationpdf['id']) ? $reservationpdf['id'] : '';
	$firstname          = isset($reservationpdf['firstname']) ? $reservationpdf['firstname'] : '';
	$lastname           = isset($reservationpdf['lastname']) ? $reservationpdf['lastname'] : '';
	$mobile             = isset($reservationpdf['mobile']) ? $reservationpdf['mobile'] : '';
	$eventname          = isset($reservationpdf['eventname']) ? $reservationpdf['eventname'] : '';
	$checkin            = isset($reservationpdf['check_in']) ? formatdate($reservationpdf['check_in'], 1) : '';
	$checkout           = isset($reservationpdf['check_out']) ? formatdate($reservationpdf['check_out'], 1) : '';
	$createdat       	= isset($reservationpdf['created_at']) ? formatdate($reservationpdf['created_at'], 2) : '';
	$barnstalls         = isset($reservationpdf['barnstall']) ? $reservationpdf['barnstall'] : '';
	$rvbarnstalls       = isset($reservationpdf['rvbarnstall']) ? $reservationpdf['rvbarnstall'] : '';
	$feeds              = isset($reservationpdf['feed']) ? $reservationpdf['feed'] : '';
	$shavings           = isset($reservationpdf['shaving']) ? $reservationpdf['shaving'] : '';
	$paymentmethod      = isset($reservationpdf['paymentmethod_name']) ? $reservationpdf['paymentmethod_name'] : '';
	$path               = base_url().'/assets/images/black-logo.png';
    $type               = pathinfo($path, PATHINFO_EXTENSION);
    $data               = file_get_contents($path);
    $logo               = 'data:image/' . $type . ';base64,' . base64_encode($data);
	$mwnlist 			= ['M', 'W', 'N'];
	?>
	<div class="container event__ticket">
		<div class="pdf-logo text-center" style="text-align:ceter;width:100%;padding:10px;float:none;display:flex;align-item:center;">
		    <p class="pdf-logo text-center"><img class="text-center" style="width:200px;height:40px;text-align:ceter;margin:0 auto;" src="<?php echo $logo; ?>"></p>
		</div>
		<div class="reservation-sect" style="padding:20px;border:2px solid #f0f0f0;">
		<p class="text-center">View Reservation</p>
		<div class="row" style="">
			<div class="row base_stripe">
				<table class="table">
				    <tbody>
				        <tr style="background-color: #F9F9F9">
        					<td class="ticket_title_tag" style="padding-left:20px;"><label>Booking ID</label></td>
        					<td class="ticket_title_tag"><label>Name</label></td>
        				</tr>
        				<tr style="background-color: #F9F9F9">
        					<td class="ticket_values" style="padding-left:20px;"><label ><b><?php echo $bookingid;?></b></label></td>
        					<td class="ticket_values"><label ><b><?php echo $firstname; ?> <?php echo $lastname;?></b></label></td>
        				</tr>
        				<tr>
							<td class="ticket_title_tag" style="padding-left:20px;"><label>Check In</label></td>
							<td class="ticket_title_tag"><label>Check Out</label></td>
						</tr>
				    	<tr>
				    	    <td class="ticket_values" style="padding-left:20px;"><label ><b><?php echo $checkin;?></b></label></td>
        					<td class="ticket_values"><label ><b><?php echo $checkout;?></b></label></td>
						</tr>
						<tr  style="background-color: #F9F9F9">
							<td class="ticket_title_tag" style="padding-left:20px;"><label>Booked By</label></td>
							<td class="ticket_title_tag"><label>Date of booking</label></td>
						</tr>
						<tr style="background-color: #F9F9F9">
						    <td class="ticket_values" style="padding-left:20px;"><label ><b><?php echo $usertype[$reservationpdf['usertype']];?></b></label></td>
        					<td class="ticket_values"><label ><b><?php echo $createdat;?></b></label></td>
						</tr>
					    <tr>
							<td class="ticket_title_tag" style="padding-left:20px;"><label>Payment Method</label></td>
							<td class="ticket_title_tag"><label>Status</label></td>
						</tr>
						<tr>
							 <td class="ticket_values" style="padding-left:20px;"><label ><b><?php echo $paymentmethod;?></b></label></td>
        					<td class="ticket_values"><label ><b>Booked</b></label></td>
						</tr>
				        <tr style="background-color: #F9F9F9">
							<td class="ticket_title_tag" style="padding-left:20px;"><label>Mobile</label></td>
							<td class="ticket_title_tag"><label>Booked Event</label></td>
						</tr>
						<tr style="background-color: #F9F9F9">
						    <td class="ticket_values" style="padding-left:20px;"><label ><b><?php echo $mobile;?></b></label></td>
        					<td class="ticket_values"><label ><b>Event ( <?php echo $eventname;?> )</b></label></td>
						</tr>
					</tbody>
				</table>
	        </div>
	    </div>
	</div>
	<br>
    <br>
    <div class="cartsummary-sect" style="border:2px solid #f0f0f0;">
		<div class="row">
			<div class="row base_stripe" style="display: flex;justify-content: space-between;align-items: center;">
            <table class="table">
                <tr>
                   <td style="padding:20px;">
                        <div class="col-md-7">
                            <div class="stall-summary-list">
								<?php if(!empty($barnstalls)){  ?>
									<h5 style="padding:15px;" class="fw-bold text-muted">Barn&amp;Stall</h5><br>
									<table class="table-hover table-striped table-light table" style="width: 80%;border: 1px solid #f0f0f0;padding: 20px;border-left: 3px solid #e00000;">
										<?php 
											$barnname = '';
											foreach($barnstalls as $barnstall){ 
												if($barnname!=$barnstall['barnname']){
												$barnname = $barnstall['barnname']; 
										?> 
											<thead>
												<tr>
													<th style="width:200px;padding:10px;"> <?php echo $barnstall["barnname"];?></th>
													<th style="padding:10px;"> <p class="totalbg"> Total</p></th>
												</tr>
											</thead>
											<?php 
												} 										
												$pricetype = '';
												if($barnstall['price_type']!=0 && !in_array($barnstall['price_type'], ['1','2','3'])){ 
													$pricetype = '<span class="pricelist_tagline">('.$pricelists[$barnstall['price_type']].')</span>'; 
												}	
											?>
											<tbody>
												<tr>
													<td style="width:200px;padding:10px;"><?php echo $barnstall["stallname"].$pricetype; ?></td>
													<?php if(in_array($barnstall['price_type'], ['1','2','3'])){ ?>
														<?php 
															$mwnprice 		= explode(',', $barnstall['mwn_price']);
															$mwninterval 	= explode(',', $barnstall['mwn_interval']);
															$mwntotal 		= explode(',', $barnstall['mwn_total']);
														?>
														<td style="padding:10px;">
															<?php
																for($i=0; $i<count($mwnprice); $i++){
																	if($mwnprice[$i]!=0){
																		echo $mwnlist[$i].'('.$currencysymbol.$mwnprice[$i].'x'.$mwninterval[$i].')'.$currencysymbol.$mwntotal[$i].'<br>';  
																	}
																}
															?>
														</td>
													<?php }else{ ?>
														<td style="padding:10px;"><?php echo ($currencysymbol.$barnstall['price'].'x'.$barnstall['quantity']); ?><?php echo $currencysymbol.$barnstall['total']; ?></td>
													<?php } ?>
												</tr>
												<?php if($barnstall['price_type']==5){ ?>
													<tr>
														<td style="padding:10px;"><span><?php echo 'Date : ('.formatdate($barnstall['subscriptionstartdate'], 1).')'; ?></span></td>
														<td style="padding:10px;"><span><?php echo $currencysymbol.$barnstall['subscription_price']; ?></span></td>
													</tr>
												<?php } ?>
											</tbody>
										<?php } ?>
									</table>
                                <?php } ?>
								<?php if(!empty($rvbarnstalls)) { ?>
									<h5 style="padding:15px;" class="fw-bold text-muted">RV&amp;Stall</h5><br>
									<table class="table-hover table-striped table-light table" style="width: 80%;border: 1px solid #f0f0f0;padding: 20px;border-left: 3px solid #e00000;">
										<?php 
											$rvbarnstallname = '';
											foreach ($rvbarnstalls as $rvbarnstall) {
												if($rvbarnstallname!=$rvbarnstall['barnname']){
													$rvbarnstallname = $rvbarnstall['barnname']; 
										?>
											<thead>
												<tr>
													<th style="width:200px;padding:10px;"> <?php echo $rvbarnstall["barnname"];?></th>
													<th style="padding:10px;"> <p class="totalbg"> Total</p></th>
												</tr>
											</thead>
											<?php 
											}
											$pricetype = '';
											if($rvbarnstall['price_type']!=0 && !in_array($rvbarnstall['price_type'], ['1','2','3'])){ 
												$pricetype = '<span class="pricelist_tagline">('.$pricelists[$rvbarnstall['price_type']].')</span>'; 
											}
											?>
											<tbody>
												<tr>
													<td style="width:200px;padding:10px;"><?php echo $rvbarnstall["stallname"].$pricetype;?></td>
													<?php if(in_array($rvbarnstall['price_type'], ['1','2','3'])){ ?>
														<?php 
															$mwnprice 		= explode(',', $rvbarnstall['mwn_price']);
															$mwninterval 	= explode(',', $rvbarnstall['mwn_interval']);
															$mwntotal 		= explode(',', $rvbarnstall['mwn_total']);
														?>
														<td style="padding:10px;">
															<?php
																for($i=0; $i<count($mwnprice); $i++){
																	if($mwnprice[$i]!=0){
																		echo $mwnlist[$i].'('.$currencysymbol.$mwnprice[$i].'x'.$mwninterval[$i].')'.$currencysymbol.$mwntotal[$i].'<br>'; 
																	}
																}
															?>
														</td>
													<?php }else{ ?>
														<td style="padding:10px;"><?php echo ($currencysymbol.$rvbarnstall['price'].'x'.$rvbarnstall['quantity']);?><?php echo $currencysymbol.$rvbarnstall['total']; ?></td>
													<?php } ?>
												</tr>
												<?php if($rvbarnstall['price_type']==5){ ?>
													<tr>
														<td><span><?php echo 'Date : ('.formatdate($rvbarnstall['subscriptionstartdate'], 1).')'; ?></span></td>
														<td><span><?php echo $currencysymbol.$rvbarnstall['subscription_price']; ?></span></td>
													</tr>
												<?php } ?>
											</tbody>
										<?php } ?>
									</table>
                                <?php } ?>
                                
								<?php  if(!empty($feeds)) { ?> 
									<h5 style="padding:15px;" class="fw-bold text-muted">Feed</h5><br>
									<table class="table-hover table-striped table-light table" style="width: 80%;border: 1px solid #f0f0f0;padding: 20px;border-left: 3px solid #e00000;">
										<thead>
											<tr>
												<th  style="width:200px;padding:10px;"> Feed Name</th>
												<th style="padding:10px;"> <p class="totalbg"> Total</p></th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($feeds as $feed) { ?>
												<tr>
													<td style="width:200px;padding:10px;"><?php echo $feed['productname']; ?></td>
													<td style="padding:10px;"><?php echo ($currencysymbol.$feed['price'].'x'.$feed['quantity']);?> <?php echo $currencysymbol.$feed['total']; ?></td>
												</tr>
											<?php } ?>
										</tbody>
									</table>    
                                <?php } ?>
								
								<?php if(!empty($shavings)) { ?>
									<h5 style="padding:15px;" class="fw-bold text-muted">Shavings</h5><br>
									<table class="table-hover table-striped table-light table" style="width: 80%;border: 1px solid #f0f0f0;padding: 20px;border-left: 3px solid #e00000;">
										<thead>
											<tr>
												<th style="width:200px;padding:10px;"> Feed Name</th>
												<th style="padding:10px;"> <p class="totalbg"> Total</p></th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($shavings as $shaving){ ?>
												<tr>
													<td  style="width:200px;padding:10px;"><?php echo $shaving['productname'];?></td>
													<td style="padding:10px;"><?php echo ($currencysymbol.$shaving['price'].'x'.$shaving['quantity'])?><?php echo $currencysymbol.$shaving['total']; ?></td>
												</tr>
											<?php } ?>
										</tbody>
									</table> 
                                <?php } ?>
                            </div>
                        </div>
                    </td>
                    <td style="background-color:#f0f0f0;padding:20px;">
                        <div class="col-md-5 summary-total" style="background-color:#f0f0f0;">
                                    <div class="summary-sec" style="background-color:#f0f0f0;">
                                        <h5 class="fw-bold">Cart Summary</h5>

                                        <table style="border-spacing: 0px 10px;border-collapse: separate;">
                                            <tr>
                                                <th style="width:220px; padding:10px 20px;"></th>
                                                <th></th>
                                            </tr>
                                            <tr class="summaryprc" style="background-color:#fff;">
                                                <td style="width:220px; padding:10px 20px;">Total</td>
                                                <td style="padding:10px 20px;"><?php echo $currencysymbol.$reservationpdf['price'];?></td>
                                            </tr>
                                            <tr class="summaryprc" style="background-color:#fff;">
                                                <td style="width:220px; padding:10px 20px;">Transaction Fees</td>
                                                <td style="padding:10px 20px;"><?php if(!empty($reservationpdf['transaction_fee'])) { echo $currencysymbol.$reservationpdf['transaction_fee']; } ?></td>
                                            </tr>
                                            <tr class="summaryprc" style="background-color:#fff;">
                                                <td style="width:220px; padding:10px 20px;">Cleaning Fee</td>
                                                <td style="padding:10px 20px;"><?php if(!empty($reservationpdf['cleaning_fee'])) { echo $currencysymbol.$reservationpdf['cleaning_fee']; } ?></td>
                                            </tr>
                                             <tr class="summaryprc" style="background-color:#fff;">
                                                <?php if(!empty($reservationpdf['event_tax'])){?>
                                                    <td style="width:220px; padding:10px 20px;">Tax</td>
                                                    <td style="padding:10px 20px;"><?php if(!empty($reservationpdf['event_tax'])) { echo $currencysymbol.$reservationpdf['event_tax']; } ?></td>
                                                <?php } ?>
                                            </tr>
                                        </table>
                                        
                                    </div>
                                    <table>
                                    <tr class="summaryprcy">
                                        <td style="width:220px; color:#fff; padding:10px 20px;font-weight:700;"><b>Amount</b></td>
                                        <td style="color:#fff; padding:10px 20px;font-weight:700;" align="right"><?php echo $currencysymbol.$reservationpdf['amount'];?></td>
                                    </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
    		</div>
		</div>
	</div>
	</body>
	</html>