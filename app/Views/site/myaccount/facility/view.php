<?php $this->extend('site/common/layout/layout1') ?>
<?php $this->section('content') ?>

<div class="page-action mb-3 mt-2 m-0" align="right">
	<a href="<?php echo base_url(); ?>/myaccount/facility" class="btn btn-dark">Back</a>
</div>
<section class="container-lg">
	<div class="row">
		<div class="col-12">
			<div class="border rounded pt-3 ps-3 pe-3">
				<div class="row">
					<div class="col-md-12">
						<img src="<?php echo base_url() ?>/assets/uploads/event/<?php echo $detail['image']?>" width="100%" height="auto" class="rounded">
					</div>
					<div class="col-md-12 mt-3">
						<h4 class="checkout-fw-6"><?php echo $detail['name'] ?></h4>
						<p><?php echo $detail['description'] ?></p>
					</div>
				</div>
			</div>
			<?php echo $barnstall; ?>
		</div>
	</div>
</section>
<?php $this->endSection() ?>