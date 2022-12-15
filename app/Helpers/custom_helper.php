<?php
function getAdminUrl()
{
	return base_url().'/administrator';
}

function getUserDetails($id)
{	
	$users 	= new \App\Models\Users;
	$result = $users->getUsers('row', ['users', 'payment'], ['id' => $id]);

	if ($result) {
		return $result;
	} else {
		return false;
	}
}

function getUserID($id)
{
	$userDetails = getUserDetails($id);

	if ($userDetails) {
		return $userDetails['id'];
	} else {
		return false;
	}
}

function getAdminUserID($id='')
{
	if($id=='' && !isset(session()->get('adminsession')['userid'])) return false;
	$id = ($id=='') ? session()->get('adminsession')['userid'] : $id;
	return getUserID($id);
}

function getSiteUserID($id='')
{
	if($id=='' && !isset(session()->get('sitesession')['userid'])) return false;	
	$id = ($id=='') ? session()->get('sitesession')['userid'] : $id;
	return getUserID($id);
}

function getAdminUserDetails($id='')
{
	if($id=='' && !isset(session()->get('adminsession')['userid'])) return false;	
	$id = ($id=='') ? session()->get('adminsession')['userid'] : $id;
	return getUserDetails($id);
}

function getSiteUserDetails($id='')
{
	if($id=='' && !isset(session()->get('sitesession')['userid'])) return false;	
	$id = ($id=='') ? session()->get('sitesession')['userid'] : $id;
	return getUserDetails($id);
}

function imagetobase64($url){
    $type = pathinfo($url, PATHINFO_EXTENSION);
    $data = file_get_contents($url);
    return 'data:image/' . $type . ';base64,' . base64_encode($data);
}

function checkEvent($data)
{
	$userdetail 	= getSiteUserDetails();
	$currentdate 	= date("Y-m-d");
	$userid 		= isset($userdetail['id']) ? $userdetail['id'] : '';
	$parentid 		= isset($userdetail['parent_id']) ? $userdetail['parent_id'] : '';
	$usertype 		= isset($userdetail['type']) ? $userdetail['type'] : '';
	$userplanend 	= isset($userdetail['subscriptionenddate']) ? date('Y-m-d', strtotime($userdetail['subscriptionenddate'])) : '';
	$strstartdate 	= date("Y-m-d", strtotime($data['start_date']));
	$strenddate 	= date("Y-m-d", strtotime($data['end_date']));

	if($currentdate >= $strstartdate && $currentdate <= $strenddate){
		$btn = "Book now";
		$status = "1";
		if(in_array($usertype, [2, 3, 4])){
			if($userid == $data['user_id']){
				$btn = "Book now";
				$status = "1";
			}elseif($usertype == 4 && $parentid == $data['user_id']){
				$btn = "Book now";
				$status = "1";
			}else{
				$btn = "Booking Not Available";
				$status = "0";
			}
		}//elseif($usertype==5 && $currentdate > $userplanend){
			//$btn = "Subscription Expired";
			//$status = "0";
		//}
	}elseif($currentdate <= $strstartdate && $currentdate <= $strenddate){
		$btn = "Upcoming";
		$status = "1";
	}else{
		$btn = "Closed";
		$status = "0";
	}

	return ['btn' => $btn, 'status' => $status];
}

function getStallManagerIDS($parentid)
{	
	$users 		= new \App\Models\Users;	
	$result		= $users->getUsers('all', ['users'], ['parentid' => $parentid, 'status' => ['1']]);
	return array_column($result, 'id');
}

function checkSubscription()
{
	$date = date('Y-m-d');
	$userdetails = getSiteUserDetails();
	$type = '0';
	$facility = '0';
	$producer = '0';
	$stallmanager = '0';
	
	if(isset($userdetails)){
		$type = $userdetails['type'];
		
		if($type=='2' && $date < $userdetails['subscriptionenddate']){
			$facility = '1';
		}
		
		if($type=='3'){
			$producer = $userdetails['producer_count'];
		}

		if($type=='4' && $date < $userdetails['subscriptionenddate']){
			$stallmanager = '1';
		}
	}
	
	return ['type' => $type, 'facility' => $facility, 'producer' => $producer, 'stallmanager' => $stallmanager];
}

