<?php $this->extend('site/common/layout/layout1') ?>
<?php $this->section('content') ?>
	<section>
		<div id='calendar'></div>
	</section>
<?php $this->endSection(); ?>

<?php $this->section('js') ?>
<script>
	$(function(){
		var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
			initialView: 'dayGridMonth'
        });
        calendar.render();
	});
</script>
<?php $this->endSection(); ?>
