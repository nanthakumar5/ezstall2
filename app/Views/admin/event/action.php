<?= $this->extend("admin/common/layout/layout2") ?>

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
$stalls_price 			= isset($result['stalls_price']) ? $result['stalls_price'] : '';
$rvspots_price 			= isset($result['rvspots_price']) ? $result['rvspots_price'] : '';
$image      			= isset($result['image']) ? $result['image'] : '';
$image 				    = filedata($image, base_url().'/assets/uploads/event/');
$status 				= isset($result['status']) ? $result['status'] : '';
$eventflyer      		= isset($result['eventflyer']) ? $result['eventflyer'] : '';
$eventflyer 			= filedata($eventflyer, base_url().'/assets/uploads/eventflyer/');
$stallmap      			= isset($result['stallmap']) ? $result['stallmap'] : '';
$stallmap 				= filedata($stallmap, base_url().'/assets/uploads/stallmap/');
$feed_flag 				= isset($result['feed_flag']) ? $result['feed_flag'] : '';
$shaving_flag 			= isset($result['shaving_flag']) ? $result['shaving_flag'] : '';
$rv_flag 				= isset($result['rv_flag']) ? $result['rv_flag'] : '';
$cleaning_flag 			= isset($result['cleaning_flag']) ? $result['cleaning_flag'] : '';
$charging_flag 			= isset($result['charging_flag']) ? $result['charging_flag'] : '';
$notification_flag 		= isset($result['notification_flag']) ? $result['notification_flag'] : '';
$cleaning_fee 			= isset($result['cleaning_fee']) ? $result['cleaning_fee'] : '';
$barn        			= isset($result['barn']) ? $result['barn'] : [];
$rvbarn        			= isset($result['rvbarn']) ? $result['rvbarn'] : [];
$feed 					= isset($result['feed']) ? $result['feed'] : '';
$shaving 				= isset($result['shaving']) ? $result['shaving'] : '';
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
			<form method="post" id="form" action="<?php echo getAdminUrl(); ?>/event/action" autocomplete="off">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label>User</label>								
								<?php echo form_dropdown('userid', getUsersList(['type'=>['3']]), $userid, ['id' => 'userid', 'class' => 'form-control']); ?>
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
								<label>street</label>								
								<input type="text" name="location" class="form-control" id="location" placeholder="Enter Location" value="<?php echo $location; ?>">
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
						<div class="col-md-6">
							<div class="form-group">
								<label>Stalls Price</label>								
								<input type="text" name="stalls_price" class="form-control" id="stalls_price" placeholder="Enter Stalls Price" value="<?php echo $stalls_price;?>">								
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
								<label>RV Spots Price</label>								
								<input type="text" name="rvspots_price" class="form-control" id="rvspots_price" placeholder="Enter RV Spots Price" value="<?php echo $rvspots_price;?>">								
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

						<div class="card">
							<div class="card-body">
								<div class="d-flex justify-content-between flex-wrap align-items-center my-3">
									<p>Will you be selling feed at this event? </p>
									<div>
										<?php foreach($yesno as $key => $data){ ?>
											<button type="button" class="btn questionmodal_feed event_btn" value="<?php echo $key; ?>"><?php echo $data; ?></button>
										<?php } ?>
										<input type="hidden" value="" class="feed_flag" name="feed_flag">
									</div>
								</div>
								<div class="d-flex justify-content-between flex-wrap align-items-center my-3">
									<p>Will you be selling shavings at this event?</p>
									<div>
										<?php foreach($yesno as $key => $data){ ?>
											<button type="button" class="btn questionmodal_shaving event_btn" value="<?php echo $key; ?>"><?php echo $data; ?></button>
										<?php } ?>
										<input type="hidden" value="" class="shaving_flag" name="shaving_flag">
									</div>
								</div>
								<div class="d-flex justify-content-between flex-wrap align-items-center my-3">
									<p>Will you have RV Hookups at this event? </p>
									<div>
										<?php foreach($yesno as $key => $data){ ?>
											<button type="button" class="btn questionmodal_rv event_btn" value="<?php echo $key; ?>"><?php echo $data; ?></button>
										<?php } ?>
										<input type="hidden" value="" class="rv_flag" name="rv_flag">
									</div>
								</div>
								<div class="d-flex justify-content-between flex-wrap align-items-center my-3">
									<p>Will you collect the Cleaning fee from Horse owner? </p>
									<div>
										<?php foreach($yesno as $key => $data){ ?>
											<button type="button" class="btn questionmodal_cleaning event_btn" value="<?php echo $key; ?>"><?php echo $data; ?></button>
										<?php } ?>
										<input type="hidden" value="" class="cleaning_flag" name="cleaning_flag">
									</div>
								</div>
								<div class="d-flex justify-content-between flex-wrap align-items-center my-3">
									<p>Send a text message to users when their stall is unlocked and ready for use? </p>
									<div>
										<?php foreach($yesno as $key => $data){ ?>
											<button type="button" class="btn questionmodal_notification event_btn" value="<?php echo $key; ?>"><?php echo $data; ?></button>
										<?php } ?>
										<input type="hidden" value="" class="notification_flag" name="notification_flag">
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="container row mt-5 dash-barn-style mx-auto">
						<div class="row align-items-center mb-4 p-0">
							<div class="col-md-2">
								<p class="fs-2 fw-bold mb-0">Barn</p>
							</div>
							<div class="col-md-9 t-right p-0 respsm">
								<input type="hidden" value="" name="barnvalidation" id="barnvalidation">
								<button class="btn-stall barnbtn" value="4" name="tst" id="tes">Add Barn</button>
							</div>
						</div>
						<ul class="nav nav-pills flex-column col-md-3 barntab" role="tablist"></ul>
						<div class="tab-content col-md-9 stalltab"></div>
					</div>
					<div class="card-body p-0 feed_wrapper" style="display: none;">
						<div class="container row mt-5 dash-barn-style mx-auto">
							<div class="row align-items-center mb-4 p-0 addfeed">
								<div class="col-md-3">
									<h4 class="fw-bold mb-0 barntfontfeed">Feed</h4>
								</div>
								<div class="col-md-9 t-right p-0 respsm">
								<input type="hidden" value="" name="feedvalidation" id="feedvalidation">
								<button class="btn-stall feedbtn">Add feed</button>
								</div>
							</div>

							<div class="row" >
								<ul class="nav nav-pills flex-column feedlist" role="tablist"></ul>
								<div class="tab-content col-md-9 feedstalltab"></div>
							</div>
						</div>
					</div>
					<div class="card-body p-0 shaving_wrapper" style="display: none;">
						<div class="container row mt-5 dash-barn-style mx-auto">
							<div class="row align-items-center mb-4 p-0">
								<div class="col-md-3">
									<h4 class="fw-bold mb-0 barntfontshavings">Shavings</h4>
								</div>
								<div class="col-md-9 t-right p-0 respsm">
								<input type="hidden" value="" name="shavingsvalidation" id="shavingsvalidation">
									<button class="btn-stall shavingsbtn">Add Shavings</button>
								</div>
							</div>
							<div class="row" >
								<ul class="nav nav-pills flex-column shavingslist" role="tablist"></ul>
								<div class="tab-content col-md-9 shavingsstalltab"></div> 
							</div>
						</div>
					</div>
					<div class="card-body p-0 rv_wrapper" style="display: none;">
						<div class="container row mt-5 dash-barn-style mx-auto">
							<div class="row align-items-center mb-4 p-0">
								<div class="col-md-3">
									<h4 class="fw-bold mb-0 barntfont">RV Hookups</h4>
								</div>
								<div class="col-md-9 t-right p-0 respsm">
									<input type="hidden" value="" name="rvhookupsvalidation" id="rvhookupsvalidation">
									<button class="btn-stall rvhookupsbtn">Add RV Hookups</button>
								</div>
							</div>
							<div class="row">
								<ul class="nav nav-pills flex-column col-md-3 rvhookupsbarntab" role="tablist"></ul>
								<div class="tab-content col-md-9 rvhookupsstalltab"></div>
							</div>
						</div>
					</div>
					<div class="card-body p-0 cleaning_wrapper" style="display: none;">
						<div class="container row mt-5 dash-barn-style mx-auto">
							<div class="row align-items-center mb-4 p-0 cleaningfee">
								<div class="col-md-3">
									<h4 class="fw-bold mb-0 barntfontfee">Cleaning Fee</h4>
								</div>
								<div class="col-md-12 my-2">
									<div class="form-group">						
										<input type="text" name="cleaning_fee" class="form-control" id="cleaning_fee" placeholder="Enter Cleaning Fee" value="<?php echo $cleaning_fee; ?>">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12 mt-4">
						<input type="hidden" name="actionid" value="<?php echo $id; ?>">
						<input type="hidden" name="status" value="1">
						<input type="hidden" name="type" value="">
						<input type="submit" id ="eventSubmit" class="btn btn-danger" value="Submit">
						<a href="<?php echo base_url(); ?>/events" class="btn btn-dark">Back</a>
					</div>
				</div>
			</form>
		</div>
	</div>

	<div class="modal fade" id="myModal" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Stall</h4>
					<button type="button" class="close" data-bs-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<div class="col-md-12 my-2">
						<div class="form-group">
							<label>Stall Name</label>
							<input type="text" id="stall_name" class="form-control" placeholder="Enter Your Stall Name">
						</div>
					</div>
					<div class="col-md-12 my-2">
						<div class="form-group">
							<label>Price</label>
							<input type="text" id="stall_price" class="form-control" placeholder="Enter Price">
						</div>
					</div>
					<div class="col-md-6 my-2">
						<div class="form-group">
							<label>Stall Image</label>			
							<div>
								<a href="" target="_blank">
									<img src="" class="stall_source" width="100">
								</a>
							</div>
							<input type="file" class="stall_file">
							<span class="stall_msg"></span>
							<input type="hidden" id="stall_image" class="stall_input" value="">
						</div>
					</div>	
					<div class="col-md-12 my-2">
						<div class="form-group">
							<label>Total Number of Stalls</label>
							<input type="number" id="stall"  name="stall" class="form-control" placeholder="Enter Total Number of Stalls" min="1" required>
						</div>
					</div>
					<div class="col-md-12 my-2">
						<div class="form-group">
							<label>First Stall Number</label>
							<input type="text" id="stallstarting"  name="stallstarting" class="form-control" placeholder="Enter First Stall Number" required>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="hidden" id="barnIndexValue" name="barnIndexValue" value="0">
					<button type="button"class="btn btn-info bulkstallbtn">Submit</button>
					<button type="button"class="btn btn-info " data-bs-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal" tabindex="-1">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Modal title</h5>
	        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	      </div>
	      <div class="modal-body">
	        <p>Modal body text goes here.</p>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary">Save changes</button>
	      </div>
	    </div>
	  </div>
	</div>