function dateformat($date, $type='')
{
	if ($type == '1') return date('m-d-Y H:i:s', strtotime($date));
	else return date('m-d-Y', strtotime($date));
}

function filedata($file, $path, $extras=[])
{
	$sourceimg 			= (in_array('profile', $extras)) ? base_url().'/assets/images/profile.jpg' : base_url().'/assets/images/upload.png';
	$pdfimg 			= base_url().'/assets/images/pdf.png';
	$relativepath		= str_replace(base_url(), '.', $path);
	
	if($file!=''){
		$explodefile 	= explode('.', $file);
		$ext 			= array_pop($explodefile);
		$image 			= (in_array($ext, ['pdf', 'tiff'])) ? $pdfimg : (file_exists($relativepath.$file) ? $path.$file : $sourceimg);
	}else{
		$image 			= $sourceimg;
	}
	
	return [$file, $image];
}

function filemove($file, $destination)
{
	createDirectory($destination);
	
	$source 			= './assets/uploads/temp/'.$file;
	$destination 		= $destination.'/'.$file;
		
	if(file_exists($source)) rename($source, $destination);
}

function createDirectory($path)
{
	$location = explode('/', $path);
	for($i=0; $i<count($location); $i++)
	{
		if($location[$i]!='.'){
			$dir = implode('/', array_slice($location, 0, $i+1));
			if(!is_dir($dir))
			{
				$mask = umask(0);
				mkdir($dir, 0755);
				umask($mask);
			}
		}
	}
}

function send_mail($to,$subject,$message,$attachment='')
{

	$email = \Config\Services::email();

	$config['protocol']  	= 'smtp';
	/*$config['SMTPHost']  	= 'mail.ezstall.com';
	$config['SMTPUser']  	= 'support@ezstall.com';
	$config['SMTPPass']  	= 'qx~JrSJxRBZ0';*/
	$config['SMTPHost'] 	= 'mail.itfhrm.com';
	$config['SMTPUser'] 	= 'info@itfhrm.com';
	$config['SMTPPass'] 	= 'itFlex@123';
	$config['SMTPPort']  	= '587';
	$config['SMTPCrypto']   = 'tls';
	$config['charset']   	= 'iso-8859-1';
	$config['wordWrap']  	= true;

	$email->initialize($config);

	$email->setFrom('info@itfhrm.com', 'Ezstall');
	$email->setTo($to);
	$email->setSubject($subject);
	$email->setMessage(strip_tags($message));

	if($attachment !=''){
		$filename 	= 'Eventinvoice.pdf';
		$email->attach($attachment, 'attachment', $filename, 'application/pdf');
	}

    if($email->send()){
        return "sent";
    }else{
       	print_r($email->printDebugger());exit;
        return "not sent";
    }
} 

function getUsersList($data=[])
{    
    $users         =     new \App\Models\Users;;    
    $result        =     $users->getUsers('all', ['users'], ['status' => ['1']]+$data);
    
    if(count($result) > 0){
		if(isset($data['all'])) return $result;
		else return ['' => 'Select User']+array_column($result, 'name', 'id');
    }else{
		return [];    
	}
}

function getEventsList($data=[])
{    
    $event         =     new \App\Models\Event;   
    $result        =     $event->getEvent('all', ['event'], ['status' => ['1']]+$data);
    
    if(count($result) > 0){
		if(isset($data['all'])) return $result;
		else return array_column($result, 'name', 'id');
    }else{
		return [];    
	}
}


function formatdate($date, $type=''){
    if($type==''){
		$date = (strpos($date, '/')!== false) ? explode('/', $date) : explode('-', $date);
		return $date[2].'-'.$date[0].'-'.$date[1]; //m-d-Y to Y-m-d
	}elseif($type=='1'){
		return date("m-d-Y", strtotime($date)); //Y-m-d to m-d-Y
	}elseif($type=='2'){
		return date('m-d-Y h:i A',strtotime($date)); //Y-m-d H:i:s to m-d-Y h:i A
	}
}

function formattime($time, $type=''){
    if($type==''){
    	return date('h:i A', strtotime($time)); //24 hours to 12 hours
	}elseif($type=='1'){
		return date("H:i A", strtotime($time));; //12 hours to 24 hours
	}
}

