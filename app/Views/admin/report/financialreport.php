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
							<div class="col-md-12">
								<div class="form-group">
									<label>User</label>					
									<select id="userid" class="form-control userlist" name="user_id">
										<option value="" data-usertype="">Select User</option>
										<?php foreach(getUsersList(['type'=>['2', '3'], 'all' => '1']) as $userlist){ ?>
											<option value="<?php echo $userlist['id']; ?>" data-usertype="<?php echo $userlist['type']; ?>" data-userid="<?php echo $userlist['id']; ?>"><?php echo $userlist['name']; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Type</label>
									<?php echo form_dropdown('type', ['' => 'All']+$frtype, '', ['class' => 'form-control eventtype']); ?>						
								</div>
							</div>
							<div class="col-md-12 eventbox">
								<div class="form-group">
									<label>Event</label>
									<select id="eventid" class="form-control eventlist" name="event_id">
										<option value="">All Event</option>
										<?php foreach(getEventsList(['type'=> '1', 'all' => '1']) as $eventlist){ ?>
											<option value="<?php echo $eventlist['id']; ?>" data-userid="<?php echo $eventlist['user_id']; ?>"><?php echo $eventlist['name']; ?></option>
										<?php } ?>
									</select>				
								</div>
							</div>
							<div class="col-md-12 facilitybox displaynone">
								<div class="form-group">
									<label>Facility</label>
									<select id="facilityid" class="form-control facilitylist" name="facility_id">
										<option value="">All Facility</option>
										<?php foreach(getEventsList(['type'=> '2', 'all' => '1']) as $facilitylist){ ?>
											<option value="<?php echo $facilitylist['id']; ?>" data-userid="<?php echo $facilitylist['user_id']; ?>"><?php echo $facilitylist['name']; ?></option>
										<?php } ?>
									</select>						
								</div>
							</div>
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
	dateformat('#checkin, #checkout');
	eventtype()
});

$('#userid').change(function(){
	var userid = $(this).val();
	var usertype = $(this).find('option:selected').attr('data-usertype');
	
	eventtype()
	$('.eventtype').val('');
	$('.eventtype').find('option:eq(2)').removeClass('displaynone');
	if(usertype==3){
		$('.facilitybox').addClass('displaynone');
		$('.eventtype').find('option:eq(2)').addClass('displaynone');
	}
	
	$('#eventid, #facilityid').val('');
	$('#eventid option, #facilityid option').removeClass('displaynone');
	
	if(userid!=''){
		$('#eventid option:not([data-userid="'+userid+'"])').addClass('displaynone');
		$('#facilityid option:not([data-userid="'+userid+'"])').addClass('displaynone');
	}
});

$('.eventtype').change(function(){
	eventtype($(this).val())
})

function eventtype(value=''){
	$('.eventbox, .facilitybox').addClass('displaynone');
	
	if(value=='1'){
		$('.eventbox').removeClass('displaynone');
	}else if(value=='2'){
		$('.facilitybox').removeClass('displaynone');
	}
}
</script>

<?php $this->endSection(); ?>