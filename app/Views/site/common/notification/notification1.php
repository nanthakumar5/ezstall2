<?php 
	if(session()->getFlashdata('danger')){
?>
		<div class="alert alert-danger alert-dismissible fade show m-2" role="alert">
			<?php echo session()->getFlashdata('danger'); ?>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">X</button>
		</div>
<?php
	}else if(session()->getFlashdata('success')){
?>
		<div class="alert alert-success alert-dismissible fade show m-2" role="alert">
			<?php echo session()->getFlashdata('success'); ?>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">X</button>
		</div>
<?php
	}
?>