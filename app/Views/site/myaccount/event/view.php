<?php $this->extend('site/common/layout/layout1') ?>
<?php $this->section('content') ?>
<div class="page-action mb-4 m-0" align="left">
	<a href="<?php echo base_url(); ?>/myaccount/events" class="btn btn-dark">Back</a>
</div>
<section class="container-lg">
	<div class="row">
		<div class="col-12">
			<div class="border rounded pt-5 ps-3 pe-3">
				<div class="row">
					<div class="col-6">
						<span class="edimg">
							<img src="<?php echo base_url() ?>/assets/uploads/event/<?php echo $detail['image']?>" width="350px" height="auto">
						</span>
					</div>
					<div class="col-6">
						<h4 class="checkout-fw-6"><?php echo $detail['name'] ?></h4>
						<ul class="edaddr">
							<li class="mb-3 mt-3"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
								<path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
							</svg> 
							<?php echo $detail['location'] ?>
						</li>
						<li class="mb-3"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16">
							<path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
						</svg> 
						<?php echo $detail['mobile'] ?>
					</li>
					<div class="row">
						<span class="col-6">
							<p class="mb-1 fw-bold"><img class="eventFirstIcon" src="<?php echo base_url()?>/assets/site/img/stall.jpg">Stalls</p>
							<h6 class="ucprice"> from $<?php echo $detail['stalls_price'] ?> / night</h6>
						</span>
								<!-- <span class="col-6">
									<p class="mb-1 fw-bold"><img class="eventSecondIcon" src="<?php //echo base_url()?>/assets/site/img/rv.jpg">RV Spots</p>
									<h6 class="ucprice">from $<?php //echo $detail['rvspots_price'] ?> / night</h6>
								</span> -->
							</div>
						</ul>
					</div>
				</div>
				<div class="row row border-top pt-4 pb-4">
					<span class="col-3">
						<p class="mb-1 fw-bold"><img class="eventDIcon" src="<?php echo base_url() ?>/assets/site/img/date.png"> Start Date: </p>
						<p class="ucDAte mb-0">
							<?php  echo formatdate($detail['start_date'], 1);?></p>
						</span>
						<span class="col-3 border-end">
							<p class="mb-1 fw-bold"><img class="eventDIcon" src="<?php echo base_url() ?>/assets/site/img/date.png"> End Date: </p>
							<p class="ucDAte mb-0"><?php echo formatdate($detail['end_date'], 1); ?></p>
						</span>
						<span class="col-3">
							<p class="mb-1 fw-bold"><img class="eventDIcon" src="<?php echo base_url() ?>/assets/site/img/time.png"> Start Time: </p>
							<p class="ucDAte mb-0"> after <?php echo formattime($detail['start_time']) ?></p>
						</span>
						<span class="col-3">
							<p class="mb-1 fw-bold"><img class="eventDIcon" src="<?php echo base_url() ?>/assets/site/img/time.png"> End Time:</p>
							<p class="ucDAte mb-0">by <?php echo formattime($detail['end_time']) ?></p>
						</span>
					</div> 
				</div>
				<?php 
				$tabbtn = '';
				$tabcontent = '';
				foreach ($detail['barn'] as $barnkey => $barndata) {
					$barnid = $barndata['id'];
					$barnname = $barndata['name'];
					$barnactive = $barnkey=='0' ? ' show active' : '';
					$tabbtn .= '<button class="nav-link'.$barnactive.'" data-bs-toggle="tab" data-bs-target="#barn'.$barnid.'" type="button" role="tab" aria-controls="barn'.$barnid.'" aria-selected="true">'.$barnname.'</button>';
					
					$tabcontent .= '<div class="tab-pane fade'.$barnactive.'" id="barn'.$barnid.'" role="tabpanel" aria-labelledby="nav-home-tab">
					<ul class="list-group">';

					foreach($barndata['stall'] as $stalldata){
						$initialsub=''; $night_price=''; $month_price=''; $flat_price=''; $week_price='';
						if($stalldata['night_price']!=''){
							$night_price = 'N ('.$currencysymbol.$stalldata['night_price'].')';
						}
						if($stalldata['week_price']!=''){
							$week_price = 'W ('. $currencysymbol.$stalldata['week_price'].')';
						}
						if($stalldata['night_price']!=''){
							$month_price = 'M ('. $currencysymbol.$stalldata['month_price'].')';
						}
						if($stalldata['flat_price']!=''){
							$flat_price = 'F ('. $currencysymbol.$stalldata['flat_price'].')';
						}
						if($stalldata['subscription_initial_price']!='' && $stalldata['subscription_month_price']!=''){
							$initial 	= 'S ('. $currencysymbol.$stalldata['subscription_initial_price'].')';
							$subscrip 	=  'M ('. $currencysymbol.$stalldata['subscription_month_price'].')';
							$initialsub = $initial.$subscrip;
						}
						$bookedstalldata = [];
						if (!empty($stalldata['bookedstall'])) {
							foreach($stalldata['bookedstall'] as $bookedstall){
								if($bookedstall['status']=='1'){
									$bookedstalldata[] = 	
									'<div class="col-custom-3 p-2 border rounded ad-stall-base mx-2">
									<table>
									<tr>
									<td class="p-0"><p class="fs-7 mb-0 text-bold px-2">Name</p></td>
									<td class="p-0"><p class="mb-0 fs-7 fw-normal">'.$bookedstall['name'].'</p></td>
									</tr>
									<tr>
									<td class="p-0"><p class="fs-7 mb-0 text-bold px-2">Date</p></td>
									<td class="p-0"><p class="mb-0 fs-7 fw-normal">'.formatdate($bookedstall['check_in'], 1).' to '.formatdate($bookedstall['check_out'], 1).'</p></td>
									</tr>
									<tr>
									<td class="p-0"><p class="fs-7 mb-0 text-bold px-2">Payment Method</p></td>
									<td class="p-0"><p class="mb-0 fs-7 fw-normal">'.$bookedstall['paymentmethod'].'</p></td>
									</tr>
									</table>
									</div>
									';
								}
							}
						}
						
						$tabcontent .= 	'<li class="list-group-item px-4 py-3">
							<p class="text-bold mb-1">
							'.$stalldata['name'].'<div class="row">'.implode('', $bookedstalldata).'</p> 
							<p class="text-bold mb-1">
							'.$night_price.'</p>
							<p class="text-bold mb-1">
							'.$week_price.'</p>
							<p class="text-bold mb-1">
							'.$month_price.'</p>
							<p class="text-bold mb-1">
							'.$flat_price.'</p>
							<p class="text-bold mb-1">
							'.$initialsub.'</p>
						</li>';
					}

					$tabcontent .= '</ul></div>';
				}
				
				$rvtabbtn = '';
					$rvtabcontent = '';
					foreach ($detail['rvbarn'] as $rvbarnkey => $rvbarndata) {
						$rvbarnid = $rvbarndata['id'];
						$rvbarnname = $rvbarndata['name'];
						$rvbarnactive = $rvbarnkey=='0' ? ' show active' : '';
						$rvtabbtn .= '<button class="nav-link'.$rvbarnactive.'" data-bs-toggle="tab" data-bs-target="#barn'.$rvbarnid.'" type="button" role="tab" aria-controls="barn'.$rvbarnid.'" aria-selected="true">'.$rvbarnname.'</button>';
					
						$rvtabcontent .= '<div class="tab-pane fade'.$rvbarnactive.'" id="barn'.$barnid.'" role="tabpanel" aria-labelledby="nav-home-tab">
											<ul class="list-group">';
							foreach($rvbarndata['rvstall'] as $rvstalldata){
								$rvinitialsub=''; $rvnight_price=''; $rvmonth_price=''; $rvflat_price=''; $week_price='';
								if($rvstalldata['night_price']!=''){
									$rvnight_price = 'N ('.$currencysymbol.$rvstalldata['night_price'].')';
								}
								if($stalldata['week_price']!=''){
									$rvweek_price = 'W ('. $currencysymbol.$rvstalldata['week_price'].')';
								}
								if($stalldata['night_price']!=''){
									$rvmonth_price = 'M ('. $currencysymbol.$rvstalldata['month_price'].')';
								}
								if($stalldata['flat_price']!=''){
									$rvflat_price = 'F ('. $currencysymbol.$rvstalldata['flat_price'].')';
								}
								if($stalldata['subscription_initial_price']!='' && $stalldata['subscription_month_price']!=''){
									$initial 	= 'S ('. $currencysymbol.$rvstalldata['subscription_initial_price'].')';
									$subscrip 	=  'M ('. $currencysymbol.$rvstalldata['subscription_month_price'].')';
									$rvinitialsub = $initial.$subscrip;
								}
								$rvbookedstalldata = [];
								if (!empty($rvstalldata['rvbookedstall'])) {
									foreach($rvstalldata['rvbookedstall'] as $rvbookedstall){
										if($rvbookedstall['status']=='1'){
											$rvbookedstalldata[] ='<div class="col-custom-3 p-2 border rounded ad-stall-base mx-2">
																	<table>
																		<tr>
																			<td class="p-0"><p class="fs-7 mb-0 text-bold px-2">Name</p></td>
																			<td class="p-0"><p class="mb-0 fs-7 fw-normal">'.$rvbookedstall['name'].'</p></td>
																		</tr>
																		<tr>
																			<td class="p-0"><p class="fs-7 mb-0 text-bold px-2">Date</p></td>
																			<td class="p-0"><p class="mb-0 fs-7 fw-normal">'.formatdate($rvbookedstall['check_in'], 1).' to '.formatdate($rvbookedstall['check_out'], 1).'</p></td>
																		</tr>
																	</table>
																</div>
																';
									}
									}
								}
									$rvtabcontent .= 	'<li class="list-group-item px-4 py-3">
															<p class="text-bold mb-1">
															'.$rvstalldata['name'].'<div class="row">'.implode('', $rvbookedstalldata).'</div>
															</p>
															<p class="text-bold mb-1">
															'.$rvnight_price.'</p>
															<p class="text-bold mb-1">
															'.$rvweek_price.'</p>
															<p class="text-bold mb-1">
															'.$rvmonth_price.'</p>
															<p class="text-bold mb-1">
															'.$rvflat_price.'</p>
															<p class="text-bold mb-1">
															'.$rvinitialsub.'</p>
														</li>';
						}
							$rvtabcontent .= '</ul></div>';
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
				<div class="rvbarn-nav mt-4">
					<nav>
						<div class="nav nav-tabs mb-4" id="nav-tab" role="tablist">
							<?php echo $rvtabbtn; ?>
						</div>
					</nav>
					<div class="tab-content" id="nav-tabContent">
						<?php echo $rvtabcontent; ?>
					</div>    
				</div>
			</div>
		</div>
	</section>
	<?php $this->endSection() ?>