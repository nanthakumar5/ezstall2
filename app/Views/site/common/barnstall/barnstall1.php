<style>
	.modal-content {
		border: 3px solid grey;
		padding: 15px;
		text-align: center;
	}
</style>

<?php
$id 					= isset($result['id']) ? $result['id'] : '';
$feed_flag 				= isset($result['feed_flag']) ? $result['feed_flag'] : '';
$shaving_flag 			= isset($result['shaving_flag']) ? $result['shaving_flag'] : '';
$rv_flag 				= isset($result['rv_flag']) ? $result['rv_flag'] : '';
$cleaning_flag 			= isset($result['cleaning_flag']) ? $result['cleaning_flag'] : '';
$notification_flag 		= isset($result['notification_flag']) ? $result['notification_flag'] : '';
$price_flag 			= isset($result['price_flag']) ? explode(',', $result['price_flag']) : [];
$price_fee 				= isset($result['price_fee']) ? explode(',', $result['price_fee']) : [];
$cleaning_fee 			= isset($result['cleaning_fee']) ? $result['cleaning_fee'] : '';
$barn        			= isset($result['barn']) ? $result['barn'] : [];
$rvbarn        			= isset($result['rvbarn']) ? $result['rvbarn'] : [];
$feed 					= isset($result['feed']) ? $result['feed'] : '';
$shaving 				= isset($result['shaving']) ? $result['shaving'] : '';
?>
<div class="card">
	<div class="card-body">
		<div>
			<div class="d-flex justify-content-between flex-wrap align-items-center my-3">
				<p>Will you be selling feed at this event? </p>

				<div>
					<?php foreach($yesno as $key => $data){ ?>
						<button type="button" class="btn questionmodal_feed event_btn" value="<?php echo $key; ?>"><?php echo $data; ?></button>
					<?php } ?>
					<input type="hidden" value="" class="feed_flag" name="feed_flag">
				</div>
			</div>
			<div class="d-flex justify-content-between flex-wrap align-items-center my-3">
				<p>Will you be selling shavings at this event?</p>
				<div>
					<?php foreach($yesno as $key => $data){ ?>
						<button type="button" class="btn questionmodal_shaving event_btn" value="<?php echo $key; ?>"><?php echo $data; ?></button>
					<?php } ?>
					<input type="hidden" value="" class="shaving_flag" name="shaving_flag">
				</div>
			</div>
			<div class="d-flex justify-content-between flex-wrap align-items-center my-3">
				<p>Will you have RV Hookups at this event? </p>
				<div>
					<?php foreach($yesno as $key => $data){ ?>
						<button type="button" class="btn questionmodal_rv event_btn" value="<?php echo $key; ?>"><?php echo $data; ?></button>
					<?php } ?>
					<input type="hidden" value="" class="rv_flag" name="rv_flag">
				</div>
			</div>
			<div class="d-flex justify-content-between flex-wrap align-items-center my-3">
				<p>Will you Collect the Cleaning fee from Horse owner? </p>
				<div>
					<?php foreach($yesno as $key => $data){ ?>
						<button type="button" class="btn questionmodal_cleaning event_btn" value="<?php echo $key; ?>"><?php echo $data; ?></button>
					<?php } ?>
					<input type="hidden" value="" class="cleaning_flag" name="cleaning_flag">
				</div>
			</div>
			<div class="d-flex justify-content-between flex-wrap align-items-center my-3">
				<p>Send a text message to users when their stall is unlocked and ready for use? </p>
				<div>
					<?php foreach($yesno as $key => $data){ ?>
						<button type="button" class="btn questionmodal_notification event_btn" value="<?php echo $key; ?>"><?php echo $data; ?></button>
					<?php } ?>
					<input type="hidden" value="" class="notification_flag" name="notification_flag">
				</div>
			</div>
			<?php if($usertype=='2'){ ?>
				<div align="center">
					<div class="col-md-12 mt-3 bblg-2 pb-3">	
						<div class="row">	
							<div class="col-md-2">
								<input type="checkbox" class="questionmodal_priceflagall">
							</div>
							<div class="col-md-4">
								Rates
							</div>
							<div class="col-md-6">
								$
							</div>
						</div>
					</div>
					<?php foreach($pricelist as $key => $data){ ?>
						<div class="col-md-12 mt-3">	
							<div class="row">	
								<div class="col-md-2">
									<input type="checkbox" class="questionmodal_priceflag questionmodal_priceflag<?php echo $key; ?>" data-key="<?php echo $key; ?>" value="1" name="price_flag[<?php echo $key; ?>]" <?php if(isset($price_flag[$key-1]) && $price_flag[$key-1]==1){ echo 'checked'; } ?>>
								</div>
								<div class="col-md-4">
									<?php echo $data; ?>
								</div>
								<div class="col-md-6">
									<input type="number" min="0" class="questionmodal_pricefee questionmodal_pricefee<?php echo $key; ?> form-control" name="price_fee[<?php echo $key; ?>]" value="<?php if(isset($price_fee[$key-1]) && $price_fee[$key-1]!=0){ echo $price_fee[$key-1]; } ?>" <?php if(!isset($price_flag[$key-1]) || (isset($price_flag[$key-1]) && $price_flag[$key-1]==0)){ echo 'disabled'; } ?>>
								</div>
							</div>
						</div>
					<?php } ?>
					<input type="hidden" id="price_flag" value="<?php echo implode(',', $price_flag); ?>">
					<input type="hidden" id="price_fee" value="<?php echo implode(',', $price_fee); ?>">
				</div>
			<?php } ?>
		</div>
	</div>
