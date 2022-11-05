<?= $this->extend("admin/common/layout/layout2") ?>

<?php $this->section('content') ?>
	<?php
		$title 					= isset($result['title']) ? $result['title'] : '';
		$content 		    	= isset($result['content']) ? $result['content'] : '';
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
						<li class="breadcrumb-item active">Privacy Policy</li>
					</ol>
				</div>
			</div>
		</div>
	</section>
	
	<section class="content">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Privacy Policy</h3>
			</div>
			<div class="card-body">
				<form method="post" id="form" action="<?php echo getAdminUrl(); ?>/privacypolicy" autocomplete="off">
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
								<input type="hidden" name="actionid" value="5">
								<input type="submit" class="btn btn-danger" value="Submit">
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
