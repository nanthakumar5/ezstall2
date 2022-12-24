<?php $this->extend('site/common/layout/layout1') ?>
<?php $this->section('content') ?>
<?php $currentusertype = $userdetail['type']; ?>
<div class="dFlexComBetween eventTP flex-wrap py-2">
	<h2 class="fw-bold mb-2">Past Reservation</h2>
</div>
<section class="maxWidth eventPagePanel">
	<?php if(!empty($bookings)) {  ?>
	<?php foreach ($bookings as $data) { ?>
		<div class="event__ticket mb-5 p-3">
			<div class="row position-relative">
				<div class="row col-md-12 ticket_row mt-3">
					<div class="ticket_content res_mx_3 col-md-2 mx-3">
						<p class="ticket_title_tag">Name</p>
						<p class="ticket_values"><?php echo $data['firstname'].$data['lastname'];?></p>
					</div>
					<div class="ticket_content res_mx_3 col-md-2 mx-3">
						<p class="ticket_title_tag">Mobile</p>
						<p class="ticket_values"><?php echo $data['mobile'];?></p>
					</div>
					<div class="ticket_content col-md-2 mx-2">
						<p class="ticket_title_tag">Payment Method</p>
						<p class="ticket_values"><?php echo $data['paymentmethod_name'];?></p>
					</div>
					<div class="ticket_content col-md-2 mx-2">
						<p class="ticket_title_tag">Booked By</p>
						<p class="ticket_values"><?php echo $usertype[$data['usertype']]; ?></p>
					</div>
					<div class="ticket_content col-md-2 mx-2">
						<p class="ticket_title_tag">Date of booking</p>
						<p class="ticket_values"><?php echo date("m-d-Y h:i A", strtotime($data['created_at']));?></p>
					</div>
					<div class="ticket_content res_mx_3 col-md-2 mx-3">
						<p class="ticket_title_tag">Booking ID</p>
						<p class="ticket_values ticket_checkinout"><?php echo $data['id'];?></p>
					</div>
					<div class="ticket_content res_mx_3 col-md-2 mx-3">
						<p class="ticket_title_tag">Check In</p>
						<p class="ticket_values ticket_checkinout"><?php echo formatdate($data['check_in'], 1);?></p>
					</div>
					<div class="ticket_content col-md-2 mx-2">
						<p class="ticket_title_tag">Check Out</p>
						<p class="ticket_values ticket_checkinout"><?php echo formatdate($data['check_out'], 1);?></p>
					</div>
					<div class="ticket_content col-md-2 mx-2">
						<p class="ticket_title_tag">Cost</p>
						<p class="ticket_values ticket_checkinout"><?php echo $currencysymbol.($currentusertype=='5' ? $data['amount'] : $data['amount']-$data['transaction_fee']); ?></p>
					</div>
					<div class="ticket_content col-md-2 mx-2">
						<p class="ticket_title_tag">Special Request</p>
						<p class="ticket_values ticket_checkinout"><?php if(!empty($data['special_notice'])){ echo$data['special_notice']; } else{ echo "No Special Request";}?></p>
					</div>
					<div class="ticket_content col-md-2 mx-2 d-flex align-items-end">
						<?php $statuscolor = ($data['status']=='2') ? "cancelcolor" : "activecolor"; ?>
							<p class="my-2 ticket_values ticket_status <?php echo  $statuscolor;?>" ><?php echo $bookingstatus[$data['status']];?></p>
					</div>
				</div>
				<div class="top_event_border">
					<div class="ticket__event row mt-2">
						<div class="ticket_content col-md-2 mx-3">
							<p class="ticket_e_tag">Booked Event</p>
						</div>
						<div class="ticket_content col-md-9 mx-3">
							<p class="ticket_values mr-3">Event ( <?php echo $data['eventname'];?> )</p>
						</div>
						<div class="flex-wrap d-flex align-items-center">
						<?php if(!empty($data['barnstall'])){?>
						<div class="col-md-2 px-3">
							<p class="ticket_event_tag">STALL</p>
						</div>
						<?php foreach ($data['barnstall'] as $stalls) { ?>
						<div class="d-flex flex-wrap">
							<div class="mx-3">
								<p class="ticket_values"><?php echo $stalls['barnname'];?></p>
								<span class="d-flex flex-wrap">
									<p class="ticket_sub_values"><?php echo $stalls['stallname'];?></p>
								</span>
							</div>
						</div>
						<?php } } ?>
						</div>
						<div class="flex-wrap d-flex align-items-center">
						<?php if(!empty($data['rvbarnstall'])){?>
							<div class="col-md-2 px-3">
								<p class="ticket_event_tag">RV HOOKUP</p>
							</div>
							<?php foreach ($data['rvbarnstall'] as $rvstall) { ?>
								<div class="d-flex flex-wrap">
									<div class="mx-3">
										<p class="ticket_values"><?php echo $rvstall['barnname'];?></p>
										<span class="d-flex flex-wrap">
											<p class="ticket_sub_values"><?php echo $rvstall['stallname'];?></p>
										</span>
									</div>
								</div>
							<?php } } ?>
						</div>
						<div class="flex-wrap d-flex align-items-center">
							<?php if(!empty($data['feed'])){?>
							<div class="col-md-2 px-3">
								<p class="ticket_event_tag">FEED</p>
							</div>
							<?php foreach ($data['feed'] as $feed) { ?>
							<div class="d-flex align-items-center mx-3">
								<span class="d-flex flex-wrap">
									<p class="ticket_sub_values e_mr_1"><?php echo $feed['productname'];?>
								<?php echo '('.$feed['quantity'].')'.$currencysymbol.$feed['total']?></p>
								</span>
							</div>
							<?php } } ?>
						</div>
						<div class="flex-wrap d-flex align-items-center">
							<?php if(!empty($data['shaving'])){?>
							<div class="col-md-2 px-3">
								<p class="ticket_event_tag">SHAVINGS</p>
							</div>
							<?php foreach ($data['shaving'] as $shaving) { ?>
							<div class="d-flex flex-wrap">
								<div class="d-flex align-items-center mx-3">
									<p class="ticket_sub_values e_mr_1"><?php echo $shaving['productname'];?>  <?php echo '('.$shaving['quantity'].')'.$currencysymbol.$shaving['total']?></p>
								</div>
							</div>
							<?php } } ?>
						</div>
					</div>
				</div>
				<div class="text-center event_border">
					<?php if($userdetail['type']=='2' || $userdetail['type']=='3' || $userdetail['type']=='5'){ ?> 
						<a href="<?php echo base_url().'/myaccount/pastactivity/view/'.$data['id']; ?>" class="mt-0 mx-3 view-res">View</a>
					<?php } ?>
					<i id="ticket_toggle_up" class="fas fa-angle-up ticket__up"></i>
					<i id="ticket_toggle_down" class="fas fa-angle-down ticket__down"></i>
				</div>
			</div>
		</div>
	<?php } ?>
<?php }else{ ?>
		<p>No Reservation Found.</p>
<?php } ?>
</section>
<?php echo $pager; ?>
<?php $this->endSection(); ?>
<?php $this->section('js') ?>
<script type="text/javascript">
	$(document).ready(function(){
		$(".ticket__up").click(function(){
			$(this).parent().siblings(".top_event_border").slideUp();
			$(this).css("display", "none");
			$(this).next(".ticket__down").css("display", "block");
		});
		$(".ticket__down").click(function(){
			$(this).parent().siblings(".top_event_border").slideDown();  
			$(this).prev(".ticket__up").css("display", "block");
			$(this).css("display", "none");
		});
	});
</script>
<?php $this->endSection(); ?>