<?php $this->extend('site/common/layout/layout1') ?>
<?php $this->section('content') ?>
<?php
	$barnstall				= $cartdetail['barnstall']; 
	$rvbarnstall            = $cartdetail['rvbarnstall'];
	$feed             		= $cartdetail['feed'];
	$shaving             	= $cartdetail['shaving'];
?>
<section class="maxWidth">
	<div class="pageInfo">
		<span class="marFive">
			<a href="<?php echo base_url(); ?>">Home /</a>
			<a href="javascript:void(0);"> Checkout</a>
		</span>
	</div>
	<div class="marFive dFlexComBetween eventTP">
		<div class="pageInfo m-0 bg-transparent">
			<span class="eventHead">
				<a href="<?php echo base_url().'/events'; ?>" class="mb-4 d-block"> 
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
					<path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
					</svg> 
					Back To Details
				</a>
				<h1 class="eventPageTitle">Checkout</h1>
			</span>
		</div>
	</div>
</section>

<section class="container-lg">
	<div class="row">
		<div class="col-lg-9">
			<form action="" method="post" class="checkoutform">
				<div class="col-lg-12">
					<div class="checkout-renter border rounded pt-4 ps-4 pe-4 mb-5">
						<h2 class="checkout-fw-6 mb-2">Renter Information</h2>
						<p>Changes to this information will be reflected on all of your existing reservations.</p>
						<div class="row">
							<div class="col-lg-6 mb-4">
								<input placeholder="First Name" name="firstname" autocomplete='off' value="">
							</div>
							<div class="col-lg-6 mb-4">
								<input type="text" placeholder="Last Name" name="lastname" autocomplete='off' value="">
							</div>
						</div>
						<div class="row">
							<div class="col-lg-6  mb-4">
								<input placeholder="Mobile Number" name="mobile" id="mobile" autocomplete='off'  value="">
							</div>
							<div class="col-lg-6 mb-4">
								<span class="info-box d-flex justify-content-between"><img class="dash-info-i" src="<?php echo base_url()?>/assets/site/img/chekout-info.png"><p>You may receive a text message with your stall assignment before your arrival.</p></span>
							</div>
						</div>
					</div> 
					<div class="checkout-payment border rounded pt-4 ps-4 pe-4 mb-5">
						<h2 class="checkout-fw-6 mb-4">Payment Details</h2>
						<div class="row ">
							<div class="col-lg-6 mb-4">
								<div>
									<?php foreach ($paymentmethod as $key => $method){ ?>
										<?php if(in_array($userdetail['type'], explode(',', $method['type']))){ ?>
											<div class="px-3">
												<input type="radio" id="paymentmethod<?php echo $key; ?>" data-error="firstparent" name="paymentmethodid" value="<?php echo $method['id'];?>" style="display: inline;width: auto; margin-right: 10px;"><label for="paymentmethod<?php echo $key; ?>"><?php echo $method['name']; ?></label>
											</div>
										<?php } ?>
									<?php } ?>
								</div>
							</div>
						</div>
						<div class='error hide'><div class='alert' style="color: red;"></div></div> 
					</div>
					<div class="checkout-special border rounded pt-4 ps-4 pe-4 mb-5">
						<h2 class="checkout-fw-6">Special Requests</h2>
						<p>Optional</p>
						<p>Enter any requests, such as stall location or other renters you want to be placed near.
						<b>Please note: special requests are not guaranteed</b></p>
						<textarea placeholder="Message" name="special_notice"></textarea>
					</div> 
					<div class="checkout-reservation border rounded pt-4 ps-4 pe-4 mb-5">
						<h2 class="checkout-fw-6">Reservation Summary</h2>
						<div class="row">
							<div class="col-lg-6 mb-4">
								<b>Event</b>
								<p><?php echo $cartdetail['event_name'];?></p>
								<b>Location</b>
								<p><?php echo $cartdetail['event_location'];?><br>
							</div>
							<div class="col-lg-6 mb-4">
								<b>Venue</b>
								<p><?php echo $cartdetail['event_description'];?></p>
							</div>
						</div>
						<div class="row">
							<h2 class="checkout-fw-6 stallsum-head">Stall Summary</h2>
							<div class="col-lg-6 mb-4">
								<b>Check In</b>
								<p class="mb-4"><?php echo $cartdetail['check_in'] ?></p>
							</div>
							<div class="col-lg-6 mb-4">
								<b>Check Out</b>
								<p><?php echo $cartdetail['check_out'] ?></p>
							</div>
							<div class="col-lg-6 mb-4">
								<b>Number Of Stalls (<?php echo count($barnstall); ?>)</b>
								<p>
								<?php
								$barnname = '';
								foreach ($barnstall as $data) {
									if($barnname!=$data['barn_name']){
										$barnname = $data['barn_name'];
										echo '<p>'.$barnname.'</p>';
									}
									
									echo '<p>'.$data['stall_name'].'</p>';
								}
								?>
								</p>
							</div>
							<?php if(count($rvbarnstall) > 0){ ?>
								<div class="col-lg-6 mb-4">
									<b>Number Of Rv Stall (<?php echo count($rvbarnstall); ?>)</b>
									<p>
									<?php 
									$rvbarnname = '';
									foreach ($rvbarnstall as $rvdata) { 
										if($rvbarnname!= $rvdata['barn_name']){
											$rvbarnname = $rvdata['barn_name'];
											echo '<p>'.$rvbarnname.'</p>';
										}

										echo '<p>'.$rvdata['stall_name'].'</p>';
									}
									?>
									</p>
								</div>
							<?php } ?>
							<?php if(count($feed) > 0){ ?>
								<div class="col-lg-6 mb-4">
									<b>Number Of Feed (<?php echo count($feed); ?>)</b>
									<p>
									<?php 
									foreach ($feed as $feed) { 
										echo '<p>'.$feed['product_name'].'</p>';
									}
									?>
									</p>
								</div>
							<?php } ?>
							<?php if(count($shaving) > 0){ ?>
								<div class="col-lg-6 mb-4">
									<b>Number Of Shaving (<?php echo count($shaving); ?>)</b>
									<p>
									<?php 
									foreach ($shaving as $shaving) { 
										echo '<p>'.$shaving['product_name'].'</p>';
									}
									?>
									</p>
								</div>
							<?php } ?>
						</div>
					</div>
					<div class="checkout-complete-btn">
						<span>
							<input class="form-check-input me-1" type="checkbox" name="tc" data-error="firstparent">I have read and accepted the 
							<span class="redcolor">Terms and Conditions.</span>
						</span>
						<input type="hidden" name="userid" value="<?php echo $userdetail['id']; ?>">
						<input type="hidden" name="email" value="<?php echo $userdetail['email']; ?>">
						<input type="hidden" name="checkin" value="<?php echo formatdate($cartdetail['check_in']); ?>">
						<input type="hidden" name="checkout" value="<?php echo formatdate($cartdetail['check_out']); ?>">
						<input type="hidden" name="price" id="checkout_price">
						<input type="hidden" name="transactionfee" id="checkout_transactionfee">
						<input type="hidden" name="cleaningfee" id="checkout_cleaningfee">
						<input type="hidden" name="amount" id="checkout_amount">
						<input type="hidden" name="eventid" value="<?php echo $cartdetail['event_id']; ?>">
						<input type="hidden" name="eventuserid" value="<?php echo $event['user_id']; ?>">
						<input type="hidden" name="eventtax" value="<?php echo $cartdetail['event_tax']; ?>">
						<input type="hidden" name="type" value="<?php echo $cartdetail['type']; ?>">
						<textarea style="display:none" name="barnstall"><?php echo json_encode($barnstall); ?></textarea>
						<textarea style="display:none" name="rvbarnstall"><?php echo json_encode($rvbarnstall); ?></textarea>
						<textarea style="display:none" name="feed"><?php echo json_encode($feed); ?></textarea>
						<textarea style="display:none" name="shaving"><?php echo json_encode($shaving); ?></textarea>
						<input type="hidden" name="page" value="checkout" >
						<button class="payment-btn checkoutpayment" type="button">Complete Payment</button>
					</div>
				</div>
			</form>
		</div>
		<div class="col-lg-3 cartsummary"></div>
    </div>
