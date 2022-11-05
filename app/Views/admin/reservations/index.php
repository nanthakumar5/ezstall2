<?= $this->extend("admin/common/layout/layout2") ?>
	<?php $this->section('content') ?>
		<section class="content-header">		
			<div class="container-fluid">			
				<div class="row mb-2">				
					<div class="col-sm-6">					
						<h1>Reservations</h1>				
					</div>				
					<div class="col-sm-6">					
						<ol class="breadcrumb float-sm-right">						
							<li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
							<li class="breadcrumb-item active">Reservations</li>
						</ol>				
					</div>			
				</div>
			</div>
		</section>
		<section class="content"> 
			<div class="card">			
				<div class="card-header">
					<h3 class="card-title">Reservations</h3>
				</div>
				<div class="card-body">	
					<table class="table table-striped table-hover datatables">
						<thead>
							<th>Booking ID</th>
							<th>Payment Method</th>
							<th>Firstname</th>
							<th>Lastname</th>
							<th>Mobile</th>
							<th>Status</th>	
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
				url 		: 	'<?php echo getAdminUrl()."/reservations/DTreservations"; ?>',	
				data		:	{ 'page' : 'adminreservations' },				
				columns 	: 	[
									{ 'data' : 'id' },
									{ 'data' : 'paymentmethod' },
    				                { 'data' : 'firstname' },
                    				{ 'data' : 'lastname' },
                    				{ 'data' : 'mobile' },					
                    				{ 'data' : 'status' },
                    				{ 'data' : 'action' }								
                				],
				columndefs	:	[{"sortable": false, "targets": [6]}]											
			};				
			
			ajaxdatatables('.datatables', options);		
		});

		$('body').on('click','.striperefunds', function(){
		var action 	= 	'<?php echo getAdminUrl()."/reservations"; ?>';
		var data   = '\
		<input type="hidden" name="id" value="'+$(this).attr("data-id")+'">\
		<input type="hidden" name="paymentid" value="'+$(this).attr("data-paymentid")+'">\
		<input type="hidden" name="paymentintentid" value="'+$(this).attr("data-paymentintentid")+'">\
		<input type="hidden" name="amount" value="'+$(this).attr("data-amount")+'">\
		';
		sweetalert2(action,data);
	});	
	</script>
<?php $this->endSection(); ?>

