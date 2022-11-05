<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Ezstall Management</title>
	
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/plugins/fontawesome-free/css/all.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/adminlte.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/plugins/datatables/css/dataTables.bootstrap4.min.css"  />		
	<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/plugins/datatables/css/responsive.bootstrap4.min.css"  />		
	<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/plugins/sweetalert2/sweetalert2.min.css"  />
	<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/bootstrap.min.css">		
	<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/css/custom.css">
	
</head>
<body class="hold-transition sidebar-mini">
	<div class="wrapper">
		<nav class="main-header navbar navbar-expand navbar-white navbar-light">
			<ul class="navbar-nav">
				<li class="nav-item">
				   <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
				</li>
			</ul>
			<ul class="navbar-nav ml-auto"></ul>
		</nav>

		<aside class="main-sidebar sidebar-dark-primary elevation-4">
			<a href="<?php echo getAdminUrl(); ?>" class="brand-link text-decoration-none">
				<span class="brand-text font-weight-light">Ezstall Management</span>
			</a>
			<div class="sidebar">
			   <nav class="mt-2">
					<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
					<?php echo $this->include('admin/common/sidebar/sidebar1') ?>
					</ul>
			  </nav>
			</div>
		</aside>

		<div class="content-wrapper">
			<?php echo $this->include('admin/common/notification/notification1') ?>
			<?php echo $this->renderSection('content') ?>
		</div>
        <footer class="main-footer">
			<div class="float-right d-none d-sm-block"></div>
			<strong>Copyright &copy; 2022.</strong> All rights reserved.
		</footer>
    </div>
    <script src="<?php echo base_url();?>/assets/plugins/jquery/jquery.min.js"></script>
	<script src="<?php echo base_url();?>/assets/plugins/jquery-validation/jquery.validate.min.js"></script>
	<script src="<?php echo base_url();?>/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
	<script src="<?php echo base_url();?>/assets/plugins/datatables/js/jquery.dataTables.min.js"></script>
	<script src="<?php echo base_url();?>/assets/plugins/datatables/js/dataTables.bootstrap4.min.js"></script>
	<script src="<?php echo base_url();?>/assets/plugins/datatables/js/dataTables.responsive.min.js"></script>
	<script src="<?php echo base_url();?>/assets/plugins/datatables/js/responsive.bootstrap4.min.js"></script>
	<script src="<?php echo base_url();?>/assets/plugins/sweetalert2/sweetalert2.min.js"></script>
	<script src="<?php echo base_url();?>/assets/plugins/tinymce/tinymce.min.js"></script>
	<script src="<?php echo base_url();?>/assets/js/adminlte.min.js"></script>
	<script src="<?php echo base_url();?>/assets/plugins/rater/rater.min.js"></script>
	<!-- bootstrap 5 cdn-->
	<script src="<?php echo base_url();?>/assets/js/bootstrap.bundle.min.js"></script>
	<script src="<?php echo base_url();?>/assets/js/custom.js"></script>
	<script src="<?php echo base_url();?>/assets/plugins/inputmask/inputmask.js"></script>
	<?php $this->renderSection('js') ?>
</body>
</html>