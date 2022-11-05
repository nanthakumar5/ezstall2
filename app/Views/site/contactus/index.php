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
							<input type="text" name="name" class="form-control col-md-4 contact-input" placeholder="Enter name"/>
						</div>
						<div class="mb-4 col-md-8">
							<label class="form-label">Enter Email</label>
							<input type="email" name="email" class="form-control col-md-4 contact-input" placeholder="Enter Email"/>
						</div>
						<div class="mb-4 col-md-8">
							<label class="form-label">Enter Subject</label>
							<input	type="text" name="subject" class="form-control col-md-4 contact-input" placeholder="Enter Subject"
							/>
						</div>
						<div class="mb-4 col-md-8">
							<label class="form-label">Your Message</label>
							<textarea name="message" class="form-control col-md-4 contact-input" placeholder="Enter message here"></textarea>
						</div>
						<div class="mb-4 col-md-8">
							<button type="submit" class="contact-submit form-control">Send</button>
						</div>
					</form>
				</div>
				<div class="col-md-8">
					<p class="h2 fw-bold mb-4">Contact Information</p>
				<!-- 	<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d15719.342430819235!2d78.16783275!3d9.9476323!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin!4v1647852032607!5m2!1sen!2sin" width="100%" height="300px" style="border:0;" allowfullscreen="" loading="lazy"></iframe> -->
				    <div id="map" class="contact_map"></div>
					<div class="row mt-3 contact-info">
						<div class="col-md-4">
							<label class="font-w-600 form-label">Mobile Number</label>
							<div class="d-flex align-items-center">
							<i class="pr-2 fas fa-phone-alt"></i><?php echo $settings['phone'];?>
							</div>
						</div>
						<div class="col-md-4">
							<label class="font-w-600 form-label">Email</label>
							<div class="d-flex align-items-center">
							<i class="pr-2 fas fa-envelope"></i><?php echo $settings['email'];?>
							</div>
						</div>
						<div class="col-md-4">
							<label class="font-w-600 form-label">Address</label>
							<div class="d-flex align-items-center">
							<i class="pr-2 fas fa-map-marker-alt"></i> <?php echo $settings['address'];?>
							</div>
						</div>
					</div>
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
		
		initMap();
	});

	function initMap() {
		var map = new google.maps.Map(document.getElementById('map'), {
		  zoom: 15,
		});
		var geocoder = new google.maps.Geocoder();
		codeAddress(geocoder, map);
	}

	function codeAddress(geocoder, map) {
		geocoder.geocode({'address': address}, function(results, status) {
			if (status === 'OK') {
				map.setCenter(results[0].geometry.location);
				var marker = new google.maps.Marker({
					map: map,
					position: results[0].geometry.location
				});
			} else {
				alert('Geocode was not successful for the following reason: ' + status);
			}
		});
	}
</script>
<?php $this->endSection(); ?>