function getCart($type=''){ 
	$request 		= service('request');
    $condition 		= getSiteUserID() ? ['user_id' => getSiteUserID(), 'ip' => $request->getIPAddress()] : ['user_id' => 0, 'ip' =>$request->getIPAddress()] ;
	if($type!='') $condition['type'] = $type;
	$cart 		    = new \App\Models\Cart;
	$result         = $cart->getCart('all', ['cart', 'event', 'barn', 'stall', 'product', 'tax'], $condition);
	if($result){
		$setting 				= getSettings();
		$cartreservedtime		= $cart->getReserved($setting['cartreservedtime']);
		$timer					= $cartreservedtime ? $cartreservedtime : '';
		$count					= count($result);
		
		$event_id 				= array_unique(array_column($result, 'event_id'))[0];
		$event_name 			= array_unique(array_column($result, 'eventname'))[0];
		$event_location 		= array_unique(array_column($result, 'eventlocation'))[0];
		$event_description 		= array_unique(array_column($result, 'eventdescription'))[0];
		$cleaning_fee 			= array_unique(array_column($result, 'eventcleaningfee'))[0];
	    $check_in       		= formatdate(array_unique(array_column($result, 'check_in'))[0], 1);
	    $check_out      		= formatdate(array_unique(array_column($result, 'check_out'))[0], 1);
	    $start          		= strtotime(array_unique(array_column($result, 'check_in'))[0]);
		$end            		= strtotime(array_unique(array_column($result, 'check_out'))[0]);
		$daydiff           		= ceil(abs($start - $end) / 86400);
		$interval           	= $daydiff==0 ? 1 : $daydiff;
		$type          			= array_unique(array_column($result, 'type'))[0];
		$tax 					= isset($result[0]['tax']['tax_price']) ? $result[0]['tax']['tax_price'] :'0';

		$barnstall = $rvbarnstall = $feed =  $shaving = [];
		$price = 0;
		
		foreach ($result as $res) {
			$singleprice = $res['price'];
				
			if($res['flag']=='1' || $res['flag']=='2'){				
				$singlechargingid 	= $res['chargingid'];
				$singlepricetype 	= $res['price_type'];
				
				if($singlepricetype==0){
					$intervalday = $interval;
					$intervalday = ($intervalday%7==0) ? ($intervalday/7) : $intervalday;
					$intervalday = ($intervalday%30==0) ? ($intervalday/30) : $intervalday;
					if($singlechargingid=='4') $intervalday = 1;
						
					$singletotal = $singlechargingid=='4' ?  $singleprice : $singleprice * $intervalday;
				}else{
					$intervalday = $interval;
					if($singlepricetype=='2') 		$intervalday = ceil($intervalday/7);
					elseif($singlepricetype=='3') 	$intervalday = ceil($intervalday/30);
					elseif($singlepricetype=='4') 	$intervalday = 1;
					elseif($singlepricetype=='5') 	$intervalday = 1;
					
					if($singlepricetype=='1') 		$singletotal = $singleprice * $intervalday;
					elseif($singlepricetype=='2') 	$singletotal = $singleprice * $intervalday;
					elseif($singlepricetype=='3') 	$singletotal = $singleprice * $intervalday;
					elseif($singlepricetype=='4') 	$singletotal = $singleprice;
					elseif($singlepricetype=='5') 	$singletotal = $singleprice;
				}
				
				$barnrvdata = [
					'barn_id' 				=> $res['barn_id'], 
					'barn_name' 			=> $res['barnname'], 
					'stall_id' 				=> $res['stall_id'],
					'stall_name' 			=> $res['stallname'],
					'subscriptionprice' 	=> $res['subscription_price'],
					'price' 				=> $singleprice,
					'pricetype' 			=> $singlepricetype,
					'chargingid' 			=> $singlechargingid,
					'interval' 				=> $interval,
					'intervalday' 			=> $intervalday,
					'total' 				=> $singletotal
				];
				
				if($res['flag']=='1') 		$barnstall[] = $barnrvdata;
				elseif($res['flag']=='2')	$rvbarnstall[] = $barnrvdata;
				
				$price += $singletotal;
			}else if($res['flag']=='3' || $res['flag']=='4'){
				$singlequantity = $res['quantity'];
				$singletotal = $singleprice * $singlequantity;
				
				$feedshavingdata = [
					'product_id'	=> $res['product_id'], 
					'product_name'	=> $res['productname'], 
					'price' 		=> $singleprice,
					'quantity' 		=> $singlequantity,
					'total' 		=> $singletotal
				];
				
				if($res['flag']=='3') 		$feed[] = $feedshavingdata;
				elseif($res['flag']=='4')	$shaving[] = $feedshavingdata;
				
				$price += $singletotal;
			}
		}


		$barnstallcolumn = array_column($barnstall, 'barn_id');
		array_multisort($barnstallcolumn, SORT_ASC, $barnstall);
		$rvbarnstallcolumn = array_column($rvbarnstall, 'barn_id');
		array_multisort($rvbarnstallcolumn, SORT_ASC, $rvbarnstall);
		$feedcolumn = array_column($feed, 'product_id');
		array_multisort($feedcolumn, SORT_ASC, $feed);
		$shavingcolumn = array_column($shaving, 'product_id');
		array_multisort($shavingcolumn, SORT_ASC, $shaving);
		return [
			'event_id'			=> $event_id, 
			'event_name'		=> $event_name, 
			'event_tax'			=> $tax, 
			'event_location' 	=> $event_location, 
			'event_description' => $event_description, 
			'cleaning_fee' 		=> $cleaning_fee, 
			'barnstall'			=> $barnstall,
			'rvbarnstall'		=> $rvbarnstall, 
			'feed'				=> $feed, 
			'shaving'			=> $shaving, 
			'interval' 			=> $interval, 
			'check_in' 			=> $check_in,
			'check_out'			=> $check_out,
			'price' 			=> $price,
			'type' 				=> $type,
			'timer' 			=> $timer,
			'count' 			=> $count
		];	
	}else{
		return false;
	}
}

