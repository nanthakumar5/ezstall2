<?= $this->extend("admin/common/layout/layout2") ?>
<?php $this->section('content') ?>
	<?php
		$userid = '';
	?>
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Stripe Payments</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
						<li class="breadcrumb-item"><a href="<?php echo getAdminUrl(); ?>/stripepayments">Stripe Payments</a></li>
						<li class="breadcrumb-item active"></li>
					</ol>
				</div>
			</div>
		</div>
	</section>
	
	<section class="content">
		<div class="page-action">
			<a href="<?php echo getAdminUrl(); ?>/stripepayments" class="btn btn-primary">Back</a>
		</div>
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Stripe Payments</h3>
			</div>
			<div class="card-body">
				<form method="post" id="form" action="<?php echo getAdminUrl(); ?>/stripepayments/action" autocomplete="off">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>Users</label>	
									<?php echo form_dropdown('user_id', getUsersList(['type'=>['2','3']]), $userid, ['id' => 'user_id', 'class' => 'form-control']); ?>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group"> 
									<label>Amount</label>								
									<input type="number" name="amount" min="1" class="form-control" id="amount" placeholder="Enter Amount">
								</div>
							</div>	
							<div class="col-md-12">
								<div class="form-group"> 
									<label>Balance - </label>		
									<?php echo $currencysymbol.(number_format(($balance/100), 2, '.', '')); ?>
								</div>
							</div>	
							<div class="col-md-12">
								<input type="submit" class="btn btn-primary" value="Submit">
								<a href="<?php echo getAdminUrl(); ?>/stripepayments" class="btn btn-primary">Back</a>
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
					amount  : {	
						required	: 	true
					},
				}
			);
			
		});

	</script>
<?php $this->endSection(); ?>
