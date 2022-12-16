<?php
	$settings 			= getSettings();
	$userdetails 		= getSiteUserDetails();	
	
	$stripepublickey 	= $settings['stripepublickey'];
?>
<style>
* { margin : 0; }
#card-element {
	padding: 10px;
	border: 1px solid #ccc;
	border-radius: 5px;
}
#card-errors {
	font-size: 14px;
    padding: 5px 0;
    color: red;
}
</style>

<div class="modal fade" id="stripeFormModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="stripeFormModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Stripe Payment <span class="stripetotal"></span></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
		    <div class="modal-body">
				<form id="payment-form">
					<div id="card-element"></div>
					<div id="card-errors" role="alert"></div>
					<button class="btn btn-primary" id="submit">Pay Now</button>
				</form>
		    </div>
	    </div>
  	</div>
</div>

<form action="" method="post" class="stripeconfirm">
	<div class="mb-3 stripepaybutton">
		<input type="hidden" value="1" name="stripepay">
	</div>
</form>

<script src="https://js.stripe.com/v3/"></script>
<script>
	var stripe = Stripe('<?php echo $stripepublickey; ?>');
	var elements = stripe.elements();
	var style = {
		base: {
			color: "#32325d",
			fontSize: "16px",
		}
	};

	var card = elements.create("card", { hidePostalCode: true, style: style });
	card.mount("#card-element");
	card.on('change', function(event) {
		var displayError = document.getElementById('card-errors');
		if (event.error) {
			displayError.textContent = event.error.message;
		} else {
			displayError.textContent = '';
		}
	});
	
	$(document).on('click', '#submit', function(e){
		e.preventDefault();
		var displayError = document.getElementById('card-errors');
		
		stripe.createToken(card).then(function(result) {
			if (result.error) {
				displayError.textContent = result.error.message;
			} else {
				var subscribe = 0;
				var schedulesubscribe = 0;
				
				if($(document).find('.stripeextra input[name="page"]').length && $(document).find('.stripeextra input[name="page"]').val()=='myaccountsubscription'){
					subscribe = 1;
				}
				
				for(var i=0; i<2; i++){
					var selectorname = i==0 ? "barnstall" : "rvbarnstall";
					
					if($(document).find('.stripeextra textarea[name="'+selectorname+'"]').length){
						var barnstall = $.parseJSON($(document).find('.stripeextra textarea[name="'+selectorname+'"]').val());	
						$(barnstall).each(function(i, v){
							if(v.pricetype=='5' && v.subscriptionprice!='' && v.subscriptionprice!='0'){
								schedulesubscribe = 1;
								return false;
							}
						})		
						
						if(schedulesubscribe==1) break;
					}
				}
				
				if(subscribe==1){
					stripe.createPaymentMethod({type: 'card', card: card}).then(function(result){
						$(document).find('.stripeextra').append('<input type="hidden" name="stripe_payment_method_id" value="'+result.paymentMethod.id+'">');
						stripepay(card);
					})
				}else if(schedulesubscribe==1){
					stripe.createPaymentMethod({type: 'card', card: card}).then(function(result){
						for(var i=0; i<2; i++){
							var selectorname = i==0 ? "barnstall" : "rvbarnstall";
							
							if($(document).find('.stripeextra textarea[name="'+selectorname+'"]').length){
								var barnstalldata = [];
								var barnstall = $.parseJSON($(document).find('.stripeextra textarea[name="'+selectorname+'"]').val());	
								$(barnstall).each(function(i, v){
									if(v.pricetype=='5' && v.subscriptionprice!='' && v.subscriptionprice!='0'){
										v.stripe_payment_method_id = result.paymentMethod.id;
									}
									barnstalldata.push(v);
								})
								
								$(document).find('.stripeextra textarea[name="'+selectorname+'"]').remove();
								$(document).find('.stripeextra').append('<textarea style="display:none;" name="'+selectorname+'">'+JSON.stringify(barnstalldata)+'</textarea>');
							}
						}
						
						stripepay(card)
					});
				}else{
					stripepay(card)
				}				
			}
		})
	})
	
	function stripepay(card)
	{
		var basedata = {};
		$('.stripeextra input, .stripeextra textarea').each(function(){
			basedata[$(this).attr('name')] = $(this).val();
		})
		
		ajax('<?php echo base_url()."/ajax/ajaxstripepayment"; ?>', basedata, {
			beforesend: function() {
				$('.modal-content').append('<div class="loader_wrapper"><img src="<?php echo base_url()."/assets/site/img/loading.svg"; ?>"></div>');
			},
			success: function(data){
				var clientsecret = data.success.paymentintents.client_secret;
				var paymentid = data.success.id;
				
				stripe.confirmCardPayment(clientsecret, {
					payment_method: {
						card: card
					}
				}).then(function(res) {
					if (res.error) {
						displayError.textContent = res.error.message;
						$('.loader_wrapper').remove();
					} else {
						if(res.paymentIntent.status === 'succeeded'){
							if($(document).find('input[name="type"]').length){
								$(document).find('.stripepaybutton').append('<input type="hidden" name="type" value="'+$(document).find('input[name="type"]').val()+'">');
							}
							
							$(document).find('.stripeextra').remove();
							$(".stripeconfirm").submit();
						}else{
							$('.loader_wrapper').remove();
						}
					}
				});
			}
		})
	}
</script>