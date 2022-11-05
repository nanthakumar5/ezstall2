<?php 
namespace App\Controllers\Site\Myaccount\Stallmanager;

use App\Controllers\BaseController;
use App\Models\Users;

class Index extends BaseController
{
	public function __construct()
	{	
		$this->users = new Users();
	}
    
    public function index()
    {
    	$userid = getSiteUserID(); 			

		if ($this->request->getMethod()=='post')
        {
			$requestData = $this->request->getPost();
			$result = $this->users->delete($requestData);

			if($result){
				$this->session->setFlashdata('success', 'Stall manager deleted successfully.');
				return redirect()->to(base_url().'/myaccount/stallmanager'); 
			}else{
				$this->session->setFlashdata('danger', 'Try Later');
				return redirect()->to(base_url().'/myaccount/stallmanager'); 
			}
		}
		
    	$pager = service('pager'); 
		$page = (int)(($this->request->getVar('page')!==null) ? $this->request->getVar('page') :1)-1;
		$perpage =  5; 
		$offset = $page * $perpage;
		
		$managercount = $this->users->getUsers('count',['users'],['type' => ['4'],'status' => ['1'], 'parentid' => $userid]);
		$manager = $this->users->getUsers('all',['users'],['type' => ['4'],'status' => ['1'], 'parentid' => $userid]);
        
        $data['list'] = $manager;
        $data['pager'] = $pager->makeLinks($page, $perpage, $managercount);
		$data['userid'] = $userid;
				
		return view('site/myaccount/stallmanager/index', $data);
    }

    public function action($id='')
	{   
		$userid = getSiteUserID();

		if ($this->request->getMethod()=='post')
		{ 
			$requestData = $this->request->getPost();
			$requestData['userid'] = $userid;
			$requestData['status'] = '1';
			$requestData['email_status'] = '1';
			$requestData['type'] = '4'; 
			$userid = $this->users->action($requestData); 
		
			if($userid){ 
				$this->session->setFlashdata('success', 'Stall Manager Updated Successfully'); 
			}else{ 
				$this->session->setFlashdata('danger', 'Try again Later.');
			}

			return redirect()->to(base_url().'/myaccount/stallmanager'); 
		}

		if($id!=''){
			$result = $this->users->getUsers('row',['users'],['id' => $id, 'parentid' => $userid]);
			
			if($result){
				$data['result'] = $result;	
			}else{
				$this->session->setFlashdata('danger', 'No Record Found.');
				return redirect()->to(base_url().'/myaccount/stallmanager'); 
			} 
		}
		
		$data['userid'] = $userid;
		return view('site/myaccount/stallmanager/action', $data);
	}
}
