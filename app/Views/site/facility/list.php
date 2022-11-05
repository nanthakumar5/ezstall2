<?= $this->extend("site/common/layout/layout1") ?>
<style type="text/css">
	.ui-menu img{
		width:40px;
		height:40px;
	}
	.ui-menu li span{
		font-size:2em;
		padding:0 0 10px 10px;
		margin:0 0 10px 0 !important;
		white-space:nowrap;
	}
</style>

<?php $this->section('content') ?>
<div class="infoPanel stallform container-lg">
</div>
<section class="maxWidth">
		<!-- <div class="pageInfo">
		  <span class="marFive">
			<a href="<?php echo base_url(); ?>">Home</a> /
			<a href="javascript:void(0);"> Events</a>
		  </span>
		</div> -->

		<div class="marFive dFlexComBetween eventTP">
			<h1 class="eventPageTitle">Facility</h1>
			<span class="mar0">
				<input
				type="text"
				placeholder="Find your facility"
				class="searchEvent"
				id="searchfacility"
				value="<?php echo $search; ?>"
				/>

				<svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" class="eventSearch" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M456.69 421.39L362.6 327.3a173.81 173.81 0 0034.84-104.58C397.44 126.38 319.06 48 222.72 48S48 126.38 48 222.72s78.38 174.72 174.72 174.72A173.81 173.81 0 00327.3 362.6l94.09 94.09a25 25 0 0035.3-35.3zM97.92 222.72a124.8 124.8 0 11124.8 124.8 124.95 124.95 0 01-124.8-124.8z"></path></svg>
			</span>
		</div>
		<section class="maxWidth marFiveRes eventPagePanel">
			<?php if(count($list) > 0) { ?>  
				<?php foreach ($list as $data) { ?>
				<div class="ucEventInfo">
					<div class="EventFlex facility">
						<span class="wi-50">
							<div class="EventFlex leftdata facility">
								<span class="wi-30">
									<span class="ucimg">
										<img src="<?php echo base_url() ?>/assets/uploads/event/<?php echo '120x90_'.$data['image']?>"> <!--120x90_-->
									</span>
								</span>

								<span class="wi-70"> 
									<a class="text-decoration-none" href="<?php echo base_url() ?>/facility/detail/<?php echo $data['id']?>"><h5><?php echo $data['name']; ?><h5></a>
										<p class=""><?php echo strip_tags(substr($data['description'],0,30)) ; ?></p>
								</span>
							
							</div>
						</span>
					<div class="pr-f2">
								<a class="text-decoration-none text-white" id="booknow_link" href="<?php echo base_url() ?>/facility/detail/<?php echo $data['id']?>">
									<button class="ucEventBtn">
										Book Now
									</button>
								</a>
							</div>
					</div>
				</div>
			<?php } ?>
			<?php echo $pager; ?>
			<?php }else{ ?>
				No Record Found
			<?php } ?>
		</section>
</section>
<?php $this->endSection(); ?>
<?php $this->section('js') ?>
<script>
	var baseurl = "<?php echo base_url(); ?>";

	$(function() {
		$("#searchfacility").autocomplete({
			source: function(request, response) {
				ajax(baseurl+'/ajaxsearchfacility', {search: request.term}, {
					success: function(result) {
						response(result);
					}
				});
			},
			html: true, 
			select: function(event, ui) {
				$('#ajaxsearchfacility').val(ui.item.name); 
				window.location.href = baseurl+'/facility/detail/'+ui.item.id;
				return false;
			},
			focus: function(event, ui) {
				$("#ajaxsearchfacility").val(ui.item.name);
				return false;
			}
		})
		.autocomplete("instance")
		._renderItem = function( ul, item ) {
			return $( "<li><div><img src='"+baseurl+'/assets/uploads/facility/'+item.image+"' width='50' height='50'><span>"+item.name+"</span></div></li>" ).appendTo( ul );
		};
	});

	$('.listeventsearch').submit(function (e) {
		e.preventDefault();
		var query = $(this).serializeArray().filter(function (i) {
			return i.value;
		});
		window.location.href = $(this).attr('action') + (query ? '?' + $.param(query) : '');
	});
</script>
<?php $this->endSection(); ?>

