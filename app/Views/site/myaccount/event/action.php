<?php $this->extend("site/common/layout/layout1") ?>

<?php $this->section('content') ?>
<?php
$id 					= isset($result['id']) ? $result['id'] : '';
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

$facilityid				= isset($result['facility_id']) ? $result['facility_id'] : '';
$facilitylist			= isset($facilitylist) ? $facilitylist : '';
?>

<section class="content">
	<div class="d-flex justify-content-between align-items-center flex-wrap">
		<div align="left" class="m-0"><h3>Events</h3></div>
		<div class="page-action mb-4 m-0" align="right">
			<a href="<?php echo base_url(); ?>/myaccount/events" class="btn btn-dark">Back</a>
		</div>
	</div>
	<div class="card">
		<div class="card-header w-100">
			<h3 class="card-title"><?php echo $pageaction; ?> Event</h3>
		</div>
		<div class="card-body">
			<form method="post" id="form" action="" autocomplete="off">
				<input type="hidden" id="id" name="id" value="<?php echo $id;?>" >
				<div class="col-md-12">
					<div class="row">
						<?php if($facilitylist!=''){ ?>
							<div class="col-md-12 my-2">
								<div class="form-group">
									<label>Facility</label>		
									<?php echo form_dropdown('facility_id', ['' => 'Select Facility']+$facilitylist, $facilityid, ['class' => 'form-control facilityid']); ?>									
								</div>
							</div>
						<?php } ?>
						<div class="col-md-12 eventwrapper">
							<div class="row">
								<div class="col-md-12 my-2">
									<div class="form-group">
										<label>Name</label>								
										<input type="text" name="name" class="form-control" id="name" placeholder="Enter Name" value="<?php echo $name; ?>">
									</div>
								</div>
								<div class="col-md-12 my-2">
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
								<div class="col-md-6 my-2">
									<div class="form-group">
										<label>Mobile</label>								
										<input type="text" name="mobile" class="form-control mobile" id="mobile" placeholder="Enter Mobile" value="<?php echo $mobile; ?>">								
									</div>
								</div>
								<div class="col-md-6 my-2">
									<div class="form-group">
										<label>Start Date</label>	
										<input type="text" class="form-control" name="start_date" value="<?php echo $start_date;?>" id="start_date">
									</div>
								</div>
								<div class="col-md-6 my-2">
									<div class="form-group">
										<label>End Date</label>	
										<input type="text" class="form-control" name="end_date" value="<?php echo $end_date;?>" id="end_date">
									</div>
								</div>
								<div class="col-md-6 my-2">
									<div class="form-group">
										<label>Start Time</label>	
										<input type="time" class="form-control" name="start_time" value="<?php echo $start_time;?>" id="start_time">
									</div>
								</div>
								<div class="col-md-6 my-2">
									<div class="form-group">
										<label>End Time</label>	
										<input type="time" class="form-control" name="end_time" value="<?php echo $end_time;?>" id="end_time">
									</div>
								</div>
								<div class="col-md-12 my-2">
									<div class="form-group">
										<label>Event Description</label>
										<textarea class="form-control" id="description" name="description" placeholder="Enter Description" rows="3"><?php echo $description;?></textarea>
									</div>
								</div>
								<div class="col-md-4 my-2">
									<div class="form-group">
										<label>Event Image</label>			
										<div>
											<a href="<?php echo $image[1];?>" target="_blank">
												<img src="<?php echo $image[1];?>" class="image_source" width="100">
											</a>
										</div>
										<input type="file" id="file" name="file" class="image_file">
										<span class="image_msg messagenotify"></span>
										<input type="hidden" id="image" name="image" class="image_input" value="<?php echo $image[0];?>">
									</div>
								</div>							
								<div class="col-md-4 my-2">
									<div class="form-group">
										<label>Event Flyer</label>			
										<div>
											<a href="<?php echo $eventflyer[1];?>" target="_blank">
												<img src="<?php echo $eventflyer[1];?>" class="eventflyer_source" width="100">
											</a>
										</div>
										<input type="file" id="" name="" class="eventflyer_file">
										<span class="eventflyer_msg messagenotify"></span>
										<input type="hidden" id="eventflyer" name="eventflyer" class="eventflyer_input" value="<?php echo $eventflyer[0];?>">
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
						</div>
					</div>
					<div class="eventwrapper">
						<?php echo isset($barnstall) ? $barnstall : '<div class="facilitybarnstall"></div>'; ?>
						<div class="col-md-12 mt-4">
							<input type="hidden" name="actionid" value="<?php echo $id; ?>">
							<input type="hidden" name="userid" value="<?php echo $userid; ?>">
							<input type="submit" id ="eventSubmit" class="btn btn-danger" value="Submit">
							<a href="<?php echo base_url(); ?>/myaccount/events" class="btn btn-dark">Back</a>
						</div>
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
		uidatepicker("#start_date, #end_date");
		fileupload([".image_file"], ['.image_input', '.image_source','.image_msg'],['1']);
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

<script> 
	if($('.facilityid').length){
		var id = '<?php echo $id; ?>';
		var userid = '<?php echo $userid; ?>';
		var facility_id = '<?php echo $facilityid; ?>';
		
		$(function(){
			if(id!=''){
				$('.facilityid').attr('disabled', 'disabled'); 
				$('.facilityid').parent().append('<input type="hidden" name="facility_id" value="'+facility_id+'">'); 
			}
			
			facility($('.facilityid').val());
			checkblockunblock();
		})

		$('.facilityid').change(function(){
			facility($(this).val());
		})

		function facility(facilityid=''){
			$('.facilitybarnstall').html('');
			
			if(facilityid!=''){
				$('.eventwrapper').removeClass('displaynone');
				
				ajax(
					'<?php echo base_url()."/ajax/barnstall1"; ?>',
					{ eventid : id, facilityid: facilityid, userid : userid },
					{
						datatype: 'html',
						asynchronous : 1,
						success : function(data){
							$('.facilitybarnstall').html(data);
						}
					}
				)
			}else{
				$('.eventwrapper').addClass('displaynone');
			}
		}
		
		$("#start_date, #end_date").change(function(){
			facility($('.facilityid').val());
			checkblockunblock();
		})
		
		function checkblockunblock(){
			setTimeout(function(){
				var id 			= '<?php echo $id; ?>';
				var startdate 	= $("#start_date").val(); 
				var enddate   	= $("#end_date").val(); 

				if(startdate!='' && enddate!=''){
					ajax(
						'<?php echo base_url()."/ajax/ajaxblockunblock"; ?>',
						{ eventid : $('.facilityid').val(), checkin : startdate, checkout : enddate, nqeventid : id, type : 2 },
						{
							asynchronous : 1,
							success : function(data){
								$(data.success).each(function(i,v){
									$(document).find('.block_unblock[data-stallid='+v+']').prop('checked', true).attr('disabled', 'disabled');
								});
							}
						}
					)
				}
			}, 100);
		}
	}
</script>
<?php $this->endSection(); ?>
