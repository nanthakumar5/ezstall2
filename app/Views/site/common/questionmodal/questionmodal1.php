<style>
	.modal-content {
		border: 3px solid grey;
		padding: 15px;
		text-align: center;
	}
</style>
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
<script>
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
</script>