</section>
<?php $this->endSection(); ?>
  
<?php $this->section('js') ?>
<?php echo $stripe; ?>
	<script>
		var transactionfee		= '<?php echo $settings["transactionfee"];?>';  
		var currencysymbol 		= '<?php echo $currencysymbol; ?>';
		var pricelists 			= $.parseJSON('<?php echo json_encode($pricelists); ?>');
		
		$(function(){
		    $('#mobile').inputmask("(999) 999-9999");
			validation( 
				'.checkoutform',
				{
					firstname      : {
						required  :   true
					},
					lastname      : {
						required  :   true
					},
					mobile      : {
						required  :   true
					},
					paymentmethodid : {
						required	:	true 
					},
					tc   : {
						required  :   true
					}
				},
				{ 
					firstname      : {
						required    : "Please Enter Your Firstname."
					},
					lastname      : {
						required    : "Please Enter Your Lastname."
					},
					mobile      : {
						required    : "Please Enter Mobile Number."
					},
					paymentmethodid : {
						required    : "Please select one."
					},
					tc   : {
						required    : "Please check the checkbox."
					},
				}
			);
			
			var cartdata 	= cartbox(2, $.parseJSON('<?php echo json_encode($cartdetail); ?>'));
			$('.cartsummary').append(cartdata);
		});

		$('.checkoutpayment').click(function(){
			if($('.checkoutform').valid()){
				var paymentmethod = $('input[type=radio]:checked').val();
				if(paymentmethod=='1'){
					$('.checkoutform').submit();
				}else{
					$('#stripeFormModal').modal('show');
				}
			}
		});
		
		
		$('#stripeFormModal').on('shown.bs.modal', function () { 
			var result = [];
			var formdata = $('.checkoutform').serializeArray();
			
			$.each(formdata, function(i, field){
				if(field.name=='barnstall' || field.name=='rvbarnstall' || field.name=='feed' || field.name=='shaving' ) result.push('<textarea style="display:none;" name="'+field.name+'">'+field.value+'</textarea>')
				else result.push('<input type="hidden" name="'+field.name+'" value="'+field.value+'">')
			});
			
			$('.stripeextra').remove();
			var data = 	'<div class="stripeextra">'+result.join("")+'</div>';
			$('.stripetotal').text('(Total - '+$('#checkout_amount').val()+')');

			$('.stripepaybutton').append(data);
		})
	</script>
<?php $this->endSection() ?>
