<?php 
namespace App\Models;

use App\Models\BaseModel;

class Report extends BaseModel
{
	public function getFinancialReport($type, $querydata=[], $requestdata=[], $extras=[])
    {  
		$checkin 		= isset($requestdata['checkin']) ? $requestdata['checkin'] : '';
		$checkout 		= isset($requestdata['checkout']) ? $requestdata['checkout'] : '';
		
    	$select 		= [];
		
		if(in_array('booking', $querydata)){
			$data		= 	['SUM(bk.amount) as totalamount, SUM(bk.price) as totalprice, SUM(bk.transaction_fee) as totaltransactionfee, SUM(bk.cleaning_fee) as totalcleaningfee, SUM(bk.event_tax) as totaltax'];							
			$select[] 	= 	implode(',', $data);
		}
		
		if(in_array('event', $querydata)){
			$data		= 	['e.id AS eventid, e.name AS eventname, e.start_date AS startdate, e.end_date AS enddate'];							
			$select[] 	= 	implode(',', $data);
		}

		$query = $this->db->table('booking bk');

		if(in_array('event', $querydata)) 				$query->join('event e', 'e.id=bk.event_id', 'left');

		if(isset($extras['select'])) 					$query->select($extras['select']);
		else											$query->select(implode(',', $select));
		
		if(isset($requestdata['eventid'])) 				$query->where('bk.event_id', $requestdata['eventid']);		
		if(isset($requestdata['type'])) 				$query->where('e.type', $requestdata['type']);		
		if($checkin!='' && $checkout!='') 				$query->groupStart()->where("bk.check_in BETWEEN '".$checkin."' AND '".$checkout."'")->orWhere("bk.check_out BETWEEN '".$checkin."' AND '".$checkout."'")->groupEnd();
			
		$query->groupBy('bk.event_id');

		if($type=='count'){
			$result = $query->countAllResults(); 
		}else{
			$query = $query->get();
		
			if($type=='all'){
				$result = $query->getResultArray();
			}elseif($type=='row'){
				$result = $query->getRowArray();
			}
			
			$result = $this->getFinancialReportEventdetails($type, $querydata, ['result' => $result, 'type' => '1','barnname' => 'barn', 'stallname' => 'stall', 'bookedstall' => 'bookedstall', 'checkin' => $checkin, 'checkout' => $checkout]);
			$result = $this->getFinancialReportEventdetails($type, $querydata, ['result' => $result, 'type' => '2', 'barnname' => 'rvbarn', 'stallname' => 'rvstall', 'bookedstall' => 'rvbookedstall', 'checkin' => $checkin, 'checkout' => $checkout]);
			$result = $this->getFinancialReportProducts($type, $querydata, ['result' => $result, 'type' => '1', 'productname' => 'feed', 'bookedproduct' => 'feedbooked', 'checkin' => $checkin, 'checkout' => $checkout]);
			$result = $this->getFinancialReportProducts($type, $querydata, ['result' => $result, 'type' => '2', 'productname' => 'shaving', 'bookedproduct' => 'shavingbooked', 'checkin' => $checkin, 'checkout' => $checkout]);
		}
		
		return $result;
    }

