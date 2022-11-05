<?php $this->extend('site/common/layout/layout1') ?>
<?php $this->section('content') ?>
<div class="dFlexComBetween eventTP flex-wrap">
	<h2 class="fw-bold mb-4">Past Reservation</h2>
</div>
<section class="maxWidth eventPagePanel">
	<?php if(!empty($bookings)) {  ?>
		<?php foreach ($bookings as $data) {  ?>
			<div class="dashboard-box">
				<div class="EventFlex leftdata">
					<div class="wi-30 row w-100 align-items-center">
						<div class="row row m-0 p-0 dash-booking">
							<!--             <div class="col-md-2 mb-2">
								<div>
									<p class="mb-0 text-sm fs-7 fw-600">Status</p>
									<p class="mb-0 fs-7"><?php //echo $bookingstatus[$data['status']];?></p>
								</div>
							</div> -->
							<div class="col-md-3 col-lg-3 mb-2">
								<div>
									<p class="mb-0 text-sm fs-7 fw-600 w-100">Payment Method</p>
									<p class="mb-0 fs-7 w-100"><?php echo $data['paymentmethod_name'];?></p>
								</div>
							</div>
							<div class="col-md-3 col-lg-2 mb-2">
								<div>
									<p class="mb-0 text-sm fs-7 fw-600 w-100">Booking ID</p>
									<p class="mb-0 fs-7 w-100"><?php echo $data['id'];?></p>
								</div>
							</div>
							<div class="col-md-3 col-lg-3 mb-2">
								<div>
									<p class="mb-0 fs-7 fw-600 w-100">Booked By</p>
									<p class="mb-0 fs-7 w-100"><?php echo $usertype[$data['usertype']]; ?></p>
								</div>
							</div>
							<div class="col-md-3 col-lg-3 mb-2">
								<div>
									<p class="mb-0 fs-7 fw-600 w-100">Date of booking</p>
									<p class="mb-0 fs-7 w-100"><?php echo date("m-d-Y h:i A", strtotime($data['created_at']));?></p>
								</div>
							</div>
						</div>
						<div class="col-md-3 col-lg-3">
							<div>
								<p class="mb-0 text-sm fs-7 fw-600 w-100">Name</p>
								<p class="mb-0 fs-7 w-100"><?php echo $data['firstname'].$data['lastname'];?></p>
							</div>
						</div>
						<div class="col-md-3 col-lg-2">
							<div>
								<p class="mb-0 fs-7 fw-600 w-100">Booked Event</p>
								<p class="mb-0 fs-7 w-100"><?php echo $data['eventname'];?> (
								<?php 
									$stallname = [];
									foreach ($data['barnstall'] as $stalls) {
										$stallname[] = $stalls['stallname'];
									}
									echo implode(',', $stallname);
								?>)
								</p>
							</div>
						</div>
						<div class="col-md-3 col-lg-3">
							<div>
								<p class="mb-0 fs-7 fw-600 w-100">CheckIn - CheckOut</p>
								<p class="mb-0 fs-7 w-100"><?php echo formatdate($data['check_in'], 1);?> - <?php echo formatdate($data['check_out'], 1);?></p>
							</div> 
						</div>
						<div class="col-md-3 col-lg-2">
							<div>
								<p class="mb-0 fs-7 fw-600 w-100">Cost</p>
								<p class="mb-0 fs-7 w-100"><?php echo $currencysymbol.$data['amount'];?></p>
							</div>
						</div>
						<div class="col-md-1 col-lg-2 viewpast">
							<div class="d-flex justify-content-end">
								<a href="<?php echo base_url().'/myaccount/pastactivity/view/'.$data['id']; ?>" class="view-res">View</a>
							</div>
						</div>
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