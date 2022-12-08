<?= $this->extend("admin/common/layout/layout2") ?>

<?php $this->section('content') ?>
	<?php
		$id 					    = isset($result['id']) ? $result['id'] : '';
      	$paymentmethod      		= isset($result['paymentmethod_name']) ? $result['paymentmethod_name'] : '';
		$firstname 					= isset($result['firstname']) ? $result['firstname'] : '';
		$lastname 					= isset($result['lastname']) ? $result['lastname'] : '';
		$mobile 					= isset($result['mobile']) ? $result['mobile'] : '';
		$eventname 					= isset($result['eventname']) ? $result['eventname'] : '';
		$barnstalls 				= isset($result['barnstall']) ? $result['barnstall'] : '';
		$checkin 					= isset($result['check_in']) ? $result['check_in'] : '';
		$checkin                    = formatdate($checkin, 1);
		$checkout 					= isset($result['check_out']) ? $result['check_out'] : '';
		$checkout                   = formatdate($checkout, 1);
		$createdat       	  		= isset($result['created_at']) ? formatdate($result['created_at'], 2) : '';
	?>
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Reservations</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
						<li class="breadcrumb-item"><a href="<?php echo getAdminUrl(); ?>/reservations">Reservations</a></li>
						<li class="breadcrumb-item active">View Reservations</li>
					</ol>
				</div>
			</div>
		</div>
	</section>
	
	<section class="content">
		<div class="page-action">
			<a href="<?php echo getAdminUrl(); ?>/reservations" class="btn btn-primary">Back</a>
		</div>
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">View Reservations</h3>
			</div>
			<div class="card-body">
				<table class="table">
				  <tbody>
				  	<tr>
				      <th>Booking ID</th>
				      <td><?php echo $id;?></td>
				    </tr>
				    <tr>
				      <th>First Name</th>
				      <td><?php echo $firstname;?></td>
				    </tr>
				    <tr>
				      <th>Last Name</th>
				      <td><?php echo $lastname;?></td>
					</tr>
					<tr>
						<th>Mobile</th>
						<td><?php echo $mobile;?></td>
					</tr>
					<tr>
						<th>Event Name</th>
						<td><?php echo $eventname;?></td>
					</tr>
					<tr>
						<th>Barn & Stall Name</th>
						<td> <?php foreach ($barnstalls as $barnstall) {
              				echo ' <p class="my-2">'.$barnstall['barnname'].'-'.$barnstall['stallname'].'</p>';  } ?>
              			</td>
					</tr>
					<tr>
						<th>Check In</th>
						<td><?php echo $checkin;?></td>
					</tr>
					<tr>
						<th>Check Out</th>
						<td><?php echo $checkout;?></td>
					</tr>
					<tr>
						<th>Date of Booking</th>
						<td><?php echo $createdat;?></td>
					</tr>
					<tr>
						<th>Booked By</th>
						<td><?php echo $usertype[$result['usertype']];?></td>
					</tr>
				  	<tr>
				      <th>Payment Method</th>
				      <td><?php echo $paymentmethod;?></td>
				    </tr>
				    <tr>
				      <th>Status</th>

				      <?php $statuscolor = ($result['status']=='2') ? "cancelcolor" : "activecolor"; ?>
			            <td class="<?php echo $statuscolor;?>" ><?php echo $bookingstatus[$result['status']];?></td>
				    </tr>
				  </tbody>
				</table>
			</div>
		</section>
		<section class="container-lg">
			<div class="card">
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
												foreach ($barnstalls as $barnstall) {
													if($barnname!=$barnstall['barnname']){
														$barnname = $barnstall['barnname']; 
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
													if($barnstall['price_type']!=0){ 
														$pricetype = '<span class="pricelist_tagline">('.$pricelists[$barnstall['price_type']].')</span>'; 
													}	
													?>
														<tbody>
															<tr>
																<td><?php echo $barnstall['stallname'].$pricetype; ?></td>
																<td><?php echo '('.$currencysymbol.$barnstall['price'].'x'.$barnstall['quantity'].')'.$currencysymbol.$barnstall['total']; ?></td>
															</tr>
															<?php if($barnstall['price_type']==5){ ?>
																<tr>
																	<td><span class="subscriptionprice_date"><?php echo 'Date : ('.formatdate($barnstall['subscriptionstartdate'], 1).')'; ?></span></td>
																	<td>
																		<span class="subscriptionprice_amount"><?php echo $currencysymbol.$barnstall['subscription_price']; ?></span>
																		<?php if($barnstall['subscription_status']=='1'){ ?>
																			<a href="javascript:void(0);" class="btn btn-primary cancelsubscription" data-bookingid="<?php echo $bookingid; ?>" data-paymentid="<?php echo $barnstall['payment_id']; ?>">Cancel</a>
																		<?php }else{ ?>
																			<a href="javascript:void(0);" class="btn btn-danger">Cancelled</a>
																		<?php } ?>
																	</td>
																</tr>
															<?php } ?>
														</tbody>
												<?php } ?>
											</table>
										<?php } ?>

										<?php if(!empty($rvbarnstall)){ ?>
											<h5 class="fw-bold text-muted">Campsites</h5>
											<table class="table-hover table-striped table-light table">
											<?php 	
												$rvbarnstalls = '';
												foreach ($rvbarnstall as $rvbarnstall) {
													$rvstallname = $rvbarnstall['stallname'];
													$rvprice     = $rvbarnstall['price'];
													$rvtotal     = $rvbarnstall['total'];
													$rvquantity  = $rvbarnstall['quantity'];
													if($rvbarnstall!=$rvbarnstall['barnname']){
														$rvbarnstalls = $rvbarnstall['barnname']; 
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
													if($rvbarnstall['price_type']!=0){ 
														$pricetype = '<span class="pricelist_tagline">('.$pricelists[$rvbarnstall['price_type']].')</span>'; 
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
												<?php foreach ($feed as $feed){ ?>
													<tr>
														<td><?php echo $feed['productname'];?></td>
														<td><?php echo '('.$currencysymbol.$feed['price'].'x'.$feed['quantity'].')'.$currencysymbol.$feed['total']?></td>
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
												<?php foreach ($shaving as $shaving){ ?>
													<tr>
														<td><?php echo $shaving['productname']?></td>
														<td><?php echo '('.$currencysymbol.$shaving['price'].'x'.$shaving['quantity'].')'.$currencysymbol.$shaving['total']?></td>
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
									<div class="summaryprc"><p><b>Transaction Fees</b></p><p align="right"><?php echo $currencysymbol.$result['transaction_fee'];?></p></div>
									<?php 
									if($result['cleaning_fee']!=""){?>
									<div class="summaryprc"><p><b>Cleaning Fee</b></p><p align="right"><?php echo $currencysymbol.$result['cleaning_fee'];?></div>
									<?php } ?>
									<?php 
									if($result['event_tax']!='0'){?>
									<div class="summaryprc"><p><b>Tax</b></p><p align="right"><?php echo $currencysymbol.$result['event_tax'];?></div>
									<?php } ?>
									</div>
									<div class="summaryprcy"><p><b>Amount</b></p><p align="right"><?php echo $currencysymbol.$result['amount'];?></p></div>
									
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
<?php $this->endSection(); ?>