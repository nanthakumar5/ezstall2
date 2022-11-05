<?php $this->extend("site/common/layout/layout1") ?>
<?php $this->section('content') ?>
<section class="maxWidth">
	<div class="pageInfo">
		<span class="marFive">
			<a href="<?php echo base_url(); ?>">Home /</a>
			<a href="javascript:void(0);">About Us</a>
		</span>
	</div>
	<?php foreach ($aboutus as $key => $aboutus) { if($key%2==0){?>
		<div class="about-banner text-center my-5">
			<p class="mb-2 h3 fw-bold"><?php echo $aboutus['title'];?></p>
		</div>
		<div class="wi-1200">
			<div class="row d-flex justify-content-between">
				<div class="col-md-5 beforeRound">
					<img class="about-img" src="<?php echo base_url().'/assets/uploads/aboutus/'.'486x300_'.$aboutus['image']?>" />
				</div>
				<div class="col-md-6 afterHorse">
					<p class="commonContent mt-0">
						<?php echo substr($aboutus['content'], 0, 250);?>
					</p>
					<button class="greyButton"><a class="text-white text-decoration-none" href="<?php echo base_url().'/aboutus/detail/'.$aboutus['id']?>">Read More</a></button>
				</div>
			</div>
		</div>
	<?php } else{ ?>
		<div class="about-banner text-center my-5">
			<p class="mb-2 h3 fw-bold"><?php echo $aboutus['title'];?></p>
		</div>

		<div class="wi-1200">
			<div class="row d-flex justify-content-between">
				<div class="col-md-5 beforeRound">
					<p class="commonContent mt-0">
						<?php echo $aboutus['content'];?>
					</p>
					<button class="greyButton"><a class="text-white text-decoration-none" href="<?php echo base_url().'/aboutus/detail/'.$aboutus['id']?>">Read More</a></button>
				</div>
				<div class="col-md-6 afterHorse">
					<img class="about-img" src="<?php echo base_url().'/assets/uploads/aboutus/'.'486x300_'.$aboutus['image']?>" />

				</div>
			</div>
		</div>
	<?php } } ?>

</section>
<?php $this->endSection(); ?>
