<?= $this->extend("admin/common/layout/layout2") ?>
<?php $this->section('content') ?>
	<?php
		$id 					= isset($result['id']) ? $result['id'] : '';
		$username       		= isset($result['username']) ? $result['username'] : '';
		$name 					= isset($result['name']) ? $result['name'] : '';
		$email 					= isset($result['email']) ? $result['email'] : '';
		$type           		= isset($result['type']) && $result['type']=='1' ? 'Payment' : 'Subscription';
		$amount 				= isset($result['amount']) ? $result['amount'] : '';
		$plan_start 		    = isset($result['plan_period_start']) ? $result['plan_period_start'] : '';
		$plan_start             = formatdate($plan_start, 1);
		$plan_end 		        = isset($result['plan_period_start']) ? $result['plan_period_end'] : '';
		$plan_end               = formatdate($plan_end, 1);
		$created        		= isset($result['created']) ? formatdate($result['created'], 2) : '';
	?>
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Payments</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
						<li class="breadcrumb-item"><a href="<?php echo getAdminUrl(); ?>/payments">Payments</a></li>
						<li class="breadcrumb-item active">View Payments</li>
					</ol>
				</div>
			</div>
		</div>
	</section>
	
	<section class="content">
		<div class="page-action">
			<a href="<?php echo getAdminUrl(); ?>/payments" class="btn btn-primary">Back</a>
		</div>
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">View Payments</h3>
			</div>
			<div class="card-body">
				<table class="table">
				  <tbody>
						<tr>
							<th>Transaction ID</th>
							<td><?php echo $id;?></td>
						</tr>
						<tr>
							<th>Name</th>
							<td><?php echo $username;?></td>
						</tr>
						<tr>
							<th>Name On Card</th>
							<td><?php echo $name;?></td>
						</tr>
						<tr>
							<th>Email</th>
							<td><?php echo $email;?></td>
						</tr>
						<tr>
							<th>Payment Type</th>
							<td><?php echo $type;?></td>
						</tr>
						<tr>
							<th>Amount</th>
							<td><?php echo $currencysymbol.$amount;?></td>
						</tr>
						<?php if($type == 2 ){?>
						<tr>
							<th>Plan Date</th>
							<td><?php echo $plan_start;?></td>
						</tr>
						<tr>
							<th>Plan End</th>
							<td><?php echo $plan_end;?></td>
						</tr>
						<?php } else{ ?>
						<tr>
							<th>Payed Date</th>
							<td><?php echo $created;?></td>
						</tr>
						<?php } ?>
						<tr>
							<th>Paid By</th>
							<td><?php echo $usertype[$result['usertype']]; ?></td>
						</tr>
				  </tbody>
				</table>
			</div>
		</div>
	</section>
<?php $this->endSection(); ?>