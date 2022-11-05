<?= $this->extend("admin/common/layout/layout2") ?>
	<?php $this->section('content') ?>
		<section class="content-header">		
			<div class="container-fluid">			
				<div class="row mb-2">				
					<div class="col-sm-6">					
						<h1>Tax</h1>				
					</div>				
					<div class="col-sm-6">					
						<ol class="breadcrumb float-sm-right">						
							<li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
							<li class="breadcrumb-item active">Tax</li>
						</ol>				
					</div>			
				</div>
			</div>
		</section>
		<section class="content">
			<div class="page-action">			
				<a href="<?php echo getAdminUrl(); ?>/tax/action" class="btn btn-primary">Add Tax</a>
			</div>
			<div class="card">			
				<div class="card-header">
					<h3 class="card-title">Taxs</h3>
				</div>
				<div class="card-body">	
					<table class="table table-striped table-hover datatables">
						<thead>
							<th>From</th>
							<th>To</th>	
							<th>price</th>	
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
				url 		: 	'<?php echo getAdminUrl()."/tax/DTtax"; ?>',	
				data		:	{ 'page' : 'tax' },				
				columns 	: 	[
    				                { 'data' : 'fromtax' },
                    				{ 'data' : 'totax' },
                    				{ 'data' : 'taxprice' },
                    				{ 'data' : 'action' }								
                				],
				columndefs	:	[{"sortable": false, "targets": [3]}]											
			};				
				ajaxdatatables('.datatables', options);		
		});
		
    	$(document).on('click','.delete',function(){
            var action 	= 	'<?php echo getAdminUrl()."/tax"; ?>';
            var data   = '\
            	<input type="hidden" value="'+$(this).data('id')+'" name="id">\
				<input type="hidden" value="0" name="status">\
            ';
          	sweetalert2(action,data);
        });	
	</script>
<?php $this->endSection(); ?>

