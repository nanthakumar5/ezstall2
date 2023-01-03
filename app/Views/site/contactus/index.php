<?= $this->extend("site/common/layout/layout1") ?>
<?php $this->section('content') ?>
<style>
      #map {
        height: 300px;
        width: 100%;
      }
</style>
<section class="maxWidth">
	<div class="pageInfo">
		<span class="marFive">
			<a href="<?php echo base_url(); ?>">Home /</a>
			<a href="javascript:void(0);"> Contact Us</a>
		</span>
	</div>
	<section>
		<div class="my-5 maxWidth marFive">
			<div class="row mx-auto">
				<div class="p-0 col-md-4">
					<p class="h2 fw-bold mb-4">Get In Touch</p>
					<form method="post" action="" id="form" autocomplete="off">
						<div class="mb-4 col-md-8">
							<label class="form-label">Enter Name</label>
							<input type="text" name="name" class="form-control col-md-12 contact-input contact_frm" placeholder="Enter name"/>
						</div>
						<div class="mb-4 col-md-8">
							<label class="form-label">Enter Email</label>
							<input type="email" name="email" class="form-control col-md-4 contact-input contact_frm" placeholder="Enter Email"/>
						</div>
						<div class="mb-4 col-md-8">
							<label class="form-label">Enter Subject</label>
							<input	type="text" name="subject" class="form-control col-md-4 contact-input contact_frm" placeholder="Enter Subject"
							/>
						</div>
						<div class="mb-4 col-md-8">
							<label class="form-label">Your Message</label>
							<textarea name="message" class="form-control col-md-4 contact-input contact_frm" placeholder="Enter message here"></textarea>
						</div>
						<div class="mb-4 col-md-8">
							<button type="submit" class="contact-submit form-control">Send</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
</section>
<?php $this->endSection(); ?>
<?php $this->section('js') ?>
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $googleapikey; ?>"></script>
<script>
  	var address = "<?php echo $settings['address'];?>";
	
	$(function(){
		validation(
			'#form',
			{
				name 	     : {
					required	: 	true
				},
				email  		: {	
					required	: 	true,
					email     	: true   
				},
				subject   	: {
					required	: 	true
				},
				message 	 : {
					required	: 	true
				}
			}
		);
	});

</script>
<?php $this->endSection(); ?>