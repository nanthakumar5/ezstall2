<?= $this->extend("admin/common/layout/layout2") ?>
<?php $this->section('content') ?>
	<?php
		$id 					= isset($result['id']) ? $result['id'] : '';
		$name 					= isset($result['name']) ? $result['name'] : '';
		$subject 		    	= isset($result['subject']) ? $result['subject'] : '';
		$message      			= isset($result['message']) ? $result['message'] : '';
	?>
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Email Template</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
						<li class="breadcrumb-item"><a href="<?php echo getAdminUrl(); ?>/emailtemplate">Email Template</a></li>
						<li class="breadcrumb-item active">Update Email Template</li>
					</ol>
				</div>
			</div>
		</div>
	</section>
	<section class="content">
		<div class="page-action">
			<a href="<?php echo getAdminUrl(); ?>/emailtemplate" class="btn btn-primary">Back</a>
		</div>
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Update Email Template</h3>
			</div>
			<div class="card-body">
				<form method="post" id="email_template_form" action="<?php echo getAdminUrl().'/emailtemplate/action/'.$id;?>" autocomplete="off">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>Title : </label>								
									<input type="text" name="name" class="form-control" id="name" placeholder="Enter Email Template" value="<?php echo $name; ?>">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Subject :</label>
									<textarea class="form-control" id="subject" name="subject" placeholder="Enter Subject" rows="3"><?php echo $subject;?></textarea>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Message</label>
									<textarea class="form-control" id="message" name="message" placeholder="Enter Message" rows="5"><?php echo $message;?></textarea>
								</div>
							</div>							
							<div class="col-md-12">
								<input type="hidden" name="actionid" value="<?php echo $id; ?>">
								<input type="submit" class="btn btn-primary" value="Submit">
								<a href="<?php echo getAdminUrl(); ?>/emailtemplate" class="btn btn-primary">Back</a>
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
			//editor('#message');
			validation(
				'#email_template_form',
				{
					name 	     : {
						required	: 	true
					},
					subject  : {	
						required	: 	true
					},
					message  : {	
						required	: 	true
					}
				}
			);
		});

	</script>
<?php $this->endSection(); ?>
