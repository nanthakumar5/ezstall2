<?= $this->extend("admin/common/layout/layout2") ?>

<?php $this->section('content') ?>
	<?php
		$id 					= isset($result['id']) ? $result['id'] : '';
		$userid 				= isset($result['user_id']) ? $result['user_id'] : '';
		$title 					= isset($result['title']) ? $result['title'] : '';
		$content 		    	= isset($result['content']) ? $result['content'] : '';
		$image      			= isset($result['image']) ? $result['image'] : '';
		$image 				    = filedata($image, base_url().'/assets/uploads/aboutus/');
		$pageaction 			= $id=='' ? 'Add' : 'Update';
	?>
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>About Us</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
						<li class="breadcrumb-item"><a href="<?php echo getAdminUrl(); ?>/aboutus">About Us</a></li>
						<li class="breadcrumb-item active"><?php echo $pageaction; ?> About Us</li>
					</ol>
				</div>
			</div>
		</div>
	</section>
	
	<section class="content">
		<div class="page-action">
			<a href="<?php echo getAdminUrl(); ?>/aboutus" class="btn btn-primary">Back</a>
		</div>
		<div class="card">
			<div class="card-header">
				<h3 class="card-title"><?php echo $pageaction; ?> About Us</h3>
			</div>
			<div class="card-body">
				<form method="post" id="form" action="<?php echo getAdminUrl(); ?>/aboutus/action" autocomplete="off">
					<div class="col-md-12">
						<div class="row">
							
							<div class="col-md-12">
								<div class="form-group">
									<label>Title</label>								
									<input type="text" name="title" class="form-control" id="title" placeholder="Enter Title" value="<?php echo $title; ?>">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Content</label>
									<textarea class="form-control" id="content" name="content" placeholder="Enter Content" rows="3"><?php echo $content;?></textarea>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Upload About Us Image</label>			
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
							<div class="col-md-12">
								<input type="hidden" name="actionid" value="<?php echo $id; ?>">
								<input type="submit" class="btn btn-danger" value="Submit">
								<a href="<?php echo getAdminUrl(); ?>/aboutus" class="btn btn-dark">Back</a>
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
			editor('#content');
			fileupload([".image_file"], ['.image_input', '.image_source','.image_msg'],['aboutus']);
			validation(
				'#form',
				{
					title 	     : {
						required	: 	true
					},
					content  : {	
						required	: 	true
					}
				}
			);
			
		});

	</script>
<?php $this->endSection(); ?>
