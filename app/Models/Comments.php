<?php

namespace App\Models;

use App\Models\BaseModel;

class Comments extends BaseModel
{	
	public function getComments($type, $querydata=[], $requestdata=[], $extras=[])
    { 
    	$select 			= [];
		
		if(in_array('comments', $querydata)){
			$data		= 	['c.*'];							
			$select[] 	= 	implode(',', $data);
		}

		if(in_array('users', $querydata)){
			$data		= 	['u.name username','u.type usertype'];							
			$select[] 	= 	implode(',', $data);
		}

		if(in_array('event', $querydata)){
			$data		= 	['e.name eventname'];							
			$select[] 	= 	implode(',', $data);
		}

	
		$query = $this->db->table('comments c');
		if(in_array('users', $querydata)) $query->join('users u', 'u.id=c.user_id', 'left');
		if(in_array('event', $querydata)) $query->join('event e', 'e.id=c.event_id', 'left');

		if(isset($extras['select'])) 					$query->select($extras['select']);
		else											$query->select(implode(',', $select));
		
		if(isset($requestdata['id'])) 					$query->where('c.id', $requestdata['id']);
		if(isset($requestdata['eventid'])) 				$query->where('c.event_id', $requestdata['eventid']);
		if(isset($requestdata['commentid'])) 			$query->where('c.comment_id ', $requestdata['commentid']);
		if(isset($requestdata['status'])) 				$query->where('c.status ', $requestdata['status']);

		if($type!=='count' && isset($requestdata['start']) && isset($requestdata['length'])){
			$query->limit($requestdata['length'], $requestdata['start']);
		}
		if(isset($requestdata['order']['0']['column']) && isset($requestdata['order']['0']['dir'])){
			if(isset($requestdata['page']) && $requestdata['page']=='comments'){
				$column = ['e.name','u.name','c.comment'];
				$query->orderBy($column[$requestdata['order']['0']['column']], $requestdata['order']['0']['dir']);
			}
		}
		if(isset($requestdata['search']['value']) && $requestdata['search']['value']!=''){
			$searchvalue = $requestdata['search']['value'];
						
			if(isset($requestdata['page'])){ 
				$page = $requestdata['page'];
				$query->groupStart();
					if($page=='comments'){ 		
						$query->like('e.name', $searchvalue);
						$query->orLike('u.name', $searchvalue);
						$query->orLike('c.comment', $searchvalue);
					}
				$query->groupEnd();
			}			
		}
		
		if(isset($extras['groupby'])) 	$query->groupBy($extras['groupby']);
		if(isset($extras['orderby'])) 	$query->orderBy($extras['orderby']);

		if($type=='count'){
			$result = $query->countAllResults();
		}else{
			$query = $query->get();
			
			if($type=='all'){
				$result = $query->getResultArray();	
				if(count($result) > 0){
					if(in_array('replycomments', $querydata)){		
						foreach ($result as $key => $comments) {							
							$replycomment = $this->db->table('comments r')
											->join('users u','u.id  = r.user_id', 'left')
											->select('r.id replyid,r.user_id replieduserid,r.comment_id commentid,r.comment reply, u.name username')
											->where('r.comment_id', $comments['id'])
											->get()
											->getResultArray();
											
							$result[$key]['replycomments'] = $replycomment;
						}
					}
				}
			}elseif($type=='row'){
				$result = $query->getRowArray();
				if($result){
					if(in_array('replycomments', $querydata)){		
						$replycomment = $this->db->table('comments r')
										->join('users u','u.id  = r.user_id', 'left')
										->select('r.id replyid,r.user_id replieduserid,r.comment_id commentid,r.comment reply, u.name username')
										->where('r.comment_id', $result['id'])
										->get()
										->getResultArray();
						$result['replycomments'] = $replycomment;
					}
				}
			}
		}
		
		return $result;
    }
	
	public function action($data)
	{

		$this->db->transStart();
		
		$datetime		= date('Y-m-d H:i:s');
		$commentid 		= (isset($data['comment_id'])) ? $data['comment_id'] : 0;
		$actionid 		= (isset($data['actionid'])) ? $data['actionid'] : '';

		if(isset($data['eventid']) && $data['eventid']!='')      			$request['event_id'] 						= $data['eventid'];
		if(isset($data['userid']) && $data['userid']!='') 	 				$request['user_id'] 						= $data['userid'];
		if(isset($data['comment']) && $data['comment']!='')					$request['comment'] 						= $data['comment'];
		
		$request['communication']	= (isset($data['communication']) && $data['communication']!='') ? $data['communication'] : 0; 
		$request['cleanliness']		= (isset($data['cleanliness']) && $data['cleanliness']!='') ? $data['cleanliness'] : 0; 
		$request['friendliness']	= (isset($data['friendliness']) && $data['friendliness']!='') ? $data['friendliness'] : 0; 
		
		if(isset($request)){
			$request['status'] 			= 	'1';		
			$request['updated_at'] 		= 	$datetime;
			$request['updated_by'] 		= 	$data['userid'];						
			$request['comment_id'] 		= 	$commentid;

			if($actionid==''){
				$request['created_at'] 		= 	$datetime;
				$request['created_by'] 		= 	$data['userid'];
				
				$comments = $this->db->table('comments')->insert($request);
				$commentsinsertid = $this->db->insertID();
			}else{
				$comments = $this->db->table('comments')->update($request, ['id' => $actionid]);
				$commentsinsertid = $actionid;
			}			
		}
		
		if(isset($commentsinsertid) && $this->db->transStatus() === FALSE){
			$this->db->transRollback();
			return false;
		}else{
			$this->db->transCommit();
			return $commentsinsertid;
		}
	}

	public function delete($data)
	{
		$this->db->transStart();
		
		$datetime		= date('Y-m-d H:i:s');
		$userid			= (isset($data['userid'])) ? $data['userid'] : '';
		$id 			= $data['id'];

		
		$cms 			= $this->db->table('comments')->update(['updated_at' => $datetime, 'updated_by' => $userid, 'status' => '0'], ['id' => $id]);
		
		if($cms && $this->db->transStatus() === FALSE){
			$this->db->transRollback();
			return false;
		}else{
			$this->db->transCommit();
			return true;
		}
	}
}