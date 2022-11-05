<?php 

namespace App\Controllers\Site\Cart;

use App\Controllers\BaseController;
use App\Models\Cart;

class Index extends BaseController
{
	public function __construct()
	{
		$this->cart = new Cart();
	}

    public function action(){ 
    	if($this->request->getMethod()=='post'){ 
			$userid 			= getSiteUserID();
			
    		$requestdata       		= $this->request->getPost();
			$requestdata['ip']  	= $this->request->getIPAddress();
			$requestdata['user_id'] = $userid ? $userid : 0;
			
			if(!isset($requestdata['cart'])){    			
    			if($requestdata['checked']==1){
	    			$requestdata['startdate'] 		= formatdate($requestdata['startdate']);
    				$requestdata['enddate'] 		= formatdate($requestdata['enddate']);
					
	               	$result = $this->cart->action($requestdata);  
	            }else{
	            	$result = $this->cart->delete($requestdata);
	            }
        	}

	        echo json_encode(getCart($requestdata['type']) ? getCart($requestdata['type']) : []);
        }
    }
}
