<?php $this->extend("site/common/layout/layout1") ?>
<?php $this->section('content') ?>
<section class="maxWidth">
	<div class="pageInfo">
		<span class="marFive">
			<a href="<?php echo base_url(); ?>">Home /</a>
			<a href="javascript:void(0);">Privacy Policy</a>
		</span>
	</div>
	<div class="about-banner text-center my-5">
		<p class="mb-2 h3 fw-bold"><?php echo $result['title'];?></p>
		<p class="col-md-6 mx-auto"></p>
	</div>

	<div class="wi-1200">
		<div class="row d-flex justify-content-between">
			<div class="col-md-12 afterHorse">
				<p class="commonContent mt-0">
					<?php echo $result['content'];?>
				</p>
			</div>
		</div>
	</div>
</section>
<?php $this->endSection(); ?>
