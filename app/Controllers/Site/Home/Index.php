<?php 

namespace App\Controllers\Site\Home;

use App\Controllers\BaseController;
use App\Models\Event;
use App\Models\Cms;
use App\Models\Newsletter;

class Index extends BaseController
{
	public function __construct()
	{
		$this->event = new Event();
        $this->cms = new Cms;
        $this->news = new Newsletter();
	}
    
    public function index()
    { 
    	if($this->request->getMethod()=='post'){ 

            $post           = $this->request->getPost();
            $check_email    = $this->news->getNewsletter('count', ['newsletter'],['email' => $post['email']]);

			if($check_email!=0){ 
				$this->session->setFlashdata('toastr', '0');
				return redirect()->to(base_url().'/'); 
			}else{ 
				$result = $this->news->action($post);
				if($result){ 
					$this->session->setFlashdata('toastr', '1');
					return redirect()->to(base_url().'/'); 
				} 
			}
			
			return redirect()->to(base_url().'/'); 
        }
		
    	$date = date('Y-m-d');
    	$data['upcomingevents'] = $this->event->getEvent('all', ['event'],['status' => ['1'], 'start_date' => $date], ['orderby' => 'e.id desc', 'limit' => '5', 'type' => '1']);         
    	$data['pastevents'] = $this->event->getEvent('all', ['event'],['status' => ['1'], 'end_date' => $date], ['orderby' => 'e.id desc', 'limit' => '5', 'type' => '1']);
        $data['banners'] = $this->cms->getCms('all', ['cms'], ['status' => ['1'], 'type' => ['3']]);
        $data['aboutus'] = $this->cms->getCms('all', ['cms'], ['status' => ['1'], 'type' => ['1']]);
        
     	return view('site/home/index', $data);
    }
    
}
