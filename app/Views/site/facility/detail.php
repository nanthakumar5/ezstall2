<?php $this->extend('site/common/layout/layout1') ?>
<?php $this->section('content') ?>
<?php
$userid 		= getSiteUserID() ? getSiteUserID() : 0;
$currentdate 	= date("m-d-Y");
$getcart 	 	= getCart('2');
$cartevent 	 	= ($getcart && $getcart['event_id'] != $detail['id']) ? 1 : 0;
$name 		 	= $detail['name'];
$description 	= $detail['description'];
$image 		 	= base_url().'/assets/uploads/event/'.'1200x600_'.$detail['image'];
$profileimage 	= isset($detail['profile_image']) ? $detail['profile_image'] : '';
$profileimage 	= ($profileimage!="") ? base_url().'/assets/uploads/profile/'.$detail['profile_image'] : base_url().'/assets/images/profile.jpg';
?>
<!--1200x600_-->

<?php if($cartevent==1){?>
	<div class="alert alert-success alert-dismissible fade show m-2" role="alert">
		For booking this stall remove other stalls from the cart <a href="<?php echo base_url().'/facility/detail/'.$getcart['event_id']; ?>">Go To Facility</a>
		<!--<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">X</button>-->
	</div>
<?php } ?>

<div class="container-lg"> 
	<div class="row">
		<div class="stalldetail-banner mt-4 mb-5">
			<img src="<?php echo $image; ?>">
		</div>
	</div>
</div>

