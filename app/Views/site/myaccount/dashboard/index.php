<?php $this->extend('site/common/layout/layout1') ?>
<?php $this->section('content') ?>
<div class="welcome-content mb-5 mt-2">
	<h3 class="fw-bold d-flex flex-wrap">Welcome to EZ Stall, <p class="welcome-user"><?php echo $userdetail['name']; ?></p></h3>
	<p class="c-5">
		<?php echo "Thank you for being an EZ Stall"." ".$usertype[$userdetail['type']]?>
	</p> 
	<div>
	<?php if($userdetail['type']=='6' || $userdetail['type']=='4'){
		echo '<h4><b>Today Checkin Event</b></h4>';
		 if(!empty($checkinstall)){
		 	echo '<button class="btn_dash_lock delete_lockunlockbtn mx-0">Unlocked</button>';
			echo '<button class="btn_dash_lock delete_dirtyclean mx-2">Clean</button>';
			echo '<button class="btn-select-all checkinstallbtn">Select All</button>';

			foreach($checkinstall as $availablestall){   
				$eventname =  $availablestall['eventname'];
				
				foreach($availablestall['barnstall'] as $stall){
					//if(($stall['lockunlock']=='1' && $stall['dirtyclean']=='0') || ($stall['lockunlock']=='0' && $stall['dirtyclean']=='1') || ($stall['lockunlock']=='0' && $stall['dirtyclean']=='0')) {						
						$btnlockunlock ='<div class="bookselectbtn"><button class="btn_dash_lock">Locked</button></div>';
						$btndirtyclean ='<div class="bookselectbtn"><button class="btn_dash_dirty">Dirty</button></div>';
						
						if($stall['lockunlock']=='0' ){
							$btnlockunlock = '<div class="bookselectbtn"><button class="btn btn-success lockunlock mx-2" data-stallid="'.$stall['stall_id'].'">Unlocked</button></div>';
						}
						
						if($stall['dirtyclean']=='0'){
							$btndirtyclean = '<div class="bookselectbtn"><button class="btn btn-success dirtyclean" data-stallid="'.$stall['stall_id'].'">Cleaned</button></div>'; 
						}
						
						echo '
								<div class="d-flex col-md-12 justify-content-between my-2 dash_border_ operator-list">
									<div class="bookselect">
										<div class="form-check">
											<input class="form-check-input checkinstall" type="checkbox" name="removestallid" value="'.$stall['stall_id'].'">
										</div>
										<div class="bookdetails">
											<p class="mb-0 fw-bold fs-7">'.$availablestall['eventname'].'-'.$availablestall['username'].'</p>
											<p class="mb-0 fs-7">'.dateformat($availablestall['check_in']).' / '.dateformat($availablestall['check_out']).' - '.$stall['stallname'].'</p>
										</div>
									</div>
									<div>'.$btnlockunlock.$btndirtyclean.'</div>	
								</div>
							';
					//}
				}
				
				foreach($availablestall['rvbarnstall'] as $rvbarnstall){ 					
					//if(($rvbarnstall['lockunlock']=='1' && $rvbarnstall['dirtyclean']=='0') || ($rvbarnstall['lockunlock']=='0' && $rvbarnstall['dirtyclean']=='1') || ($rvbarnstall['lockunlock']=='0' && $rvbarnstall['dirtyclean']=='0')) {
						$btnlockunlock ='<div class="bookselectbtn"><button class="btn_dash_lock">Locked</button></div>';
						$btndirtyclean ='<div class="bookselectbtn"><button class="btn_dash_dirty">Dirty</button></div>';
						
						if($rvbarnstall['lockunlock']=='0' ){
							$btnlockunlock = '<div class="bookselectbtn"><button class="btn btn-success lockunlock mx-2" data-stallid="'.$rvbarnstall['stall_id'].'">Unlocked</button></div>';
						}
						
						if($rvbarnstall['dirtyclean']=='0'){
							$btndirtyclean = '<div class="bookselectbtn"><button class="btn btn-success dirtyclean" data-stallid="'.$rvbarnstall['stall_id'].'">Cleaned</button></div>'; 
						}

						echo '
								<div class="d-flex col-md-12 justify-content-between my-2 dash_border_ operator-list">
									<div class="bookselect ">
										<div class="form-check">
											<input class="form-check-input checkinstall" type="checkbox" name="removestallid" value="'.$rvbarnstall['stall_id'].'">
										</div>
										<div class="bookdetails">
											<p class="mb-0 fw-bold fs-7">'.$availablestall['eventname'].'-'.$availablestall['username'].'</p>
											<p class="mb-0 fs-7">'.dateformat($availablestall['check_in']).' / '.dateformat($availablestall['check_out']).' - '.$rvbarnstall['stallname'].'</p>
										</div>
									</div>
									<div>'.$btnlockunlock.$btndirtyclean.'</div>	
								</div>
							';
					//}						
				}
			}
		}else{ 
			echo "<p>No Stalls Checkin Today</p>";
		}
		
		echo "<br>";

		echo '<h4><b>Today Checkout Event</b></h4>';		 
		if(!empty($checkoutstall)){
			echo '<button class="btn_dash_lock delete_lockunlockbtn_checkout mx-0">Locked</button>';
			echo '<button class="btn_dash_lock delete_dirtyclean_checkout mx-2">Dirty</button>';
			echo '<button class="btn-select-all checkoutstallbtn">Select All</button>';
			
			foreach($checkoutstall as $availablestall){   
				$eventname =  $availablestall['eventname'];
				
				foreach($availablestall['barnstall'] as $stall){
					//if(($stall['lockunlock']=='1' && $stall['dirtyclean']=='0') || ($stall['lockunlock']=='0' && $stall['dirtyclean']=='1') || ($stall['lockunlock']=='1' && $stall['dirtyclean']=='1')){
						$btnlockunlock ='<div class="bookselectbtn"><button class="btn btn-success out-btn mx-2">Unlocked</button></div>';
						$btndirtyclean ='<div class="bookselectbtn"><button class="btn btn-success out-btn">Cleaned</button></div>';

						if($stall['lockunlock']=='1'){
							$btnlockunlock = '<div class="bookselectbtn"><button class="btn_dash_lock lockunlock_checkout"  data-stallid="'.$stall['stall_id'].'">Locked</button></div>';
						}
						
						if($stall['dirtyclean']=='1'){
							$btndirtyclean = '<div class="bookselectbtn"><button class="btn_dash_dirty dirtyclean_checkout" data-stallid="'.$stall['stall_id'].'">Dirty</button></div>'; 
						}
						
						echo '
							<div class="d-flex col-md-12 justify-content-between my-2 dash_border_ operator-list ">
								<div class="bookselect">
									<div class="form-check">
										<input class="form-check-input checkoutstall" type="checkbox" name="removestallid" value="'.$stall['stall_id'].'">
									</div>
									<div class="bookdetails">
										<p class="mb-0 fw-bold fs-7">'.$availablestall['eventname'].'-'.$availablestall['username'].'</p>
										<p class="mb-0 fs-7">'.dateformat($availablestall['check_in']).' / '.dateformat($availablestall['check_out']).' - '.$stall['stallname'].'</p>
									</div>
								</div>
								<div>'.$btnlockunlock.$btndirtyclean.'</div>	
							</div>';
					//}					
				}

				foreach($availablestall['rvbarnstall'] as $rvbarnstall){ 
					//if(($rvbarnstall['lockunlock']=='1' && $rvbarnstall['dirtyclean']=='0') || ($rvbarnstall['lockunlock']=='0' && $rvbarnstall['dirtyclean']=='1') || ($rvbarnstall['lockunlock']=='1' && $rvbarnstall['dirtyclean']=='1')){
						$btnlockunlocks ='<div class="bookselectbtn"><button class="btn btn-success out-btn mx-2">Unlocked</button></div>';
						$btndirtycleans ='<div class="bookselectbtn"><button class="btn btn-success out-btn">Cleaned</button></div>';

						if($rvbarnstall['lockunlock']=='1'){
							$btnlockunlocks = '<div class="bookselectbtn"><button class="btn_dash_lock lockunlock_checkout"  data-stallid="'.$rvbarnstall['stall_id'].'">Locked</button></div>';
						}
						
						if($rvbarnstall['dirtyclean']=='1'){
							$btndirtycleans = '<div class="bookselectbtn"><button class="btn_dash_dirty dirtyclean_checkout" data-stallid="'.$rvbarnstall['stall_id'].'">Dirty</button></div>'; 
						}
						
						echo '
							<div class="d-flex col-md-12 justify-content-between my-2 dash_border_ operator-list ">
								<div class="bookselect">
									<div class="form-check">
										<input class="form-check-input checkoutstall" type="checkbox" name="removestallid" value="'.$rvbarnstall['stall_id'].'">
									</div>
									<div class="bookdetails">
										<p class="mb-0 fw-bold fs-7">'.$availablestall['eventname'].'-'.$availablestall['username'].'</p>
										<p class="mb-0 fs-7">'.dateformat($availablestall['check_in']).' / '.dateformat($availablestall['check_out']).' - '.$rvbarnstall['stallname'].'</p>
									</div>
								</div>
								<div>'.$btnlockunlocks.$btndirtycleans.'</div>	
							</div>';
					//}
				}
			}
		}else{ 
			echo "<p>No Stalls Checkout Today</p>";
		}
	}?>
	</div>
	
	<?php if($userdetail['type']=='2' || $userdetail['type']=='3' || $userdetail['type']=='4' ) { ?>
		<div class="col-md-12 mt-4 p-4 bg-white rounded-sm">
			<h5 class="font-w-600">Current Stall Reservations</h5>
			<div class="row mt-4 first">
				<div class="col-md-4 mb-3">
					<div class="card">
						<div class="row mt-4 p-3">
							<div class="col-md-3">
								<img src="<?php echo base_url()?>/assets/site/img/stall.png" class="rounded d-block" />
							</div>
							<div class="col-md-9">
								<h2><?php echo $countcurrentstall;?></h2>
								<p>Total no of. Stalls</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 mb-3">
					<div class="card">
						<div class="row mt-4 p-3">
							<div class="col-md-3">
								<img
								src="<?php echo base_url()?>/assets/site/img/currently_available.png"
								class="rounded d-block"
								/>
							</div>
							<div class="col-md-9"> 
								<h2><?php echo $countcurrentavailablestalls;?></h2>
								<p>Currently Available</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 mb-3">
					<div class="card">
						<div class="row mt-4 p-3">
							<div class="col-md-3">
								<img
								src="<?php echo base_url()?>/assets/site/img/currently_booked.png"
								class="rounded d-block"
								/>
							</div>
							<div class="col-md-9">
								<h2><?php echo $countcurrentbookingstalls;?></h2>
								<p>Currently booked</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-12 mt-4 p-4 bg-white rounded-sm">
			<h5 class="font-w-600">Current RV Reservations</h5>
			<div class="row mt-4 first">
				<div class="col-md-4 mb-3">
					<div class="card">
						<div class="row mt-4 p-3">
							<div class="col-md-3">
								<img src="<?php echo base_url()?>/assets/site/img/stall.png" class="rounded d-block" />
							</div>
							<div class="col-md-9">
								<h2><?php echo $countcurrentrvlots;?></h2>
								<p>Total no of. Rv Lots</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 mb-3">
					<div class="card">
						<div class="row mt-4 p-3">
							<div class="col-md-3">
								<img
								src="<?php echo base_url()?>/assets/site/img/currently_available.png"
								class="rounded d-block"
								/>
							</div>
							<div class="col-md-9"> 
								<h2><?php echo $countcurrentavailablervlots;?></h2>
								<p>Currently Available</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 mb-3">
					<div class="card">
						<div class="row mt-4 p-3">
							<div class="col-md-3">
								<img
								src="<?php echo base_url()?>/assets/site/img/currently_booked.png"
								class="rounded d-block"
								/>
							</div>
							<div class="col-md-9">
								<h2><?php echo $countcurrentbookingrvlots;?></h2>
								<p>Currently booked</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-12 p-4 mt-5 bg-white rounded-sm">
			<h5 class="font-w-600">Past Month Activity</h5>
			<div class="row mt-4 second">
				<div class="col-md-4 mb-3">
					<div class="card">
						<div class="row mt-4 p-3">
							<div class="col-md-3">
								<img src="<?php echo base_url()?>/assets/site/img/rented_stalls.png" class="rounded d-block" />
							</div>
							<div class="col-md-9">
								<h2><?php echo $countpaststall ?></h2>
								<p>Rented Stalls</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 mb-3">
					<div class="card">
						<div class="row mt-4 p-3">
							<div class="col-md-3">
								<img src="<?php echo base_url()?>/assets/site/img/total_revenue.png" class="rounded d-block" />
							</div>
							<div class="col-md-9">
								<h2>$<?php echo $countpastamount ?></h2>
								<p>Total Revenue</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 mb-3">
					<div class="card">
						<div class="row mt-4 p-3">
							<div class="col-md-3">
								<img src="<?php echo base_url()?>/assets/site/img/total_events.png" class="rounded d-block" />
							</div>
							<div class="col-md-9">
								<h2><?php echo $pastevent;?></h2>
								<p>Total Events</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
	<?php if($userdetail['type']=='5') { ?>
		<div class="col-md-12 mt-4 p-4 bg-white rounded-sm">
			<h5 class="font-w-600">Current Reservation</h5>
			<div class="row mt-4 first">
				<div class="col-md-4 mb-3">
					<div class="card">
						<div class="row mt-4 p-3">
							<div class="col-md-3">
								<img src="<?php echo base_url()?>/assets/site/img/stall.png" class="rounded d-block" />
							</div>
							<div class="col-md-9">
								<h2><?php echo $countcurrentstall;?></h2>
								<p>Total no of. Stalls</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 mb-3">
					<div class="card">
						<div class="row mt-4 p-3">
							<div class="col-md-3">
								<img src="<?php echo base_url()?>/assets/site/img/stall.png" class="rounded d-block" />
							</div>
							<div class="col-md-9">
								<h2><?php echo $countcurrentrvlots;?></h2>
								<p>Total no of. Rv Lots</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 mb-3">
					<div class="card">
						<div class="row mt-4 p-3">
							<div class="col-md-3">
								<img src="<?php echo base_url()?>/assets/site/img/currently_available.png" class="rounded d-block"/>
							</div>
							<div class="col-md-9"> 
								<h2>$<?php echo $countpayedamount;?></h2>
								<p>Total Paid</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 mb-3">
					<div class="card">
						<div class="row mt-4 p-3">
							<div class="col-md-3">
								<img src="<?php echo base_url()?>/assets/site/img/currently_booked.png" class="rounded d-block"/>
							</div>
							<div class="col-md-9">
								<h2><?php echo $countcurrentevent;?></h2>
								<p>Total Events</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-12 p-4 mt-5 bg-white rounded-sm">
			<h5 class="font-w-600">Past Month Activity</h5>
			<div class="row mt-4 second">
				<div class="col-md-4 mb-3">
					<div class="card">
						<div class="row mt-4 p-3">
							<div class="col-md-3">
								<img src="<?php echo base_url()?>/assets/site/img/rented_stalls.png" class="rounded d-block" />
							</div>
							<div class="col-md-9">
								<h2><?php echo $countpaststall ?></h2>
								<p>Total no of. Stalls</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 mb-3">
					<div class="card">
						<div class="row mt-4 p-3">
							<div class="col-md-3">
								<img src="<?php echo base_url()?>/assets/site/img/total_revenue.png" class="rounded d-block" />
							</div>
							<div class="col-md-9">
								<h2>$<?php echo $countpastamount ?></h2>
								<p>Total Payed</p>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-4 mb-3">
					<div class="card">
						<div class="row mt-4 p-3">
							<div class="col-md-3">
								<img src="<?php echo base_url()?>/assets/site/img/total_events.png" class="rounded d-block" />
							</div>
							<div class="col-md-9">
								<h2><?php echo $pastevent;?></h2>
								<p>Total Events</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
	<?php if($userdetail['type']=='2' || $userdetail['type']=='3' || $userdetail['type']=='4'){ ?>
		<div class="row tablesec mt-5 mb-5">
			<div class="col-md-6">
				<h5 class="font-w-600">Monthly Accrued Income</h5>
				<div class="table-responsive mt-3">
					<table class="table m-0" id="monthlyincome">
						<thead>
							<tr class="welcome-table table-active">
								<th scope="col">#</th>
								<th scope="col">Month</th>
								<th scope="col">Amount</th>
								<th scope="col">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($monthlyincome as $key => $income){?>						
								<tr class="monthlyincome">
									<td><?php echo $key+1; ?></td>
									<td><?php echo $income['month']?></td>
									<td><?php echo number_format($income['paymentamount'], 2); ?></td>
									<td>
										<button class="View">
											<a href="<?php echo base_url();?>/myaccount/payments" >View</a>
										</button><br>
									</td>
								</tr>
							<?php } ?>
							
							<tr>
								<td colspan="4" class="text-center">
									<a href="<?php echo base_url();?>/myaccount/payments" id="loadincome" class="dash-view">VIEW ALL</a>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="col-md-6">
				<h5 class="font-w-600">Upcoming events</h5>
				<div class="table-responsive mt-3">
					<table class="table m-0" id="upcoming">
						<thead>
							<tr class="welcome-table table-active">
								<th scope="col">Date</th>
								<th scope="col">Event Name</th>
								<th scope="col">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($upcomingevents as $value){ 
								$url = ($value['type']=='2') ? 'facility' : 'events'; ?>
								<tr class="upcoming"> 
									<td><?php echo  $value['type']=='2' ? '-' : date('m-d-Y',strtotime($value['start_date'])); ?></td>
									<td><?php echo $value['name']; ?></td>
									<td>
										<button class="View">
											<a href="<?php echo base_url().'/myaccount/'.$url.'/view/'.$value['id']; ?>">View</a>
										</button>
									</td>
								</tr>
							<?php } ?>
							<tr>
								<td colspan="3" class="text-center">
									<a href="<?php echo base_url().'/myaccount/events'; ?>" id="loadMore" class="dash-view">VIEW ALL</a>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	<?php } ?>
</div>
<?php $this->endSection(); ?>
<?php $this->section('js') ?>
<script>
	$(".bookselect input.form-check-input:checkbox").on('click', function(){
        $(this).parent().parent().parent().toggleClass("checked");
   	});

	$(document).on('click','.checkinstallbtn',function(){
		$('.checkinstall').prop('checked', true);
	});	
	
	$(document).on('click','.checkoutstallbtn',function(){
		$('.checkoutstall').prop('checked', true);
	});	
	
	$(document).on('click','.lockunlock',function(){
		lockunlock($(this).attr('data-stallid'), 'lockunlock', '1');
	});	
	
	$(document).on('click','.lockunlock',function(){
		lockunlock($(this).attr('data-stallid'), 'lockunlock', '1');
	});	

	$(document).on('click','.dirtyclean',function(){
		lockunlock($(this).attr('data-stallid'),'dirtyclean', '1');
	});	

	$(document).on('click','.lockunlock_checkout',function(){
		lockunlock($(this).attr('data-stallid'),'lockunlock', '0');
	});	

	$(document).on('click','.dirtyclean_checkout',function(){
		lockunlock($(this).attr('data-stallid'),'dirtyclean', '0');
	});

	$(document).on('click','.delete_lockunlockbtn',function(){
	 	var stallid = [];
	    $.each($("input[name='removestallid']:checked"), function(){
	        stallid.push($(this).val());
	    });
	    lockunlock(stallid.join(','), 'lockunlock', '1');
	});	

	$(document).on('click','.delete_dirtyclean',function(){
	 	var stallid = [];
	    $.each($("input[name='removestallid']:checked"), function(){
	        stallid.push($(this).val());
	    });
	    lockunlock(stallid.join(','), 'dirtyclean', '1');
	});

	$(document).on('click','.delete_lockunlockbtn_checkout',function(){
	 	var stallid = [];
	    $.each($("input[name='removestallid']:checked"), function(){
	        stallid.push($(this).val());
	    });
	    lockunlock(stallid.join(','), 'lockunlock', '0');
	});	

	$(document).on('click','.delete_dirtyclean_checkout',function(){
	 	var stallid = [];
	    $.each($("input[name='removestallid']:checked"), function(){
	        stallid.push($(this).val());
	    });
	    lockunlock(stallid.join(','), 'dirtyclean', '0');
	});

	function lockunlock(stallid,name,value){
		var action 	= 	'<?php echo base_url()."/myaccount/updatedata"; ?>';
		var data   	= '\
			<input type="hidden" value="'+stallid+'" name="stallid">\
			<input type="hidden" value="'+value+'" name="lock_dirty_status">\
			<input type="hidden" value="'+value+'" name="'+name+'">\
		';
		sweetalert2(action,data);
	}
</script>
<?php $this->endSection();?>