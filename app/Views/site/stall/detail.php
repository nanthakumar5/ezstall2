<?php $this->extend('site/common/layout/layout1') ?>
<?php $this->section('content') ?>
	<?php
	    $currentdate = date("m-d-Y");
		$getcart 	 = getCart('2');
		$cartevent 	 = ($getcart && $getcart['barnstall'][0]['stall_id'] != $detail['id']) ? 1 : 0;

		$name 		 = $detail['name'];
		$description = $detail['description'];
		$image 		 = base_url().'/assets/uploads/stall/'.$detail['image'];
		$price 		 = $detail['price'];
		$startdate 	 = $detail['start_date'];
		$enddate 	 = $detail['end_date'];
		$eventid 	 = $detail['event_id'];
		$barnid 	 = $detail['barn_id'];
		$stallid 	 = $detail['id'];
	?>
	
	<?php if($cartevent==1){?>
		<div class="alert alert-success alert-dismissible fade show m-2" role="alert">
			For booking this stall remove other stalls from the cart <a href="<?php echo base_url().'/stalls/detail/'.$getcart['barnstall'][0]['stall_id']; ?>">Go To Stall</a>
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
						<img src="<?php echo base_url() ?>/assets/site/img/stallhead.png">
					</div>
					<div class="float-next">
						<h4 class="fw-bold"><?php echo $name; ?></h4>
					</div>
				</div>
				<div class="stall-description">
					<h4 class="fw-bold">Description</h4>
					<p><?php echo $description;?> </p>
				</div>
				<div class="stall-riding">
					<h4 class="fw-bold">Riding Disciplines</h4>
					<ul>
						<li>Ut pharetra sem vehicula pulvinar bibendum.</li>
						<li>Aenean convallis turpis nec turpis consequat aliquam.</li>
						<li>In ullamcorper velit lobortis quam pretium, et malesuada dolor rutrum.</li>
						<li>Fusce quis mauris vitae metus mattis convallis sed at nulla.</li>
					</ul>
				</div>
				<div class="stall-cancel">
					<h4 class="fw-bold">Cancellation Policy</h4>
					<p>Quis autem vel eum iure reprehenderit qui in ea volute velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur.</p>
				</div>
			</div>
			
			<div class="col-lg-4">
				<div class="stall-right">
					<div class="stall-price">
						<b><?php echo $currencysymbol.$price; ?></b> per day
					</div>
					<div class="section1">
						<div class="col-md-12 px-3 mt-3">
							<div class="mb-3">
								<label>Check In</label>
								<input type="text" name="startdate" id="startdate" class="form-control" autocomplete = "off" placeholder = "Check-In"/>                                   
							</div>
							<div class="mb-3">
								<label>Check Out</label>
								<input type="text" name="enddate" id="enddate" class="form-control" autocomplete = "off" placeholder = "Check-Out"/>                       
							</div>
						</div>
						<div class="stall-btn">
							<button class="stalldetail-btn" id="checkavailability">Check Availability</button>
						</div>
						<div class="tagline displaynone notavailable text-center">Stall is not available on the selected dates.</div>
					</div>
					<div class="section2 displaynone">
						<div class="stall-date">
							<p class="fw-bold">Dates</p>                                 
							<p class="float-left" id="startdatetxt" ></p> - <p class="float-end" id="enddatetxt"></p>
						</div>
						<div class="stall-total">
							<p class="float-start fw-bold tot">Total</p>
							<p class="float-end  fw-bold"><span id="stallamount"></span><span class="redcolor">Fees</span></p>
						</div>
						<div class="stall-points">
							<ul>
								<li>You can cancel at any point of time.</li>
								<li>You will not be charged without your approval.</li>
							</ul>
						</div> 
						<div class="stall-btn">
							<button class="stalldetail-btn" id="booknow">Book Now</button>
							<button class="stalldetail-btn mt-1" id="remove">Remove</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
<?php $this->endSection() ?>

<?php $this->section('js') ?>
<script> 
    var currentdate         = '<?php echo $currentdate;?>';
	var cartevent 			= '<?php echo $cartevent; ?>';
	var eventid 			= '<?php echo $eventid; ?>';
	var barnid 				= '<?php echo $barnid; ?>';
	var stallid 			= '<?php echo $stallid; ?>';
	var eventstartdate  	= '<?php echo $startdate > $currentdate ? formatdate($startdate, 1) : 0; ?>';
	var eventenddate 		= '<?php echo formatdate($enddate, 1); ?>';
	var eventenddateadd 	= '<?php echo formatdate(date("Y-m-d", strtotime($enddate." +1 day")), 1); ?>';
	var price 				= '<?php echo $price; ?>';
	var currencysymbol 		= '<?php echo $currencysymbol; ?>';
	
	$(document).ready(function (){
		if(cartevent == 0 ){
			cart();
		}
		else{
			$("#startdate, #enddate, #checkavailability").attr('disabled', 'disabled');
        }
		
		if(currentdate > eventenddate){
			$("#startdate, #enddate, #checkavailability").attr('disabled', 'disabled');
        }

		uidatepicker(
			'#startdate', 
			{ 
				'mindate' 	: eventstartdate,
				'maxdate' 	: eventenddate,
				'close'    	: function(selecteddate){
					var date = new Date(selecteddate)
					date.setDate(date.getDate() + 1);
					$("#enddate").datepicker( "option", "minDate", date );
				}
			}
		);
		
		uidatepicker('#enddate', { 'mindate' : eventstartdate, 'maxdate' : eventenddateadd});
	});

	$("#enddate").click(function(){
		var startdate 	= $("#startdate").val();
		if(startdate==''){
			$("#startdate").focus();
		}
	});
	
	$("#checkavailability").on("click", function() {
		var startdate 	= $("#startdate").val(); 
		var enddate   	= $("#enddate").val(); 
		
		if(startdate==''){
			$("#startdate").focus();
			toastr.warning('Please select the Check-In Date.', {timeOut: 5000});
		}else if(enddate==''){
			$("#enddate").focus();
			toastr.warning('Please select the Check-Out Date.', {timeOut: 5000});
		}
		
		if(startdate!='' && enddate!=''){
			var dt1 			= new Date(startdate);
			var dt2 			= new Date(enddate);
			var datediff        = (dt2.getTime() - dt1.getTime()) / (1000 * 60 * 60 * 24);
			var totalfees       = parseFloat(Math.floor(datediff)) * parseFloat(price);
			var stallamount     = currencysymbol+totalfees

			occupiedreserved(startdate, enddate).then((data) => { 
				if(data==1){
					$('#startdatetxt').text(startdate);
					$('#enddatetxt').text(enddate);
					$('#stallamount').text(stallamount);
					
					$('.section1').addClass('displaynone');
					$('.section2').removeClass('displaynone');
					$('.notavailable').addClass('displaynone');
				}else{
					$('.notavailable').removeClass('displaynone');
				}
			});
		}
	});

	function occupiedreserved(startdate, enddate){
		return new Promise((resolve, reject) => {
			ajax(
				'<?php echo base_url()."/ajax/ajaxoccupied"; ?>',
				{ eventid : eventid, checkin : startdate, checkout : enddate },
				{
					success : function(data){
						if(data.success.length){
							resolve(0);
						}else{
							ajax(
								'<?php echo base_url()."/ajax/ajaxreserved"; ?>',
								{ eventid : eventid, checkin : startdate, checkout : enddate },
								{
									asynchronous : 1,
									success : function(data){
										if(data.success.length){
											resolve(0);
										}else{
											resolve(1);
										}
									}
								}
							)
						}
					}
				}
			)
		})
	}
	
	$("#booknow").click(function(){
		var startdate 	= $("#startdate").val();
		var enddate 	= $("#enddate").val();
		
		cart({stall_id : stallid, event_id : eventid, barn_id : barnid, price : price, startdate : startdate, enddate : enddate, type : '2', checked : 1, actionid : ''}).then(data => {
			window.location.href = '<?php echo base_url()."/checkout"; ?>';
		});
	});
	
	$("#remove").click(function(){
		cart({type : '2', checked : 0}).then(data => {
			$("#startdate, #enddate").val(''); 
			$('.section1').removeClass('displaynone');
			$('.section2').addClass('displaynone');
			$('.notavailable').addClass('displaynone');
		});
	});
	
	function cart(data={cart:1, type:2}){	 	
		return new Promise((resolve, reject) => {
			ajax(
				'<?php echo base_url()."/cart"; ?>',
				data,
				{ 
					asynchronous : 1,
					success  : function(result){
						if(Object.keys(result).length && data.cart==1){  
							$("#startdate").val(result.check_in); 
							$("#enddate").val(result.check_out);
							
							$('#startdatetxt').text(result.check_in);
							$('#enddatetxt').text(result.check_out);
							$('#stallamount').text(currencysymbol+result.price);
							
							$('.section1').addClass('displaynone');
							$('.section2').removeClass('displaynone');
							$('.notavailable').addClass('displaynone');
						}else {
							resolve(1);
						}
					}
				}
			);
		})
	}
	</script>
<?php echo $this->endSection() ?>