<section class="container-lg">
	<div class="row">
		<div class="col-lg-8">
			<div class="stall-head">
				<div class="float-start">
					<img class="profile_pic" src="<?php echo $profileimage; ?>">
				</div>
				<div class="float-next">
					<h4 class="fw-bold"><?php echo $name; ?></h4>
				</div>
			</div>
			<?php echo ucfirst($description);?>
			<?php if($detail['stallmap']!=""){ ?>
				<button class="ucEventdetBtn"><a href="<?php echo base_url();?>/facility/download/<?php echo $detail['stallmap'] ?>" class="text-decoration-none text-white"><img src="<?php echo base_url() ?>/assets/site/img/flyer.png"> Download Stall Map</a></button>
			<?php } ?>
		</div>
		<div class="row m-0 p-0">
			<div class="col-md-9">
				<div class="border rounded py-2 ps-3 pe-3 mt-4 mb-3">
					<div class="infoPanel form_check bookyourstalls">
						<span class="infoSection bookborder flex-wrap">
							<div class="col-md-6">
								<p class="fw-bold mx-2 fs-5 mb-0">Check In</p>
								<span class="iconProperty w-100 w-auto pad_100">			
									<input type="text" name="startdate" id="startdate" class="w-100 land_width checkdate checkin borderyt" autocomplete="off" placeholder="Check-In" readonly/>
									<img src="<?php echo base_url() ?>/assets/site/img/calendar.svg" class="iconPlace" alt="Calender Icon">
								</span>
							</div>
							<div class="col-md-6">
								<p class="fw-bold mx-2 fs-5 mb-0">Check Out</p>
								<span class="iconProperty w-100 col-md-12 w-auto pad_100">
									<input type="text" name="enddate" id="enddate" class="w-100 land_width checkdate checkout borderyt" autocomplete="off"placeholder="Check-Out" readonly/>
									<img src="<?php echo base_url() ?>/assets/site/img/calendar.svg" class="iconPlace" alt="Calender Icon">
								</span>
							</div>
						</span>
					</div>
				</div>

				<div class="border rounded pt-4 ps-3 pe-3 mt-4 mb-5">
					<h3 class="fw-bold mb-4">Book</h3>
					<div class="barn-nav mt-4">
						<nav>
							<div class="nav nav-tabs" id="multi-nav-tab" role="tablist">
								<button class="nav-link m-0 show active" data-bs-toggle="tab" data-bs-target="#barnstall" type="button" role="tab" aria-controls="barnstall" aria-selected="true">Stalls</button>
								<?php if($detail['rv_flag'] =='1' && !empty($detail['rvbarn'])) { ?>
									<button class="nav-link m-0" data-bs-toggle="tab" data-bs-target="#barnhook" type="button" role="tab" aria-controls="barnhook" aria-selected="false">RV Hookups</button>
								<?php } ?>
								<?php if($detail['feed_flag'] =='1' && !empty($detail['feed_flag'])) { ?>
									<button class="nav-link m-0" data-bs-toggle="tab" data-bs-target="#barnfeed" type="button" role="tab" aria-controls="barnfeed" aria-selected="false">Feed</button>
								<?php } ?>
								<?php if($detail['shaving_flag'] =='1' && !empty($detail['shaving_flag'])) { ?>
									<button class="nav-link m-0" data-bs-toggle="tab" data-bs-target="#barnshaving" type="button" role="tab" aria-controls="barnshaving" aria-selected="false">Shaving</button>
								<?php } ?>				
							</div>
						</nav>
						<div class="tab-content" id="nav-tabContent">
							<div class="tab-pane fade active show" id="barnstall" role="tabpanel" aria-labelledby="nav-home-tab">
								<div class="border rounded pt-4 ps-3 pe-3 mb-3">
									<h3 class="fw-bold mb-4">Book Your Stalls</h3>							
									<?php 
									$tabbtn = '';
									$tabcontent = '';
									foreach ($detail['barn'] as $barnkey => $barndata) {
										$barnid = $barndata['id'];
										$barnname = $barndata['name'];
										$barnactive = $barnkey=='0' ? ' show active' : '';
										$tabbtn .= '<button class="nav-link m-0'.$barnactive.'" data-bs-toggle="tab" data-bs-target="#barn'.$barnid.'" type="button" role="tab" aria-controls="barn'.$barnid.'" aria-selected="true">'.$barnname.'</button>';

										$tabcontent .= '<div class="tab-pane fade'.$barnactive.'" id="barn'.$barnid.'" role="tabpanel" aria-labelledby="nav-home-tab">
										<ul class="list-group">';
										foreach($barndata['stall'] as $stalldata){ 
											if($stalldata['charging_id']=='1'){ 
												$typeofprice = 'night_price';
											}else if($stalldata['charging_id']=='2'){
												$typeofprice = 'week_price';
											}else if($stalldata['charging_id']=='3'){
												$typeofprice = 'month_price';
											}else if($stalldata['charging_id']=='4'){
												$typeofprice = 'flat_price';
											}else{
												$typeofprice = '';
											}

											$boxcolor  = 'green-box';
											$checkboxstatus = '';

											if($cartevent=='1'){
												$checkboxstatus = 'disabled';
											}


											$tabcontent .= 	'<li class="list-group-item '.$typeofprice.'">
											<input class="form-check-input eventbarnstall stallid me-1" data-stallenddate="'.$stalldata['end_date'].'" data-price="'.$stalldata['price'].'" data-barnid="'.$stalldata['barn_id'].'" value="'.$stalldata['id'].'" name="checkbox"  type="checkbox" '.$checkboxstatus.'>
											'.$stalldata['name'].'
											<span class="'.$boxcolor.' stallavailability" data-stallid="'.$stalldata['id'].'" ></span>
											</li>';
										}
										$tabcontent .= '</ul></div>';
									}
									?>
									<div class="barn-nav mt-4">
										<nav>
											<div class="nav nav-tabs" id="nav-tab" role="tablist">
												<?php echo $tabbtn; ?>
											</div>
										</nav>
										<div class="tab-content" id="nav-tabContent">
											<?php echo $tabcontent; ?>
											<div class="row">
												<div class="btm-color">
													<p><span class="green-circle"></span>Available</p>
													<p><span class="yellow-circle"></span>Reserved</p>
													<p><span class="red-circle"></span>Occupied</p>
												</div>
											</div>
										</div>    
									</div>
								</div>
							</div>
							<?php if($detail['rv_flag'] =='1' && !empty($detail['rvbarn'])) { ?>
								<div class="tab-pane fade" id="barnhook" role="tabpanel" aria-labelledby="nav-home-tab">
									<div class="border rounded pt-4 ps-3 pe-3 mt-4 mb-3">
										<h3 class="fw-bold mb-4">Book Your Rvhookups</h3>							
										<?php 
										$tabbtn = '';
										$tabcontent = ''; 
										foreach ($detail['rvbarn'] as $rvkey => $rvdata) { 
											$rvid = $rvdata['id'];
											$rvname = $rvdata['name'];
											$rvactive = $rvkey=='0' ? ' show active' : '';
											$tabbtn .= '<button class="nav-link m-0'.$rvactive.'" data-bs-toggle="tab" data-bs-target="#barn'.$rvid.'" type="button" role="tab" aria-controls="barn'.$rvid.'" aria-selected="true">'.$rvname.'</button>';

											$tabcontent .= '<div class="tab-pane fade'.$rvactive.'" id="barn'.$rvid.'" role="tabpanel" aria-labelledby="nav-home-tab">
											<ul class="list-group">';
											foreach($rvdata['rvstall'] as $rvstalldata){
												if($rvstalldata['charging_id']=='1'){
													$typeofprice = 'night_price';
												}else if($rvstalldata['charging_id']=='2'){
													$typeofprice = 'week_price';
												}else if($rvstalldata['charging_id']=='3'){
													$typeofprice = 'month_price';
												}else if($rvstalldata['charging_id']=='4'){
													$typeofprice = 'flat_price';
												}

												$boxcolor  = 'green-box';
												$checkboxstatus = '';

												if($cartevent=='1'){
													$checkboxstatus = 'disabled';
												}

												$tabcontent .= 	'<li class="list-group-item '.$typeofprice.'">
												<input class="form-check-input rvbarnstall stallid me-1" data-price="'.$rvstalldata['price'].'" data-barnid="'.$rvstalldata['barn_id'].'" data-flag="2" value="'.$rvstalldata['id'].'" name="checkbox"  type="checkbox" '.$checkboxstatus.'>
												'.$rvstalldata['name'].'
												<span class="'.$boxcolor.' stallavailability" data-stallid="'.$rvstalldata['id'].'" ></span>
												</li>';
											}
											$tabcontent .= '</ul></div>';
										}
										?>
										<div class="barn-nav mt-4">
											<nav>
												<div class="nav nav-tabs" id="nav-tab" role="tablist">
													<?php echo $tabbtn; ?>
												</div>
											</nav>
											<div class="tab-content" id="nav-tabContent">
												<?php echo $tabcontent; ?>
												<div class="row">
													<div class="btm-color">
														<p><span class="green-circle"></span>Available</p>
														<p><span class="yellow-circle"></span>Reserved</p>
														<p><span class="red-circle"></span>Occupied</p>
													</div>
												</div>
											</div>    
										</div>
									</div>
								</div>	
							<?php } ?>
							<?php if($detail['feed_flag'] =='1' && !empty($detail['feed_flag'])) { ?>
								<div class="tab-pane fade" id="barnfeed" role="tabpanel" aria-labelledby="nav-home-tab">
									<div class="border rounded py-4 ps-3 pe-3 mt-4 mb-3">
										<h3 class="fw-bold mb-4">Book Your Feed</h3>							
										<table class="table table-bordered table-hover mb-0">
											<thead class="table-dark">
												<tr>
													<td class="text-light">Product Name</td>
													<td class="text-light">Product Price</td>
													<td class="text-light">Product Quantity</td>
													<td class="text-light">Action</td>
												</tr>
											</thead>
											<?php foreach ($detail['feed'] as $feed) { ?>
												<tr>
													<td style="border: 1px solid #e4e4e4;"><?php echo $feed['name'];?></td>
													<td style="border: 1px solid #e4e4e4;"><?php echo $feed['price'];?></td>
													<td style="border: 1px solid #e4e4e4;">
														<input type="number" min="0" class="form-control quantity" data-productid="<?php echo $feed['id']?>" data-flag="3" <?php if($cartevent=='1'){ echo 'disabled'; } ?>>
													</td>
													<td style="border: 1px solid #e4e4e4;">
														<?php if($cartevent!='1'){ ?>
															<button class="btn btn-primary feedcart" data-productid="<?php echo $feed['id']?>" data-originalquantity="<?php echo $feed['quantity']?>" data-price="<?php echo $feed['price']?>">Add to Cart</button>
															<button class="btn btn-danger feedcartremove cartremove displaynone" data-productid="<?php echo $feed['id']?>">Remove</button>
														<?php } ?>
													</td>
												</tr>
											<?php } ?>
										</table>
									</div>
								</div>
							<?php } ?>
							<?php if($detail['shaving_flag'] =='1' && !empty($detail['shaving_flag'])) { ?>
								<div class="tab-pane fade" id="barnshaving" role="tabpanel" aria-labelledby="nav-home-tab">
									<div class="border rounded py-4 ps-3 pe-3 mt-4 mb-3">
										<h3 class="fw-bold mb-4">Book Your Shaving</h3>							
										<table class="table table-bordered table-hover mb-0">
											<thead class="table-dark">
												<tr>
													<td class="text-light">Product Name</td>
													<td class="text-light">Product Price</td>
													<td class="text-light">Product Quantity</td>
													<td class="text-light">Action</td>
												</tr>
											</thead>
											<?php foreach ($detail['shaving'] as $shaving) { ?>
												<tr>
													<td style="border: 1px solid #e4e4e4;"><?php echo $shaving['name'];?></td>
													<td style="border: 1px solid #e4e4e4;"><?php echo $shaving['price'];?></td>
													<td style="border: 1px solid #e4e4e4;">
														<?php if($shaving['quantity']==0){ $msg = 'sold out'; $readonly = 'readonly';} else{ $msg = ''; $readonly = ''; } ?>
														<input type="number" min="0" class="form-control quantity" <?php echo $readonly;?> data-productid="<?php echo $shaving['id']?>" data-flag="4" <?php if($cartevent=='1'){ echo 'disabled'; } ?>>
														<p style="color:red"><?php echo $msg; ?></p>
													</td>
													<td style="border: 1px solid #e4e4e4;">
														<?php if($cartevent!='1'){ ?>
															<button class="btn btn-primary shavingcart" data-productid="<?php echo $shaving['id']?>" data-originalquantity="<?php echo $shaving['quantity']?>" data-price="<?php echo $shaving['price']?>">Add to Cart</button>
															<button class="btn btn-danger shavingcartremove cartremove displaynone" data-productid="<?php echo $shaving['id']?>">Remove</button>
														<?php } ?>
													</td>
												</tr>
											<?php } ?>
										</table>
									</div>
								</div>
							<?php } ?>
						</div>    
					</div>
				</div>
			</div> 
			<div class="sticky-top checkout col-md-3 mt-4 h-100"></div>
		</div>
	</div>
