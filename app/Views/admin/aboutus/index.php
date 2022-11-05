<?= $this->extend("admin/common/layout/layout2") ?>
	<?php $this->section('content') ?>
		<section class="content-header">		
			<div class="container-fluid">			
				<div class="row mb-2">				
					<div class="col-sm-6">					
						<h1>About Us</h1>				
					</div>				
					<div class="col-sm-6">					
						<ol class="breadcrumb float-sm-right">						
							<li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
							<li class="breadcrumb-item active">About Us</li>
						</ol>				
					</div>			
				</div>
			</div>
		</section>
		<section class="content">
			<div class="page-action">			
				<a href="<?php echo getAdminUrl(); ?>/aboutus/action" class="btn btn-primary">Add About Us</a>
			</div>
			<div class="card">			
				<div class="card-header">
					<h3 class="card-title">About Us</h3>
				</div>
				<div class="card-body">	
					<table class="table table-striped table-hover datatables">
						<thead>
							<th>Title</th>
							<th>content</th>
							<th>image</th>	
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
				url 		: 	'<?php echo getAdminUrl()."/aboutus/DTaboutus"; ?>',	
				data		:	{ 'page' : 'adminbanner' },				
				columns 	: 	[
    				                { 'data' : 'title' },
                    				{ 'data' : 'content' },
                    				{ 'data' : 'image' },
                    				{ 'data' : 'action' }					
                				],
				columndefs	:	[{"sortable": false, "targets": [2,3]}]											
			};				
				ajaxdatatables('.datatables', options);		
		});
		
    	$(document).on('click','.delete',function(){
            var action 	= 	'<?php echo getAdminUrl()."/aboutus"; ?>';
            var data   = '\
            	<input type="hidden" value="'+$(this).data('id')+'" name="id">\
				<input type="hidden" value="0" name="status">\
            ';
          	sweetalert2(action,data);
        });	
	</script>
<?php $this->endSection(); ?>

