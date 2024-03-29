<?php $this->extend('site/common/layout/layout1') ?>
<?php $this->section('content') ?>
<?php
	$id           = isset($result['id']) ? $result['id'] : '';
	$name         = isset($result['name']) ? $result['name'] : '';
	$email        = isset($result['email']) ? $result['email'] : '';
?>
	<h3 class="fw-bold mb-4 mt-2">Operator</h3>
	<div class="card p-3">
		<form method="post" action="" id="accountinformtion" class="accountinformtion">
			<div class="mb-3">
				<label for="username" class="form-label fw-bold form-label mb-1" id="username-lbl" >Name</label>
				<input type="text" name="name"class="form-control"  id="username" value="<?php echo $name ; ?>">
			</div>
			<div class="mb-3">
				<label for="useremail" class="form-label fw-bold form-label mb-1" id="useremail_lbl" >Email address</label>
				<input type="email" name="email" class="form-control"  id="useremail" value="<?php echo $email; ?>">
			</div>
			<div class="mb-3">
				<label for="userpassword" class="form-label fw-bold form-label mb-1" id="userpass_lbl" >Password</label>
				<input type="password" name="password" class="form-control"  id="userpassword" value="">
			</div>
			<input type="hidden" name="actionid" value="<?php echo $id; ?>">
			<input type="hidden" name="parentid" value="<?php echo $userid; ?>">
			<button type="submit" class="btn btn-primary btn-stall" id="updateinfo" >Submit</button>
		</form>
	</div>
<?php $this->endSection(); ?>
<?php $this->section('js') ?>
<script>
$(function(){
	validation(
		'#accountinformtion',
		{
			name      : {
				required  :   true
			},
			email      : {
				required  :   true,
				email     : true,
				remote    : {
					url   :   "<?php echo base_url().'/validation/emailvalidation'; ?>",
					type  :   "post",
					data  :   {id : "<?php echo $id; ?>"},
					async :   false,
				}
			}, 
		},
		{ 
			name      : {
				required    : "Please Enter Your Name."
			},
			email      : {
				required    : "Please Enter Your Email.",
				email     	: "Enter valid email address.",
				remote    	:  "Email Already Taken"
			},
		}
	);
 });
</script>
<?php $this->endSection() ?>