</section>
<?php $this->endSection() ?>

<?php $this->section('js') ?>
<script>
	var transactionfee 		= '<?php echo $settings['transactionfee']?>';
	var currencysymbol 		= '<?php echo $currencysymbol; ?>';
	var eventid 			= '<?php echo $detail["id"]; ?>';
	var stallid 			= '<?php echo $stalldata["id"]; ?>';
	var cartevent 			= '<?php echo $cartevent; ?>';

	$(document).ready(function (){ 
		if(cartevent == 0 ){ 
			cart();
		}else{ 
			$("#startdate, #enddate").attr('disabled', 'disabled');
		}
		
		uidatepicker(
			'#startdate', 
			{ 
				'mindate' 	: '0',
				'close' 	: function(selecteddate){
					var date = new Date(selecteddate)
					date.setDate(date.getDate() + 1);
					$("#enddate").datepicker( "option", "minDate", date );
				}
			}
			);

		uidatepicker('#enddate', { 'mindate' : '0' });
	});
	
	$("#enddate").click(function(){
		var startdate 	= $("#startdate").val();
		if(startdate==''){
			$("#startdate").focus();
		}
	});
	
	$("#startdate, #enddate").change(function(){  
		setTimeout(function(){
			var startdate 	= $("#startdate").val(); 
			var enddate   	= $("#enddate").val(); 
			if(enddate!=""){
				var startdates 		= new Date(startdate);
				var enddates 		= new Date(enddate);
				var stallinterval  	= enddates.getTime() - startdates.getTime(); 
				var intervaldays 	= stallinterval / (1000 * 3600 * 24); 
				$('.week_price').show();
				$('.month_price').show();
				$('.night_price').show();
				if(intervaldays%7==0){
					$('.night_price').hide();
					$('.month_price').hide();
				}else if(intervaldays%30==0){ 
					$('.week_price').hide();
					$('.night_price').hide();
				}else{
					$('.week_price').hide();
					$('.month_price').hide();
				}
			}


			if(startdate!='' && enddate!=''){
				cart({type : '2', checked : 0}); 
				$('.stallid').prop('checked', false).removeAttr('disabled');
				$('.stallavailability').removeClass("yellow-box").removeClass("red-box").addClass("green-box");
				
				validatestalldates(enddate);
				occupiedreserved(startdate, enddate);
			}
		}, 100);
	})

	function validatestalldates(enddate){ 
		$('.stallid').each(function(){
			var stallenddate		= $(this).attr('data-stallenddate');
			var stallid	 			= $(this).val();

			$('.stallid[value='+stallid+']').removeAttr('disabled', 'disabled');
			$('.stallavailability[data-stallid='+stallid+']').removeClass("yellow-box").addClass("green-box");
			
			if(Date.parse(stallenddate) < Date.parse(enddate)){ 
				$('.stallid[value='+stallid+']').attr('disabled', 'disabled');
				$('.stallavailability[data-stallid='+stallid+']').removeClass("green-box").addClass("yellow-box");
			}
		});
	}
	
	function occupiedreserved(startdate, enddate, stallid=''){ 
		var result = 1;
		ajax(
			'<?php echo base_url()."/ajax/ajaxoccupied"; ?>',
			{ eventid : eventid, checkin : startdate, checkout : enddate },
			{
				asynchronous : 1,
				success : function(data){
					$(data.success).each(function(i,v){ 
						if(stallid==v){
							result = 0;
							toastr.warning('Stall is already booked.', {timeOut: 5000});
							$('.stallid[value='+stallid+']').prop('checked', false);
						}

						$('.stallid[value='+v+']').prop('checked', true).attr('disabled', 'disabled');
						$('.stallavailability[data-stallid='+v+']').removeClass("green-box").addClass("red-box");
					});
				}
			}
			)
		
		ajax(
			'<?php echo base_url()."/ajax/ajaxreserved"; ?>',
			{ eventid : eventid, checkin : startdate, checkout : enddate },
			{
				asynchronous : 1,
				success : function(data){
					$.each(data.success, function (i, v) {
						if(stallid==i){
							result = 0;
							toastr.warning('Stall is already booked.', {timeOut: 5000});
							$('.stallid[value='+stallid+']').prop('checked', false);
						}
						
						$('.stallid[value='+i+']').prop('checked', true).attr('disabled', 'disabled');
						$('.stallavailability[data-stallid='+i+']').removeClass("green-box").addClass("yellow-box");
					});
				}
			}
			)

		ajax(
			'<?php echo base_url()."/ajax/ajaxblockunblock"; ?>',
			{ eventid : eventid},
			{
				asynchronous : 1,
				success : function(data){
					$(data.success).each(function(i,v){
						$('.stallid[value='+v+']').attr('disabled', 'disabled');
						$('.stallavailability[data-stallid='+v+']').removeClass("green-box").addClass("yellow-box");
					});
				}
			}
			)
		
		return result;
	} 

	function productquantity(productid){
		var result = 0;
		ajax(
			'<?php echo base_url()."/ajax/ajaxproductquantity"; ?>',
			{ eventid : eventid, productid : productid },
			{
				asynchronous : 1,
				success : function(data){
					result = data.success;
				}
			}
			)
		
		return result;
	}

	$(".eventbarnstall").on("click", function() {
		cartaction($(this), 1);
	});
	
	$(".rvbarnstall").on("click", function() {
		cartaction($(this), 2);
	});

	$(".feedcart, .feedcartremove").on("click", function() {
		cartaction($(this), 3);
	});
	
	$(".shavingcart, .shavingcartremove").on("click", function() {
		cartaction($(this), 4);
	});

	$(".quantity").keyup(function(){
		checkdate($(this).attr('data-flag'));
	})

	function cartaction(_this, flag){ 
		var datevalidation = checkdate(flag);
		if(!datevalidation) return false;
		
		var startdate 	= $("#startdate").val(); 
		var enddate   	= $("#enddate").val(); 

		if(flag==1 || flag==2){			
			var barnid    	= _this.attr('data-barnid');
			var stallid		= _this.val(); 
			var price 		= _this.attr('data-price');

			if($(_this).is(':checked')){  
				var checkoccupiedreserved = occupiedreserved(startdate, enddate, stallid);
				if(checkoccupiedreserved==1) cart({event_id : eventid, barn_id : barnid, stall_id : stallid, price : price, quantity : 1, startdate : startdate, enddate : enddate, type : '2', checked : 1, flag : flag, actionid : ''});
			}else{ 
				$('.stallavailability[data-stallid='+stallid+']').removeClass("yellow-box").addClass("green-box");
				cart({stall_id : stallid, type : '2', checked : 0}); 
			}		
		}else{
			var productid      		= _this.attr('data-productid');
			var quantitywrapper		= _this.parent().parent().find('.quantity');
			
			if(!_this.hasClass('cartremove')){
				var price         		= _this.attr('data-price'); 
				var originalquantity	= _this.attr('data-originalquantity'); 
				var cartquantity		= productquantity(productid);
				var quantity 			= quantitywrapper.val();
				
				if(quantity==""){ 
					quantitywrapper.focus();
					toastr.warning('Please Enter Quantity .', {timeOut: 5000});
				}else if(parseInt(quantity) > (parseInt(originalquantity) - parseInt(cartquantity))){
					quantitywrapper.focus();
					toastr.warning('Please Select Quantity Less Than or equal to.'+(parseInt(originalquantity) - parseInt(cartquantity)), {timeOut: 5000});
				}else{ 
					cart({event_id : eventid, product_id : productid, price : price, quantity : quantity, startdate : startdate, enddate : enddate, type : '2', checked : 1, flag : flag, actionid : ''});
				}
			}else{
				quantitywrapper.val('');
				$('.cartremove[data-productid='+productid+']').addClass('displaynone'); 
				cart({product_id : productid, type : '2', checked : 0});
			}
		}
	}

	function checkdate(flag){
		var classarray = ['eventbarnstall', 'rvbarnstall', 'feedcart', 'shavingcart'];
		
		if(flag==1 || flag==2){
			if($("."+classarray[flag-1]+":checked").length > 0){			
				var datevalidation = datetoastr()
				if(!datevalidation){
					$("."+classarray[flag-1]+":not(:disabled)").prop('checked', false);
					return false;
				}
			}
		}else{
			var datevalidation = datetoastr()
			if(!datevalidation){
				$("."+classarray[flag-1]).each(function(){
					$(this).parent().parent().find('.quantity').val('');
				});
				
				return false;
			}
		}
		
		return true;
	}
	
	function datetoastr(){
		var startdate 	= $("#startdate").val(); 
		var enddate   	= $("#enddate").val(); 
		
		if(startdate=='' || enddate==''){
			if(startdate==''){
				$("#startdate").focus();
				toastr.warning('Please select the Check-In Date.', {timeOut: 5000});
			}else if(enddate==''){
				$("#enddate").focus();
				toastr.warning('Please select the Check-Out Date.', {timeOut: 5000});
			}

			return false;
		}
		
		return true;
	}

	function cart(data={cart:1, type:2}){	
		ajax(
			'<?php echo base_url()."/cart"; ?>',
			data,
			{ 
				asynchronous : 1,
				success  : function(result){
					if(Object.keys(result).length){   
						$("#startdate").val(result.check_in); 
						$("#enddate").val(result.check_out); 
						
						validatestalldates($("#enddate").val());
						occupiedreserved($("#startdate").val(), $("#enddate").val());
						
						var barnstalldata = cartsummary(1, 'STALL', result.barnstall);
						var rvbarnstalldata = cartsummary(1, 'RV HOOKUP', result.rvbarnstall);
						var feeddata = cartsummary(2, 'FEED', result.feed);
						var shavingdata = cartsummary(2, 'SHAVING', result.shaving);
						var stallcleaning_fee = (result.cleaning_fee!='') ? result.cleaning_fee : '0';
						
						var total = (parseFloat(stallcleaning_fee)+parseFloat(result.price)+parseFloat((transactionfee/100) * result.price)).toFixed(2);
						var cleaning_fee = '';
						if(result.cleaning_fee!=''){
							var cleaning_fee = '<div class="col-8 event_c_text">Cleaning Fee</div>\
							<div class="col-4 event_c_text text-end">'+currencysymbol+parseFloat(result.cleaning_fee).toFixed(2)+'\</div>';
						}

						if(result.interval%7==0){
							$('.night_price').hide();
							$('.month_price').hide();
						}else if(result.interval%30==0){
							$('.week_price').hide();
							$('.night_price').hide();
						}else{
							$('.week_price').hide();
							$('.month_price').hide();
						}

						var result ='\
						<div class="w-100">\
						<div class="border rounded pt-4 ps-3 pe-3 mb-5">\
						<div class="row mb-2">\
						<div class="col-md-12">\
						<div class="row"> <span class="col-6 fw-bold">Total Day :</span><span class="col-6 fw-bold text-end">'+result.interval+'</span></div>\
						'+barnstalldata+'\
						'+rvbarnstalldata+'\
						'+feeddata+'\
						'+shavingdata+'\
						</div>\
						</div>\
						<div class="row mb-2 event_border_top pt-4">\
						<div class="col-8 event_c_text">Total</div>\
						<div class="col-4 event_c_text text-end">'+currencysymbol+result.price.toFixed(2)+'\</div>\
						<div class="col-8 event_c_text">Transaction Fees</div>\
						<div class="col-4 event_c_text text-end">'+currencysymbol+((transactionfee/100) * result.price).toFixed(2)+'\</div>\
						'+cleaning_fee+'\
						</div>\
						<div class="row mb-2 border-top mt-3 mb-3 pt-3">\
						<div class="col-8 fw-bold ">Total Due</div>\
						<div class="col-4 fw-bold">'+currencysymbol+total+'</div>\
						</div>\
						<div class="row mb-2 w-100">\
						<a href="<?php echo base_url()?>/checkout" class="w-100 text-center mx-2 ucEventdetBtn ps-3 mb-3 ">Continue to Checkout</a>\
						</div>\
						</div>\
						</div>\
						';

						$('.checkout').empty().append(result);
					}else{
						$('.checkout').empty();
					}
				}
			}
			);
	}
	
	function cartsummary(type, title, result){
		var data = '';
		if(result.length){
			if(type==1){
				var name = '';
				data += '<div class="event_cart_title"><span class="col-12 fw-bold">'+title+'</span></div>';
				$(result).each(function(i,v){
					if(name!=v.barn_name){
						data += '<div><span class="col-12 fw-bold">'+v.barn_name+'</span></div>';
					}
					
					if(v.interval%7==0){
						var intervalss = v.interval/7;
					}else if(v.interval%30==0){
						var intervalss = v.interval/30; 
					}else{ 
						var intervalss = v.interval
					}
					
					if(v.chargingid=='4'){ 
						var intervaldays = currencysymbol+v.price;
						var total 		 = currencysymbol+v.price;
					}else{ 
						var intervaldays = currencysymbol+v.price+'x'+intervalss;
						var total 		 = currencysymbol+v.total;
					}

					data += '<div class="row"><span class="col-7 event_c_text">'+v.stall_name+'</span><span class="col-5 text-end event_c_text">('+intervaldays+') '+total+'</span></div>';
					$('.stallid[value='+v.stall_id+']').removeAttr('disabled');
					name = v.barn_name;
				});
			}else{
				data += '<div class="event_cart_title"><span class="col-12 fw-bold">'+title+'</span></div>';
				$(result).each(function(i,v){								
					data += '<div class="row"><span class="col-7 event_c_text">'+v.product_name+'</span><span class="col-5 text-end event_c_text">('+currencysymbol+v.price+'x'+v.quantity+') '+currencysymbol+v.total+'</span></div>';
					$('.quantity[data-productid='+v.product_id+']').val(v.quantity);
					$('.cartremove[data-productid='+v.product_id+']').removeClass('displaynone');
				});
			}
		}
		
		return data;
	}
</script>
<?php echo $this->endSection() ?>