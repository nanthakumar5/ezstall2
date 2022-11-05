<?= $this->extend("admin/common/layout/layout2") ?>

<?php $this->section('content') ?>
	<?php
		$id 					= isset($result['id']) ? $result['id'] : '';
		$fromtax 				= isset($result['from_tax']) ? $result['from_tax'] : '';
		$totax 		    		= isset($result['to_tax']) ? $result['to_tax'] : '';
		$taxprice 		    	= isset($result['tax_price']) ? $result['tax_price'] : '';
		$pageaction 			= $id=='' ? 'Add' : 'Update';
	?>
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Tax</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
						<li class="breadcrumb-item"><a href="<?php echo getAdminUrl(); ?>/tax">Tax</a></li>
						<li class="breadcrumb-item active"><?php echo $pageaction; ?> Taxs</li>
					</ol>
				</div>
			</div>
		</div>
	</section>
	
	<section class="content">
		<div class="page-action">
			<a href="<?php echo getAdminUrl(); ?>/tax" class="btn btn-primary">Back</a>
		</div>
		<div class="card">
			<div class="card-header">
				<h3 class="card-title"><?php echo $pageaction; ?> Tax</h3>
			</div>
			<div class="card-body">
				<form method="post" id="form" action="<?php echo getAdminUrl(); ?>/tax/action" autocomplete="off">
					<div class="col-md-12">
						<div class="row">
							
							<div class="col-md-12">
								<div class="form-group">
									<label>From Postal Code</label>								
									<input type="text" name="fromtax" class="form-control" id="fromtax" placeholder="Enter From postal code" value="<?php echo $fromtax; ?>">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>To Postal Code</label>								
									<input type="number" name="totax" class="form-control" id="totax" placeholder="Enter To postal code" value="<?php echo $totax; ?>">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Tax</label>								
									<input type="number" name="taxprice" class="form-control" id="taxprice" placeholder="Enter Tax" value="<?php echo $taxprice; ?>">
								</div>
							</div>					
							<div class="col-md-12">
								<input type="hidden" name="actionid" value="<?php echo $id; ?>">
								<input type="submit" class="btn btn-danger" value="Submit">
								<a href="<?php echo getAdminUrl(); ?>/tax" class="btn btn-dark">Back</a>
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
					fromtax 	     : {
						required	: 	true
					},
					totax  : {	
						required	: 	true
					},
					taxprice  : {	
						required	: 	true
					}
				}
			);
			
		});

	</script>
<?php $this->endSection(); ?>
