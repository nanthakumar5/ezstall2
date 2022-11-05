<?php $this->extend("admin/common/layout/layout2") ?>
<?php $this->section('content') ?>
<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Profile</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
						<li class="breadcrumb-item active">Update Profile</li>
					</ol>
				</div>
			</div>
		</div>
	</section>
	<section class="content">
		<div class="page-action">
			<a href="<?php echo getAdminUrl(); ?>/users" class="btn btn-primary">Back</a>
		</div>
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Update Profile</h3>
			</div>
			<div class="card-body">
				<form method="post" action="" id="accountinformtion" class="accountinformtion">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="username" class="form-label" id="username-lbl" >Name</label>
									<input type="text" name="name"class="form-control"  id="username" value="<?php echo $userdetail['name']; ?>">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label for="useremail" class="form-label" id="useremail_lbl" >Email address</label>
									<input type="email" name="email" class="form-control"  id="useremail" value="<?php echo $userdetail['email']; ?>">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label for="userpassword" class="form-label" id="userpass_lbl" >Password</label>
									<input type="password" name="password" class="form-control"  id="userpassword">
								</div>
							</div>
							<div class="col-md-12">
								<input type="hidden" name="actionid" value="<?php echo $userdetail['id']; ?>">
								<button type="submit" class="btn btn-danger account-btn" id="updateinfo" >Update</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</section>
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
				required  :   	true,
				email     : 	true,
				remote    : 	{
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
			}
		}
	);
});
</script>
<?php $this->endSection() ?>