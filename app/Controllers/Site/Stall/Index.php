<?php 

namespace App\Controllers\Site\Stall;

use App\Controllers\BaseController;
use App\Models\Stall;

class Index extends BaseController
{
	public function __construct()
	{
		$this->stall = new Stall();
	}
    
    public function index()
    {  
    	$pager = service('pager'); 
		$page = (int)(($this->request->getVar('page')!==null) ? $this->request->getVar('page') :1)-1;
		$perpage =  9; 
		$offset = $page * $perpage;
		
		if($this->request->getVar('q')!==null){
			$searchdata = ['search' => ['value' => $this->request->getVar('q')], 'page' => 'stalls'];
			$data['search'] = $this->request->getVar('q');
		}else{
			$searchdata = [];
			$data['search'] = '';
		}

        if($this->request->getGet('name')!="")   		    $searchdata['stallname']        = $this->request->getGet('name');
		if($this->request->getGet('start_date')!="")   	 	$searchdata['btw_start_date']   = formatdate($this->request->getGet('start_date'));
		if($this->request->getGet('end_date')!="")   	 	$searchdata['btw_end_date']    	= formatdate($this->request->getGet('end_date'));
		$stallcount = $this->stall->getStall('count', ['stall','event'], $searchdata+['status'=> ['1'],'type' => '2']);
    	$stalls 	= $this->stall->getStall('all', ['stall','event'], $searchdata+['status'=> ['1'],'type' => '2', 'start' => $offset, 'length' => $perpage]);

        $data['stalllist'] 	= $stalls; 
		$data['currencysymbol'] = $this->config->currencysymbol;
        $data['pager'] 		= $pager->makeLinks($page, $perpage, $stallcount);
        
    	return view('site/stall/index',$data);
    }
	
    public function detail($id)
    {
    	$data['detail'] = $this->stall->getStall('row', ['stall','event'],['id' => $id]);
		$data['currencysymbol'] = $this->config->currencysymbol;

    	return view('site/stall/detail',$data);
    }
}
