<?= $this->extend("admin/common/layout/layout2") ?>

<?php $this->section('content') ?>
	<?php
		$id 					= isset($result['id']) ? $result['id'] : '';
		$userid 				= isset($result['user_id']) ? $result['user_id'] : '';
		$name 					= isset($result['name']) ? $result['name'] : '';
		$price 		    		= isset($result['price']) ? $result['price'] : '';
		$interval 		    	= isset($result['interval']) ? $result['interval'] : '';
		$type 					= isset($result['type']) ? $result['type'] : '';
		$pageaction 			= $id=='' ? 'Add' : 'Update';
	?>
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Plans</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
						<li class="breadcrumb-item"><a href="<?php echo getAdminUrl(); ?>/plan">Plans</a></li>
						<li class="breadcrumb-item active"><?php echo $pageaction; ?> Plans</li>
					</ol>
				</div>
			</div>
		</div>
	</section>
	
	<section class="content">
		<div class="page-action">
			<a href="<?php echo getAdminUrl(); ?>/plan" class="btn btn-primary">Back</a>
		</div>
		<div class="card">
			<div class="card-header">
				<h3 class="card-title"><?php echo $pageaction; ?> Plans</h3>
			</div>
			<div class="card-body">
				<form method="post" id="form" action="<?php echo getAdminUrl(); ?>/plan/action" autocomplete="off">
					<div class="col-md-12">
						<div class="row">
							
							<div class="col-md-12">
								<div class="form-group">
									<label>Name</label>								
									<input type="text" name="name" class="form-control" id="name" placeholder="Enter Name" value="<?php echo $name; ?>">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Price</label>								
									<input type="number" name="price" class="form-control" id="price" placeholder="Enter Price" value="<?php echo $price; ?>">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Interval</label>	
									<?php echo form_dropdown('interval', $paymentinterval, $interval, ['class' => 'form-control']); ?>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Type</label>	
									<?php echo form_dropdown('type', $paymentuser, $type, ['class' => 'form-control']); ?>
								</div>
							</div>						
							<div class="col-md-12">
								<input type="hidden" name="actionid" value="<?php echo $id; ?>">
								<input type="submit" class="btn btn-danger" value="Submit">
								<a href="<?php echo getAdminUrl(); ?>/plan" class="btn btn-dark">Back</a>
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
					name 	     : {
						required	: 	true
					},
					price  : {	
						required	: 	true
					},
					interval  : {	
						required	: 	true
					},
					type  : {	
						required	: 	true
					}
				}
			);
			
		});

	</script>
<?php $this->endSection(); ?>
