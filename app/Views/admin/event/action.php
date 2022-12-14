<?php $this->extend("admin/common/layout/layout2") ?>

<?php $this->section('content') ?>
<?php
$id 					= isset($result['id']) ? $result['id'] : '';
$userid 				= isset($result['user_id']) ? $result['user_id'] : '';
$name 					= isset($result['name']) ? $result['name'] : '';
$description 		    = isset($result['description']) ? $result['description'] : '';
$location 				= isset($result['location']) ? $result['location'] : '';
$city 					= isset($result['city']) ? $result['city'] : '';
$state 					= isset($result['state']) ? $result['state'] : '';
$zipcode 				= isset($result['zipcode']) ? $result['zipcode'] : '';
$latitude 				= isset($result['latitude']) ? $result['latitude'] : '';
$longitude 				= isset($result['longitude']) ? $result['longitude'] : '';
$mobile 				= isset($result['mobile']) ? $result['mobile'] : '';
$start_date 		    = isset($result['start_date']) ? dateformat($result['start_date']) : '';
$end_date 				= isset($result['end_date']) ? dateformat($result['end_date']) : '';
$start_time 			= isset($result['start_time']) ? $result['start_time'] : '';
$end_time 			    = isset($result['end_time']) ? $result['end_time'] : '';
$image      			= isset($result['image']) ? $result['image'] : '';
$image 				    = filedata($image, base_url().'/assets/uploads/event/');
$status 				= isset($result['status']) ? $result['status'] : '';
$eventflyer      		= isset($result['eventflyer']) ? $result['eventflyer'] : '';
$eventflyer 			= filedata($eventflyer, base_url().'/assets/uploads/eventflyer/');
$stallmap      			= isset($result['stallmap']) ? $result['stallmap'] : '';
$stallmap 				= filedata($stallmap, base_url().'/assets/uploads/stallmap/');
$pageaction 			= $id=='' ? 'Add' : 'Update';
?>
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1>Events</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
					<li class="breadcrumb-item"><a href="<?php echo getAdminUrl(); ?>/event">Events</a></li>
					<li class="breadcrumb-item active"><?php echo $pageaction; ?> Event</li>
				</ol>
			</div>
		</div>
	</div>
</section>

<section class="content">
	<div class="page-action">
		<a href="<?php echo getAdminUrl(); ?>/event" class="btn btn-primary">Back</a>
	</div>
	<div class="card">
		<div class="card-header">
			<h3 class="card-title"><?php echo $pageaction; ?> Event</h3>
		</div>
		<div class="card-body">
			<form method="post" id="form" action="<?php echo getAdminUrl(); ?>/<?php echo $usertype==2 ? 'facilityevent' : 'producerevent'; ?>/action" autocomplete="off">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>User</label>					
								<?php echo form_dropdown('userid', $userlist, $userid, ['id' => 'userid', 'class' => 'form-control']); ?>
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Name</label>								
								<input type="text" name="name" class="form-control" id="name" placeholder="Enter Name" value="<?php echo $name; ?>">
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>Event Description</label>
								<textarea class="form-control" id="description" name="description" placeholder="Enter Description" rows="3"><?php echo $description;?></textarea>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Street</label>								
								<input type="text" name="location" class="form-control" id="location" placeholder="Enter Street" value="<?php echo $location; ?>">
							</div>
						</div>
						<div class="col-md-6">
	                    	<div class="form-group">
		                        <label>City</label>                        
		                        <input type="text" name="city" class="form-control" id="city" placeholder="Enter City" value="<?php echo $city; ?>">
		                        <input type="hidden" name="latitude" id="latitude" value="<?php echo $latitude; ?>">
		                        <input type="hidden" name="longitude" id="longitude" value="<?php echo $longitude; ?>">
		                    </div>
	                    </div>
		                <div class="col-md-6">
		                    <div class="form-group">
		                        <label>State</label>                      
		                        <input type="text" name="state" class="form-control" id="state" placeholder="Enter State" value="<?php echo $state; ?>">
		                    </div>
		                </div>
						<div class="col-md-6">
							<div class="form-group">
							    <label>Zip Code</label>                      
							    <input type="text" name="zipcode" class="form-control" id="zipcode" placeholder="Enter Zip Code" value="<?php echo $zipcode; ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Mobile</label>								
								<input type="text" name="mobile" class="form-control mobile" id="mobile" placeholder="Enter Mobile" value="<?php echo $mobile; ?>">								
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Start Date</label>	
								<input type="text" class="form-control" name="start_date" value="<?php echo $start_date;?>" id="start_date">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>End Date</label>	
								<input type="text" class="form-control" name="end_date" value="<?php echo $end_date;?>" id="end_date">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Start Time</label>	
								<input type="time" class="form-control" name="start_time" value="<?php echo $start_time;?>" id="start_time">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>End Time</label>	
								<input type="time" class="form-control" name="end_time" value="<?php echo $end_time;?>" id="end_time">
							</div>
						</div>
						<!-- <div class="col-md-6">
							<div class="form-group">
								<label>Status</label>								
								<?php echo form_dropdown('status', ['' => 'Select Status']+$statuslist, $status, ['id' => 'status', 'class' => 'form-control']); ?>
							</div>
						</div> -->
						<div class="col-md-4">
							<div class="form-group">
								<label>Upload Event Image</label>			
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
						<div class="col-md-4">
							<div class="form-group">
								<label>Upload Event Flyer</label>			
								<div>
									<a href="<?php echo $eventflyer[1];?>" target="_blank">
										<img src="<?php echo $eventflyer[1];?>" class="eventflyer_source" width="100">
									</a>
								</div>
								<input type="file" class="eventflyer_file">
								<span class="eventflyer_msg messagenotify"></span>
								<input type="hidden" id="eventflyer" name="eventflyer" class="eventflyer_input" value="<?php echo $eventflyer[0];?>">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Upload Stall Map (optional)</label>			
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
						<?php echo $barnstall; ?>
					</div>
					<div class="col-md-12 mt-4">
						<input type="hidden" name="actionid" value="<?php echo $id; ?>">
						<input type="hidden" name="status" value="1">
						<input type="hidden" name="type" value="">
						<input type="submit" id ="eventSubmit" class="btn btn-danger" value="Submit">
						<a href="<?php echo getAdminUrl(); ?>/event" class="btn btn-dark">Back</a>
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
		$('#mobile').inputmask("(999) 999-9999");
		dateformat('#start_date, #end_date');
		fileupload([".image_file"], ['.image_input', '.image_source','.image_msg']);
		fileupload([".eventflyer_file", ['jpg','jpeg','png','gif','tiff','tif','pdf']], ['.eventflyer_input', '.eventflyer_source','.eventflyer_msg']);
		fileupload([".stallmap_file", ['jpg','jpeg','png','gif','tiff','tif','pdf']], ['.stallmap_input', '.stallmap_source','.stallmap_msg']);
		fileupload([".stall_file"], ['.stall_input', '.stall_source','.stall_msg']);
		
		validation(
			'#form',
			{
				name 	     : {
					required	: 	true
				},
				description  : {	
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
				mobile       : {
					required	: 	true
				},
				start_date   : {
					required	: 	true
				},
				end_date     : {
					required	: 	true
				},
				start_time   : {
					required	: 	true
				},
				end_time     : {
					required	: 	true
				},
				status        : {  
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
	
	
	$('#eventSubmit').click(function(e){
		tabvalidation();
	});
	
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

	document.getElementById("location").addEventListener('keyup', debounce( () => {
		getCoordinates(document.getElementById("location").value);
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