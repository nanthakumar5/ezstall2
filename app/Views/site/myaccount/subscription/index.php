<?php $this->extend('site/common/layout/layout1') ?>

<?php $this->section('content') ?>
	<style type="text/css">
	input[type="radio"] {
		display: inline-block;
	}
	</style>
	<?php  	 
		$currentdate 	  		= date("Y-m-d"); 
		$subscriptionenddate	= $userdetail['subscriptionenddate'];
		$subscriptiondate 		= $subscriptionenddate != NULL ? date("Y-m-d", strtotime($subscriptionenddate)) : '';
	?>		
	<?php if($subscriptionenddate=='' || $subscriptiondate=='0000-00-00' || $subscriptiondate < $currentdate){ ?>			
		<?php if($subscriptionenddate!='' && $subscriptiondate < $currentdate){ ?>
			<div class="">
				<h6><?php echo 'Your Subscription plan ended'; ?></h6>
				<h6>Your Last Subscription Plan is : </h6>
				<h6>Amount : <?php echo $currencysymbol.$subscriptions['amount']; ?></h6>
				<h6>Subscription Plan : <?php echo $subscriptions['planname']; ?></h6>
			</div>
		<?php } ?>
		<?php foreach($plans as $plan){ ?>
			<div class="col payment-border">
				<div class="text-center">
					<input type="radio" class="subscribe" name="subscribe">
					<label class="subscription_select_label"><?php echo $plan['name']; ?></label>
					<label class="subscription_select_label"><?php echo $currencysymbol.$plan['price']; ?></label>
						<div class="paymentfields">
							<input type="hidden" value="<?php echo $plan['id']; ?>" name="plan_id">
							<input type="hidden" value="<?php echo $plan['name']; ?>" name="plan_name">
							<input type="hidden" value="<?php echo $plan['price']; ?>" name="plan_price">
							<input type="hidden" value="<?php echo $plan['interval']; ?>" name="plan_interval">
							<input type="hidden" value="2" name="type">
						</div>
				</div>
				<div class="choose_subscription_btn text-center">
					<button class="pay-btn paynowbtn">Pay Now</button>
					<button style="display:none" class="pay-btn paynowhidden"  type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#stripeFormModal" data-bs-whatever="@getbootstrap"></button>
				</div>
			</div>
		<?php } ?>			
	<?php } else{ ?>
		<div class="col payment-border">
			<div>
				<h6>Your subscription was activated.</h6><h6>Your next subscription payment will be due by <?php echo formatdate($userdetail['subscriptionenddate'], 1);?></h6>
			</div>
		</div>
	<?php } ?>
<?php $this->endSection(); ?>

<?php $this->section('js') ?>
	<?php echo $stripe; ?>
	<script>
		$('.paynowbtn').click(function(){
			if(!$(this).parent().parent().find('.subscribe').is(':checked')){
				$(this).parent().parent().find('.subscribe').focus();
			}else{
				$(this).parent().find('.paynowhidden').click();
				
				$('.stripeextra').remove();
				var data = 	'<div class="stripeextra">'+$(this).parent().parent().find('.paymentfields').html()+'</div>';
				$('.stripepaybutton').append(data);
			}
		})
	</script>
<?php echo $this->endSection(); ?>
