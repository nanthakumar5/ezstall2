<?php $this->extend('site/common/layout/layout1') ?>
<?php $this->section('content') ?>
	<section>
		<div id='calendar'></div>
	</section>
<?php $this->endSection(); ?>

<?php $this->section('js') ?>
<script>
	var eventdata 		= $.parseJSON('<?php echo json_encode($eventdata); ?>');
	var resourcedata 	= $.parseJSON('<?php echo json_encode($resourcedata); ?>');

	$(function(){
		var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
			schedulerLicenseKey: 'CC-Attribution-NonCommercial-NoDerivatives',
			initialView: 'resourceTimelineMonth',
			headerToolbar: {
				left: 'prev,next',
				center: 'title',
				right: 'dayGridMonth,resourceTimelineMonth'
			},
			dayMaxEventRows: true, 
			resources: resourcedata,
			events: eventdata
		});
        calendar.render();
	});
</script>
<?php $this->endSection(); ?>
