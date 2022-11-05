<?php $this->extend('site/common/layout/layout1') ?>
<?php $this->section('content') ?>
<?php
	$barnstall				= $cartdetail['barnstall']; 
	$rvbarnstall            = $cartdetail['rvbarnstall'];
	$feed             		= $cartdetail['feed'];
	$shaving             	= $cartdetail['shaving'];
	$cartprice				= number_format(floor($cartdetail['price']*100)/100, 2);
	$transactionfee 		= number_format((($settings['transactionfee'] / 100) * $cartprice), 2);
	$cleaningfee 			= $cartdetail['cleaning_fee'] ? number_format($cartdetail['cleaning_fee'], 2) : 0;
	$tax					= number_format(floor($cartdetail['event_tax']*100)/100, 2);
	$amount					= number_format($cartprice+$transactionfee+$cleaningfee+$tax, 2);
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
					
					<input type="hidden" name="userid" value="<?php echo $userdetail['id']; ?>">
					<input type="hidden" name="email" value="<?php echo $userdetail['email']; ?>" >
					<input type="hidden" name="checkin" value="<?php echo formatdate($cartdetail['check_in']); ?>" >
					<input type="hidden" name="checkout" value="<?php echo formatdate($cartdetail['check_out']); ?>" >
					<input type="hidden" name="price" value="<?php echo $cartprice; ?>" >
					<input type="hidden" name="transactionfee" value="<?php echo $transactionfee; ?>" >
					<input type="hidden" name="cleaningfee" value="<?php echo $cleaningfee; ?>" >
					<input type="hidden" name="amount" value="<?php echo $amount; ?>" >
					<input type="hidden" name="eventid" value="<?php echo $cartdetail['event_id']; ?>" >
					<input type="hidden" name="eventtax" value="<?php echo $cartdetail['event_tax']; ?>" >
					<input type="hidden" name="type" value="<?php echo $cartdetail['type']; ?>" >
					<input type="hidden" name="barnstall" value='<?php echo json_encode($barnstall); ?>'>
					<input type="hidden" name="rvbarnstall" value='<?php echo json_encode($rvbarnstall); ?>'>
					<input type="hidden" name="feed" value='<?php echo json_encode($feed); ?>'>
					<input type="hidden" name="shaving" value='<?php echo json_encode($shaving); ?>'>
					<input type="hidden" name="page" value="checkout" >

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
								$barnstalldata = '';
								$barnname = '';
								foreach ($barnstall as $data) {
									if($barnname!=$data['barn_name']){
										$barnname = $data['barn_name'];
										$barnstalldata .= '<div><span class="col-12 fw-bold">'.$barnname.'</span></div>';
										echo '<p>'.$barnname.'</p>';
									}
									
									if($data['interval']%7==0){
										$intervalss = $data['interval']/7;
									}else if($data['interval']%30==0){
										$intervalss = $data['interval']/30; 
									}else{ 
										$intervalss = $data['interval'];
									}

									if($data['chargingid']=='4'){
										$intervaldays = $currencysymbol.$data['price'];
										$total 		  = $currencysymbol.$data['price'];
									}else{
										$intervaldays = $currencysymbol.$data['price'].'x'.$intervalss;
										$total 		  = $currencysymbol.$data['total'];
									}

									$barnstalldata .= '<div class="row"><span class="col-7 event_c_text">'.$data['stall_name'].'</span><span class="col-5 text-end event_c_text">('.$intervaldays.') '.$total.'</span></div>';
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
									$rvbarnstalldata = '';
									$rvbarnname = '';
									foreach ($rvbarnstall as $rvdata) { 
										if($rvbarnname!= $rvdata['barn_name']){
											$rvbarnname = $rvdata['barn_name'];
											$rvbarnstalldata .= '<div><span class="col-12 fw-bold">'.$rvbarnname.'</span></div>';
											echo '<p>'.$rvbarnname.'</p>';
										}

										if($rvdata['interval']%7==0){
											$intervalss = $rvdata['interval']/7;
										}else if($rvdata['interval']%30==0){
											$intervalss = $rvdata['interval']/30; 
										}else{ 
											$intervalss = $rvdata['interval'];
										}

										if($rvdata['chargingid']=='4'){
										$intervaldays = $currencysymbol.$rvdata['price'];
										$total 		  = $currencysymbol.$rvdata['price'];
									}else{
										$intervaldays = $currencysymbol.$rvdata['price'].'x'.$intervalss;
										$total 		  = $currencysymbol.$rvdata['total'];
									}

										$rvbarnstalldata .= '<div class="row"><span class="col-7 event_c_text">'.$rvdata['stall_name'].'</span><span class="col-5 text-end event_c_text">('.$intervaldays.') '.$total.'</span></div>';
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
									$feeddata = '';
									foreach ($feed as $feed) { 
										echo '<p>'.$feed['product_name'].'</p>';
										$feeddata .= '<div class="row"><span class="col-7 event_c_text">'.$feed['product_name'].'</span><span class="col-5 text-end event_c_text">('.$currencysymbol.$feed['price'].'x'.$feed['quantity'].') '.$currencysymbol.$feed['total'].'</span></div>';
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
									$shavingdata = '';
									foreach ($shaving as $shaving) { 
										echo '<p>'.$shaving['product_name'].'</p>';
										$shavingdata .= '<div class="row"><span class="col-7 event_c_text">'.$shaving['product_name'].'</span><span class="col-5 text-end event_c_text">('.$currencysymbol.$shaving['price'].'x'.$shaving['quantity'].') '.$currencysymbol.$shaving['total'].'</span></div>';
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
						<button class="payment-btn checkoutpayment" type="button">Complete Payment</button>
					</div>
				</div>
			</form>
		</div>
		<div class="col-lg-3">
			<div class="border rounded pt-4 ps-3 pe-3 mb-5">
				<div class="row mb-2">
					<div class="row"><span class="col-6 fw-bold">Total Day :</span><span class="col-6 fw-bold text-end"><?php echo $cartdetail['interval']; ?></span></div>
					<?php if(count($barnstall) > 0){ ?>
					<div>
						<div class="event_cart_title"><span class="col-12 fw-bold">STALL</span></div>
						<p><?php echo $barnstalldata; ?></p>
					</div>
					<?php } ?>
					<?php if(count($rvbarnstall) > 0){ ?>
						<div>
							<div class="event_cart_title"><span class="col-12 fw-bold">RV HOOKUP</span></div>
							<p><?php echo $rvbarnstalldata; ?></p>
						</div>
					<?php } ?>
					<?php if(count($feed) > 0){ ?>
						<div>
							<div class="event_cart_title"><span class="col-12 fw-bold">FEED</span></div>
							<p><?php echo $feeddata; ?></p>
						</div>
					<?php } ?>
					<?php if(count($shaving) > 0){ ?>
						<div>
							<div class="event_cart_title"><span class="col-12 fw-bold">SHAVING</span></div>
							<p><?php echo $shavingdata; ?></p>
						</div>
					<?php } ?>
				</div> 
				<div class="row mb-2 event_border_top pt-4">
					<div class="col-8 event_c_text">Total</div>
					<div class="col-4 event_c_text text-end"><?php echo $currencysymbol.$cartprice; ?></div>
					<div class="col-8 event_c_text">Transaction Fees</div>
					<div class="col-4 event_c_text text-end"><?php echo $currencysymbol.$transactionfee; ?></div>
					<?php if($cleaningfee!=0){?> 
						<div class="col-8 event_c_text">Cleaning Fees</div>
						<div class="col-4 event_c_text text-end"><?php echo $currencysymbol.$cleaningfee; ?></div>
					<?php } ?>
					<?php if($tax!=0){?> 
						<div class="col-8 event_c_text">Tax</div>
						<div class="col-4 event_c_text text-end"><?php echo $currencysymbol.$tax; ?></div>
					<?php } ?>
				</div>
				<div class="row mb-2 border-top mt-3 mb-3 pt-3">
					<div class="col-8 fw-bold ">Total Due</div>
					<div class="col-4 fw-bold totaldue"><?php echo $currencysymbol.$amount; ?></div>
				</div>
			</div>
		</div>
    </div>
</section>
<?php $this->endSection(); ?>
  
<?php $this->section('js') ?>
<?php echo $stripe; ?>
	<script>
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
			$('.stripetotal').text('(Total - '+$('.totaldue').text()+')');

			$('.stripepaybutton').append(data);
		})
	</script>
<?php $this->endSection() ?>
