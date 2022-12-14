<?php $this->extend('site/common/layout/layout1') ?>

<?php $this->section('content') ?>
<section class="signInFlex">
	<div class="signInLeft">
		<img class="signInImage" src="<?php echo base_url()?>/assets/site/img/signin_img.jpg" alt="Horse Image">
	</div>
	<div class="signInRight">
		<div class="signInFormPanel">
			<?php if($expired==0){ ?>
				<h1 class="topPad">Change Password</h1>
				<form class="signInForm" id="form" method="post" action="" autocomplete="off">
					<span>
						<input type="password" class="signInPassword" placeholder="Enter Password" id="password" name="password">
					</span>
					<span>
						<input type="password" class="signInPassword" placeholder="Enter Confirm Password" name="confirmpassword">
					</span>
					<button type="submit" class="signInSubmitBtn">Submit</button>
				</form>			
			<?php }else{ ?>
				<p class="topPad">Your Link is Expired.</p>
			<?php } ?>
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
					password	: {
						required  	: true,
						minlength	: 6
					},
					confirmpassword	: {
						required  	: true,
						equalTo		: "#password"
					}
				},
				{
					password	: {
						required  : "Password field is required."
					},
					confirmpassword	: {
						required  : "Confirm Password field is required.",
						equalTo  : "Password is not matched."
					}
				}
			);
		});
	</script>
<?php echo $this->endSection() ?>