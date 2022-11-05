<style>
table{
	width : 100%;
}
table, th, td {
	border: 1px solid black;
	border-collapse: collapse;
}

table tr th, 
table tr td {
	padding : 10px;
	text-align : left;
}

.sub_heading{
	margin-bottom : 15px;
	font-size : 22px;
}
</style>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Event Report</title>
	</head>
	<body>
	    <div> 
	        <?php
	        $eventname  = $result['name'];
	        $startdate 	= $result['start_date'];
         	$enddate 	= $result['end_date'];
         	?>
		    <div style="text-align:center;"><img src="<?php echo $logo; ?>" width="500"></div>
	        <h1><?php echo $eventname.' ('.date("m-d-y", strtotime($startdate)).' to '.date("m-d-y", strtotime($enddate)).')';?></h1>
         	<?php 
        	while (strtotime($startdate) <= strtotime($enddate)) {
	        	$arriving = getBooking(['eventid' => $result['id'],'check_in'=> $startdate]);
	        	$outgoing = getBooking(['eventid' => $result['id'],'check_out'=> $startdate]);
     		?>
		        <div>       	
					<h4 class="sub_heading">Arriving (<?php echo date("m-d-y", strtotime($startdate));?>)</h4>
					<table>
					  <thead>
							<tr>
								<th width="40%">Horse Owner</th>
								<th width="30%">Stalls Reserved</th>
								<th width="30%">Rv Reserved</th>
								<th width="30%">Feed Reserved</th>
								<th width="30%">Shavings Reserved</th>
								<th width="30%">Contact Phone Number</th>
							</tr>
					  </thead>
					  <tbody>
							<?php if (!empty(array_filter($arriving))) { ?>
								<?php foreach ($arriving as $data) { ?>
									<tr>
										<td><?php echo $data['firstname'].' '.$data['lastname'];?></td>

										<td><?php 
											$barnstall = (array_column($data['barnstall'], 'stallname')) ?  implode(', ', array_column($data['barnstall'], 'stallname')) : 'No Stalls available.'; 
												echo $barnstall; ?></td>

										<td><?php 
											$rvlots = (array_column($data['rvbarnstall'], 'stallname')) ?  implode(', ', array_column($data['rvbarnstall'], 'stallname')) : 'No RV lots available.'; 
												echo $rvlots; ?></td>

										<td><?php if(!empty($data['feed'])){
        										foreach($data['feed'] as $feed){
        										    $quantity = $feed['quantity'];
        										}
    											$feed = (array_column($data['feed'], 'productname')) ?  implode(', ', array_column($data['feed'], 'productname')) : 'No Feed inventory available.'; 
    												echo $feed.'('. $quantity .')';
												} else{
												    echo 'No Feed inventory available.';
												}?>
										</td>
										<td><?php if(!empty($data['shaving'])){
        										foreach($data['shaving'] as $shaving){
        										    $quantity = $shaving['quantity'];
        										}
        										$shavings = (array_column($data['shaving'], 'productname')) ?  implode(', ', array_column($data['shaving'], 'productname')) : 'No Shavings inventory available.'; 
    												echo $shavings.'('. $quantity .')';
    										}else{ echo 'No Shavings inventory available.';}?></td>
										<td><?php echo $data['mobile'];?></td>		
									</tr>
								<?php } ?>
							<?php }else{ ?>
								<tr><td colspan="3">No Arriving on <?php echo date("m-d-y", strtotime($startdate));?></td></tr>
							<?php } ?>
					  </tbody>
					</table>
					
					<h4 class="sub_heading">Outgoing (<?php echo date("m-d-y", strtotime($startdate));?>)</h4>
					<table>
						<thead>
							<tr>
								<th width="40%">Horse Owner</th>
								<th width="30%">Stalls Reserved</th>
								<th width="30%">RV Reserved</th>
								<th width="30%">Feed Reserved</th>
								<th width="30%">Shavings Reserved</th>
								<th width="30%">Contact Phone Number</th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty(array_filter($outgoing))) { ?>
								<?php foreach ($outgoing as $data) { ?>
									<tr>
										<td><?php echo $data['firstname'].' '.$data['lastname'];?></td>
										<td><?php echo implode(', ', array_column($data['barnstall'], 'stallname')); ?></td>
										<td><?php echo implode(', ', array_column($data['rvbarnstall'], 'stallname')); ?></td>
										<td><?php echo implode(', ', array_column($data['feed'], 'productname')); ?></td>
										<td><?php echo implode(', ', array_column($data['shaving'], 'productname')); ?></td>
										<td><?php echo $data['mobile'];?></td>		
									</tr>
								<?php } ?>
							<?php }else{ ?>
								<tr><td colspan="3">No Outgoing on <?php echo date("m-d-y", strtotime($startdate));?></td></tr>
							<?php } ?>
						</tbody>
					</table>
	    		</div>
    			<?php  $startdate = date("Y-m-d", strtotime("+1 day", strtotime($startdate))); ?>
    		<?php } ?>
	    </div>
	</body>
</html>