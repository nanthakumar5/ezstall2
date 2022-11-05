<?php $this->extend("admin/common/layout/layout2") ?>
	<?php $this->section('content') ?>
		<section class="content-header">		
			<div class="container-fluid">			
				<div class="row mb-2">				
					<div class="col-sm-6">					
						<h1>Newsletter</h1>				
					</div>				
					<div class="col-sm-6">					
						<ol class="breadcrumb float-sm-right">						
							<li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
							<li class="breadcrumb-item active">Newsletter</li>
						</ol>				
					</div>			
				</div>
			</div>
		</section>
		<section class="content">
			<div class="card">			
				<div class="card-header">
					<h3 class="card-title">Newsletter</h3>
				</div>
				<div class="card-body">	
					<table class="table table-striped table-hover datatables">
						<thead>
							<th>Email</th>
							<th>Date</th>
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
				url 		: 	'<?php echo getAdminUrl()."/newsletter/DTnewsletter"; ?>',	
				data		:	{ 'page' : 'newsletter' },				
				columns 	: 	[
                    				{ 'data' : 'email' },
                    				{ 'data' : 'date' }					
                				]									
			};				
			
			ajaxdatatables('.datatables', options);		
		});
	</script>
<?php $this->endSection(); ?>

