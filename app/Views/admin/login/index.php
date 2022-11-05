<?php $this->extend('admin/common/layout/layout1') ?>

<?php $this->section('content') ?>
	<div class="card">
		<div class="card-body login-card-body">
			<p class="login-box-msg">Sign In</p>
			<form action="<?php echo getAdminUrl(); ?>" id="form" method="post">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<input type="text" name="email" class="form-control" placeholder="Email">
						</div>
					</div> 
					<div class="col-md-12">
						<div class="form-group">
							<input type="password" name="password" class="form-control" placeholder="Password">
						</div>
					</div>
					<div class="col-12">
						<button type="submit" class="btn btn-primary btn-block">Submit</button>
					</div>
				</div>
			</form>
		</div>
	</div>	
	
<?php $this->endSection() ?>

<?php $this->section('js') ?>
	<script>
		$(function(){
			validation(
				'#form',
				{
					email		: {
						required  : true,
						email  	  : true
					},
					password	: {
						required  : true
					}
				},
				{
					email		: {
						required  : "Email field is required.",
						email  	  : "Enter valid email address."
					},
					password	: {
						required  : "Password field is required."
					}
				}
			);
		});
	</script>
<?php echo $this->endSection() ?>