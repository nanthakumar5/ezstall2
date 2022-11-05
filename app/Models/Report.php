<?php 
namespace App\Models;

use App\Models\BaseModel;

class Report extends BaseModel
{
	public function getReport($type, $querydata=[], $requestdata=[], $extras=[])
    {  
    	$select 		= [];
		
		if(in_array('booking', $querydata)){
			$data		= 	['bk.*'];							
			$select[] 	= 	implode(',', $data);
		}
		
		if(in_array('event', $querydata)){
			$data		= 	['e.name eventname, e.zipcode'];							
			$select[] 	= 	implode(',', $data);
		}

		$query = $this->db->table('booking bk');

		if(in_array('event', $querydata)) 				$query->join('event e', 'e.id=bk.event_id', 'left');

		if(isset($extras['select'])) 					$query->select($extras['select']);
		else											$query->select(implode(',', $select));
		
		if(isset($requestdata['id'])) 					$query->where('bk.id', $requestdata['id']);
		if(isset($requestdata['status'])) 				$query->where('bk.status', $requestdata['status']);

		if(isset($requestdata['financialcheckin'])) 	$query->where('bk.created_at >=', $requestdata['financialcheckin']);	
		if(isset($requestdata['financialcheckout'])) 	$query->where('bk.created_at <=', $requestdata['financialcheckout']);

		
		if(isset($extras['groupby'])) 	$query->groupBy($extras['groupby']);
		if(isset($extras['orderby'])) 	$query->orderBy($extras['orderby']);

		if($type=='count'){
			$result = $query->countAllResults(); 
		}else{
			$query = $query->get();
		
			if($type=='all'){
				$result = $query->getResultArray();
			}elseif($type=='row'){
				$result = $query->getRowArray();
			}
			
			$result = $this->getReportEventdetails($type, $querydata, ['result' => $result, 'type' => '1','barnname' => 'barn', 'stallname' => 'stall', 'bookedstall' => 'bookedstall']);
			$result = $this->getReportEventdetails($type, $querydata, ['result' => $result, 'type' => '2', 'barnname' => 'rvbarn', 'stallname' => 'rvstall', 'bookedstall' => 'rvbookedstall']);

			$result = $this->getReportProducts($type, $querydata, ['result' => $result, 'type' => '1', 'productname' => 'feed']);
			$result = $this->getReportProducts($type, $querydata, ['result' => $result, 'type' => '2', 'productname' => 'shaving']);
		}
		return $result;
    }

    public function getReportEventdetails($type, $querydata, $extras)
    { 
		$result = $extras['result'];
		$barnname = $extras['barnname'];
		$stallname = $extras['stallname'];
		$bookedstall = $extras['bookedstall'];
		
    	if($type=='all'){
    		if(count($result) > 0){
				if(in_array($barnname, $querydata)){
					foreach ($result as $key => $eventdata) { 
						$barndatas = $this->db->table('barn b')->where(['b.status' => '1', 'b.event_id' => $eventdata['eventid'], 'b.type' => $extras['type']])->get()->getResultArray();						
						$result[$key][$barnname] = $barndatas;

						if(in_array($stallname, $querydata) && count($barndatas) > 0){ 
							foreach($barndatas as $barnkey => $barndata){
								$stalldatas = $this->db->table('stall s')->where(['s.status' => '1', 's.event_id' => $barndata['event_id'], 's.type' => $extras['type']])->get()->getResultArray();
								$result[$key][$barnname][$barnkey][$stallname] = $stalldatas;

								if(in_array($bookedstall, $querydata)){
									foreach($stalldatas as $stallkey => $stalldata){
										$bookedstalls = 	$this->db->table('booking_details bd')
																->join('booking bks', 'bd.booking_id = bks.id', 'left')
																->join('payment_method pm','bks.paymentmethod_id = pm.id')
																->select('(pm.name) paymentmethod, (pm.id) paymentid, bks.paid_unpaid As paidunpaid')
																->where(['bd.stall_id' => $stalldata['id'], 'bd.barn_id' => $barndata['id']])
																->get()
																->getResultArray();
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
								$stalldatas = $this->db->table('stall s')->where(['s.status' => '1', 's.event_id' => $barndata['event_id'], 's.type' => $extras['type']])->get()->getResultArray();
								$result[$key][$barnname][$barnkey][$stallname] = $stalldatas;

								if(in_array($bookedstall, $querydata)){
									foreach($stalldatas as $stallkey => $stalldata){
										$bookedstalls = 	$this->db->table('booking_details bd')
																->join('booking bks', 'bd.booking_id = bks.id', 'left')
																->join('payment_method pm','bks.paymentmethod_id = pm.id' )
																->select('(pm.name) paymentmethod, (pm.id) paymentid, bks.paid_unpaid As paidunpaid')
																->where(['bd.stall_id' => $stalldata['id'], 'bd.barn_id' => $barndata['id']])
																->get()
																->getResultArray();
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

	public function getReportProducts($type, $querydata, $extras)
    {	
		$result 		= $extras['result'];
		$productname 	= $extras['productname'];
    	if($type=='all'){
			if(in_array($productname, $querydata) && count($result) > 0){
				foreach ($result as $key => $eventdata) {
					$productsdata = 	$this->db->table('products pds')
										->join('booking_details pbd', 'pds.id = pbd.product_id', 'left')
										->select('pbd.price As productprice, pbd.quantity As productquantity, SUM(pbd.total) As total, pds.quantity As totalquantity')
										->where(['pds.status' => '1', 'pds.event_id' => $eventdata['eventid'], 'pds.type' => $extras['type']])
										->groupBy('pds.id')
										->get()
										->getResultArray();
					$result[$key][$productname] = $productsdata;

				}
			}
    	}else if($type=='row'){
			if(in_array($productname, $querydata) && $result){
				$productsdata = 	$this->db->table('products pds')
										->join('booking_details pbd', 'pds.id = pbd.product_id', 'left')
										->select('pbd.price As productprice, pbd.quantity As productquantity, pbd.total As total, p.quantity As totalquantity')
										->where(['p.status' => '1', 'p.event_id' => $eventdata['eventid'], 'p.type' => $extras['type']])
										->get()
										->getResultArray();
				$result[$productname] = $productsdata;
			}
		}
		
		return $result;
    }
	
}