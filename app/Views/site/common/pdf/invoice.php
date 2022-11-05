<style>
table{
	width : 100%;
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
		<title>Invoice Report</title>
	</head>
	<body>
	    <div> 
		    <div style="text-align:center;"><img src="<?php echo $logo; ?>" width="500"></div>
		    <?php foreach($events as $event){ ?>
                <?php
                    $eventname  = $event['name'];
                    $startdate 	= $event['start_date'];
                 	$enddate 	= $event['end_date'];
                ?>
    	        <h2><?php echo $eventname;?></h2>
    	        <table>
    	            <tr>
    	                <td><?php echo 'Start Date: '.date("m-d-y", strtotime($startdate)); ?></td>
    	                <td><?php echo 'Start Date: '.date("m-d-y", strtotime($enddate)); ?></td>
    	                <td><?php echo 'TOTAL EVENT REVENUE: '.$totalamount; ?></td>
    	            </tr>
    	        </table>
    	        <table>
    	            <tr>
    	                <td><h2>STALLS</h2></td>
    	                <td><h2>RV HOOKUPS</h2></td>
    	            </tr>
    	            <tr>
    	                <td>Total Stalls Available:</td>
    	                <td>Total Lots Available:</td>
    	            </tr>
    	            <tr>
    	                <td>Total Stalls Rented:</td>
    	                <td>Total Lots Rented:</td>
    	            </tr>
    	            <tr>
    	                <td>Total Stalls Not Rented:</td>
    	                <td>Total Lots Not Rented:</td>
    	            </tr>
    	            <tr>
    	                <td>Percentage Occupied:</td>
    	                <td>Percentage Occupied:</td>
    	            </tr>
    	            <tr>
    	                <td>TOTAL STALL REVENUE:</td>
    	                <td>TOTAL LOT REVENUE:</td>
    	            </tr>
    	            <tr>
    	                <td>STRIPE TRANSACTIONS:</td>
    	                <td>STRIPE TRANSACTIONS:</td>
    	            </tr>
    	            <tr>
    	                <td>CASH ON DELIVERY:</td>
    	                <td>CASH ON DELIVERY:</td>
    	            </tr>
    	            <tr>
    	                <td>RESERVED STALLS:</td>
    	                <td>RESERVED STALLS:</td>
    	            </tr>
    	        </table>
    	        <table>
    	            <tr>
    	                <td><h2>FEED</h2></td>
    	                <td><h2>SHAVINGS</h2></td>
    	            </tr>
    	            <tr>
    	                <td>QUANTITY SOLD:</td>
    	                <td>QUANTITY SOLD:</td>
    	            </tr>
    	            <tr>
    	                <td>TOTAL FEED REVENUE:</td>
    	                <td>TOTAL SHAVINGS REVENUE:</td>
    	            </tr>
    	            <tr>
    	                <td>STRIPE TRANSACTIONS:</td>
    	                <td>STRIPE TRANSACTIONS:</td>
    	            </tr>
    	            <tr>
    	                <td>CASH ON DELIVERY:</td>
    	                <td>CASH ON DELIVERY:</td>
    	            </tr>
    	            <tr>
    	                <td>RESERVED STALLS:</td>
    	                <td>RESERVED STALLS:</td>
    	            </tr>
    	        </table>
    	        <h2><?php echo 'TOTAL EVENT REVENUE: '.$totalamount; ?></h2>
    	        <p>Thank You for Choosing EZ Stall!</p>
	        <?php } ?>
	    </div>
	</body>
</html>