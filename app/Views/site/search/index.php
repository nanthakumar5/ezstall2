<?= $this->extend("site/common/layout/layout1") ?>
<?php $this->section('content') ?>
	<section class="maxWidth">
		<div class="marFive dFlexComBetween eventTP">
			<h1 class="eventPageTitle">Events / Facility</h1>
		</div>
		<form method="get" autocomplete="off" action="<?php echo base_url();?>/search" class="homeeventsearch eventsearch">
			<div class="infoPanel searchPanel">
				<span class="mx-auto infoSection">
					<span class="iconProperty">
						<input type="text" name="location" placeholder="Search" value="<?php echo isset($searchdata['llocation']) ? $searchdata['llocation'] : ''; ?>">
						<img src="<?php echo base_url()?>/assets/site/img/location.svg" class="iconPlace" alt="Map Icon">
					</span>
					<span class="iconProperty">
						<input type="text" name="start_date" class="event_search_start_date" placeholder="Check-In" value="<?php echo isset($searchdata['btw_start_date']) ? $searchdata['btw_start_date'] : ''; ?>">
						<img src="<?php echo base_url()?>/assets/site/img/calendar.svg" class="iconPlace" alt="Calender Icon">
					</span>
					<span class="iconProperty">
						<input type="text" name="end_date" class="event_search_end_date" placeholder="Check-Out" value="<?php echo isset($searchdata['btw_end_date']) ? $searchdata['btw_end_date'] : ''; ?>">
						<img src="<?php echo base_url()?>/assets/site/img/calendar.svg" class="iconPlace" alt="Calender Icon">
					</span>
					<input type="text" name="no_of_stalls" placeholder="No.of stalls" value="<?php echo isset($searchdata['no_of_stalls']) ? $searchdata['no_of_stalls'] : ''; ?>">
					<span class="searchResult">
						<button type="submit">
							<svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" class="searchIcon" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
								<path d="M456.69 421.39L362.6 327.3a173.81 173.81 0 0034.84-104.58C397.44 126.38 319.06 48 222.72 48S48 126.38 48 222.72s78.38 174.72 174.72 174.72A173.81 173.81 0 00327.3 362.6l94.09 94.09a25 25 0 0035.3-35.3zM97.92 222.72a124.8 124.8 0 11124.8 124.8 124.95 124.95 0 01-124.8-124.8z"></path>
							</svg>
						</button>
					</span>
				</span>
			</div>
		</form>
		<section class="maxWidth marFiveRes eventPagePanel">
			<?php if(count($list) > 0) { ?>  
				<?php foreach ($list as $data) {  
					$eventtype 		= $data['type']; 
					$startdate 		= formatdate($data['start_date'], 1);
					$enddate 		= formatdate($data['end_date'], 1);
					$booknowBtn 	= checkEvent($data); 
					$recbooknowBtn 	= $booknowBtn['btn'];
				?>
				<div class="ucEventInfo">
					<?php if($eventtype=='1'){ ?>
						<div class="EventFlex">
							<span class="wi-50 m-0">
								<div class="EventFlex leftdata">
									<span class="wi-30">
										<span class="ucimg">
											<img src="<?php echo base_url() ?>/assets/uploads/event/<?php echo '120x90_'.$data['image']?>"> 
										</span>
									</span>
									<span class="wi-70"> 
										<p class="topdate"> <?php echo $startdate; ?> - 
											<?php echo $enddate; ?> -  
											<?php echo $data['location']; ?></p>
										<a class="text-decoration-none" href="<?php echo base_url() ?>/events/detail/<?php echo $data['id']?>"><h5><?php echo $data['name']; ?><h5></a></h5>
									</span>
								</div>
							</span>
							<div class="wi-50-2 justify-content-between">
								<span class="m-left upevent">
									<p><img class="eventFirstIcon" src="<?php echo base_url()?>/assets/site/img/horseShoe.svg">Stalls</p>
									<h6 class="ucprice"> from $<?php echo $data['startingstallprice'] ?> / night</h6>
								</span>
								<a class="text-decoration-none text-white" id="booknow_link" href="<?php echo base_url() ?>/events/detail/<?php echo $data['id']?>">
									<button class="ucEventBtn"><?php echo $booknowBtn['btn'];?></button>
								</a>
							</div>
						</div>
					<?php }elseif($eventtype=='2'){ ?>
						<div class="EventFlex facility">
							<span class="wi-50">
								<div class="EventFlex leftdata facility">
									<span class="wi-30">
										<span class="ucimg">
											<img src="<?php echo base_url() ?>/assets/uploads/event/<?php echo '120x90_'.$data['image']?>">
										</span>
									</span>
									<span class="wi-70"> 
										<a class="text-decoration-none" href="<?php echo base_url() ?>/facility/detail/<?php echo $data['id']?>"><h5><?php echo $data['name']; ?><h5></a>
										<p class="facilityDes"><?php echo strip_tags(substr($data['description'],0,30)) ; ?></p>
									</span>
								</div>
							</span>
							<div class="wi-50-2 justify-content-between">
								<span class="m-left upevent">
									<p><img class="eventFirstIcon" src="<?php echo base_url()?>/assets/site/img/horseShoe.svg">Stalls</p>
									<h6 class="ucprice"> starting from $<?php echo $data['startingstallprice']; ?></h6>
								</span>
								<a class="text-decoration-none text-white" id="booknow_link" href="<?php echo base_url() ?>/facility/detail/<?php echo $data['id']?>">
									<button class="ucEventBtn">
										Book Now
									</button>
								</a>
							</div>
						</div>
					<?php } ?>
				</div>
			<?php } ?>
			<?php echo $pager; ?>
			<?php }else{ ?>
				No Record Found
			<?php } ?>
		</section>
	</section>
<?php $this->endSection(); ?>