</section>
<?php $this->endSection(); ?>
<?php $this->section('js') ?>

<script> 
	var barn				 	= $.parseJSON('<?php echo addslashes(json_encode($barn)); ?>');
	var chargingflag			= $.parseJSON('<?php echo addslashes(json_encode($chargingflag)); ?>');
	var rvbarn					= $.parseJSON('<?php echo addslashes(json_encode($rvbarn)); ?>');
	var feed				 	= $.parseJSON('<?php echo addslashes(json_encode($feed)); ?>');
	var shaving					= $.parseJSON('<?php echo addslashes(json_encode($shaving)); ?>');
	var occupied 	 			= $.parseJSON('<?php echo json_encode((isset($occupied)) ? array_filter($occupied) : []); ?>');
	var reserved 	 			= $.parseJSON('<?php echo json_encode((isset($reserved)) ? array_filter(explode(",", implode(",", array_keys($reserved)))) : []); ?>');
	var feed_flag				= '<?php echo $feed_flag ?>';
	var shaving_flag			= '<?php echo $shaving_flag ?>';
	var rv_flag				 	= '<?php echo $rv_flag ?>';
	var charging_flag			= '<?php echo $charging_flag ?>';
	var cleaning_flag			= '<?php echo $cleaning_flag ?>';
	var notification_flag		= '<?php echo $notification_flag ?>';

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
		
		questionpopup1(1, 'rv', rv_flag)
		questionpopup1(1, 'feed', feed_flag)
		questionpopup1(1, 'shaving', shaving_flag)
		questionpopup1(1, 'cleaning', cleaning_flag)
		questionpopup1(2, 'charging', charging_flag)
		questionpopup1(2, 'notification', notification_flag)
		
		barnstall('barn', [['.barnbtn'], ['.barntab', '.stalltab'], [0, 0], ['#barnvalidation'],[chargingflag, 3]], [barn, occupied, reserved])
		barnstall('rvhookups', [['.rvhookupsbtn'], ['.rvhookupsbarntab', '.rvhookupsstalltab'], [0, 0], ['#rvhookupsvalidation'], [chargingflag, 3]], [rvbarn, occupied, reserved])
		products('feed', [['.feedbtn'], ['.feedlist'], [0], ['#feedvalidation']], [feed])
		products('shavings', [['.shavingsbtn'], ['.shavingslist'], [0], ['#shavingsvalidation']], [shaving])
	});
	
	$('.questionmodal_shaving').click(function(e){ 
		e.preventDefault();
        questionpopup1(1, 'shaving', $(this).val())
    });
	    
    $('.questionmodal_feed').click(function(e){ 
    	e.preventDefault();
        questionpopup1(1, 'feed', $(this).val())
    });

    $('.questionmodal_rv').click(function(e){ 
    	e.preventDefault();
        questionpopup1(1, 'rv', $(this).val())
    });

     $('.questionmodal_cleaning').click(function(e){ 
    	e.preventDefault();
        questionpopup1(1, 'cleaning', $(this).val())
    });

    $('.questionmodal_charging').click(function(e){ 
    	e.preventDefault();
        questionpopup1(2, 'charging', $(this).val())
    });

    $('.questionmodal_notification').click(function(e){ 
    	e.preventDefault();
        questionpopup1(2, 'notification', $(this).val())
    });
	    
    function questionpopup1(type, name, value){ 
        $('.questionmodal_'+name).removeClass("btn-stall").addClass("event_btn");
        $('.questionmodal_'+name+'[value="'+value+'"]').removeClass("event_btn").addClass("btn-stall");
        $('.'+name+'_flag').val(value);   
        
        if(type=='1'){
            if(value=='1'){
                $('.'+name+'_wrapper').show();  
            }else{
                $('.'+name+'_wrapper').hide();      
            }
        }
    }

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