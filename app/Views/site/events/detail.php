<?php $this->extend('site/common/layout/layout1') ?>
<?php $this->section('content') ?>
<?php 
$userid 			= getSiteUserID() ? getSiteUserID() : 0;
$getcart 			= getCart('1');
$cartevent 			= ($getcart && $getcart['event_id'] != $detail['id']) ? 1 : 0;
$bookedeventid 		= (isset($bookings['event_id'])) ? $bookings['event_id'] : 0;
$bookeduserid 		= (isset($bookings['user_id'])) ? $bookings['user_id'] : 0;
$comments        	= (isset($comments)) ? $comments : [];
?>
<body style="overflow: initial;">
	<section class="maxWidth">
		<div class="pageInfo">
			<span class="marFive">
				<a href="<?php echo base_url(); ?>">Home /</a>
				<a href="<?php echo base_url().'/events'; ?>"> Events /</a>
				<a href="javascript:void(0);"><?php echo $detail['name'] ?></a>
			</span>
		</div>
		<?php if($cartevent==1){?>
			<div class="alert alert-success alert-dismissible fade show m-2" role="alert">
				For booking this event remove other event from the cart <a href="<?php echo base_url().'/events/detail/'.$getcart['event_id']; ?>">Go To Event</a>
				<!--<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">X</button>-->
			</div>
		<?php } ?>
		<div class="marFive dFlexComBetween eventTP pb-3 pt-4">
			<div class="pageInfo m-0 bg-transparent">
				<span class="eventHead">
					<a href="<?php echo base_url().'/events'; ?>" class="d-block"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" fill="currentColor" class="bi bi-chevron-left" viewBox="0 0 16 16">
						<path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
					</svg> Back To All Events</a>
				</span>
			</div>
		</div>
	</section>
	<section class="container-lg">
		<div class="row" style="display: initial !important;">
			<div class="col-md-12">
				<div class="border rounded pt-5 ps-3 pe-3 myaccupevent">
					<div class="row myaccupevent1">
						<div class="col-6">
							<span class="edimg">
								<img src="<?php echo base_url() ?>/assets/uploads/event/<?php echo '559x371_'.$detail['image']?>"> <!--559x371_-->
							</span>
						</div>
						<div class="col-6">
							<h4 class="checkout-fw-6"><?php echo $detail['name'] ?></h4>
							<ul class="edaddr">
								<li class="mb-3 mt-3">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
										<path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
									</svg> 
									<?php echo $detail['location'] ?>
								</li>
							<!-- <li class="mb-3">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar4" viewBox="0 0 16 16">
									<path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM2 2a1 1 0 0 0-1 1v1h14V3a1 1 0 0 0-1-1H2zm13 3H1v9a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V5z"/>
								</svg> 
								<?php //echo date('m-d-Y', strtotime($detail['start_date'])); ?>
							</li> -->
							<li class="mb-3">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16">
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
							<?php echo $detail['description'] ?>
						</ul>
					</div>
					<div class="col-12 mb-5 mt-2">
						<p>Contact the stall manager at <?php echo $detail['mobile'] ?> for more information and stall maps.</p>
						<?php if($detail['eventflyer']!=""){ ?>
							<button type="button" class="btn btn-outline-success btn-lg float-right ucEventdetBtn" data-toggle="modal" data-target="#exampleModal">
    						 <img src="<?php echo base_url() ?>/assets/site/img/flyer.png">Download Event Flyer
  							</button>
						<?php } ?>
						<?php if($detail['stallmap']!=""){ ?>
							<button class="ucEventdetBtn"><a href="<?php echo base_url();?>/event/downloadstallmap/<?php echo $detail['stallmap'] ?>" class="text-decoration-none text-white"><img src="<?php echo base_url() ?>/assets/site/img/flyer.png"> Download Stall Map</a></button>
						<?php } ?>
					</div>
				</div>
				<div class="row row border-top pt-4 pb-4 eventdate">
					<span class="col-3">
						<p class="mb-1 fw-bold"><img class="eventDIcon" src="<?php echo base_url() ?>/assets/site/img/date.png"> Start Date: </p>
						<p class="ucDAte mb-0">
							<?php echo formatdate($detail['start_date'], 1);?></p>
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
			</div>
			<div class="row m-0 p-0">
				<div class="col-md-9 tabook">
					<?php echo $barnstall; ?>
					<?php if(($usertype == '5') && ($bookedeventid == $detail['id']) && ($bookeduserid == $userid)){ ?>
						<div class="border rounded py-4 ps-3 pe-3 mt-4 mb-3">
							<h3 class="fw-bold mb-4">Add Comment</h3>
							<form method="post" action="" id="comment_form" autocomplete="off">
								<div class="mb-3">
									<label class="form-label">Comment:</label>
									<textarea class="form-control" name="comment" placeholder="Add Your Comment" id="comment" rows="3"></textarea>
								</div>
								<div class="row mb-1">
									<label class="fw-bold col-md-3">Communication</label>
									<div class="communicationRating commentratings col-md-6" data-rate-value="0"></div>
								</div>
								<div class="row mb-1">
									<label class="fw-bold col-md-3">Cleanliness</label>
									<div class="cleanlinessRating commentratings col-md-6" data-rate-value="0"></div>
								</div>
								<div class="row mb-3">
									<label class="fw-bold col-md-3">Friendliness</label>
									<div class="friendlinessRating commentratings col-md-6" data-rate-value="0"></div>
								</div>
								<input type="hidden" name="eventid" value="<?php echo $detail["id"]; ?>">
								<input type="hidden" name="userid" 	value="<?php echo $userid; ?>">
								<input type="hidden" name="communication" id="communication">
								<input type="hidden" name="cleanliness" id="cleanliness">
								<input type="hidden" name="friendliness" id="friendliness">
								<button type="submit" class="btn btn-primary add-comment-btn">Submit</button>
							</form>
						</div>
					<?php } ?>
					<?php if(!empty($comments)) { ?>
						<div class="border rounded py-4 ps-3 pe-3 mt-4 mb-3">
							<h3 class="fw-bold mb-4">Comment List</h3>
							<h5 class="fw-bold">User Comments</h5>
							<?php foreach ($comments as $commentdata ) { ?>
								<div id="usercommentlist">
									<div class="mb-1">
										<p class="commented_username"><?php echo $commentdata['username'];?></p>
									</div>
									<div class="mb-3">
										<p class="usercomment"><?php echo $commentdata['comment'];?></p>
									</div>
									<div class="row mb-1">
										<label for="communication_lbl" class="fw-bold col-md-3">Communication</label>
										<div class="communicationRating commentratings col-md-6" data-rate-value="<?php echo $commentdata['communication'];?>">
										</div>
									</div>
									<div class="row mb-1">
										<label for="cleanliness_lbl" class="fw-bold col-md-3">Cleanliness</label>
										<div class="cleanlinessRating commentratings col-md-6"  data-rate-value="<?php echo $commentdata['cleanliness'];?>">
										</div>
									</div>
									<div class="row mb-1">
										<label for="friendliness_lbl" class="fw-bold col-md-3">Friendliness</label>
										<div class="friendlinessRating commentratings col-md-6" data-rate-value="<?php echo $commentdata['friendliness'];?>"></div>
									</div>
								</div>
								<?php if(($usertype != '5') && ($detail['user_id'] == $userid)){ ?>
									<button class="btn btn-primary replycomment" data-commentid="<?php echo $commentdata['id'];?>">Reply</button>
									<div id="replybox<?php echo $commentdata['id'];?>"></div>
								<?php } ?>
								<?php if(!empty($commentdata['replycomments'])){ ?>
									<!-- <h5 class="fw-bold">Replies : </h5> -->
									<?php foreach ($commentdata['replycomments'] as $replydata){ ?>
										<div id="replylist">
											<div class="mb-1">
												<p class="commented_username"><?php echo $replydata['username'];?></p>
											</div>
											<div>
												<p class="usercomment"><?php echo $replydata['reply'];?></p>
											</div>
										</div>
									<?php } ?>
								<?php } ?>
							<?php } ?>
						</div>
					<?php } ?>
				</div> 
				<div class="sticky-top checkout col-md-3 mt-4 h-100"></div>
			</div>
		</div>
	</section>
	<div class="container mt-5">
		<div class="modal fade e_detail_popup" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"  align="right">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<img src="<?php echo base_url();?>/event/pdf/<?php echo $detail['eventflyer'] ?>" width="100" height="100">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary"><a href="<?php echo base_url();?>/event/pdf/<?php echo $detail['eventflyer'] ?>" class="text-decoration-none text-white"><i class="fa fa-download" aria-hidden="true"></i> Download</a></a></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
