<?= $this->extend("admin/common/layout/layout2") ?>
<?php $this->section('content') ?>
<?php
$id 					= isset($result['id']) ? $result['id'] : '';
$eventid 				= isset($result['event_id']) ? $result['event_id'] : '';
$userid 				= isset($result['user_id']) ? $result['user_id'] : '';
$username 				= isset($result['username']) ? $result['username'] : '';
$eventname 				= isset($result['eventname']) ? $result['eventname'] : '';
$comment 		    	= isset($result['comment']) ? $result['comment'] : '';
$commentid 				= isset($result['comment_id']) ? $result['comment_id'] : '';
$communication 			= isset($result['communication']) ? $result['communication'] : '';
$cleanliness 			= isset($result['cleanliness']) ? $result['cleanliness'] : '';
$friendliness 		    = isset($result['friendliness']) ? $result['friendliness'] : '';
$replycomments 			= isset($result['replycomments']) ? $result['replycomments'] : [];
?>
<section class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1>Comments</h1>
			</div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
					<li class="breadcrumb-item"><a href="<?php echo getAdminUrl().'/comments/'.$eventid;?>">Comments</a></li>
					<li class="breadcrumb-item active">Update Comment</li>
				</ol>
			</div>
		</div>
	</div>
</section>
<section class="content">
	<div class="page-action">
		<a href="<?php echo getAdminUrl().'/comments/'.$eventid;?>" class="btn btn-primary">Back</a>
	</div>
	<div class="card">
		<div class="card-header">
			<h3 class="card-title">Update Comment</h3>
		</div>
		<div class="card-body">
			<form method="post" id="form" class="comment_form" action="<?php echo getAdminUrl(); ?>/comments/action" autocomplete="off">
				<div class="commentsection">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label class="admin_comment_title">Eventname</label>
									<p class="admin_comment_content"><?php echo $eventname;?></p>								
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label class="admin_comment_title">Username</label>
									<p class="admin_comment_content"><?php echo $username;?></p>								
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label class="admin_comment_title">Comment</label>
									<textarea class="form-control" name="comment" placeholder="Add Your Comment" id="comment" rows="3"><?php echo $comment;?></textarea>
								</div>
							</div>
							<?php if($commentid == 0){ ?>
								<div class="row mb-1">
									<label class="admin_comment_title fw-bold col-md-3">Communication</label>
									<div class="communicationRating commentratings col-md-6" data-rate-value="<?php echo $communication;?>"></div>
								</div>
								<div class="row mb-1">
									<label class="admin_comment_title fw-bold col-md-3">Cleanliness</label>
									<div class="cleanlinessRating commentratings col-md-6" data-rate-value="<?php echo $cleanliness;?>"></div>
								</div>
								<div class="row mb-1">
									<label class="admin_comment_title fw-bold col-md-3">Friendliness</label>
									<div class="friendlinessRating commentratings col-md-6" data-rate-value="<?php echo $friendliness;?>"></div>
								</div>
								<input type="hidden" name="communication" value="<?php echo $communication;?>">
								<input type="hidden" name="cleanliness" value="<?php echo $cleanliness;?>">
								<input type="hidden" name="friendliness" value="<?php echo $friendliness;?>">
							<?php }?>
							<input type="hidden" name="eventid" value="<?php echo $eventid;?>">
							<input type="hidden" name="actionid" value="<?php echo $id;?>">
							<input type="hidden" name="userid" 	value="<?php echo $userid;?>">
							<input type="hidden" name="comment_id" value="<?php echo $commentid;?>">	
							<input type="hidden" name="status" value="1">
						</div>
						<div class="mt-4">
							<input type="submit" id ="commentSubmit" class="btn del-btn" value="Submit">
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</section>
<?php $this->endSection(); ?>
<?php $this->section('js') ?>
<script> 
	var commentid = '<?php echo $commentid;?>';
	$(function(){
		validation(
			'#form',
			{
				comment      : {
					required  :   true
				}
			},
			{ 
				comment      : {
					required    : "Please Enter Comment."
				}
			}
			);
	});
	$(".commentratings").rate({ initial_value: 0, max_value: 5 });

</script>
<?php echo $this->endSection() ?>