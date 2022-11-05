<?= $this->extend("admin/common/layout/layout2") ?>

<?php $this->section('content') ?>
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Financial Report</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
						<li class="breadcrumb-item active">Financial Report</li>
					</ol>
				</div>
			</div>
		</div>
	</section>
	
	<section class="content">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Financial Report</h3>
			</div>
			<div class="card-body">
				<form method="post" id="form">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>From Date</label>
									<input type="text" name="financialcheckin" autocomplete="off" class="form-control"  id="financialcheckin">	
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>To Date</label>
									<input type="text" name="financialcheckout" autocomplete="off" class="form-control"  id="financialcheckout">		
								</div>
							</div>
							<div class="col-md-12 exportup" align="right">
								<input type="submit" value="search" class="btn btn-danger">
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</section>
<?php $this->endSection(); ?>
<?php $this->Section('js'); ?>
<script>
$(function(){
    validation(
    	'#form',
    	{
    		financialcheckin 	     : {
    			required	: 	true
    		},
    		financialcheckout  : {	
    			required	: 	true
    		}
    	},
    	{},
    	{
    		ignore : []
    	}
    );
});
	dateformat('#financialcheckin, #financialcheckout');
</script>

<?php $this->endSection(); ?>