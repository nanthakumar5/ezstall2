<?= $this->extend("admin/common/layout/layout2") ?>

<?php $this->section('content') ?>
	<?php
		$id 					= isset($result['id']) ? $result['id'] : '';
		$userid 				= isset($result['user_id']) ? $result['user_id'] : '';
		$name 					= isset($result['name']) ? $result['name'] : '';
		$email 					= isset($result['email']) ? $result['email'] : '';
		$type 					= isset($result['type']) ? $result['type'] : '';
		$parentid 				= isset($result['parent_id']) ? $result['parent_id'] : '';
		$stripe_account_id 		= isset($result['stripe_account_id']) ? $result['stripe_account_id'] : '';
		$status 				= isset($result['status']) ? $result['status'] : '';
		$pageaction 			= $id=='' ? 'Add' : 'Update';
	?>
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Users</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
						<li class="breadcrumb-item"><a href="<?php echo getAdminUrl(); ?>/users">Users</a></li>
						<li class="breadcrumb-item active"><?php echo $pageaction; ?> User</li>
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
				<h3 class="card-title"><?php echo $pageaction; ?> User</h3>
			</div>
			<div class="card-body">
				<form method="post" id="form" action="<?php echo getAdminUrl(); ?>/users/action">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label>Name</label>								
									<input type="name" name="name" class="form-control" id="name" placeholder="Enter Name" value="<?php echo $name; ?>">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Email</label>								
									<input type="email" name="email" class="form-control" id="email" placeholder="Enter Email" value="<?php echo $email; ?>">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Password</label>								
									<input type="password" name="password" class="form-control" id="password" placeholder="Enter Password">								
									<?php if($id!=''){ ?>
										<p class="tagline">(Leave blank if you don't want to change the password).</p>
									<?php } ?>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Type</label>	
									<?php  echo form_dropdown('type', ['' => 'Select Type']+$usertype, $type, ['id' => 'type', 'class' => 'form-control usertype']); ?>	
									<?php ?>
								</div>
							</div>
							<div class="col-md-12 parentid">
								<div class="form-group">
									<label>Parent ID</label>
									<?php echo form_dropdown('parentid', getUsersList(['type'=>['2','3']]), $parentid, ['id' => 'parentid', 'class' => 'form-control  ']); ?>						
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Status</label>								
									<?php echo form_dropdown('status', ['' => 'Select Status']+$userstatus, $status, ['id' => 'status', 'class' => 'form-control']); ?>
								</div>
							</div>
							<div class="col-md-12">
								<input type="hidden" name="actionid" value="<?php echo $id; ?>">
								<input type="submit" class="btn btn-danger" value="Submit">
								<a href="<?php echo getAdminUrl(); ?>/users" class="btn btn-dark">Back</a>
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
		var id          = '<?php echo $id; ?>';
        var type        = '<?php echo $type; ?>';

		$(function(){ 
			parentid(type);
			validation(
				'#form',
				{
					name 	: {
						required	: 	true
					},
					email 	: {
						required	: 	true,
						remote		: 	{
											url		: 	"<?php echo base_url().'/validation/emailvalidation'; ?>",
											type	: 	"post",
											async	: 	false,
											data	: 	{ id : id }
										}
					},					
					password : {
						required	:  	function() {
											if(id=="") return true;
											else return false;
										},
						maxlength	: 15,
						minlength	: 6
					},
					type    : {
						required	: 	true
					},
					status  : {  
					    required	: 	true
					}
				},
				{
					email : {
						remote		: 	"Enter valid email address."
					}
				}
			);
		});	

		$('.usertype').change(function(){ 
			parentid($(this).val());
		});	

		function parentid(val){ 
            if(val=='4'){ 
                $('.parentid').removeClass('displaynone');
            }else{
                $('.parentid').addClass('displaynone');
            }           
        }
	</script>
<?php $this->endSection(); ?>

