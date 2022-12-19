<?= $this->extend("site/common/layout/layout1") ?>
<?php $this->section('content') ?>
<section class="maxWidth">
	<div class="pageInfo">
		<span class="marFive">
			<a href="<?php echo base_url(); ?>">Home</a>/
			<a href="javascript:void(0);">About Us</a>
		</span>
	</div>
	<div class="container px-5 my-5">
		<div class="accordion" id="accordionFlush">
			<?php foreach ($result as $key => $val) { ?>
				<div class="accordion-item">
					<h2 class="accordion-header">
						<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne<?php echo $key; ?>" aria-expanded="false" aria-controls="flush-collapseOne"><?php echo $val['title'];?>
						</button>
					</h2>
					<div id="flush-collapseOne<?php echo $key; ?>" class="accordion-collapse collapse" data-bs-parent="#accordionFlush">
						<div class="accordion-body"><?php echo $val['content'];?></div>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
	<div class="container px-5 my-5 col-md-6 text-center">
		<p>If you still have questions, please contact us. Send us a message and we will answer your valuable questions.</p>
		<a class="faq-c-link" href="<?php echo base_url() ?>/contactus">Contact Us</a>
	</div>
</section>
<?php $this->endSection(); ?>
