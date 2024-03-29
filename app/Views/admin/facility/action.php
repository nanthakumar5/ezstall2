<?= $this->extend("admin/common/layout/layout2") ?>

<?php $this->section('content') ?>
<?php
$id 					= isset($result['id']) ? $result['id'] : '';
$userid  				= isset($result['user_id']) ? $result['user_id'] : '';
$name 					= isset($result['name']) ? $result['name'] : '';
$location 				= isset($result['location']) ? $result['location'] : '';
$city 					= isset($result['city']) ? $result['city'] : '';
$state 					= isset($result['state']) ? $result['state'] : '';
$zipcode 				= isset($result['zipcode']) ? $result['zipcode'] : '';
$latitude 				= isset($result['latitude']) ? $result['latitude'] : '';
$longitude 				= isset($result['longitude']) ? $result['longitude'] : '';
$description 		    = isset($result['description']) ? $result['description'] : '';
$image      			= isset($result['image']) ? $result['image'] : '';
$image 				    = filedata($image, base_url().'/assets/uploads/event/');
$profileimage      		= isset($result['profile_image']) ? $result['profile_image'] : '';
$profileimage 			= filedata($profileimage, base_url().'/assets/uploads/profile/');
$stallmap      			= isset($result['stallmap']) ? $result['stallmap'] : '';
$stallmap 				= filedata($stallmap, base_url().'/assets/uploads/stallmap/');
$pageaction 			= $id=='' ? 'Add' : 'Update';
?>
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1>Facility</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
					<li class="breadcrumb-item"><a href="<?php echo getAdminUrl(); ?>/facility">Facility</a></li>
					<li class="breadcrumb-item active"><?php echo $pageaction; ?> Facility</li>
				</ol>		
			</div>
		</div>
	</div>
</section>
<section class="content">
	<div class="page-action">
		<a href="<?php echo getAdminUrl(); ?>/facility" class="btn btn-primary">Back</a>
	</div>
	<div class="card">
		<div class="card-header w-100">
			<h3 class="card-title"><?php echo $pageaction; ?> Facility</h3>
		</div>
		<div class="card-body">
			<form method="post" id="form" action="" autocomplete="off">
				<input type="hidden" id="id" name="id" value="<?php echo $id;?>" >
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-12 my-2">
							<div class="form-group">
								<label>Facility Users</label>								
								<?php echo form_dropdown('userid', getUsersList(['type'=>['2']]), $userid, ['id' => 'userid', 'class' => 'form-control']); ?>
							</div>
						</div>
						<div class="col-md-12 my-2">
							<div class="form-group">
								<label>Name</label>								
								<input type="text" name="name" class="form-control" id="name" placeholder="Enter Name" value="<?php echo $name; ?>">
							</div>
						</div>
						<div class="col-md-6 my-2">
							<div class="form-group">
								<label>Street</label>								
								<input type="text" name="location" class="form-control" id="location" placeholder="Enter Location" value="<?php echo $location; ?>">
							</div>
						</div>
						<div class="col-md-6 my-2">
							<div class="form-group">
								<label>City</label>                        
								<input type="text" name="city" class="form-control" id="city" placeholder="Enter City" value="<?php echo $city; ?>">
								<input type="hidden" name="latitude" id="latitude" value="<?php echo $latitude; ?>">
								<input type="hidden" name="longitude" id="longitude" value="<?php echo $longitude; ?>">
							</div>
						</div>
						<div class="col-md-6 my-2">
							<div class="form-group">
								<label>State</label>                      
								<input type="text" name="state" class="form-control" id="state" placeholder="Enter State" value="<?php echo $state; ?>">
							</div>
						</div>
						<div class="col-md-6 my-2">
							<div class="form-group">
								<label>Zip Code</label>                      
								<input type="text" name="zipcode" class="form-control" id="zipcode" placeholder="Enter Zip Code" value="<?php echo $zipcode; ?>">
							</div>
						</div>
						<div class="col-md-12 my-2">
						    <div class="form-group">
								<label>Stall Description</label>
								<textarea class="form-control" id="description" name="description" placeholder="Enter Description" rows="3"><?php echo $description;?></textarea>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Upload Facility Image</label>			
								<div>
									<a href="<?php echo $image[1];?>" target="_blank">
										<img src="<?php echo $image[1];?>" class="image_source" width="100">
									</a>
								</div>
								<input type="file" class="image_file">
								<span class="image_msg messagenotify"></span>
								<input type="hidden" id="image" name="image" class="image_input" value="<?php echo $image[0];?>">
							</div>
						</div>
						<div class="col-md-4 my-2">
							<div class="form-group">
								<label>Profile Image</label>			
								<div>
									<a href="<?php echo $profileimage[1];?>" target="_blank">
										<img src="<?php echo $profileimage[1];?>" class="profileimage_source" width="100">
									</a>
								</div>
								<input type="file" id="" name="" class="profileimage_file">
								<span class="profileimage_msg messagenotify"></span>
								<input type="hidden" id="profile_image" name="profile_image" class="profileimage_input" value="<?php echo $profileimage[0];?>">
							</div>
						</div>
						<div class="col-md-4 my-2">
							<div class="form-group">
								<label>Stall Map (optional)</label>			
								<div>
									<a href="<?php echo $stallmap[1];?>" target="_blank">
										<img src="<?php echo $stallmap[1];?>" class="stallmap_source" width="100">
									</a>
								</div>
								<input type="file" class="stallmap_file">
								<span class="stallmap_msg messagenotify"></span>
								<input type="hidden" id="stallmap" name="stallmap" class="stallmap_input" value="<?php echo $stallmap[0];?>">
							</div>
						</div>		
					</div>
					<?php echo $barnstall; ?>
					<div class="col-md-12 mt-4">
						<input type="hidden" name="actionid" value="<?php echo $id; ?>">
						<input type="hidden" name="type" value="2">
						<button class="btn btn-danger facilitypayment"  type="submit">Submit</button>
						<a href="<?php echo getAdminUrl(); ?>/facility" class="btn btn-dark">Back</a>
					</div>
				</div>
			</form>
		</div>
	</div>
