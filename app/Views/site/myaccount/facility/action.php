<?php $this->extend("site/common/layout/layout1") ?>

<?php $this->section('content') ?>
<?php
$id 					= isset($result['id']) ? $result['id'] : '';
$name 					= isset($result['name']) ? $result['name'] : '';
$zipcode 				= isset($result['zipcode']) ? $result['zipcode'] : '';
$description 		    = isset($result['description']) ? $result['description'] : '';
$image      			= isset($result['image']) ? $result['image'] : '';
$image 				    = filedata($image, base_url().'/assets/uploads/event/');
$profileimage      		= isset($result['profile_image']) ? $result['profile_image'] : '';
$profileimage 			= filedata($profileimage, base_url().'/assets/uploads/profile/');
$stallmap      			= isset($result['stallmap']) ? $result['stallmap'] : '';
$stallmap 				= filedata($stallmap, base_url().'/assets/uploads/stallmap/');
$feed_flag 				= isset($result['feed_flag']) ? $result['feed_flag'] : '';
$shaving_flag 			= isset($result['shaving_flag']) ? $result['shaving_flag'] : '';
$rv_flag 				= isset($result['rv_flag']) ? $result['rv_flag'] : '';
$charging_flag 			= isset($result['charging_flag']) ? $result['charging_flag'] : '';
$cleaning_flag 			= isset($result['cleaning_flag']) ? $result['cleaning_flag'] : '';
$notification_flag 		= isset($result['notification_flag']) ? $result['notification_flag'] : '';
$cleaning_fee 			= isset($result['cleaning_fee']) ? $result['cleaning_fee'] : '';
$barn        			= isset($result['barn']) ? $result['barn'] : [];
$rvbarn        			= isset($result['rvbarn']) ? $result['rvbarn'] : [];
$feed 					= isset($result['feed']) ? $result['feed'] : '';
$shaving 				= isset($result['shaving']) ? $result['shaving'] : '';
$pageaction 			= $id=='' ? 'Add' : 'Update';
?>
<section class="content">
	<div class="d-flex justify-content-between align-items-center flex-wrap">
		<div align="left" class="m-0"><h3>Facility</h3></div>
		<div class="page-action mb-4 m-0" align="right">
			<a href="<?php echo base_url(); ?>/myaccount/facility" class="btn btn-dark">Back</a>
		</div>
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
								<label>Name</label>								
								<input type="text" name="facility_name" class="form-control" id="name" placeholder="Enter Name" value="<?php echo $name; ?>">
							</div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
							    <label>Zip Code</label>                      
							    <input type="text" name="zipcode" class="form-control" id="zipcode" placeholder="Enter Zip Code" value="<?php echo $zipcode; ?>">
							</div>
						</div>
						<div class="col-md-12 my-2">
							<div class="form-group">
								<label>Facility Description</label>								
								<textarea type="text" name="description" class="form-control" id="description" placeholder="Enter Description" rows="3"><?php echo $description; ?></textarea>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label>Facility Image</label>			
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
					<div class="card">
						<div class="card-body">
							<div>
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
									<p>Will you Collect the Cleaning fee from Horse owner? </p>
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
					<div class="card-body p-0">
						<div class="container row mt-5 dash-barn-style mx-auto">
							<div class="row align-items-center mb-4 p-0 addbarn">
								<div class="col-md-3">
									<p class="fs-2 fw-bold mb-0 barntfont">Barn</p>
								</div>
								<div class="col-md-9 t-right p-0 respsm">
									<input type="hidden" value="" name="barnvalidation" id="barnvalidation">
									<button class="btn-stall barnbtn" value="4" name="tst" id="tes">Add Barn</button>
								</div>
							</div>
							<ul class="nav nav-pills flex-column col-md-3 barntab" role="tablist"></ul>
							<div class="tab-content col-md-9 stalltab"></div>
						</div>
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
										<input type="text" name="cleaning_fee" class="form-control" id="cleaning_fee" placeholder="Enter Cleaning Fee" value="<?php echo $cleaning_fee ?>">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-12 mt-4">
						<input type="hidden" name="actionid" value="<?php echo $id; ?>">
						<input type="hidden" name="userid" value="<?php echo $userid; ?>">
						<input type="hidden" name="type" value="1">
						<button class="btn btn-danger facilitypayment"  type="button">Submit</button>
						<a href="<?php echo base_url(); ?>/myaccount/facility" class="btn btn-dark">Back</a>
					</div>
				</div>
			</form>

		</div>
	</div>

	<div class="modal fade" id="myModal" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content" style="text-align: left !important;">
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
</section>
<?php $this->endSection(); ?>
<?php $this->section('js') ?>
<?php echo $stripe; ?>
<?php echo $questionmodal; ?>
<script>

	var barn				 	= $.parseJSON('<?php echo addslashes(json_encode($barn)); ?>'); 
	var chargingflag			= $.parseJSON('<?php echo addslashes(json_encode($chargingflag)); ?>'); 
	var rvbarn					= $.parseJSON('<?php echo addslashes(json_encode($rvbarn)); ?>');
	var feed				 	= $.parseJSON('<?php echo addslashes(json_encode($feed)); ?>');
	var shaving					= $.parseJSON('<?php echo addslashes(json_encode($shaving)); ?>');
	var occupied 	 			= $.parseJSON('<?php echo json_encode((isset($occupied)) ? array_filter($occupied) : []); ?>');
	var reserved 	 			= $.parseJSON('<?php echo json_encode((isset($reserved)) ? array_filter(explode(",", implode(",", array_keys($reserved)))) : []); ?>');
	var occupiedstallcount 	 	= '<?php echo (isset($occupied)) ? count($occupied) : 0; ?>';
	var stallpercost 	 		= '<?php echo $settings["facilitystallfee"]; ?>';
	var currencysymbol 			= '<?php echo $currencysymbol; ?>';
	var id                      = '<?php echo $id ?>';
	var feed_flag				= '<?php echo $feed_flag ?>';
	var shaving_flag			= '<?php echo $shaving_flag ?>';
	var rv_flag				 	= '<?php echo $rv_flag ?>';
	var charging_flag			= '<?php echo $charging_flag ?>'; 
	var cleaning_flag			= '<?php echo $cleaning_flag ?>'; 
	var notification_flag		= '<?php echo $notification_flag ?>';

	
	$(function(){
		if(id==""){
			$('#questionmodal').modal('show'); 
		}
 
		editor('#description');
		fileupload([".image_file"], ['.image_input', '.image_source','.image_msg'],['2']);
		fileupload([".profileimage_file"], ['.profileimage_input', '.profileimage_source','.profileimage_msg']);
		fileupload([".stallmap_file", ['jpg','jpeg','png','gif','tiff','tif','pdf']], ['.stallmap_input', '.stallmap_source','.stallmap_msg']);
		fileupload([".stall_file"], ['.stall_input', '.stall_source','.stall_msg']);

		validation(
			'#form',
			{
				facility_name  	: {
					required	: 	true
				},
				/*description     : {
					required	: 	true
				},*/
				barnvalidation : {
					required 	: true
				},
				zipcode : {
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

		barnstall('barn', [['.barnbtn'], ['.barntab', '.stalltab'], [0, 0], ['#barnvalidation'],[chargingflag, 2]], [barn, occupied, reserved])
		barnstall('rvhookups', [['.rvhookupsbtn'], ['.rvhookupsbarntab', '.rvhookupsstalltab'], [0, 0], ['#rvhookupsvalidation'], [chargingflag, 2]], [rvbarn, occupied, reserved])
		products('feed', [['.feedbtn'], ['.feedlist'], [0]], [feed])
		products('shavings', [['.shavingsbtn'], ['.shavingslist'], [0]], [shaving])

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
    	/*if($(this).val()=='2'){
            $('#rvhookupsvalidation').val('1');
        }else if($(this).val()=='1'){
            $('#rvhookupsvalidation').val('');
        }*/
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
</script>
<?php $this->endSection(); ?>