</div>
<div class="card-body p-0">
	<div class="container row mt-5 dash-barn-style mx-auto">
		<div class="row align-items-center mb-4 p-0 addbarn">
			<div class="col-md-3">
				<p class="fs-2 fw-bold mb-0 barntfont">Barn</p>
			</div>
			<div class="col-md-9 t-right p-0 respsm">
				<input type="hidden" value="" name="barnvalidation" id="barnvalidation">
				<button class="btn-stall barnbtn" value="4" name="tst" id="tes">Add Barn</button>
			</div>
		</div>
		<ul class="nav nav-pills flex-column col-md-3 barntab" role="tablist"></ul>
		<div class="tab-content col-md-9 stalltab"></div>
	</div>
</div>
<div class="card-body p-0 feed_wrapper" style="display: none;">
	<div class="container row mt-5 dash-barn-style mx-auto">
		<div class="row align-items-center mb-4 p-0 addfeed">
			<div class="col-md-3">
				<h4 class="fw-bold mb-0 barntfontfeed">Feed</h4>
			</div>
			<div class="col-md-9 t-right p-0 respsm">
				<input type="hidden" value="" name="feedvalidation" id="feedvalidation">
			<button class="btn-stall feedbtn">Add feed</button>
			</div>
		</div>
		<div class="row" >
			<ul class="nav nav-pills flex-column feedlist" role="tablist"></ul>
			<div class="tab-content col-md-9 feedstalltab"></div>
		</div>
	</div>
</div>
<div class="card-body p-0 shaving_wrapper" style="display: none;">
	<div class="container row mt-5 dash-barn-style mx-auto">
		<div class="row align-items-center mb-4 p-0">
			<div class="col-md-3">
				<h4 class="fw-bold mb-0 barntfontshavings">Shavings</h4>
			</div>
			<div class="col-md-9 t-right p-0 respsm">
				<input type="hidden" value="" name="shavingsvalidation" id="shavingsvalidation">
				<button class="btn-stall shavingsbtn">Add Shavings</button>
			</div>
		</div>
		<div class="row" >
			<ul class="nav nav-pills flex-column shavingslist" role="tablist"></ul>
			<div class="tab-content col-md-9 shavingsstalltab"></div>
		</div>
	</div>
</div>
<div class="card-body p-0 rv_wrapper" style="display: none;">
	<div class="container row mt-5 dash-barn-style mx-auto">
		<div class="row align-items-center mb-4 p-0">
			<div class="col-md-3">
				<h4 class="fw-bold mb-0 barntfont">RV Hookups</h4>
			</div>
			<div class="col-md-9 t-right p-0 respsm">
				<input type="hidden" value="" name="rvhookupsvalidation" id="rvhookupsvalidation">
				<button class="btn-stall rvhookupsbtn">Add RV Hookups</button>
			</div>
		</div>
		<div class="row">
			<ul class="nav nav-pills flex-column col-md-3 rvhookupsbarntab" role="tablist"></ul>
			<div class="tab-content col-md-9 rvhookupsstalltab"></div>
		</div>
	</div>
