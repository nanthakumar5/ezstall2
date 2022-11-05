<?php $this->extend('site/common/layout/layout1') ?>
<?php $this->section('content') ?>

<section class="signInFlex">
<div class="signInLeft">
    <img class="signInImage" src="<?php echo base_url()?>/assets/site/img/signup_img.jpg" alt="Horse Image">
</div>
<div class="signInRight">
    <div class="signInFormPanel">
        <h1>Let's Sign Up</h1><p>Enter details to sign up</p>
        <form class="signInForm" method="post" action="" id="form" autocomplete="off">
            <div class="wrapper">
                <input type="radio" name="type" id="option-1" value="5" checked="">
                <input type="radio" name="type" id="option-2" value="2">
                <input type="radio" name="type" id="option-3" value="3">
                <label for="option-1" class="option option-1">
                    <div class="dot"></div>
                    <span>Horse Owner</span>
                </label>
                <label for="option-2" class="option option-2">
                    <div class="dot"></div>
                    <span>Facility</span>
                </label>
                <label for="option-3" class="option option-3">
                    <div class="dot"></div>
                    <span>Producer</span>
                </label>
            </div>
            <span>
                <input type="text" class="signInText" placeholder="Enter username" name="name">
            </span>
            <span>
                <input type="email" class="signInEmail" placeholder="Enter email" name="email">
            </span>
            <span>
                <input type="password" class="signInPassword" placeholder="Create password" name="password">
				<input type="hidden" value="1" name="status">
            </span>
			<button class="signInSubmitBtn" type="submit">Sign Up</button><p>Already have an account ?
			<a href="<?php echo base_url()?>/login" class="signUpLink"> Sign In</a></p>
        </form>
    </div>
</div>
</section>
<?php $this->endSection(); ?>
<?php $this->section('js') ?>
    <script>
        $(function(){
            validation(
                '#form',
                {
                    name        : {
                        required  : true
                    },
                    email       : {
                        required  : true,
                        email     : true,
                        remote    : {
										url   :   "<?php echo base_url().'/validation/emailvalidation'; ?>",
										type  :   "post",
										async :   false,
									}
      
                    },
                    password    : {
                        required  : true,
						minlength : 6
                    }
                },
                {   
                    name        : {
                        required  : "Name field is required."
                    },
                    email       : {
                        required  : "Email field is required.",
                        email     : "Enter valid email address.",
                        remote    :  "Email Already Taken"
                    },
                    password    : {
                        required  : "Password field is required."
                    }
                }
            );
        });
    </script>
<?php echo $this->endSection() ?>