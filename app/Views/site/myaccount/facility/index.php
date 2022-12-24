<?php $this->extend('site/common/layout/layout1') ?>
<?php $this->section('content') ?>
<?php
$checksubscription 				= checkSubscription();
$checksubscriptiontype 			= $checksubscription['type'];
$checksubscriptionproducer 		= $checksubscription['facility'];
$currentdate 					= date("Y-m-d");
?>
<h2 class="fw-bold mb-4 mt-2">Facility</h2>
<section class="maxWidth eventPagePanel mt-2">
	<?php if($usertype !='4'){ ?>
		<a class="btn-custom-black" href="<?php echo base_url().'/myaccount/facility/add'; ?>">Add Facility</a>
		<a class="btn-custom-black" href="javascript:void(0);" data-toggle="modal" data-target="#importmodal">Import</a>
	<?php } ?>

	<?php if(count($list) > 0){ ?>
		<?php foreach ($list as $data) {  ?>
			<div class="dashboard-box mt-4">
				<div class="row align-items-center px-2">
					<div class="col-md-2">
						<img src="<?php echo base_url() ?>/assets/uploads/event/<?php echo $data['image']?>" class="dash-event-image">
					</div>
					<div class="col-md-5">
						<a class="text-decoration-none" href="<?php echo base_url() ?>/facility/detail/<?php echo $data['id']?>"><p class="fs-6 fw-bold"><?php echo $data['name']; ?><p></a></p>
						<a class="text-decoration-none" href="<?php echo base_url() ?>/facility/detail/<?php echo $data['id']?>"><p class="fs-6 fw-bold"><?php echo substr($data['description'], 0,50); ?><p></a></p>
					</div>
				</div>
				<div class="dash-event">
					<a href="<?php echo base_url().'/myaccount/facility/view/'.$data['id']; ?>" 
						class="dash-view-event fs-7 mx-2">
						View
					</a>
					<?php if($usertype !='4'){ ?>
						<a href="<?php echo base_url().'/myaccount/facility/edit/'.$data['id']; ?>" 
							class="dash-edit-event fs-7 mx-2">
							Edit
						</a>
						
						<?php $occupied = getOccupied($data['id']); ?>
						<?php if(count($occupied)==0){ ?>
							<a data-id="<?php echo $data['id']; ?>" href="javascript:void(0);" class="dash-delete-event fs-7 mx-2 delete">
								Delete
							</a>
						<?php } ?>
				    <?php } ?>
					<p class="mt-3"></p>
				    <a href="javascript:void(0);" data-toggle="modal" data-target="#financialmodal" class="financialreport dash-export-event fs-7 mx-2" data-id="<?php echo $data['id']; ?>">
						Financial Report
					</a>
				    <a href="<?php echo base_url().'/myaccount/facility/inventories/'.$data['id']; ?>" class="dash-export-event fs-7 mx-2">
						Inventories
					</a>
					<a href="<?php echo base_url().'/myaccount/facility/export/'.$data['id']; ?>" class="dash-export-event fs-7 mx-2">
						Export
					</a>
				</div>
			</div>
		<?php } ?>
	<?php } else{ ?>
		<p class="mt-3">No Record Found</p>
	<?php } ?>
	<?php echo $pager; ?>
</section>

<div id="financialmodal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Financial Report</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<form method="post" action="<?php echo base_url().'/myaccount/facility/financialreport'; ?>">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>From Date</label>
									<input type="text" name="checkin" autocomplete="off" class="form-control" id="checkin">	
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>To Date</label>
									<input type="text" name="checkout" autocomplete="off" class="form-control" id="checkout">		
								</div>
							</div>
							<div class="col-md-12 mt-3">
								<input type="hidden" value="" name="event_id" class="financialeventid">
								<button type="submit" class="btn btn-primary">Submit</button>
								<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div id="importmodal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Import</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<form method="post" action="<?php echo base_url().'/myaccount/facility/add'; ?>" enctype="multipart/form-data" id="importform">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>Upload Excel</label>
									<input type="file" name="import" autocomplete="off" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
								</div>
							</div>
							<div class="col-md-12 mt-3">
								<button type="submit" class="btn btn-primary">Submit</button>
								<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
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
	var userid = '<?php echo $userid; ?>';
	
	$(function(){
		uidatepicker('#checkin, #checkout');
		
		validation(
			'#importform',
			{
				import  	: {
					required	: 	true
				}
			}
		);
	});
	
	$(document).on('click', '#importbtn', function (e) { 
		$('#importfile').click();
	});
	
	$(document).on('click', '#importfile', function (e) { 
		
	});
	
	$(document).on('click', '.financialreport', function (e) { 
		e.preventDefault();
		$('.financialeventid').val($(this).attr('data-id'));
	});
	
	$(document).on('click','.delete',function(){
		var action 	= 	'<?php echo base_url()."/myaccount/facility"; ?>';
		var data   = '\
		<input type="hidden" value="'+$(this).data('id')+'" name="id">\
		<input type="hidden" value="'+userid+'" name="userid">\
		<input type="hidden" value="0" name="status">\
		';
		sweetalert2(action,data);
	});	
</script>
<?php $this->endSection(); ?>
