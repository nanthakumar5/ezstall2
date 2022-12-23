<?php $this->extend('site/common/layout/layout1') ?>
<?php $this->section('content'); ?>
<?php $currentusertype = $userdetail['type']; ?>
<?php
	$bookingid          = isset($result['id']) ? $result['id'] : '';
	$firstname          = isset($result['firstname']) ? $result['firstname'] : '';
	$lastname           = isset($result['lastname']) ? $result['lastname'] : '';
	$mobile             = isset($result['mobile']) ? $result['mobile'] : '';
	$eventname          = isset($result['eventname']) ? $result['eventname'] : '';
	$stall              = isset($result['stall']) ? $result['stall'] : '';
	$checkin            = isset($result['check_in']) ? formatdate($result['check_in'], 1) : '';
	$checkout           = isset($result['check_out']) ? formatdate($result['check_out'], 1) : '';
	$createdat       	= isset($result['created_at']) ? formatdate($result['created_at'], 2) : '';
	$barnstalls         = isset($result['barnstall']) ? $result['barnstall'] : '';
	$rvbarnstalls       = isset($result['rvbarnstall']) ? $result['rvbarnstall'] : '';
	$feed               = isset($result['feed']) ? $result['feed'] : '';
	$shaving            = isset($result['shaving']) ? $result['shaving'] : '';
	$paymentmethod      = isset($result['paymentmethod_name']) ? $result['paymentmethod_name'] : '';
	$paidunpaid         = isset($result['paid_unpaid']) ? $result['paid_unpaid'] : '';
	$specialnotice      = isset($result['special_notice']) ? $result['special_notice'] : '';

?>
<div class="row">
	<div class="col">
		<h2 class="fw-bold mb-4">View Past Reservation</h2>
	</div>
	<div class="col" align="right">
		<a href="<?php echo base_url().'/myaccount/pastactivity';?>" class="btn back-btn">Back</a>
	</div>
</div>
<section class="maxWidp eventPagePanel">
	<div class="container event__ticket mx-auto p-3 my-5">
		<p class="text-center h5 my-4 fw-bold">View Past Reservation</p>
		<div class="row mx-5 px-4">
			<div class="row base_stripe">
				<div class="col-md-6">
					<p class="ticket_title_tag">Booking ID</p>
					<p class="ticket_values"><?php echo $bookingid;?></p>
				</div>
				<div class="res_mt_3 col-md-5">
					<p class="ticket_title_tag">Name</p>
					<p class="ticket_values"><?php echo $firstname;?> <?php echo $lastname;?></p>
				</div>
			</div>
			<div class="row base_stripe">
				<div class="res_mt_3 col-md-6">
					<p class="ticket_title_tag">Mobile</p>
					<p class="ticket_values"><?php echo $mobile;?></p>
				</div>
				<div class="res_mt_3 col-md-6">
					<p class="ticket_title_tag">Booked By</p>
					<p class="ticket_values"><?php echo $usertype[$result['usertype']];?></p>
				</div>
			</div>
			<div class="row base_stripe">
				<div class="res_mt_3 col-md-6">
					<p class="ticket_title_tag">Check In</p>
					<p class="ticket_values"><?php echo $checkin;?></p>
				</div>
				<div class="res_mt_3 col-md-6">
					<p class="ticket_title_tag">Check Out</p>
					<p class="ticket_values"><?php echo $checkout;?></p>
				</div>
			</div>
			<div class="row base_stripe">
				<div class="col-md-6">
					<p class="ticket_title_tag">Payment Method</p>
					<p class="ticket_values"><?php echo $paymentmethod;?>
						<?php if($paymentmethod=='Cash on Delivery'){
							if($paidunpaid!='1'){
								echo '<button data-bookingid="'.$bookingid.'" class="btn btn-primary">Unpaid</button>';
							}else{ echo '<button class="btn btn-danger">Paid</button>'; }
						} ?>
					</p>
				</div>
				<div class="res_mt_3 col-md-5">
					<p class="ticket_title_tag">Date of booking</p>
					<p class="ticket_values"><?php echo $createdat;?></p>
				</div>
			</div>
			<div class="row base_stripe">
				<div class="col-md-6">
					<p class="ticket_title_tag">Booked Event</p>
					<p class="ticket_values">Event (<?php echo $eventname;?>)</p>
				</div>
				<div class="col-md-6">
					<p class="ticket_title_tag">Special Request</p>
					<p class="ticket_values"><?php if(!empty($specialnotice)){ echo $specialnotice; } else{ echo "No Special Request";}?></p>
				</div>
			</div>
			<div class="row base_stripe">
				<div class="res_mt_3 col-md-2">
					<?php $statuscolor = ($result['status']=='2') ? "cancelcolor" : "activecolor"; ?>
					<p class="my-2 ticket_values ticket_status <?php echo  $statuscolor;?>"><?php echo $bookingstatus[$result['status']];?></p>
				</div>
			</div>

		</div>
	</div>
