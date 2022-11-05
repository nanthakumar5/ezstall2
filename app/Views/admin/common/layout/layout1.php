<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Ezstall Management</title>
  
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

	<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/plugins/fontawesome-free/css/all.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/plugins/icheck-bootstrap/css/icheck-bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/adminlte.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/custom.css">
</head>
<body class="hold-transition login-page">
	<div class="login-box">
		<?php echo $this->include('admin/common/notification/notification1') ?>
		<div class="login-logo">
		   <a href="javascript:void(0);" class="text-decoration-none"><b>Ezstall</b> Management</a>
		</div>
		<?php echo $this->renderSection('content') ?>
	</div>

	<script src="<?php echo base_url();?>/assets/plugins/jquery/jquery.min.js"></script>
	<script src="<?php echo base_url();?>/assets/plugins/jquery-validation/jquery.validate.min.js"></script>
	<script src="<?php echo base_url();?>/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="<?php echo base_url();?>/assets/js/adminlte.min.js"></script>
	<script src="<?php echo base_url();?>/assets/js/custom.js"></script>
<?php $this->renderSection('js') ?>
</body>
</html>	
      