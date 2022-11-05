<?php $this->extend('site/common/layout/layout1') ?>
<?php $this->section('content') ?>
<div class="dFlexComBetween eventTP flex-wrap">
	<h2 class="fw-bold mb-4">Current Reservation</h2>
	<?php if(!empty($bookings)) { ?>
		<div class="flex-row-reverse bd-highlight"> 
			<input type="text" placeholder="Search By Name" class="searchEvent bookedby" id="bookedby" value="" />
			<svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" class="searchIcon searchiconreserve" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
				<path d="M456.69 421.39L362.6 327.3a173.81 173.81 0 0034.84-104.58C397.44 126.38 319.06 48 222.72 48S48 126.38 48 222.72s78.38 174.72 174.72 174.72A173.81 173.81 0 00327.3 362.6l94.09 94.09a25 25 0 0035.3-35.3zM97.92 222.72a124.8 124.8 0 11124.8 124.8 124.95 124.95 0 01-124.8-124.8z"></path>
			</svg>
		</div>
	<?php } ?>
</div>
<section class="maxWidth eventPagePanel">
	<div class="d-none button" onmousedown="party.confetti(this)">Click me!</div>
	<?php if(!empty($bookings)) {  ?>
	<?php foreach ($bookings as $data) { ?>
		<div class="event__ticket mt-5 mb-5 p-3">
			<div class="row position-relative">
				<div class="row col-md-12 ticket_row mt-3">
					<div class="ticket_content res_mx_3 col-md-2 mx-3">
						<p class="ticket_title_tag">Name</p>
						<p class="ticket_values"><?php echo $data['firstname'].$data['lastname'];?></p>
					</div>
					<div class="ticket_content res_mx_3 col-md-2 mx-3">
						<p class="ticket_title_tag">Mobile</p>
						<p class="ticket_values"><?php echo $data['mobile'];?></p>
					</div>
					<div class="ticket_content col-md-2 mx-2">
						<p class="ticket_title_tag">Payment Method</p>
						<p class="ticket_values"><?php echo $data['paymentmethod_name'];?></p>
					</div>
					<div class="ticket_content col-md-2 mx-2">
						<p class="ticket_title_tag">Booked By</p>
						<p class="ticket_values"><?php echo $usertype[$data['usertype']]; ?></p>
					</div>
					<div class="ticket_content col-md-2 mx-2">
						<p class="ticket_title_tag">Date of booking</p>
						<p class="ticket_values"><?php echo date("m-d-Y h:i A", strtotime($data['created_at']));?></p>
					</div>
					<div class="ticket_content res_mx_3 col-md-2 mx-3">
						<p class="ticket_title_tag">Booking ID</p>
						<p class="ticket_values ticket_checkinout"><?php echo $data['id'];?></p>
					</div>
					<div class="ticket_content res_mx_3 col-md-2 mx-3">
						<p class="ticket_title_tag">Check In</p>
						<p class="ticket_values ticket_checkinout"><?php echo formatdate($data['check_in'], 1);?></p>
					</div>
					<div class="ticket_content col-md-2 mx-2">
						<p class="ticket_title_tag">Check Out</p>
						<p class="ticket_values ticket_checkinout"><?php echo formatdate($data['check_out'], 1);?></p>
					</div>
					<div class="ticket_content col-md-2 mx-2">
						<p class="ticket_title_tag">Cost</p>
						<p class="ticket_values ticket_checkinout"><?php echo $currencysymbol.$data['amount'];?></p>
					</div>
					<div class="ticket_content col-md-2 mx-2">
						<p class="ticket_title_tag">Special Request</p>
						<p class="ticket_values ticket_checkinout"><?php if(!empty($data['special_notice'])){ echo$data['special_notice']; } else{ echo "No Special Request";}?></p>
					</div>
					<div class="ticket_content col-md-2 mx-2 d-flex align-items-end">
						<?php $statuscolor = ($data['status']=='2') ? "cancelcolor" : "activecolor"; ?>
							<p class="my-2 ticket_values ticket_status <?php echo  $statuscolor;?>" ><?php echo $bookingstatus[$data['status']];?></p>
					</div>
				</div>
				<div class="top_event_border">
					<div class="ticket__event row mt-2">
						<div class="ticket_content col-md-2 mx-3">
							<p class="ticket_e_tag">Booked Event</p>
						</div>
						<div class="ticket_content col-md-9 mx-3">
							<p class="ticket_values mr-3">Event ( <?php echo $data['eventname'];?> )</p>
						</div>
						<div class="flex-wrap d-flex align-items-center">
						<?php if(!empty($data['barnstall'])){?>
						<div class="col-md-2 px-3">
							<p class="ticket_event_tag">STALL</p>
						</div>
						<?php foreach ($data['barnstall'] as $stalls) {
							if($userdetail['type'] =='4'){ 
								$btnlockunlock = '<i data-stallid="'.$stalls['stall_id'].'"  class="fas fa-lock event_lock lockunlock"></i>';
								$btndirtyclean = '<i data-stallid="'.$stalls['stall_id'].' " class="fas fa-virus event_virus dirtyclean"></i>';

								if($stalls['lockunlock']=='1'){
									$btnlockunlock = '<i class="fas fa-unlock event_unlock">'; 
								}
							if($stalls['dirtyclean']=='1'){
								$btndirtyclean = '<i class="fas fa-broom event_broom"></i>'; 
							}
						} ?>
						<div class="d-flex flex-wrap">
							<div class="mx-3">
								<p class="ticket_values"><?php echo $stalls['barnname'];?></p>
								<span class="d-flex flex-wrap">
									<p class="ticket_sub_values"><?php echo $stalls['stallname'];?></p>
									<?php if($userdetail['type'] =='4'){ ?>
										<a href="#" class="ms-2"><?php echo $btnlockunlock;?></i></a>
										<a href="#" class="mx-2"><?php echo $btndirtyclean;?></a>
									<?php } ?>
								</span>
							</div>
						</div>
						<?php } } ?>
						</div>
						<div class="flex-wrap d-flex align-items-center">
						<?php if(!empty($data['rvbarnstall'])){?>
							<div class="col-md-2 px-3">
								<p class="ticket_event_tag">RV HOOKUP</p>
							</div>
							<?php foreach ($data['rvbarnstall'] as $rvstall) {
								if($userdetail['type'] =='4'){ 
									$btnlockunlock = '<i data-stallid="'.$rvstall['stall_id'].'" class="fas fa-lock event_lock lockunlock"></i>';
									$btndirtyclean = '<i data-stallid="'.$rvstall['stall_id'].' " class="fas fa-virus event_virus dirtyclean"></i>';

									if($rvstall['lockunlock']=='1'){
										$btnlockunlock = '<i class="fas fa-unlock event_unlock">'; 
									}
									if($rvstall['dirtyclean']=='1'){
										$btndirtyclean = '<i class="fas fa-broom event_broom"></i>'; 
									}
								} ?>
								<div class="d-flex flex-wrap">
									<div class="mx-3">
										<p class="ticket_values"><?php echo $rvstall['barnname'];?></p>
										<span class="d-flex flex-wrap">
											<p class="ticket_sub_values"><?php echo $rvstall['stallname'];?></p>
											<?php if($userdetail['type'] =='4'){ ?>
												<a href="#" class="ms-2"><?php echo $btnlockunlock;?></i></a>
												<a href="#" class="mx-2"><?php echo $btndirtyclean;?></a>
											<?php } ?>
										</span>
									</div>
								</div>
							<?php } } ?>
						</div>
						<div class="flex-wrap d-flex align-items-center">
							<?php if(!empty($data['feed'])){?>
							<div class="col-md-2 px-3">
								<p class="ticket_event_tag">FEED</p>
							</div>
							<?php foreach ($data['feed'] as $feed) { ?>
							<div class="d-flex align-items-center mx-3">
								<span class="d-flex flex-wrap">
									<p class="ticket_sub_values e_mr_1"><?php echo $feed['productname'];?>
								<?php echo '('.$feed['quantity'].')'.$currencysymbol.$feed['total']?></p>
								</span>
							</div>
							<?php } } ?>
						</div>
						<div class="flex-wrap d-flex align-items-center">
							<?php if(!empty($data['shaving'])){?>
							<div class="col-md-2 px-3">
								<p class="ticket_event_tag">SHAVINGS</p>
							</div>
							<?php foreach ($data['shaving'] as $shaving) { ?>
							<div class="d-flex flex-wrap">
								<div class="d-flex align-items-center mx-3">
									<p class="ticket_sub_values e_mr_1"><?php echo $shaving['productname'];?>  <?php echo '('.$shaving['quantity'].')'.$currencysymbol.$shaving['total']?></p>
								</div>
							</div>
							<?php } } ?>
						</div>
					</div>
				</div>
				<div class="text-center event_border">
					<?php if($userdetail['type']=='2' || $userdetail['type']=='3' || $userdetail['type']=='5'){ ?> 
						<a href="<?php echo base_url().'/myaccount/bookings/view/'.$data['id']; ?>" class="mt-0 mx-3 view-res">View</a>
					<?php } ?>
					<?php if($userdetail['type']!='6'){
					    if($data['status']=='1'){ ?>
							<?php $amount = $data['amount']-($data['amount'] * 10/100); ?>
								<a href="javascript:void(0);" style='align: right;' data-id='<?php echo $data['id']; ?>' data-paymentid='<?php echo $data['paymentid']; ?>' data-paymentintentid='<?php echo $data['stripe_paymentintent_id']; ?>' data-amount='<?php echo $amount; ?>' class="striperefunds bookingcancel">
									Cancel
								</a>
						<?php } } ?>
					<i id="ticket_toggle_up" class="fas fa-angle-up ticket__up"></i>
					<i id="ticket_toggle_down" class="fas fa-angle-down ticket__down"></i>
				</div>
			</div>
		</div>
	<?php } ?>
<?php }else{ ?>
		<p>No Reservation Found.</p>
<?php } ?>

