<?php $this->extend('site/common/layout/layout1') ?>

<?php $this->section('content') ?>
<section class="signInFlex">
<div class="signInLeft">
    <img class="signInImage" src="<?php echo base_url()?>/assets/site/img/signin_img.jpg" alt="Horse Image">
</div>
<div class="signInRight">
    <div class="signInFormPanel">
        <h1 class="topPad">Forgot Password</h1>
        <form class="signInForm" id="form" method="post" action="" autocomplete="off">
            <span>
                <input type="email" class="signInEmail" placeholder="Enter email" name="email">
            </span>
            <button type="submit" class="signInSubmitBtn">Submit</button>
            <p>
                <a href="<?php echo base_url()?>/login" class="signUpLink">Sign In</a>
            </p>
            <p>
				Don't have an account ?
                <a href="<?php echo base_url()?>/register" class="signUpLink">Sign Up</a>
            </p>
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
					email		: {
						required  : true,
						email  	  : true
					}
				},
				{
					email		: {
						required  : "Email field is required.",
						email  	  : "Enter valid email address."
					}
				}
			);
		});
	</script>
<?php echo $this->endSection() ?>