</div>
<div class="card-body p-0 cleaning_wrapper" style="display: none;">
	<div class="container row mt-5 dash-barn-style mx-auto">
		<div class="row align-items-center mb-4 p-0 cleaningfee">
			<div class="col-md-3">
				<h4 class="fw-bold mb-0 barntfontfee">Cleaning Fee</h4>
			</div>
			<div class="col-md-12 my-2">
				<div class="form-group">
					<input type="text" name="cleaning_fee" class="form-control" id="cleaning_fee" placeholder="Enter Cleaning Fee" value="<?php echo $cleaning_fee ?>">
				</div>
			</div>
		</div>
	</div>
</div>

<?php $this->section('js') ?>
	<?php if($id==''){ ?>
		<div class="modal" id="questionmodal" data-bs-backdrop="static">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-body modalcarousel active first">
						Will you be selling feed at this event?
						<div align="center" class="mt-3">
							<?php foreach($yesno as $key => $data){ ?>
								<button type="button" data-type="feed" class="btn questionmodal_feed model_btn questionmodal_btn" value="<?php echo $key; ?>"><?php echo $data; ?></button>
							<?php } ?>
						</div>
					</div>
					<div class="modal-body modalcarousel displaynone">
						Will you be selling shavings at this event? 
						<div align="center"  class="mt-3">
							<?php foreach($yesno as $key => $data){ ?>
								<button type="button" data-type="shaving" class="btn questionmodal_shaving model_btn questionmodal_btn" value="<?php echo $key; ?>"><?php echo $data; ?></button>
							<?php } ?>
						</div>
					</div>
					<div class="modal-body modalcarousel displaynone">
						Will you have RV Hookups at this event?
						<div align="center" class="mt-3">
							<?php foreach($yesno as $key => $data){ ?>
								<button type="button" data-type="rv" class="btn questionmodal_rv model_btn questionmodal_btn" value="<?php echo $key; ?>"><?php echo $data; ?></button>
							<?php } ?>
						</div>
					</div>
					<div class="modal-body modalcarousel displaynone">
						Will you collect the Cleaning fee from Horse owner?
						<div align="center" class="mt-3">
							<?php foreach($yesno as $key => $data){ ?>
								<button type="button" data-type="cleaning" class="btn questionmodal_cleaning model_btn questionmodal_btn" value="<?php echo $key; ?>"><?php echo $data; ?></button>
							<?php } ?>
						</div>
					</div>
					<div class="modal-body modalcarousel displaynone">
						Send a text message to users when their
						stall is unlocked and ready for use?
						<div align="center" class="mt-3">
							<?php foreach($yesno as $key => $data){ ?>
								<button type="button" data-type="notification" class="btn questionmodal_notification model_btn questionmodal_btn" value="<?php echo $key; ?>"><?php echo $data; ?></button>
							<?php } ?>
						</div>
					</div>
					<?php if($usertype=='2'){ ?>
						<div class="modal-body modalcarousel displaynone">
							<div align="center">
								<div class="col-md-10 mt-3 bblg-2 pb-3">	
									<div class="row">	
										<div class="col-md-2">
											<input type="checkbox" class="questionmodal_priceflagall_modal">
										</div>
										<div class="col-md-4">
											Rates
										</div>
										<div class="col-md-6">
											$
										</div>
									</div>
								</div>
								<?php foreach($pricelist as $key => $data){ ?>
									<div class="col-md-10 mt-3">	
										<div class="row">	
											<div class="col-md-2">
												<input type="checkbox" class="questionmodal_priceflag_modal" data-key="<?php echo $key; ?>" value="1">
											</div>
											<div class="col-md-4">
												<?php echo $data; ?>
											</div>
											<div class="col-md-6">
												<input type="number" min="0" class="questionmodal_pricefee_modal form-control" data-key="<?php echo $key; ?>" disabled>
											</div>
										</div>
									</div>
								<?php } ?>
								<button type="button" data-type="pricelist" class="btn questionmodal_pricelist model_btn questionmodal_btn mt-3" value="1">OK</button>
							</div>
						</div>
					<?php } ?>
					<div class="modal-body modalcarousel displaynone last">
						Thank You Fill out your custom event form with your stalls,
						and let your customers rest EZ!
						<div align="center" class="mt-3">
							<button type="button" class="btn questionmodalsubmit model_btn" data-bs-dismiss="modal">Go</button>
						</div>
					</div>
					<div class="d-flex">
						<a href="javascript:void(0);" class="model_arrow_left modalcarousel_prev displaynone"><i class="fas fa-chevron-left"></i></a>
						<a href="javascript:void(0);" class="model_arrow_right modalcarousel_next" align="right"><i class="fas fa-chevron-right"></i></a>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>

	<script>		
		var barn				 	= $.parseJSON('<?php echo addslashes(json_encode($barn)); ?>'); 
		var rvbarn					= $.parseJSON('<?php echo addslashes(json_encode($rvbarn)); ?>');
		var feed				 	= $.parseJSON('<?php echo addslashes(json_encode($feed)); ?>');
		var shaving					= $.parseJSON('<?php echo addslashes(json_encode($shaving)); ?>');
		var occupied 	 			= $.parseJSON('<?php echo json_encode((isset($occupied)) ? array_filter($occupied) : []); ?>');
		var reserved 	 			= $.parseJSON('<?php echo json_encode((isset($reserved)) ? array_filter(explode(",", implode(",", array_keys($reserved)))) : []); ?>');		
		var chargingflag			= $.parseJSON('<?php echo addslashes(json_encode(isset($chargingflag) ? $chargingflag : [])); ?>'); 
		var rv_flag				 	= '<?php echo $rv_flag ?>';
		var feed_flag				= '<?php echo $feed_flag ?>';
		var shaving_flag			= '<?php echo $shaving_flag ?>';
		var cleaning_flag			= '<?php echo $cleaning_flag ?>'; 
		var notification_flag		= '<?php echo $notification_flag ?>';
		var usertype				= '<?php echo $usertype ?>';
		var pagetype				= '<?php echo isset($pagetype) ? $pagetype : '' ?>';
		
		$(function(){
			questionpopup1(1, 'rv', rv_flag)
			questionpopup1(1, 'feed', feed_flag)
			questionpopup1(1, 'shaving', shaving_flag)
			questionpopup1(1, 'cleaning', cleaning_flag)
			questionpopup1(2, 'notification', notification_flag)
			
			barnstall('barn', [['.barnbtn'], ['.barntab', '.stalltab'], [0, 0], ['#barnvalidation'],[usertype, chargingflag]], [barn, occupied, reserved])
			barnstall('rvhookups', [['.rvhookupsbtn'], ['.rvhookupsbarntab', '.rvhookupsstalltab'], [0, 0], ['#rvhookupsvalidation'], [usertype, chargingflag]], [rvbarn, occupied, reserved])			
			products('feed', [['.feedbtn'], ['.feedlist'], [0]], [feed])
			products('shavings', [['.shavingsbtn'], ['.shavingslist'], [0]], [shaving])
		});
		
		$('.questionmodal_shaving').click(function(e){ 
			e.preventDefault();
			questionpopup1(1, 'shaving', $(this).val())
		});
			
		$('.questionmodal_feed').click(function(e){ 
			e.preventDefault();
			questionpopup1(1, 'feed', $(this).val())
		});

		$('.questionmodal_rv').click(function(e){ 
			e.preventDefault();
			questionpopup1(1, 'rv', $(this).val())
		});

		$('.questionmodal_cleaning').click(function(e){ 
			e.preventDefault();
			questionpopup1(1, 'cleaning', $(this).val())
		});

		$('.questionmodal_notification').click(function(e){ 
			e.preventDefault();
			questionpopup1(2, 'notification', $(this).val())
		});

		function questionpopup1(type, name, value){ 
			$('.questionmodal_'+name).removeClass("btn-stall").addClass("event_btn");
			$('.questionmodal_'+name+'[value="'+value+'"]').removeClass("event_btn").addClass("btn-stall");
			$('.'+name+'_flag').val(value);   
			
			if(type=='1'){
				if(value=='1'){
					$('.'+name+'_wrapper').show();  
				}else{
					$('.'+name+'_wrapper').hide();      
				}
			}
		}
	</script>

	<script>
		var id = '<?php echo $id ?>';
		$(function(){
			if(id=="" && pagetype!=1){
				$('#questionmodal').modal('show');
				
				$('.modalcarousel_next').click(function(){
					var modaltype = $('.modalcarousel.active').find('.questionmodal_btn').attr('data-type');
					if(modaltype!=undefined && $(document).find('.'+modaltype+'_flag').val()==''){
						if(modaltype=='charging'){
							$('.modalcarousel.active').find('.questionmodal_btn[value="1"]').click();
						}else{
							$('.modalcarousel.active').find('.questionmodal_btn[value="2"]').click();
						}
					}else{
						var nextsibling = $('.modalcarousel.active').next('.modalcarousel');
						$('.modalcarousel').addClass('displaynone').removeClass('active');
						nextsibling.addClass('active').removeClass('displaynone');

						$('.modalcarousel_prev').removeClass('displaynone');
						if(nextsibling.hasClass('last')) $('.modalcarousel_next').addClass('displaynone');
					}
				})
				
				$('.modalcarousel_prev').click(function(){
					var prevsibling = $('.modalcarousel.active').prev('.modalcarousel');
					$('.modalcarousel').addClass('displaynone').removeClass('active');
					prevsibling.addClass('active').removeClass('displaynone');

					$('.modalcarousel_next').removeClass('displaynone');
					if(prevsibling.hasClass('first')) $('.modalcarousel_prev').addClass('displaynone');
				})
				
				$('.questionmodal_btn').click(function(){
					var nextsibling = $(this).parent().parent().next('.modalcarousel');
					$('.modalcarousel').addClass('displaynone').removeClass('active');
					nextsibling.addClass('active').removeClass('displaynone');

					$('.modalcarousel_prev').removeClass('displaynone');
					if(nextsibling.hasClass('last')) $('.modalcarousel_next').addClass('displaynone');
				})
				
				// START PRICE LIST
				
				$('.questionmodal_priceflagall_modal').click(function(){
					if($(this).is(':checked')){
						$('.questionmodal_priceflagall').prop('checked', true);
					}else{
						$('.questionmodal_priceflagall').prop('checked', false);
					}
					
					$('.questionmodal_priceflag_modal').each(function(){
						$(this).click();
					});
				})
				
				$('.questionmodal_priceflag_modal').click(function(){ 
					var key = $(this).attr('data-key');
						
					if($(this).is(':checked')){
						var pricefeemodal = $(this).parent().parent().find('.questionmodal_pricefee_modal');
						pricefeemodal.removeAttr('disabled');
						
						$('.questionmodal_priceflag'+key).prop('checked', true);
						$('.questionmodal_pricefee'+key).val(pricefeemodal.val()).removeAttr('disabled');
					}else{
						var pricefeemodal = $(this).parent().parent().find('.questionmodal_pricefee_modal');
						pricefeemodal.attr('disabled', 'disabled');
						
						$('.questionmodal_priceflag'+key).prop('checked', false);
						$('.questionmodal_pricefee'+key).val('').attr('disabled', 'disabled');
					}
					
					pricedata();
				});
				
				$('.questionmodal_pricefee_modal').keyup(function(){
					var key = $(this).attr('data-key');
					$('.questionmodal_pricefee'+key).val($(this).val());
				})	
				
				$('.questionmodal_pricefee_modal').blur(function(){
					pricedata();
				})	
			}	
				
			$('.questionmodal_priceflagall').click(function(){
				if($(this).is(':checked')){
					$('.questionmodal_priceflag').prop('checked', true);
				}else{
					$('.questionmodal_priceflag').prop('checked', false);
				}
				
				questionpopup2()
			})
			
			$('.questionmodal_priceflag').click(function(){ 
				questionpopup2()
			});
			
			function questionpopup2(){ 
				$('.questionmodal_priceflag').each(function(){
					var key = $(this).attr('data-key');
					
					if($(this).is(':checked')){
						$('.questionmodal_pricefee'+key).removeAttr('disabled');
					}else{
						$('.questionmodal_pricefee'+key).val('').attr('disabled', 'disabled');
					}
				})		
				
				pricedata();
			}
			
			$('.questionmodal_pricefee').blur(function(){
				pricedata();
			})
			
			function pricedata(){
				price_flag	= [];
				price_fee	= [];
				$('.questionmodal_priceflag').each(function(){
					var key = $(this).attr('data-key');
					
					if($(this).is(':checked')){
						price_flag.push(1);
						price_fee.push($('.questionmodal_pricefee'+key).val())
						
						$(document).find('.pricelistwrapper'+key).removeClass('displaynone');
						$(document).find('.pricelistwrapper'+key).each(function(){
							if($(this).find('input').val()=='' || $(this).find('input').val()=='0'){
								$(this).find('input').val($('.questionmodal_pricefee'+key).val());
							}
						});
					}else{
						price_flag.push(0);
						price_fee.push(0)
						
						$(document).find('.pricelistwrapper'+key).addClass('displaynone');
						$(document).find('.pricelistwrapper'+key).each(function(){
							$(this).find('input').val('');
						});
					}
				})
				
				$('#price_flag').val(price_flag.join(',')).trigger('change');
				$('#price_fee').val(price_fee.join(',')).trigger('change');
			}	
			// END PRICE LIST
		})
	</script>
<?php $this->endSection(); ?>