</section>
<?php $this->endSection(); ?>

<?php $this->section('js') ?>

<script type="text/javascript">
	var baseurl = "<?php echo base_url(); ?>";

	$(document).ready(function() {
		$("#bookedby").autocomplete({
			source: function(request, response) {
				ajax(baseurl+'/myaccount/bookings/searchbookeduser', {search: request.term}, {
					success: function(result) {
						response(result);
					}
				});
			},
			html: true, 
			select: function(event, ui) {
				var name = ui.item.firstname+ui.item.lastname
				$('#bookedby').val(name); 
				window.location.href = baseurl+'/myaccount/bookings/view/'+ui.item.id;
				return false;
			},
			focus: function(event, ui) {
				$("#bookedby").val(name);
				return false;
			}
		})
		.autocomplete("instance")
		._renderItem = function( ul, item ) {
			var name = item.firstname+item.lastname
			return $( "<li><div>"+name+"</div></li>" ).appendTo( ul );
		};
	});
	
	$(document).on('click','.striperefunds', function(){
		var action 	= 	'<?php echo base_url()."/myaccount/bookings"; ?>';
		var data    = '\
		<input type="hidden" name="id" value="'+$(this).attr("data-id")+'">\
		<input type="hidden" name="paymentid" value="'+$(this).attr("data-paymentid")+'">\
		<input type="hidden" name="paymentintentid" value="'+$(this).attr("data-paymentintentid")+'">\
		<input type="hidden" name="amount" value="'+$(this).attr("data-amount")+'">\
		';
		sweetalert2(action,data);
	});	

	$(document).on('click','.lockunlock',function(){
		var action 	= 	'<?php echo base_url()."/myaccount/bookings"; ?>';
		var data   = '\
		<input type="hidden" value="'+$(this).attr('data-stallid')+'" name="stallid">\
		<input type="hidden" value="1" name="lockunlock">\
		';
		sweetalert2(action,data);
	});	

	$(document).on('click','.dirtyclean',function(){
		var action 	= 	'<?php echo base_url()."/myaccount/bookings"; ?>';
		var data   = '\
		<input type="hidden" value="'+$(this).attr('data-stallid')+'" name="stallid">\
		<input type="hidden" value="1" name="dirtyclean">\
		';
		sweetalert2(action,data);
	});	

	$(document).ready(function(){
		$(".ticket__up").click(function(){
			$(this).parent().siblings(".top_event_border").slideUp();
			$(this).css("display", "none");
			$(this).next(".ticket__down").css("display", "block");
		});
		$(".ticket__down").click(function(){
			$(this).parent().siblings(".top_event_border").slideDown();  
			$(this).prev(".ticket__up").css("display", "block");
			$(this).css("display", "none");
		});
	});

	party.confetti(runButton, {
		count: party.variation.range(20, 40)
	});
</script>
<?php $this->endSection(); ?>