<?php 
	if(session()->getFlashdata('danger')){
?>
		<div class="alert alert-danger alert-dismissible fade show m-2" role="alert">
			<?php echo session()->getFlashdata('danger'); ?>
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		</div>
<?php
	}else if(session()->getFlashdata('success')){
?>
		<div class="alert alert-success alert-dismissible fade show m-2" role="alert">
			<?php echo session()->getFlashdata('success'); ?>
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
		</div>
<?php
	}
?>