<?= $this->extend("admin/common/layout/layout2") ?>
	<?php $this->section('content') ?>
		<section class="content-header">		
			<div class="container-fluid">			
				<div class="row mb-2">				
					<div class="col-sm-6">					
						<h1>Stripe Payments</h1>				
					</div>				
					<div class="col-sm-6">					
						<ol class="breadcrumb float-sm-right">						
							<li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
							<li class="breadcrumb-item active">Stripe Payments</li>
						</ol>				
					</div>			
				</div>
			</div>
		</section>
		<section class="content">
			<div class="page-action">	
				<b>(<?php echo 'Balance - '.$currencysymbol.(number_format(($balance/100), 2, '.', '')); ?>)</b>
				<a href="<?php echo getAdminUrl(); ?>/stripepayments/action" class="btn btn-primary">Pay</a>
			</div>
			<div class="card">			
				<div class="card-header">
					<h3 class="card-title">Stripe Payments</h3>
				</div>
				<div class="card-body">	
					<table class="table table-striped table-hover datatables">
						<thead>
							<th>Name</th>
							<th>Amount</th>
						</thead>
					</table>
				</div>
			</div>
		</section>
<?php $this->endSection(); ?>
<?php $this->section('js') ?>
	<script>
		$(function(){
			var options = {	
				url 		: 	'<?php echo getAdminUrl()."/stripepayments/DTstripepayments"; ?>',	
				data		:	{ 'page' : 'stripepayments' },				
				columns 	: 	[
    				                { 'data' : 'username' },
    				                { 'data' : 'amount' },
                				],
                				
				columndefs	:	[{"sortable": false, "targets": [1]}]											
			};				
				ajaxdatatables('.datatables', options);		
		});
	</script>
<?php $this->endSection(); ?>