<?php $this->endSection() ?>
<?php $this->section('js') ?>
<script> 
	$(".commentratings").rate({ initial_value: 0, max_value: 5 });

	$(".communicationRating").on("change", function(ev, data){
		$('#communication').val(data.to);
	});

	$(".cleanlinessRating").on("change", function(ev, data){
		$('#cleanliness').val(data.to);
	});

	$(".friendlinessRating").on("change", function(ev, data){
		$('#friendliness').val(data.to);
	});

	$(".replycomment").on("click", function(ev, data){
		replyComment($(this).attr('data-commentid'));
	});

	function replyComment(commentId){
		var commentform = 	'<form method="post" action="" id="reply_form" autocomplete="off">\
		<div class="mb-3">\
		<textarea class="form-control" placeholder="Add Your Comment"  name="comment" id="replycomment" rows="3"></textarea>\
		</div>\
		<input type="hidden" name="eventid" value="<?php echo $detail["id"]; ?>">\
		<input type="hidden" name="userid" 	value="<?php echo $userid; ?>">\
		<input type="hidden" name="comment_id" value="'+commentId+'">\
		<button type="submit" class="btn btn-primary">Submit</button>\
		</form>';

		$('#replybox'+commentId).empty().append(commentform);
	}
</script>
<?php echo $this->endSection() ?>