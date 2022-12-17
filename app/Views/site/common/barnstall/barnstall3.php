<?php 
$GLOBALS['gdetail']			= $detail;
$GLOBALS['gcurrencysymbol']	= $currencysymbol;

function charging($chargingid){
	$typeofprice = '';
	if($chargingid=='1'){ 
		$typeofprice = 'night_price';
	}else if($chargingid=='2'){
		$typeofprice = 'week_price';
	}else if($chargingid=='3'){
		$typeofprice = 'month_price';
	}else if($chargingid=='4'){
		$typeofprice = 'flat_price';
	}
	
	return $typeofprice;
}

function pricinglist($night, $week, $month, $flat, $sinitial, $smonth){
	global $gdetail;
	global $gcurrencysymbol;
	
	$pricelist = '';
	//if($gdetail['eventusertype']=='2'){
		$priceflag = explode(',', $gdetail['price_flag']);
		
		if(isset($priceflag[0]) && $priceflag[0]=='1'){
			$pricelist .= '<button class="btn btn-success mr-2 night_button price_button" data-pricetype="1" data-pricebutton="'.$night.'">N ('.$gcurrencysymbol.$night.')</button>';
		}
		if(isset($priceflag[1]) && $priceflag[1]=='1'){
			$pricelist .= '<button class="btn btn-success mr-2 week_button price_button" data-pricetype="2" data-pricebutton="'.$week.'">W ('.$gcurrencysymbol.$week.')</button>';
		}
		if(isset($priceflag[2]) && $priceflag[2]=='1'){
			$pricelist .= '<button class="btn btn-success mr-2 month_button price_button" data-pricetype="3" data-pricebutton="'.$month.'">M ('.$gcurrencysymbol.$month.')</button>';
		}
		if(isset($priceflag[3]) && $priceflag[3]=='1'){
			$pricelist .= '<button class="btn btn-success mr-2 flat_button price_button" data-pricetype="4" data-pricebutton="'.$flat.'">F ('.$gcurrencysymbol.$flat.')</button>';
		}
		if(isset($priceflag[4]) && $priceflag[4]=='1'){
			$pricelist .= '<button class="btn btn-success mr-2 flat_button price_button" data-pricetype="5" data-pricebutton="'.$sinitial.'" data-sprice="'.$smonth.'" >S ('.$gcurrencysymbol.$sinitial.') M ('.$gcurrencysymbol.$smonth.')</button>';
		}
	//}
	
	return $pricelist;
}
?>
<div class="col-md-12 p-0 eventbarnstallwrapper position-relative">
	<div class="border rounded pt-4 ps-3 pe-3 mt-4 mb-3">
		<div class="barn-nav mt-4">
			<nav>
				<div class="nav nav-tabs" id="multi-nav-tab" role="tablist">
					<button class="nav-link m-0 show active" data-bs-toggle="tab" data-bs-target="#barnstall" type="button" role="tab" aria-controls="barnstall" aria-selected="true">Stalls</button>
					<?php if($detail['rv_flag'] =='1' && !empty($detail['rvbarn'])) { ?>
						<button class="nav-link m-0" data-bs-toggle="tab" data-bs-target="#barnhook" type="button" role="tab" aria-controls="barnhook" aria-selected="false">RV Hookups</button>
					<?php } ?>		
				</div>
			</nav>
			<div class="tab-content" id="nav-tabContent">
				<div class="tab-pane fade active show" id="barnstall" role="tabpanel" aria-labelledby="nav-home-tab">
					<div class="border rounded pt-4 ps-3 pe-3 mb-3">						
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
								$typeofprice 	= charging($stalldata['charging_id']);
								$pricelist 		= pricinglist($stalldata['night_price'], $stalldata['week_price'], $stalldata['month_price'], $stalldata['flat_price'], $stalldata['subscription_initial_price'], $stalldata['subscription_month_price']);
							
								$tabcontent .= 	'<li class="list-group-item d-flex align-items-center justify-content-between '.$typeofprice.'">
									<div>
										'.$stalldata['name'].'
									</div>
									<div class="d-flex align-items-center">
										<div class="pricelist f-r">'.$pricelist.'</div>
									</div>
								</li>';
							}
							$tabcontent .= '</ul></div>';
						}?>
						<div class="barn-nav mt-4">
							<nav>
								<div class="nav nav-tabs" id="nav-tab" role="tablist">
									<?php echo $tabbtn; ?>
								</div>
							</nav>
							<div class="tab-content" id="nav-tabContent">
								<?php echo $tabcontent; ?>
							</div>    
						</div>
					</div>
				</div>
				<?php if($detail['rv_flag'] =='1' && !empty($detail['rvbarn'])) { ?>
					<div class="tab-pane fade" id="barnhook" role="tabpanel" aria-labelledby="nav-home-tab">
						<div class="border rounded pt-4 ps-3 pe-3 mb-3">						
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
								$typeofprice 	= charging($rvstalldata['charging_id']);
								$pricelist 		= pricinglist($rvstalldata['night_price'], $rvstalldata['week_price'], $rvstalldata['month_price'], $rvstalldata['flat_price'], $rvstalldata['subscription_initial_price'], $rvstalldata['subscription_month_price']);
								$tabcontent .= 	'<li class="list-group-item rvhookups d-flex align-items-center justify-content-between '.$typeofprice.'">
									<div>
										'.$rvstalldata['name'].'
									</div>
									<div class="d-flex align-items-center">
										<div class="pricelist f-r">'.$pricelist.'</div>
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
								</div>    
							</div>
						</div>
					</div>	
				<?php } ?>
			</div>    
		</div>
	</div>
</div>

