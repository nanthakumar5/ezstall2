<?php $this->extend('site/common/layout/layout1') ?>
<?php $this->section('content') ?>

<h2 class="fw-bold mb-4">Transaction Details</h2>
<section class="maxWidth eventPagePanel">
<?php if(!empty($transactions)) {  ?>
<?php foreach ($transactions as $data) { ?>
	<div class="dashboard-box">
	<div class="EventFlex leftdata">
	<div class="wi-30 row w-100 align-items-center">
	<div class="col-md-3">
	<div>
	<p class="mb-0 text-sm fs-7 fw-600">Name</p>
	<p class="mb-0 fs-7"><?php echo $data['username'];?></p>
	</div>
	</div>
	<div class="col-md-3">
	<div>
	<p class="mb-0 text-sm fs-7 fw-600">Transaction Amount:</p>
	<p class="mb-0 fs-7"><?php echo $currencysymbol.$data['amount'];?></p>
	</div>
	</div>
	<div class="col-md-3">
	<div>
	<p class="mb-0 text-sm fs-7 fw-600">Transaction By:</p>
	<p class="mb-0 fs-7"><?php echo $usertype[$data['transferusertype']]; ?></p>
	</div>
	</div>
	<div class="col-md-3">
	<div>
	<p class="mb-0 text-sm fs-7 fw-600">Transaction Date:</p>
	<p class="mb-0 fs-7"><?php echo date('m-d-Y h:i A', strtotime($data['created_at']));?></p>
	</div>
	</div>
	</table>   
	</div>
	</div>
	</div>
<?php } ?>
<?php }else{ ?>
		<p>No Transaction Found.</p>
	<?php } ?>

</section>
<?php echo $pager; ?>
<?php $this->endSection(); ?>
