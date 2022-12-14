<?php $this->extend('site/common/layout/layout1') ?>
<?php $this->section('content') ?>
<?php
$userid 		= getSiteUserID() ? getSiteUserID() : 0;
$currentdate 	= date("m-d-Y");
$getcart 	 	= getCart('2');
$cartevent 	 	= ($getcart && $getcart['event_id'] != $detail['id']) ? 1 : 0;
$name 		 	= $detail['name'];
$description 	= $detail['description'];
$image 		 	= base_url().'/assets/uploads/event/'.'1200x600_'.$detail['image'];
$profileimage 	= isset($detail['profile_image']) ? $detail['profile_image'] : '';
$profileimage 	= ($profileimage!="") ? base_url().'/assets/uploads/profile/'.$detail['profile_image'] : base_url().'/assets/images/profile.jpg';
?>
<!--1200x600_-->

<?php if($cartevent==1){?>
	<div class="alert alert-success alert-dismissible fade show m-2" role="alert">
		For booking this stall remove other stalls from the cart <a href="<?php echo base_url().'/facility/detail/'.$getcart['event_id']; ?>">Go To Facility</a>
		<!--<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">X</button>-->
	</div>
<?php } ?>

<div class="container-lg"> 
	<div class="row">
		<div class="stalldetail-banner mt-4 mb-5">
			<img src="<?php echo $image; ?>">
		</div>
	</div>
</div>

<section class="container-lg">
	<div class="row">
		<div class="col-lg-8">
			<div class="stall-head">
				<div class="float-start">
					<img class="profile_pic" src="<?php echo $profileimage; ?>">
				</div>
				<div class="float-next">
					<h4 class="fw-bold"><?php echo $name; ?></h4>
				</div>
			</div>
			<?php echo ucfirst($description);?>
			<?php if($detail['stallmap']!=""){ ?>
				<button class="ucEventdetBtn"><a href="<?php echo base_url();?>/facility/download/<?php echo $detail['stallmap'] ?>" class="text-decoration-none text-white"><img src="<?php echo base_url() ?>/assets/site/img/flyer.png"> Download Stall Map</a></button>
			<?php } ?>
		</div>
		<div class="row m-0 p-0">
			<div class="col-md-9">
				<?php echo $barnstall; ?>
			</div> 
			<div class="sticky-top checkout_wrapper col-md-3 mt-4 h-100"></div>
		</div>
	</div>
</section>
<?php $this->endSection() ?>