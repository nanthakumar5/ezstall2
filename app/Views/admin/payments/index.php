<?= $this->extend("admin/common/layout/layout2") ?>
	<?php $this->section('content') ?>
		<section class="content-header">		
			<div class="container-fluid">			
				<div class="row mb-2">				
					<div class="col-sm-6">					
						<h1>Payments</h1>				
					</div>				
					<div class="col-sm-6">					
						<ol class="breadcrumb float-sm-right">						
							<li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
							<li class="breadcrumb-item active">Payments</li>
						</ol>				
					</div>			
				</div>
			</div>
		</section>
		<section class="content">
			<div class="card">			
				<div class="card-header">
					<h3 class="card-title">Payments</h3>
				</div>
				<div class="card-body">	
					<table class="table table-striped table-hover datatables">
						<thead>
							<th>Transaction ID</th>
							<th>Name</th>
							<th>Email</th>	
							<th>Action</th>
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
				url 		: 	'<?php echo getAdminUrl()."/payments/DTpayments"; ?>',	
				data		:	{ 'page' : 'adminpayments' },				
				columns 	: 	[
									{ 'data' : 'id' },
    				                { 'data' : 'name' },
                    				{ 'data' : 'email' },					
                    				{ 'data' : 'action' }								
                				],
				columndefs	:	[{"sortable": false, "targets": [3]}]											
			};				
			
			ajaxdatatables('.datatables', options);		
		});
	</script>
<?php $this->endSection(); ?>

