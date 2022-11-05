<?php $this->extend('site/common/layout/layout1') ?>
<?php $this->section('content'); ?>
<?php
	$bookingid          = isset($result['id']) ? $result['id'] : '';
	$firstname          = isset($result['firstname']) ? $result['firstname'] : '';
	$lastname           = isset($result['lastname']) ? $result['lastname'] : '';
	$mobile             = isset($result['mobile']) ? $result['mobile'] : '';
	$eventname          = isset($result['eventname']) ? $result['eventname'] : '';
	$stall              = isset($result['stall']) ? $result['stall'] : '';
	$checkin            = isset($result['check_in']) ? formatdate($result['check_in'], 1) : '';
	$checkout           = isset($result['check_out']) ? formatdate($result['check_out'], 1) : '';
	$createdat       	  = isset($result['created_at']) ? formatdate($result['created_at'], 2) : '';
	$barnstalls         = isset($result['barnstall']) ? $result['barnstall'] : '';
	$rvbarnstalls       = isset($result['rvbarnstall']) ? $result['rvbarnstall'] : '';
	$feed               = isset($result['feed']) ? $result['feed'] : '';
	$shaving            = isset($result['shaving']) ? $result['shaving'] : '';
	$paymentmethod      = isset($result['paymentmethod_name']) ? $result['paymentmethod_name'] : '';

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
	<div class="row col-md-10 base-style">
		<div class="col fw-600">
			<p class="my-2">Booking ID</p>
		</div>
		<div class="col" align="left">
			<p class="my-2"><?php echo $bookingid;?></p>
		</div>
	</div>
	<div class="row col-md-10 base-style">
		<div class="col fw-600">
			<p class="my-2">First Name</p>
		</div>
		<div class="col" align="left">
			<p class="my-2"><?php echo $firstname;?></p>
		</div>
	</div>
	<div class="row col-md-10 base-style">
		<div class="col fw-600">
			<p class="my-2">Last Name</p>
		</div>
		<div class="col" align="left">
			<p class="my-2"><?php echo $lastname;?></p>
		</div>
	</div>
	<div class="row col-md-10 base-style">
		<div class="col fw-600">
			<p class="my-2">Mobile</p>
		</div>
		<div class="col" align="left">
			<p class="my-2"><?php echo $mobile;?></p>
		</div>
	</div>
	<div class="row col-md-10 base-style">
		<div class="col fw-600">
			<p class="my-2">Event Name</p>
		</div>
		<div class="col" align="left">
			<p class="my-2"><?php echo $eventname;?></p>
		</div>
	</div>
	<div class="row col-md-10 base-style">
		<div class="col fw-600">
			<p class="my-2">Barn&Stall Name</p>
		</div>
		<div class="col" align="left">
			<?php 
			$barnname = '';
			foreach ($barnstalls as $barnstall) {
				if($barnname!=$barnstall['barnname']){
					$barnname = $barnstall['barnname'];
					echo ' <p class="my-2">'.$barnstall['barnname'].'</p>';
				}
				echo ' <p class="my-2">'.$barnstall['stallname'].'</p>';
			} ?>
		</div>
	</div>
	<div class="row col-md-10 base-style">
		<div class="col fw-600">
			<p class="my-2">Rv&Stall Name</p>
		</div>
		<div class="col" align="left">
			<?php 
			$rvbarnname = '';
			foreach ($rvbarnstalls as $rvbarnstall) {
				if($rvbarnname!=$rvbarnstall['barnname']){
					$rvbarnname = $rvbarnstall['barnname'];
					echo ' <p class="my-2">'.$rvbarnstall['barnname'].'</p>';
				}
				echo ' <p class="my-2">'.$rvbarnstall['stallname'].'</p>';
			} ?>
		</div>
	</div>
	<?php if(!empty($feed)){ ?> 
    	<div class="row col-md-10 base-style">
    		<div class="col fw-600">
    			<p class="my-2">Feed Name</p>
    		</div>
    		<div class="col" align="left">
    			<?php foreach ($feed as $feed) {
    				echo ' <p class="my-2">'.$feed['productname'].'</p>';
    			} ?>
    		</div>
    	</div>
    <?php } ?>
    <?php if(!empty($shaving)){ ?> 
    	<div class="row col-md-10 base-style">
    		<div class="col fw-600">
    			<p class="my-2">Shaving Name</p>
    		</div>
    		<div class="col" align="left">
    			<?php foreach ($shaving as $shaving) {
    				echo ' <p class="my-2">'.$shaving['productname'].'</p>';
    			} ?>
    		</div>
    	</div>
    <?php } ?>
	<div class="row col-md-10 base-style">
		<div class="col fw-600">
			<p class="my-2">Check In</p>
		</div>
		<div class="col" align="left">
			<p class="my-2"><?php echo $checkin;?></p>
		</div>
	</div>
	<div class="row col-md-10 base-style">
		<div class="col fw-600">
			<p class="my-2">Check Out</p>
		</div>
		<div class="col" align="left">
			<p class="my-2"><?php echo $checkout;?></p>
		</div>
	</div>
	<div class="row col-md-10 base-style">
		<div class="col fw-600">
			<p class="my-2">Booked By</p>
		</div>
		<div class="col" align="left">
			<p class="my-2"><?php echo $usertype[$result['usertype']];?></p>
		</div>
	</div>
	<div class="row col-md-10 base-style">
		<div class="col fw-600">
			<p class="my-2">Date of Booking</p>
		</div>
		<div class="col" align="left">
			<p class="my-2"><?php echo $createdat; ?></p>
		</div>
	</div>
	<div class="row col-md-10 base-style">
		<div class="col fw-600">
			<p class="my-2">Payment Method</p>
		</div>
		<div class="col" align="left">
			<p class="my-2"><?php echo $paymentmethod;?></p>
		</div>
	</div>
</section>
<?php $this->endSection(); ?>