function getOccupied($eventid, $extras=[]){
	$condition 	= ['eventid' => $eventid,  'subscriptionstatus' => '1',  'status' => '1'];
	if(count($extras) > 0) $condition 	= $condition+$extras;
		
	$booking	= new \App\Models\Booking;
	$booking 	= $booking->getBooking('all', ['booking','barnstall','rvbarnstall'], $condition);
	
	$occupied = [];
	foreach ($booking as  $bookdata) {
		$barnstall 	= $bookdata['barnstall'];
		$rvbarnstall = $bookdata['rvbarnstall'];
		if(!empty($barnstall)){
		    $occupied[] = implode(',', array_column($barnstall, 'stall_id'));
		}
		if(!empty($rvbarnstall)){
		    $occupied[] = implode(',', array_column($rvbarnstall, 'stall_id'));
		}
	}

	return (count($occupied) > 0) ? explode(',', implode(',', $occupied)) : [];
}

function getReserved($eventid, $extras=[]){ 
	$condition 	= ['event_id' => $eventid];
	if(count($extras) > 0) $condition 	= $condition+$extras;
	
	$cart	= new \App\Models\Cart;
	$cart	= $cart->getCart('all', ['cart'], $condition);
	
	return (count($cart) > 0) ? array_column($cart, 'user_id', 'stall_id') : [];
}

function getBlockunblock($eventid, $extras=[]){ 
	$condition1 	= ['event_id' => $eventid, 'block_unblock' => '1', 'status' => ['1']];
	$condition2 	= ['facilityid' => $eventid, 'status' => ['1'], 'type' => '1'];
	
	if(isset($extras['type']) &&  $extras['type']=='1') 	$condition = $condition2+['gtenddate' => date('Y-m-d')];
	elseif(isset($extras['type']) &&  $extras['type']=='2') $condition = $condition2+['checkin' => $extras['checkin'], 'checkout' => $extras['checkout'], 'nqeventid' => (isset($extras['nqeventid']) ? $extras['nqeventid'] : '')];
	else $condition = $condition1;
		
	$stall			= new \App\Models\Stall;
	$blockunblock	= $stall->getStall('all', ['stall', 'event'], $condition);
	
	if(isset($extras['type']) &&  $extras['type']=='1') 	return (count($blockunblock) > 0) ? $blockunblock : [];
	elseif(isset($extras['type']) &&  $extras['type']=='2') return (count($blockunblock) > 0) ? array_column($blockunblock, 'stall_id') : [];
	else return (count($blockunblock) > 0) ? array_column($blockunblock, 'id') : [];
}

