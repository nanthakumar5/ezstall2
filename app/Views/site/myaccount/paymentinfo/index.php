<?php $this->extend('site/common/layout/layout1') ?>
<?php $this->section('content') ?>

<h2 class="fw-bold mb-4">Payment Details</h2>
<section class="maxWidth eventPagePanel">
<?php if(!empty($payments)) {  ?>
<?php foreach ($payments as $data) { ?>
	<div class="dashboard-box">
	<div class="EventFlex leftdata">
	<div class="wi-30 row w-100 align-items-center">
	<div class="col-md-2">
	<div>
	<p class="mb-0 text-sm fs-7 fw-600">Status</p>
	<p class="mb-0 fs-7"><?php echo $paymentstatus[$data['status']];?></p>
	</div>
	</div>
	<div class="col-md-3">
	<div>
	<p class="mb-0 text-sm fs-7 fw-600 w-100">Transaction ID</p>
	<p class="mb-0 fs-7"><?php echo $data['id'];?></p>
	</div>
	</div>
	<div class="col-md-4">
	<div>
	<p class="mb-0 text-sm fs-7 fw-600">Name</p>
	<p class="mb-0 fs-7"><?php echo $data['name'];?></p>
	</div>
	</div>
	<div class="col-md-2">
	<div>
	<p class="mb-0 text-sm fs-7 fw-600">Amout:</p>
	<p class="mb-0 fs-7"><?php echo $currencysymbol.($data['status']=='1' ? $data['amount'] : $data['refund_amount']);?></p>
	</div>
	</div>
	<div class="col-md-2">
	<div>
	<p class="mb-0 text-sm fs-7 fw-600">Paid By:</p>
	<p class="mb-0 fs-7"><?php echo $usertype[$data['usertype']]; ?></p>
	</div>
	</div>
	<div class="col-md-3">
	<div>
	<p class="mb-0 text-sm fs-7 fw-600">Paid Date:</p>
	<p class="mb-0 fs-7"><?php echo date('m-d-Y h:i A', strtotime($data['created']));?></p>
	</div>
	</div>
	<div class="col-md-5">
	<div class="d-flex justify-content-end">
	<a href="<?php echo base_url().'/myaccount/payments/view/'.$data['id']; ?>" class="view-res">View</a>
	</div>
	</div>
	</table>   
	</div>
	</div>
	</div>
<?php } ?>
<?php }else{ ?>
		<p>No Reservation Found.</p>
	<?php } ?>

</section>
<?php echo $pager; ?>
<?php $this->endSection(); ?>
