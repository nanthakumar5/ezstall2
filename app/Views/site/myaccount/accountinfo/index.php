<?php $this->extend('site/common/layout/layout1') ?>
<?php $this->section('content') ?>
  <h2 class="fw-bold mb-4">Account Information</h2>
  <form method="post" action="" id="accountinformtion" class="accountinformtion">
    <div class="mb-3">
      <label class="form-label" id="username-lbl" >Name</label>
      <input type="text" name="name"class="form-control"  id="username" value="<?php echo $userdetail['name']; ?>">
    </div>
    <div class="mb-3">
      <label class="form-label" id="useremail_lbl" >Email address</label>
      <input type="email" name="email" class="form-control"  id="useremail" value="<?php echo $userdetail['email']; ?>">
    </div>
    <div class="mb-3">
      <label class="form-label" id="userpass_lbl" >Password</label>
      <input type="password" name="password" class="form-control"  id="userpassword" value="">
    </div>
    <div class="mb-3">
      <label class="form-label" id="accountid-lbl">Stripe Email ID</label>
      <input type="email" name="stripe_email" class="form-control"  id="stripeemail" value="<?php echo $userdetail['stripe_email']; ?>">
      <input type ="hidden" name="stripe_account_id" id="stripe_account_id" class="form-control" value="<?php echo $userdetail['stripe_account_id'];?>">
    </div>
    <input type="hidden" name="actionid" id="userid" value="<?php echo $userdetail['id']; ?>">
    <button type="submit" class="account-btn" id="updateinfo" >Update</button>
  </form>
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
      stripe_email    : { 
          required  : true,
          email     : true   
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