function getProductQuantity($eventid, $extras=[]){
	$request 		= service('request');
	$condition 		= getSiteUserID() ? ['event_id' => $eventid, 'neq_user_id' => getSiteUserID(), 'neq_ip' => $request->getIPAddress()] : ['event_id' => $eventid, 'neq_user_id' => 0, 'neq_ip' =>$request->getIPAddress()] ;
	if(count($extras) > 0) $condition 	= $condition+$extras;
	
	$cart	= new \App\Models\Cart;
	$cart	= $cart->getCart('all', ['cart'], $condition);
	
	return (count($cart) > 0) ? array_sum(array_column($cart, 'quantity')) : 0;
}

function upcomingEvents()
{
	$event	= new \App\Models\Event;
	return $event->getEvent('all', ['event'],['status' => ['1'], 'start_date' => date('Y-m-d')], ['orderby' => 'e.id desc', 'limit' => '3', 'type' => '1']);
}

function removeCartReserved()
{	
	$setting = getSettings();
	
	$cart	= new \App\Models\Cart;
	$cart	= $cart->removeReserved($setting['cartreservedtime']);
	
	return true;
}

function getSettings()
{
	$settings = new \App\Models\Settings;
    return $settings->getSettings('row', ['settings'], ['id' => '1']);
}

function getBooking($condition=[])
{
	$booking = new \App\Models\Booking;
	return $booking->getBooking('all', ['booking','users','barnstall','rvbarnstall','feed','shaving'], $condition);
}

function send_emailsms_template($id, $extras=[]){  
	$emailsmstemplate 	= new \App\Models\Emailsmstemplate;
	$users 				= new \App\Models\Users;
	$products 			= new \App\Models\Products;
	$event 				= new \App\Models\Event;

    $emailsmstemplate 	= $emailsmstemplate->getEmailTemplate('row', ['emailsmstemplate'], ['id' => $id]);
    
    if(isset($extras['userid'])){
        $users 		= $users->getUsers('row', ['users'], ['id' => $extras['userid']]);
        $username  	= $users['name'];
        $email  	= $users['email'];
		
		if($id=='1'){
			$encryptid 	= substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 10).$result.substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 5);
			$link		= base_url()."/verification/".$encryptid;
		}elseif($id=='2'){
			$link  		= base_url().'/changepassword/'.base64_encode($users['id']).'/'.base64_encode(date('Y-m-d H:i:s', strtotime('+1 day')));
		}
    }else{
		$email  	= isset($extras['email']) ? $extras['email'] : '';
	}
    
    if(isset($extras['productid'])){ 
        $products 		= $products->getProduct('row', ['product'], ['id' => $extras['productid']]);
        $productname 	= $products['name'];
    }
	
    if(isset($extras['eventid'])){ 
        $event 		= $event->getEvent('row', ['event'], ['id' => $extras['eventid']]);
        $eventname 	= $event['name'];
    }

    $attachment = '';
    if(isset($extras['attachment'])){ 
        $attachment = $extras['attachment'];
    }
	
    $message = str_replace(
        [
			'#username',
            '#productname', 
			'#eventname',
			'#stallname',
			'#link'
        ],
        [
            isset($username) ? $username : '',
            isset($productname) ? $productname : '',
            isset($eventname) ? $eventname : '',
            isset($extras['stallsname']) ? $extras['stallsname'] : ''
            isset($link) ? $link : ''
        ],
        $emailsmstemplate['message']
    );

	if($emailsmstemplate['type']=='1'){
		$subject = str_replace(
			[
				'#productname'
			],
			[
				isset($productname) ? $productname : '',
			],
			$emailsmstemplate['subject']
		);
		
		send_mail($email, $subject, $message,$attachment);
	}elseif($emailsmstemplate['type']=='2'){
		$setting = getSettings();
		
		try{
			$client = new Twilio\Rest\Client($setting['sid'], $setting['token']);
			
			$message = $client->messages->create(
				'1'.$extras['mobile'],
				[
					'from' => $setting['fromnumber'],
					'body' => $message,
				]
			);
		}catch(Exception $e){
			echo $e->getCode() . ' : ' . $e->getMessage();
		}
	}
}