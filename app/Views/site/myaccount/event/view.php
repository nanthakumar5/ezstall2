<?php $this->extend('site/common/layout/layout1') ?>
<?php $this->section('content') ?>

<div class="page-action mb-3 mt-2 m-0" align="right">
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
							<li class="mb-3 mt-3">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
									<path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
								</svg> 
								<?php echo $detail['location'] ?>
							</li>
							<li class="mb-3">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16">
									<path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
								</svg> 
								<?php echo $detail['mobile'] ?>
							</li>
							<div class="row">
								<span class="col-6">
									<p class="mb-1 fw-bold"><img class="eventFirstIcon" src="<?php echo base_url()?>/assets/site/img/stall.jpg">Stalls</p>
									<h6 class="ucprice"> starting from $<?php echo $detail['startingstallprice'] ?> / night</h6>
								</span>
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
				<?php echo $barnstall; ?>
			</div>
		</div>
	</section>
<?php $this->endSection() ?>