    public function getFinancialReportEventdetails($type, $querydata, $extras)
    { 
		$result = $extras['result'];
		$barnname = $extras['barnname'];
		$stallname = $extras['stallname'];
		$bookedstall = $extras['bookedstall'];
		$checkin = $extras['checkin'];
		$checkout = $extras['checkout'];
		
    	if($type=='all'){
    		if(count($result) > 0){
				if(in_array($barnname, $querydata)){
					foreach ($result as $key => $eventdata) { 
						$barndatas = $this->db->table('barn b')->where(['b.status' => '1', 'b.event_id' => $eventdata['eventid'], 'b.type' => $extras['type']])->get()->getResultArray();						
						$result[$key][$barnname] = $barndatas;

						if(in_array($stallname, $querydata) && count($barndatas) > 0){ 
							foreach($barndatas as $barnkey => $barndata){
								$stalldatas = $this->db->table('stall s')->where(['s.status' => '1', 's.event_id' => $barndata['event_id'], 's.barn_id' => $barndata['id'], 's.type' => $extras['type']])->get()->getResultArray();
								$result[$key][$barnname][$barnkey][$stallname] = $stalldatas;

								if(in_array($bookedstall, $querydata)){
									foreach($stalldatas as $stallkey => $stalldata){
										$bookedstalls = 	$this->db->table('booking_details bd')
															->join('booking bks', 'bd.booking_id = bks.id', 'left')
															->select('bks.paymentmethod_id as paymentmethodid, bks.paid_unpaid as paidunpaid, bd.total')
															->where(['bd.barn_id' => $stalldata['barn_id'], 'bd.stall_id' => $stalldata['id']]);
															
										if($checkin!='' && $checkout!='') $bookedstalls->groupStart()->where("bks.check_in BETWEEN '".$checkin."' AND '".$checkout."'")->orWhere("bks.check_out BETWEEN '".$checkin."' AND '".$checkout."'")->groupEnd();									
										$bookedstalls = $bookedstalls->get()->getResultArray();
															
										$result[$key][$barnname][$barnkey][$stallname][$stallkey][$bookedstall] = $bookedstalls;
									}
								}
							}
						}
					}
				}
			}
    	}else if($type=='row'){
    		if($result){
				if(in_array($bookingname, $querydata)){
					$barndatas = $this->db->table('barn b')->where(['b.status' => '1', 'b.event_id' => $eventdata['eventid'], 'b.type' => $extras['type']])->get()->getResultArray();						
					$result[$key][$barnname] = $barndatas;

					if(in_array($stallname, $querydata) && count($barndatas) > 0){ 
						foreach($barndatas as $barnkey => $barndata){
							$stalldatas = $this->db->table('stall s')->where(['s.status' => '1', 's.event_id' => $barndata['event_id'], 's.barn_id' => $barndata['id'], 's.type' => $extras['type']])->get()->getResultArray();
							$result[$key][$barnname][$barnkey][$stallname] = $stalldatas;

							if(in_array($bookedstall, $querydata)){
								foreach($stalldatas as $stallkey => $stalldata){
									$bookedstalls = 	$this->db->table('booking_details bd')
														->join('booking bks', 'bd.booking_id = bks.id', 'left')
														->select('bks.paymentmethod_id as paymentmethodid, bks.paid_unpaid as paidunpaid, bd.total')
														->where(['bd.barn_id' => $stalldata['barn_id'], 'bd.stall_id' => $stalldata['id']]);
									
									if($checkin!='' && $checkout!='') $bookedstalls->groupStart()->where("bks.check_in BETWEEN '".$checkin."' AND '".$checkout."'")->orWhere("bks.check_out BETWEEN '".$checkin."' AND '".$checkout."'")->groupEnd();										
									$bookedstalls = $bookedstalls->get()->getResultArray();
														
									$result[$key][$barnname][$barnkey][$stallname][$stallkey][$bookedstall] = $bookedstalls;
								}
							}
						}
					}
				}
			}
		}
		
		return $result;
    }

	public function getFinancialReportProducts($type, $querydata, $extras)
    {	
		$result 		= $extras['result'];
		$productname 	= $extras['productname'];
		$bookedproduct 	= $extras['bookedproduct'];
		$checkin 		= $extras['checkin'];
		$checkout 		= $extras['checkout'];
		
    	if($type=='all'){
			if(in_array($productname, $querydata) && count($result) > 0){
				foreach ($result as $key => $eventdata) {
					$productsdata = 	$this->db->table('products p')
										->select('p.id, p.name as productname, p.quantity as productquantity')
										->where(['p.status' => '1', 'p.event_id' => $eventdata['eventid'], 'p.type' => $extras['type']])
										->get()
										->getResultArray();
										
					$result[$key][$productname] = $productsdata;
					
					if(in_array($bookedproduct, $querydata)){
						foreach($productsdata as $productkey => $productdata){
							$bookedproducts = 	$this->db->table('booking_details bd')
												->join('booking bks', 'bd.booking_id = bks.id', 'left')
												->select('bks.paymentmethod_id as paymentmethodid, bks.paid_unpaid as paidunpaid, bd.quantity, bd.total')
												->where(['bd.product_id' => $productdata['id']]);
									
							if($checkin!='' && $checkout!='') $bookedproducts->groupStart()->where("bks.check_in BETWEEN '".$checkin."' AND '".$checkout."'")->orWhere("bks.check_out BETWEEN '".$checkin."' AND '".$checkout."'")->groupEnd();
							$bookedproducts = $bookedproducts->get()->getResultArray();
												
							$result[$key][$productname][$productkey][$bookedproduct] = $bookedproducts;
						}
					}
				}
			}
    	}else if($type=='row'){
			if(in_array($productname, $querydata) && $result){
				$productsdata = 	$this->db->table('products p')
									->select('p.name as productname, p.quantity as productquantity')
									->where(['p.status' => '1', 'p.event_id' => $eventdata['eventid'], 'p.type' => $extras['type']])
									->get()
									->getResultArray();
									
				$result[$productname] = $productsdata;
				
				if(in_array($bookedproduct, $querydata)){
					foreach($productsdata as $productkey => $productdata){
						$bookedproducts = 	$this->db->table('booking_details bd')
											->join('booking bks', 'bd.booking_id = bks.id', 'left')
											->select('bks.paymentmethod_id as paymentmethodid, bks.paid_unpaid as paidunpaid, bd.quantity, bd.total')
											->where(['bd.product_id' => $productdata['id']]);
											
						if($checkin!='' && $checkout!='') $bookedproducts->groupStart()->where("bks.check_in BETWEEN '".$checkin."' AND '".$checkout."'")->orWhere("bks.check_out BETWEEN '".$checkin."' AND '".$checkout."'")->groupEnd();								
						$bookedproducts = $bookedproducts->get()->getResultArray();
											
						$result[$key][$productname][$productkey][$bookedproduct] = $bookedproducts;
					}
				}
			}
		}
		
		return $result;
    }
	
}