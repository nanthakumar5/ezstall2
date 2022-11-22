<?= $this->extend("admin/common/layout/layout2") ?>
<?php $this->section('content') ?>
<section class="content-header">		
	<div class="container-fluid">			
		<div class="row mb-2">				
			<div class="col-sm-6">					
				<h1>Users</h1>				
			</div>				
			<div class="col-sm-6">					
				<ol class="breadcrumb float-sm-right">						
					<li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
					<li class="breadcrumb-item active">Users</li>
				</ol>				
			</div>			
		</div>
	</div>
</section>
<section class="content">
	<div class="page-action">			
		<a data-bs-toggle="modal" data-bs-target="#usermodal" class="btn btn-primary">Import</a>
		<a href="<?php echo getAdminUrl(); ?>/users/action" class="btn btn-primary">Add Users</a>
	</div>
	<div class="card">			
		<div class="card-header">
			<h3 class="card-title">Users</h3>
		</div>
		<div class="card-body">	
			<table class="table table-striped table-hover datatables">
				<thead>
					<th>Name</th>
					<th>Email</th>	
					<th>Type</th>
					<th>Created At</th>
					<th>Action</th>	
				</thead>
			</table>
		</div>
	</div>
</section>

<div id="usermodal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Import User</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<form method="post" action="<?php echo getAdminUrl(); ?>/users/import" enctype="multipart/form-data">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Upload Excel</label>
									<input type="file" name="import" autocomplete="off" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
									<p><a href="<?php echo getAdminUrl(); ?>/users/sampleexport">Download Sample Excel</a></p>
								</div>
							</div>
							<div class="col-md-12 mt-3">
								<input type="hidden" value="" name="event_id" class="financialeventid">
								<button type="submit" class="btn btn-primary">Submit</button>
								<button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php $this->endSection(); ?>
<?php $this->section('js') ?>
	<script>
		$(function(){
			var options = {	
				url 		: 	'<?php echo getAdminUrl()."/users/DTusers"; ?>',	
				data		:	{ 'page' : 'adminusers' },			
				columns 	: 	[
    				                { 'data' : 'name' },
                    				{ 'data' : 'email' },
                    				{ 'data' : 'type' },									
                    				{ 'data' : 'created_at' },									
                    				{ 'data' : 'action' }								
                				],
				columndefs	:	[{"sortable": false, "targets": [2,4]}]											
			};	
			
			ajaxdatatables('.datatables', options);		
		});
		
    	$(document).on('click','.delete',function(){
            var action 	= 	'<?php echo getAdminUrl()."/users"; ?>';
            var data   = '\
            	<input type="hidden" value="'+$(this).data('id')+'" name="id">\
             <input type="hidden" value="0" name="status">\
              ';
          	sweetalert2(action,data);
        });	
	</script>
<?php $this->endSection(); ?>

