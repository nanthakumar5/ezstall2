<?php $this->extend('site/common/layout/layout1') ?>
<?php $this->section('content') ?>
	<h2 class="fw-bold mb-1 mt-2">Facility Calendar</h2>
	<section>
		<div class="row">
			<div class="col-md-12 mb-5 mt-2">
				<div class="form-group">
					<label class="mb-1">Facility</label>		
					<?php echo form_dropdown('facility_id', $facilitylist, '', ['class' => 'form-control facilityid']); ?>									
				</div>
			</div>
		</div>
		
		<div id='calendar'></div>
	</section>
<?php $this->endSection(); ?>

<?php $this->section('js') ?>
<script>
	var userid = '<?php echo $userid; ?>';
	var calendardata = '';
	
	$(function(){
		calendar();
	});
	
	$('.facilityid').change(function(){
		calendardata.destroy();
		calendar();
	})
	
	function calendar(){
		ajax(
			'<?php echo base_url()."/ajax/calendar"; ?>',
			{ id: $('.facilityid').val(), userid : userid },
			{
				asynchronous : 1,
				success : function(data){
					var calendarEl = document.getElementById('calendar');
					calendardata = new FullCalendar.Calendar(calendarEl, {
						schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
						initialView: 'dayGridMonth',
						headerToolbar: {
							left: 'prev,next',
							center: 'title',
							right: 'dayGridMonth,resourceTimelineMonth'
						},
						buttonText: {
							resourceTimelineMonth: 'Time Month',
							dayGridMonth: 'Day Month'
						},
						dayMaxEventRows: true, 
						resources: data.resourcedata,
						events: data.eventdata
					});
					calendardata.render();
				}
			}
		)
	}
</script>
<?php $this->endSection(); ?>
