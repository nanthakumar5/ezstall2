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
					<?php echo $barnstall; ?>
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
</section>
<?php $this->endSection(); ?>
<?php $this->section('js') ?>
<?php echo $stripe; ?>
<script>
	var id                      = '<?php echo $id ?>';
	var currencysymbol 			= '<?php echo $currencysymbol; ?>';
	var stallpercost 	 		= '<?php echo $settings["facilitystallfee"]; ?>';
	
	$(function(){
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
</script>
<?php $this->endSection(); ?>