</section>
<section class="container-lg">
	<div class="row">
		<div class="col-12">
			<div class="rounded ">
				<div class="row cart-summary-section">
					<div class="col-md-7">
						<div class="stall-summary-list">
							<?php if(!empty($barnstalls)){ ?>
								<h5 class="fw-bold text-muted">Barn & Stall</h5>
								<table class="table-hover table-striped table-light table">	
								<?php 	
									$barnname = '';
									foreach ($barnstalls as $barnstalldata) {
										if($barnname!=$barnstalldata['barnname']){
											$barnname = $barnstalldata['barnname']; 
								?>							
											<thead>
												<tr>
													<th><?php echo $barnname;?></th>
													<th><p class="totalbg">Total</p></th>
												</tr>
											</thead>
										<?php 
										} 										
										$pricetype = '';
										if($barnstalldata['price_type']!=0){ 
											$pricetype = '<span class="pricelist_tagline">('.$pricelists[$barnstalldata['price_type']].')</span>'; 
										}	
										?>
											<tbody>
												<tr>
													<td><?php echo $barnstalldata['stallname'].$pricetype; ?></td>
													<td><?php echo '('.$currencysymbol.$barnstalldata['price'].'x'.$barnstalldata['quantity'].')'.$currencysymbol.$barnstalldata['total']; ?></td>
												</tr>
												<?php if($barnstalldata['price_type']==5){ ?>
													<tr>
														<td><span class="subscriptionprice_date"><?php echo 'Date : ('.formatdate($barnstalldata['subscriptionstartdate'], 1).')'; ?></span></td>
														<td>
															<span class="subscriptionprice_amount"><?php echo $currencysymbol.$barnstalldata['subscription_price']; ?></span>
														</td>
													</tr>
												<?php } ?>
											</tbody>
									<?php } ?>
								</table>
							<?php } ?>

							<?php if(!empty($rvbarnstalldata)){ ?>
								<h5 class="fw-bold text-muted">Campsites</h5>
								<table class="table-hover table-striped table-light table">
								<?php 	
									$rvbarnstalls = '';
									foreach ($rvbarnstall as $rvbarnstalldata) {
										$rvstallname = $rvbarnstalldata['stallname'];
										$rvprice     = $rvbarnstalldata['price'];
										$rvtotal     = $rvbarnstalldata['total'];
										$rvquantity  = $rvbarnstalldata['quantity'];
										if($rvbarnstalls!=$rvbarnstalldata['barnname']){
											$rvbarnstalls = $rvbarnstalldata['barnname']; 
								?>
											<thead>
													<tr>
													<th><?php echo $rvbarnstalls;?></th>
													<th><p class="totalbg">Total</p></th>
												</tr>
											</thead>
										<?php 
										}
										$pricetype = '';
										if($rvbarnstalldata['price_type']!=0){ 
											$pricetype = '<span class="pricelist_tagline">('.$pricelists[$rvbarnstalldata['price_type']].')</span>'; 
										}
										?>
										<tbody>
											<tr>
												<td><?php echo $rvstallname.$pricetype; ?></td>
												<td><?php echo '('.$currencysymbol.$rvprice.'x'.$rvquantity.')'.$currencysymbol.$rvtotal ?></td>
											</tr>
										</tbody>
									<?php } ?>
							</table>
							<?php } ?>

							<?php if(!empty($feed)){?>
								<h5 class="fw-bold text-muted">Feed</h5>
								<table class="table-hover table-striped table-light table">
									<thead>
										<tr>
											<th>Feed Name</th>
											<th><p class="totalbg">Total</p></th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($feed as $feeddata){ ?>
										<tr>
											<td><?php echo $feeddata['productname'];?></td>
											<td><?php echo '('.$currencysymbol.$feeddata['price'].'x'.$feeddata['quantity'].')'.$currencysymbol.$feeddata['total']?></td>
										</tr>
									<?php } ?>
									</tbody>
								</table>
							<?php } ?>

							<?php if(!empty($shaving)){ ?>
								<h5 class="fw-bold text-muted">Shavings</h5>
								<table class="table-hover table-striped table-light table">
									<thead>
										<tr>
											<th>Shavings Name</th>
											<th><p class="totalbg">Total</p></th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($shaving as $shavingdata){ ?>
										<tr>
											<td><?php echo $shavingdata['productname']?></td>
											<td><?php echo '('.$currencysymbol.$shavingdata['price'].'x'.$shavingdata['quantity'].')'.$currencysymbol.$shavingdata['total']?></td>
										</tr>
									<?php } ?>
									</tbody>
								</table>
							<?php } ?>
						</div>
					</div>
					<div class="col-md-5 summary-total">
						<div class="summary-sec">
							<h5 class="fw-bold">Cart Summary</h5>
							<div class="summaryprc"><p><b>Total</b></p> <p align="right"><?php echo $currencysymbol.$result['price'];?></p></div>
							<?php if($currentusertype=='5'){ ?>
								<div class="summaryprc"><p><b>Transaction Fees</b></p><p align="right"><?php echo $currencysymbol.$result['transaction_fee'];?></p></div>
							<?php } ?>
							<?php if($result['cleaning_fee']!=""){?>
								<div class="summaryprc"><p><b>Cleaning Fee</b></p><p align="right"><?php echo $currencysymbol.$result['cleaning_fee'];?></div>
							<?php } ?>
							<?php if($result['event_tax']!='0'){?>
								<div class="summaryprc"><p><b>Tax</b></p><p align="right"><?php echo $currencysymbol.$result['event_tax'];?></div>
							<?php } ?>
						</div>
						<div class="summaryprcy"><p><b>Amount</b></p><p align="right"><?php echo $currencysymbol.($currentusertype=='5' ? $result['amount'] : $result['amount']-$result['transaction_fee']);?></p></div>						
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php $this->endSection(); ?>