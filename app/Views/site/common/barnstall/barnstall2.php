<?php 
$getcart 			= getCart('1');
$cartevent 			= ($getcart && $getcart['event_id'] != $detail['id']) ? 1 : 0;
$checkeventstatus	= isset($checkevent) ? $checkevent["status"] : '';
?>
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
<div class="border rounded pt-4 ps-3 pe-3 mt-4 mb-3">
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
							$typeofprice = '';
							if($stalldata['charging_id']=='1'){ 
								$typeofprice = 'night_price';
							}else if($stalldata['charging_id']=='2'){
								$typeofprice = 'week_price';
							}else if($stalldata['charging_id']=='3'){
								$typeofprice = 'month_price';
							}else if($stalldata['charging_id']=='4'){
								$typeofprice = 'flat_price';
							}
							
							$pricelist = '';
							if($detail['eventusertype']=='2'){
								$priceflag = explode(',', $detail['price_flag']);
								
								if(isset($priceflag[0]) && $priceflag[0]=='1'){
									$pricelist .= '<button class="btn btn-success mr-2 night_button price_button" data-pricetype="1" data-pricebutton="'.$stalldata['night_price'].'" disabled>N ('.$currencysymbol.$stalldata['night_price'].')</button>';
								}
								if(isset($priceflag[1]) && $priceflag[1]=='1'){
									$pricelist .= '<button class="btn btn-success mr-2 week_button price_button" data-pricetype="2" data-pricebutton="'.$stalldata['week_price'].'" disabled>W ('.$currencysymbol.$stalldata['week_price'].')</button>';
								}
								if(isset($priceflag[2]) && $priceflag[2]=='1'){
									$pricelist .= '<button class="btn btn-success mr-2 month_button price_button" data-pricetype="3" data-pricebutton="'.$stalldata['month_price'].'" disabled>M ('.$currencysymbol.$stalldata['month_price'].')</button>';
								}
								if(isset($priceflag[3]) && $priceflag[3]=='1'){
									$pricelist .= '<button class="btn btn-success mr-2 flat_button price_button" data-pricetype="4" data-pricebutton="'.$stalldata['flat_price'].'" disabled>F ('.$currencysymbol.$stalldata['flat_price'].')</button>';
								}
							}
							
							$boxcolor  = 'green-box';
							$checkboxstatus = '';

							if($cartevent=='1' || ($checkeventstatus!='' && $checkeventstatus=='0')){
								$checkboxstatus = 'disabled';
							}

							$tabcontent .= 	'<li class="list-group-item d-flex align-items-center justify-content-between '.$typeofprice.'">
								<div>
									<input class="form-check-input eventbarnstall stallid me-1" data-price="'.$stalldata['price'].'" data-barnid="'.$stalldata['barn_id'].'" data-flag="1" value="'.$stalldata['id'].'" name="checkbox"  type="checkbox" '.$checkboxstatus.'>
									'.$stalldata['name'].'
								</div>
								<div class="d-flex align-items-center">
									<div class="pricelist f-r">'.$pricelist.'</div>
									<span class="'.$boxcolor.' stallavailability" data-stallid="'.$stalldata['id'].'" ></span>
								</div>
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
					<div class="border rounded pt-4 ps-3 pe-3 mb-3">
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
								$typeofprice = '';
								if($rvstalldata['charging_id']=='1'){
									$typeofprice = 'night_price';
								}else if($rvstalldata['charging_id']=='2'){
									$typeofprice = 'week_price';
								}else if($rvstalldata['charging_id']=='3'){
									$typeofprice = 'month_price';
								}else if($rvstalldata['charging_id']=='4'){
									$typeofprice = 'flat_price';
								}
								
								$pricelist = '';
								if($detail['eventusertype']=='2'){
									$priceflag = explode(',', $detail['price_flag']);
									
									if(isset($priceflag[0]) && $priceflag[0]=='1'){
										$pricelist .= '<button class="btn btn-success mr-2 night_button price_button" data-pricetype="1" data-pricebutton="'.$rvstalldata['night_price'].'" disabled>N ('.$currencysymbol.$rvstalldata['night_price'].')</button>';
									}
									if(isset($priceflag[1]) && $priceflag[1]=='1'){
										$pricelist .= '<button class="btn btn-success mr-2 week_button price_button" data-pricetype="2" data-pricebutton="'.$rvstalldata['week_price'].'" disabled>W ('.$currencysymbol.$rvstalldata['week_price'].')</button>';
									}
									if(isset($priceflag[2]) && $priceflag[2]=='1'){
										$pricelist .= '<button class="btn btn-success mr-2 month_button price_button" data-pricetype="3" data-pricebutton="'.$rvstalldata['month_price'].'" disabled>M ('.$currencysymbol.$rvstalldata['month_price'].')</button>';
									}
									if(isset($priceflag[3]) && $priceflag[3]=='1'){
										$pricelist .= '<button class="btn btn-success mr-2 flat_button price_button" data-pricetype="4" data-pricebutton="'.$rvstalldata['flat_price'].'" disabled>F ('.$currencysymbol.$rvstalldata['flat_price'].')</button>';
									}
								}
								
								$boxcolor  = 'green-box';
								$checkboxstatus = '';

								if($cartevent=='1' || ($checkeventstatus!='' && $checkeventstatus=='0')){
									$checkboxstatus = 'disabled';
								}

								$tabcontent .= 	'<li class="list-group-item rvhookups d-flex align-items-center justify-content-between '.$typeofprice.'">
									<div>
										<input class="form-check-input rvbarnstall stallid me-1" data-price="'.$rvstalldata['price'].'" data-barnid="'.$rvstalldata['barn_id'].'" data-flag="2" value="'.$rvstalldata['id'].'" name="checkbox"  type="checkbox" '.$checkboxstatus.'>
										'.$rvstalldata['name'].'
									</div>
									<div class="d-flex align-items-center">
										<div class="pricelist f-r">'.$pricelist.'</div>
										<span class="'.$boxcolor.' stallavailability" data-stallid="'.$rvstalldata['id'].'" ></span>
									</div>
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
					<div class="border rounded py-4 ps-3 pe-3 mb-3">
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
										<?php if($feed['quantity']==0){ $msg = '(Sold out)'; $readonly = 'readonly';} else{ $msg = ''; $readonly = ''; } ?>
										<input type="number" <?php echo $readonly ?> min="0" class="form-control quantity" data-productid="<?php echo $feed['id']?>" data-flag="3" <?php if($cartevent=='1' || ($checkeventstatus!='' && $checkeventstatus=='0')){ echo 'disabled'; } ?>>
										<p style="color:red">
											<?php echo $msg; ?></p>
									</td>
									<td style="border: 1px solid #e4e4e4;">
										<?php if($cartevent!='1' && $checkeventstatus!='' && $checkeventstatus!='0'){ ?>
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
					<div class="border rounded py-4 ps-3 pe-3 mb-3">
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
										<?php if($shaving['quantity']==0){ $msg = '(Sold out)'; $readonly = 'readonly';} else{ $msg = ''; $readonly = ''; } ?>
										<input type="number" min="0" class="form-control quantity" <?php echo $readonly;?> data-productid="<?php echo $shaving['id']?>" data-flag="4" <?php if($cartevent=='1' || ($checkeventstatus!='' && $checkeventstatus=='0')){ echo 'disabled'; } ?>>
										<p style="color:red"><?php echo $msg; ?></p>
									</td>
									<td style="border: 1px solid #e4e4e4;">
										<?php if($cartevent!='1' && $checkeventstatus!='' && $checkeventstatus!='0'){ ?>
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

<?php $this->section('js') ?>
	<script>
		var transactionfee		= '<?php echo $settings["transactionfee"];?>';  
		var currencysymbol 		= '<?php echo $currencysymbol; ?>';
		var eventid 			= '<?php echo $detail["id"]; ?>';
		var eventtype 			= '<?php echo $detail["type"]; ?>';
		var eventusertype 		= '<?php echo $detail["eventusertype"]; ?>';
		var cartevent 			= '<?php echo $cartevent; ?>';
		var pricelists 			= $.parseJSON('<?php echo json_encode($pricelists); ?>');
		
		$(document).ready(function (){
			if(cartevent == 0 ){
				cart();
			}else{
				$("#startdate, #enddate").attr('disabled', 'disabled');
			}
			
			if(eventtype==1){
				var checkevent 			= '<?php echo $checkeventstatus; ?>';
				var eventstartdate  	= '<?php echo $detail["start_date"] > date("Y-m-d") ? formatdate($detail["start_date"], 1) : 0; ?>';
				var eventenddate 		= '<?php echo formatdate($detail["end_date"], 1); ?>';
				var eventenddateadd 	= '<?php echo formatdate(date("Y-m-d", strtotime($detail["end_date"]." +1 day")), 1); ?>';
				
				if(checkevent == 0){
					$("#startdate, #enddate").attr('disabled', 'disabled');
				}

				uidatepicker(
					'#startdate', 
					{ 
						'mindate' 	: eventstartdate,
						'maxdate' 	: eventenddate,
						'close' 	: function(selecteddate){
							var date = new Date(selecteddate)
							date.setDate(date.getDate() + 1);
							$("#enddate").datepicker( "option", "minDate", date );
						}
					}
				);

				uidatepicker('#enddate', { 'mindate' : eventstartdate, 'maxdate' : eventenddateadd });			
			}else{
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
			}
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
					pricelist()
				}

				if(startdate!='' && enddate!=''){
					cart({type : eventtype, checked : 0}); 
					$('.stallid').prop('checked', false).removeAttr('disabled');
					$('.stallavailability').removeClass("yellow-box").removeClass("red-box").addClass("green-box");
					
					if(eventtype=='2') validatestalldates(enddate);
					occupiedreserved(startdate, enddate);
				}
			}, 100);
		})
		
		function pricelist(){			
			if(eventusertype==2){
				$('.price_button').removeClass('priceactive');
				
				$('.night_button').removeAttr('disabled');
				$('.week_button').removeAttr('disabled');
				$('.month_button').removeAttr('disabled');
				$('.flat_button').removeAttr('disabled');
			}else{					
				var startdates 		= new Date($("#startdate").val());
				var enddates 		= new Date($("#enddate").val());
				var stallinterval  	= enddates.getTime() - startdates.getTime(); 
				var intervaldays 	= stallinterval / (1000 * 3600 * 24); 
				
				$('.week_price').removeClass('displaynoneimp');
				$('.month_price').removeClass('displaynoneimp');
				$('.night_price').removeClass('displaynoneimp');
				
				if(intervaldays%7==0){
					$('.night_price').addClass('displaynoneimp');
					$('.month_price').addClass('displaynoneimp');
				}else if(intervaldays%30==0){ 
					$('.week_price').addClass('displaynoneimp');
					$('.night_price').addClass('displaynoneimp');
				}else{
					$('.week_price').addClass('displaynoneimp');
					$('.month_price').addClass('displaynoneimp');
				}
			}
		}
		
		$('.price_button').click(function(){
			$('.price_button').removeClass('priceactive');
			
			var stallbox = $(this).closest('li').find('.stallid');
			
			if(stallbox.is(':checked')){
				stallbox.click();
			}
			
			$(this).addClass('priceactive');
			stallbox.attr('data-price', $(this).attr('data-pricebutton'));
			stallbox.click();
		})
		
		function checkprice(_this){
			if(eventusertype==2 && _this.closest('li').find('.priceactive').length==0){
				_this.prop('checked', false);
				toastr.warning('Please select the price.', {timeOut: 5000});
				return false;
			}

			return true;
		}
		
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
							$('.stallid[value='+v+']').closest('li').find('.price_button').attr('disabled', 'disabled');
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
							$('.stallid[value='+i+']').closest('li').find('.price_button').attr('disabled', 'disabled');
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
							$('.stallid[value='+v+']').closest('li').find('.price_button').attr('disabled', 'disabled');
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
				var pricetype   = _this.closest('li').find('.priceactive').attr('data-pricetype'); 

				if($(_this).is(':checked')){  
					var pricevalidation = checkprice(_this);
					if(!pricevalidation) return false;
				
					var checkoccupiedreserved = occupiedreserved(startdate, enddate, stallid);
					if(checkoccupiedreserved==1) cart({event_id : eventid, barn_id : barnid, stall_id : stallid, price : price, pricetype : pricetype, quantity : 1, startdate : startdate, enddate : enddate, type : '1', checked : 1, flag : flag, actionid : ''});
				}else{ 
					$('.stallavailability[data-stallid='+stallid+']').removeClass("yellow-box").addClass("green-box");
					$('.stallavailability[data-stallid='+stallid+']').closest("li").find('.price_button').removeClass('priceactive');
					cart({stall_id : stallid, type : '1', checked : 0}); 
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
						cart({event_id : eventid, product_id : productid, price : price, quantity : quantity, startdate : startdate, enddate : enddate, type : '1', checked : 1, flag : flag, actionid : ''});
					}
				}else{
					quantitywrapper.val('');
					$('.cartremove[data-productid='+productid+']').addClass('displaynone'); 
					cart({product_id : productid, type : '1', checked : 0});
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

		function cart(data={cart:1, type:eventtype}){	
			ajax(
				'<?php echo base_url()."/cart"; ?>',
				data,
				{ 
					asynchronous : 1,
					success  : function(result){
						if(Object.keys(result).length){  
							$("#startdate").val(result.check_in); 
							$("#enddate").val(result.check_out); 

							pricelist();
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

						data += '<div class="row"><span class="col-7 event_c_text">'+v.stall_name+(v.pricetype!=0 ? '<span class="pricelist_tagline">('+pricelists[v.pricetype]+')</span>' : "")+'</span><span class="col-5 text-end event_c_text">('+currencysymbol+v.price+'x'+v.intervalday+') '+currencysymbol+v.total+'</span></div>';
						$('.stallid[value='+v.stall_id+']').removeAttr('disabled');
						
						if(v.pricetype!=0){
							$('.stallid[value='+v.stall_id+']').closest('li').find('.price_button').removeAttr('disabled');
							var pricebox = $('.stallid[value='+v.stall_id+']').closest('li').find('.price_button[data-pricetype="'+v.pricetype+'"]');
							pricebox.addClass('priceactive');
							$('.stallid[value='+v.stall_id+']').attr('data-price', pricebox.attr('data-pricebutton'));
						}
						
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
<?php $this->endSection(); ?>

