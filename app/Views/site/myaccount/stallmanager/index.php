<?php $this->extend('site/common/layout/layout1') ?>

<?php $this->section('content') ?>
<section class="maxWidth eventPagePanel mt-2">
	<a class="btn-custom-black" href="<?php echo base_url().'/myaccount/stallmanager/add'; ?>">Add Stall Manager</a>
	<?php if(count($list) > 0){ ?>
		<?php foreach ($list as $data) {  ?>
			<div class="dashboard-box mt-4">
				<div class="row align-items-center px-2">
					<div class="col-md-4">
						<label class="fs-7 stall-info-name">Name</label>
						<p class="fs-7 mb-0 text-break"><?php echo $data['name']; ?></p>
					</div>
					<div class="col-md-4">
						<label class="fs-7 stall-info-name">Email</label>
						<p class="fs-7 mb-0 text-break"><?php echo $data['email']; ?></p>
					</div>
					<div class="col-md-4">
						<a class="fs-7 dash-edit" href="<?php echo base_url().'/myaccount/stallmanager/edit/'.$data['id']; ?>">Edit <i class="far fa-edit"></i></a>
						<a class="fs-7 dash-trash delete" data-id="<?php echo $data['id']; ?>" href="javascript:void(0);">Delete <i class="far fa-trash-alt"></i></a>
					</div>
				</div>
			</div>
		<?php } ?>
	<?php }else{ ?>
		<p class="mt-3">No Record Found</p>
	<?php } ?>
	<?php echo $pager; ?>
</section>
<?php $this->endSection(); ?>

<?php $this->section('js') ?>
<script>
	var userid = '<?php echo $userid; ?>';

	$(document).on('click','.delete',function(){
		var action 	= 	'<?php echo base_url()."/myaccount/stallmanager"; ?>';
		var data   = '\
		<input type="hidden" value="'+$(this).data('id')+'" name="id">\
		<input type="hidden" value="'+userid+'" name="userid">\
		<input type="hidden" value="0" name="status">\
		';
		sweetalert2(action,data);
	});	
</script>
<?php $this->endSection(); ?>
