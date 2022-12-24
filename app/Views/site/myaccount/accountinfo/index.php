<?php $this->extend('site/common/layout/layout1') ?>
<?php $this->section('content') ?>
	<h2 class="fw-bold mb-3 mt-2">Account Information</h2>
	<div class="card p-3">
		<form method="post" action="" id="accountinformtion" class="accountinformtion">
			<div class="mb-3">
				<label class="form-label fw-bold form-label mb-1" id="username-lbl" >Name</label>
				<input type="text" name="name"class="form-control"  id="username" value="<?php echo $userdetail['name']; ?>">
			</div>
			<div class="mb-3">
				<label class="form-label fw-bold form-label mb-1" id="useremail_lbl" >Email address</label>
				<input type="email" name="email" class="form-control"  id="useremail" value="<?php echo $userdetail['email']; ?>">
			</div>
			<div class="mb-3">
				<label class="form-label fw-bold form-label mb-1" id="userpass_lbl" >Password</label>
				<input type="password" name="password" class="form-control"  id="userpassword" value="">
			</div>
			<input type="hidden" name="actionid" value="<?php echo $userdetail['id']; ?>">
			<input type="hidden" name="userid" value="<?php echo $userdetail['id']; ?>">
			<button type="submit" class="account-btn" id="updateinfo" >Update</button>
		</form>
	</div>
	<?php if(in_array($userdetail['type'], ['2', '3'])){ ?>
		<h2 class="fw-bold mt-4 mb-2">Stripe</h2>
		<div class="mb-2">
			<?php if(isset($stripeaccountid) && $stripeaccountid!=''){ ?>
				<a href="javascript:void(0);" class="btn btn-primary mt-1">Connected</a>
			<?php }else{ ?>
				<a href="<?php echo base_url('myaccount/stripeconnect'); ?>" class="btn btn-primary mt-1">Connect with stripe</a>
			<?php } ?>
		</div>
	<?php } ?>
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
                      data  :   {id : "<?php echo $userdetail['id']; ?>"},
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
        remote    	: "Email Already Taken"
      },
    }
  );
  });

	</script>
<?php $this->endSection() ?>