<?php $this->extend('site/common/layout/layout1') ?>
<?php $this->section('content') ?>
	<?php
		$userdetail  	= getSiteUserDetails();
		$usertype  		= $userdetail ? $userdetail['type'] : '';
		$liststall 		= base_url().'/login';
		if($usertype=='2')  	$liststall	= base_url().'/myaccount/facility';
		elseif($usertype=='3')  $liststall 	= base_url().'/myaccount/events';
	?>
	<div class="wi-1200">
		<?php foreach ($aboutus as $key => $about) { if($key%2==0){?>
			<div class="displayFlex">
				<div class="flexOneLeft beforeRound">
					<img class="flexoneImage" src="<?php echo base_url().'/assets/uploads/aboutus/'.'680x440_'.$about['image']?>" />
				</div>
				<div class="flexOneRight afterHorse">
					<h1 class="commonTitle"><?php echo $about['title']; ?></h1>
					<p class="commonContent"><?php echo substr($about['content'], 0, 250); ?></p>
					<a class="text-white text-decoration-none" href="<?php echo base_url().'/aboutus/detail/'.$about['id']?>"><button class="greyButton">Read More</button></a>
				</div>
			</div>
		<?php } else { ?>
			<div class="displayFlex flexReverse">
				<div class="flexOneRight">
				<h1 class="commonTitle"><?php echo $about['title']; ?></h1>
					<p class="commonContent">
					<?php  echo substr($about['content'], 0, 250); ?>
					</p>
					<a class="text-white text-decoration-none" href="<?php echo base_url().'/aboutus/detail/'.$about['id']?>"><button class="greyButton">Read More</button></a>
				</div>
				<div class="flexOneLeft afterRound">
					<img class="flexoneImage" src="<?php echo base_url().'/assets/uploads/aboutus/'.'680x440_'.$about['image']?>" />
				</div>
			</div>
		<?php } } ?>
	</div>

	<section class="homeEventsPanel">
      	<div class="wi-1200">
			<ul class="nav nav-tabs align-items-center" id="myTab" role="tablist">
				<li class="nav-item" role="presentation">
					<button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Upcoming Events</button>
				</li>
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Past Events</button>
				</li>
				<a href="<?php echo base_url().'/events'; ?>" class="allEventsLink">View All Events <svg stroke="currentColor" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><polyline points="9 18 15 12 9 6"></polyline></svg></a>
			</ul>
			<div class="tab-content" id="myTabContent">
				<div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
					<div class="MuiTabPanel-root css-13xfq8m-MuiTabPanel-root" role="tabpanel" aria-labelledby="mui-p-25453-T-1" id="mui-p-25453-P-1">
						<?php if(!empty($upcomingevents)){ ?>
							<?php foreach($upcomingevents as $row){ ?>
								<div class="ucEventInfo">
									<div class="EventFlex">
										<span class="wi-50 m-0">
											<div class="EventFlex leftdata">
												<span class="wi-30">
													<span class="ucimg">
														<img src="<?php echo filesource('assets/uploads/event/120x90_'.$row['image']); ?>">
													</span>
												</span>
												<span class="wi-70"> 
													<p class="topdate"> <?php echo formatdate($row['start_date'], 1); ?> - <?php echo formatdate($row['end_date'], 1); ?> - <?php echo $row['location']; ?></p>
													<a class="text-decoration-none" href="<?php echo base_url(); ?>/events/detail/<?php echo $row['id'];?>"><h5><?php echo $row['name']; ?><h5></a></h5>
												</span>
											</div>
										</span>
										<div class="wi-50-2 justify-content-between">
											<span class="m-left upevent">
												<p><img class="eventFirstIcon" src="<?php echo base_url()?>/assets/site/img/horseShoe.svg">Stalls</p>
												<h6 class="ucprice"> starting from $<?php echo $row['startingstallprice']; ?></h6>
											</span>
											<a class="text-decoration-none text-white" href="<?php echo base_url(); ?>/events/detail/<?php echo $row['id'];?>">
												<button class="ucEventBtn">Book Now</button>
											</a>
										</div>
									</div>
								</div>
							<?php } ?>
						<?php }else{ ?>
							<p>No Upcoming Events</p>
						<?php } ?>
					</div>
				</div>  
				<div class="tab-pane" id="profile" role="profile-tab" aria-labelledby="profile-tab">
					<div class="MuiTabPanel-root css-13xfq8m-MuiTabPanel-root" role="tabpanel" aria-labelledby="mui-p-25453-T-1" id="mui-p-25453-P-1">
						<?php if(!empty($pastevents)){ ?>
							<?php foreach($pastevents as $row){ ?>
								<div class="ucEventInfo">
									<div class="EventFlex">
										<span class="wi-50 m-0">
											<div class="EventFlex leftdata">
												<span class="wi-30">
													<span class="ucimg">
														<img src="<?php echo filesource('assets/uploads/event/120x90_'.$row['image']); ?>">
													</span>
												</span>
												<span class="wi-70"> 
													<p class="topdate"> <?php echo formatdate($row['start_date'], 1); ?> - <?php echo formatdate($row['end_date'], 1); ?> - <?php echo $row['location']; ?></p>
													<a class="text-decoration-none" href="<?php echo base_url(); ?>/events/detail/<?php echo $row['id'];?>"><h5><?php echo $row['name']; ?><h5></a></h5>
												</span>
											</div>
										</span>
										<div class="wi-50-2 justify-content-between">
											<span class="m-left upevent">
												<p><img class="eventFirstIcon" src="<?php echo base_url()?>/assets/site/img/horseShoe.svg">Stalls</p>
												<h6 class="ucprice"> starting from $<?php echo $row['startingstallprice']; ?></h6>
											</span>
											<a class="text-decoration-none text-white" href="<?php echo base_url(); ?>/events/detail/<?php echo $row['id'];?>">
												<button class="ucEventBtn">View</button>
											</a>
										</div>
									</div>
								</div>
							<?php } ?>		
						<?php }else{ ?>
							<p>No Past Events</p>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
    </section>

	<section class="howItWorks">
		<div class="contentPanel">
			<h1 class="howitworkTitle">How It Works</h1>
			<p class="hiwmainContent colorGrey">
				We are developing a community of facilities and horse owners to build a network to house equine athletes on the road.
			</p>
			<span class="hiwContent colorGrey">
				<img src="<?php echo base_url();?>/assets/site/img/download1.png" />
				<b class="sgn_up">Sign Up.</b> Join in as either a horse owner or a facility. With us, if you need a stall you are a horse owner, if you have a stall that needs a horse in it you are a facility.
			</span>
			<span class="hiwContent colorGrey">
				<img src="<?php echo base_url();?>/assets/site/img/download2.png" />
				<b class="sig_up">Search.</b> Find where you are headed next, if this is an EZ Stall facility or event find the stall you want and reserve it. If not we’ll find the closest EZ Stall facility near you.
			</span>
			<span class="hiwContent colorGrey">
				<img src="<?php echo base_url();?>/assets/site/img/download3.png" />
				<b class="rest_rel">Rest and Relax.</b> Enjoy the drive to your destination. Your stall will be waiting for you and your horse to arrive.
			</span>
		</div>
		<div class="imagePanel colorGrey">
			<img class="hiwImage" src="<?php echo base_url();?>/assets/site/img/How.png" />
		</div>
	</section>

	<section class="footerabovePanel">
		<div class="horseOwners">
			<p class="footaboveTag">Looking for stalls</p>
			<h1 class="footaboveTitle">Horse Owners</h1>
			<button class="footaboveBtn footsearchbtn">Search</button>
		</div>
		<span class="footaboveLine"></span>
		<div class="facilities">
			<p class="footaboveTag">Grow your business</p>
			<h1 class="footaboveTitle">Facilities & Producers</h1>
			<a href="<?php echo $liststall; ?>" ><button class="footaboveBtn">List Your Stall</button></a>
		</div>
    </section>
<?php $this->endSection(); ?>

<?php $this->section('js') ?>
    <script>
        $('.footsearchbtn').click(function (e) { 
			$('input[name="location"]').focus();
        });
    </script>
<?php $this->endSection(); ?>