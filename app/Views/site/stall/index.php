<?php $this->extend('site/common/layout/layout1') ?>
<?php $this->section('content') ?>
<?php $currentdate  = date("m-d-Y"); ?>
 <div class="infoPanel stallform container-lg">
    <form action="" method="GET" autocomplete="off" class="w-100">
        <span class="infoSection">
            <span class="iconProperty">
                <input type="text" name="name" placeholder="StallName">
                <img src="<?php echo base_url()?>/assets/site/img/location.svg" class="iconPlace" alt="Map Icon">
            </span>
            <span class="iconProperty">
                <input type="text" name="start_date" class="stall_search_start_date" placeholder="Check-In">
				<img src="<?php echo base_url()?>/assets/site/img/calendar.svg" class="iconPlace" alt="Calender Icon">
                
            </span>
            <span class="iconProperty">
                <input type="text" name="end_date" id="stall_search_end_date"  class="stall_search_end_date" placeholder="Check-Out">
				<img src="<?php echo base_url()?>/assets/site/img/calendar.svg" class="iconPlace" alt="Calender Icon">
            </span>
            <span class="searchResult">
                <button type="submit"><svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" class="searchIcon" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                    <path d="M456.69 421.39L362.6 327.3a173.81 173.81 0 0034.84-104.58C397.44 126.38 319.06 48 222.72 48S48 126.38 48 222.72s78.38 174.72 174.72 174.72A173.81 173.81 0 00327.3 362.6l94.09 94.09a25 25 0 0035.3-35.3zM97.92 222.72a124.8 124.8 0 11124.8 124.8 124.95 124.95 0 01-124.8-124.8z">
                    </path>
                </svg></button>
            </span>
        </span>
    </form>
</div>
<section class="container-lg">
    <div class="row">
        <?php foreach ($stalllist as $value) {?>
        <div class="col-lg-4 col-md-4 mb-3">
            <a style="text-decoration:none;" href="<?php echo base_url() ?>/stalls/detail/<?php echo $value['id']; ?>">
                <div class="stall-list">
                    <div class="stallimg">
                        <img src="<?php echo base_url() ?>/assets/uploads/stall/<?php echo $value['image']?>">
                        <span class="stall-imgover"><?php echo $currencysymbol.$value['price']?></span>
                    </div>
                    <div class="stalltitle mt-3">
                        <h5><?php echo $value['name']?></h5>
                        <?php if($currentdate <= formatdate($value['end_date'], 1)){?>
                             <p class="mb-0">Available</p>
                        <?php } else{?>
                            <p class="mb-0">Not Available</p>
                        <?php } ?>
                    </div>
                </div>
            </a>
        </div>
        <?php } ?>
        <?php echo $pager; ?>
    </div>
</section>
<?php $this->endSection() ?>
<?php $this->section('js') ?>
    <script>

		uidatepicker(".stall_search_start_date, .stall_search_end_date");
		
        $('#searchstalls').keypress(function (e) {
            if($(this).val()!='' && e.which == 13){
                window.location.href = '<?php echo base_url(); ?>/stalls?q='+$(this).val();
            }
        });
    </script>
<?php $this->endSection(); ?>

