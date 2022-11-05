<?= $this->extend("admin/common/layout/layout2") ?>

<?php $this->section('content') ?>
	<?php
		$id 					= isset($result['id']) ? $result['id'] : '';
		$userid 				= isset($result['user_id']) ? $result['user_id'] : '';
		$title 					= isset($result['title']) ? $result['title'] : '';
		$content 		    	= isset($result['content']) ? $result['content'] : '';
		$pageaction 			= $id=='' ? 'Add' : 'Update';
	?>
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>FAQ</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
						<li class="breadcrumb-item"><a href="<?php echo getAdminUrl(); ?>/faq">Events</a></li>
						<li class="breadcrumb-item active"><?php echo $pageaction; ?> FAQ</li>
					</ol>
				</div>
			</div>
		</div>
	</section>
	
	<section class="content">
		<div class="page-action">
			<a href="<?php echo getAdminUrl(); ?>/faq" class="btn btn-primary">Back</a>
		</div>
		<div class="card">
			<div class="card-header">
				<h3 class="card-title"><?php echo $pageaction; ?> FAQ</h3>
			</div>
			<div class="card-body">
				<form method="post" id="form" action="<?php echo getAdminUrl(); ?>/faq/action" autocomplete="off">
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
								<input type="hidden" name="actionid" value="<?php echo $id; ?>">
								<input type="submit" class="btn btn-primary" value="Submit">
								<a href="<?php echo getAdminUrl(); ?>/faq" class="btn btn-primary">Back</a>
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
