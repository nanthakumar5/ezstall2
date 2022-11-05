<?php $this->extend("admin/common/layout/layout2") ?>
<?php $this->section('content') ?>
<section class="content-header">		
	<div class="container-fluid">			
		<div class="row mb-2">				
			<div class="col-sm-6">					
				<h1>Comments</h1>				
			</div>				
			<div class="col-sm-6">					
				<ol class="breadcrumb float-sm-right">						
					<li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
					<li class="breadcrumb-item active">Comments</li>
				</ol>				
			</div>			
		</div>
	</div>
</section>
<section class="content">
	<?php if($eventid!=''){ ?>
		<div class="page-action">
			<a href="<?php echo getAdminUrl(); ?>/event/" class="btn btn-primary">Back</a>
		</div>
	<?php }?>
	<div class="card">			
		<div class="card-header">
			<h3 class="card-title">Comment</h3>
		</div>
		<div class="card-body">	
			<div class="border rounded py-4 ps-3 pe-3 mt-4 mb-3">
				<?php 
				if(!empty($comments)){
					$commentno = 1; 
					foreach ($comments as $commentdata ) { ?>
						<div class="admin_comment">
							<div id="usercommentlist">
								<label class="admin_comment_title">Eventname</label>
								<p class="admin_comment_content"><?php echo $commentdata['eventname'];?></p>
								<div>
									<label class="admin_comment_title">Comment <?php echo $commentno++;?></label>
									<p class="admin_comment_content usercomment"><?php echo $commentdata['comment'];?></p>
								</div>
								<div class="mb-1">
									<label class="admin_comment_title">Commented By</label>
									<p class="admin_comment_content commented_username"><?php echo $commentdata['username'];?></p>
								</div>
								<div class="row mb-1">
									<label for="communication_lbl" class="admin_comment_title fw-bold col-md-3">Communication</label>
									<div class="communicationRating commentratings col-md-6" data-rate-value="<?php echo $commentdata['communication'];?>">
									</div>
								</div>
								<div class="row mb-1">
									<label for="cleanliness_lbl" class="admin_comment_titlefw-bold col-md-3">Cleanliness</label>
									<div class="cleanlinessRating commentratings col-md-6"  data-rate-value="<?php echo $commentdata['cleanliness'];?>">
									</div>
								</div>
								<div class="row mb-1">
									<label for="friendliness_lbl" class="admin_comment_title fw-bold col-md-3">Friendliness</label>
									<div class="friendlinessRating commentratings col-md-6" data-rate-value="<?php echo $commentdata['friendliness'];?>"></div>
								</div>
								<div class="mt-4">
									<a href="<?php echo getAdminUrl().'/comments/action/'.$commentdata['id'];?>">
										<button class="btn edit-btn">Edit Comment</button></a>
										<a href="javascript:void(0);" data-id="<?php echo $commentdata['id'];?>" class="delete">
											<button class="btn del-btn">Delete Comment</button></a>
										</div>
									</div>
									<?php if(!empty($commentdata['replycomments'])){ ?>
										<?php $replyno = 1; 
										foreach ($commentdata['replycomments'] as $replydata){ ?>
											<div id="replylist">
												<div>
													<label class="admin_comment_title">Reply <?php echo $replyno++;?>:</label>
													<p class="admin_comment_content usercomment"><?php echo $replydata['reply'];?></p>
												</div>
												<div class="mb-1">
													<label class="admin_comment_title">Replied By:</label>
													<p class="admin_comment_content commented_username"><?php echo $replydata['username'];?></p>
												</div>
												<div>
													<a href="<?php echo getAdminUrl().'/comments/action/'.$replydata['replyid'];?>">
														<button class="btn edit-btn">Edit Reply</button></a>
												</div>
											</div>
										<?php } ?>
									</div>
								<?php } ?>
							<?php } ?>
						<?php } else { ?>
							<p>No Record Found for this Event!</p>
						<?php } ?>
					</div>
				</div>
			</div>
		</section>
		<?php $this->endSection(); ?>
		<?php $this->section('js') ?>
		<script>
			$(".commentratings").rate({ initial_value: 0, max_value: 5 });
			$(document).on('click', '.delete', function(){
				var action 	= 	'<?php echo getAdminUrl()."/comments/".$eventid; ?>';
				var data	= 	'\
				<input type="hidden" value="'+$(this).data('id')+'" name="id">\
				<input type="hidden" value="0" name="status">\
				';
				sweetalert2(action, data);
			})
		</script>
		<?php $this->endSection(); ?>