</section>
<?php $this->endSection(); ?>
<?php $this->section('js') ?>
<script>
	$(function(){
		editor('#description');
		fileupload([".image_file"], ['.image_input', '.image_source','.image_msg']);
		fileupload([".profileimage_file"], ['.profileimage_input', '.profileimage_source','.profileimage_msg']);
		fileupload([".stallmap_file", ['jpg','jpeg','png','gif','tiff','tif','pdf']], ['.stallmap_input', '.stallmap_source','.stallmap_msg']);
		fileupload([".stall_file"], ['.stall_input', '.stall_source','.stall_msg']);

		validation(
			'#form',
			{
				name 	        : {
					required	: 	true
				},
				location 	 : {
					required	: 	true
				},
				city 	 : {
					required	: 	true
				},
				state 	 : {
					required	: 	true
				},
				zipcode 	 : {
					required	: 	true
				},	
				barnvalidation : {
					required 	: true
				}
			},
			{},
			{
				ignore : []
			}
		);
	});

    $('.facilitypayment').click(function(){ 
		tabvalidation();
		
		if($('#form').valid()){
			if($(document).find('.stall_id[value=""]').length==0){
				$('#form').submit();
			}else{
				$('#stripeFormModal').modal('show');
			}
		}
	})

	$('#stripeFormModal').on('shown.bs.modal', function () { 
		var eventdata = [];
		var formdata = $('#form').serializeArray();
		$.each(formdata, function(i, field){
			if(field.name=='description'){ field.value = tinymce.get("description").getContent(); }
			eventdata.push('<input type="hidden" name="'+field.name+'" value="'+field.value+'">')
		});
		
		$('.stripeextra').remove();
		var price = $(document).find('.stall_id[value=""]').length * parseFloat(stallpercost);
		var data = 	'<div class="stripeextra"><input type="hidden" value="'+price+'" name="price">'+eventdata.join("")+'</div>';
		$('.stripetotal').text('(Total - '+currencysymbol+price+')');

		$('.stripepaybutton').append(data);
	})

	function tabvalidation(){
		$(document).find('.requiredtab').remove();	
		
		setTimeout(function(){
			$(document).find('.dash-stall-base').each(function(){;
				if($(this).find('input.error_class_1').length){
					var tabid = $(this).parent().attr('id');
					$(document).find('a[data-bs-target="#'+tabid+'"] input').after('<span class="requiredtab">*</span>');
				}
			})
		}, 100);
	}
	
	function debounce(callback, wait) {
		let timeout;
		return (args) => {
			clearTimeout(timeout);
			timeout = setTimeout(function () { callback.apply(this, args); }, wait);
		};
	}

	document.getElementById("city").addEventListener('keyup', debounce( () => {
		getCoordinates(document.getElementById("city").value);
	}, 1000))

	function getCoordinates(address){
		fetch("https://maps.googleapis.com/maps/api/geocode/json?address="+address+"&key=<?php echo $googleapikey; ?>")
		.then(response => response.json())
		.then(data => {
			if(data.status=="OK"){
				const latitude = data.results[0].geometry.location.lat;
				const longitude = data.results[0].geometry.location.lng;
				$('#latitude').val(latitude);
				$('#longitude').val(longitude);
			}
		})
	}
</script>
<?php $this->endSection(); ?>