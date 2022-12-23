<?php $this->extend('site/common/layout/layout1') ?>
<?php $this->section('content') ?>
<?php $currentusertype = $userdetail['type']; ?>
<?php
      $transactionid  	= isset($result['id']) ? $result['id'] : '';
      $name       	    = isset($result['username']) ? $result['username'] : '';
      $firstname     	= isset($result['firstname']) ? $result['firstname'] : '';
      $lastname         = isset($result['lastname']) ? $result['lastname'] : '';
      $email          	= isset($result['email']) ? $result['email'] : '';
      $type           	= isset($result['type']) ? $paymenttype[$result['type']] : '';
      $amount         	= isset($result['amount']) ? ($currentusertype=='5' ? $result['amount'] : $result['amount']-$result['transaction_fee']) : '';
      $plan_start     	= isset($result['plan_period_start']) ? formatdate($result['plan_period_start'], 1) : '';
      $plan_end       	= isset($result['plan_period_start']) ? formatdate($result['plan_period_start'], 1) : '';
      $created        	= isset($result['created']) ? formatdate($result['created'], 2) : '';
?>
<div class="row">
  <div class="col">
    <h2 class="fw-bold mb-4">View Payment Details</h2>
  </div>
  <div class="col" align="right">
    <a href="<?php echo base_url().'/myaccount/payments';?>" class="btn back-btn">Back</a>
  </div>
</div>
<section class="maxWidth eventPagePanel">
    <div class="row col-md-12 base-style">
    <div class="col fw-600 paymentflex">
      <p class="my-2">Transaction ID</p>
    </div>
    <div class="col" align="left">
      <p class="my-2"><?php echo $transactionid;?></p>
    </div> 
  </div> 
  <div class="row col-md-12 base-style">
    <div class="col fw-600 paymentflex">
      <p class="my-2">Name</p>
    </div>
    <div class="col" align="left">
      <p class="my-2"><?php if($firstname!='') echo $firstname.$lastname; else echo $name;?></p>
    </div>
  </div>
  <div class="row col-md-12 base-style">
    <div class="col fw-600 paymentflex">
      <p class="my-2">Name On Card</p>
    </div>
    <div class="col" align="left">
      <p class="my-2"><?php echo $name;?></p>
    </div>
  </div>
  <div class="row col-md-12 base-style">
    <div class="col fw-600 paymentflex">
      <p class="my-2">Email</p>
    </div>
    <div class="col" align="left">
      <p class="my-2"><?php echo $email;?></p>
    </div>
  </div>
  <div class="row col-md-12 base-style">
    <div class="col fw-600 paymentflex">
      <p class="my-2">Payment Type</p>
    </div>
    <div class="col" align="left">
      <p class="my-2"><?php echo $type;?></p>
    </div>
  </div>  
  <div class="row col-md-12 base-style">
    <div class="col fw-600 paymentflex">
      <p class="my-2">Amount</p>
    </div>
    <div class="col" align="left">
      <p class="my-2"><?php echo $currencysymbol.$amount;?></p>
    </div>
  </div>  
  <?php if($type == 2 ){?>
  <div class="row col-md-12 base-style">
    <div class="col fw-600 paymentflex">
      <p class="my-2">Plan Date</p>
    </div>
    <div class="col" align="left">
      <p class="my-2"><?php echo $plan_start;?></p>
    </div>
  </div>
  <div class="row col-md-12 base-style">
    <div class="col fw-600 paymentflex">
      <p class="my-2">Plan End</p>
    </div>
    <div class="col" align="left">
      <p class="my-2"><?php echo $plan_end;?></p>
    </div>
  </div>
<?php }else{ ?>
  <div class="row col-md-12 base-style">
    <div class="col fw-600 paymentflex">
      <p class="my-2">Payed Date</p>
    </div>
    <div class="col" align="left">
      <p class="my-2"><?php echo $created; ?></p>
       <div class="col" align="left">
      <p class="my-2"></p>
    </div>
    </div>
  </div>
<?php } ?>
   <div class="row col-md-12 base-style">
    <div class="col fw-600 paymentflex">
      <p class="my-2">Paid By</p>
    </div>
    <div class="col" align="left">
      <p class="my-2"><?php echo $usertype[$result['usertype']]; ?></p>
    </div>
  </div>
</section>
<?php $this->endSection(); ?>
