<?php

namespace App\Controllers\Api\Aboutus;

use App\Controllers\BaseController;

use App\Models\Cms;

class Index extends BaseController
{
    public function __construct()
    {
        $this->cms = new Cms;  
    }

    public function index()
    {
		$datas = $this->cms->getCms('all', ['cms'], ['status' => ['1'], 'type' => ['1']]);
		if(count($datas) > 0) {
		    $result=[];
			foreach($datas as $data){
				$result[] = [
				        'id'        => $data['id'],
						'title'     => $data['title'],
						'image'     => ($data['image']!='') ? base_url().'/assets/uploads/aboutus/'.'486x300_'.$data['image'] : '',
						'content'   => substr($data['content'], 0, 250),
						'read_more' => base_url().'/aboutus/view/'.$data['id'], 				
				];
			}			

            $json = ['1', count($datas).' Records Found.', $result];		 
			
		} else {
			
			$json = ['0', 'No Records Found.', []];		 
			
		}
		
        echo json_encode([
            'status'      => $json[0],
            'message'     => $json[1],
            'result'     => $json[2],
        ]);

        die;
    }

    	
	public function view() 
	{
		$post       = $this->request->getPost(); 
        $validation = \Config\Services::validation();

        $validation->setRules(
            [
                'aboutus_id'       => 'required',
            ],

            [
                'aboutus_id' => [
                    'required' => 'User id is required.',
                ]
            ]
        );

        if ($validation->withRequest($this->request)->run()) {

            $datas = $this->cms->getCms('row', ['cms'], ['id' => $post['aboutus_id'],'status' => ['1'], 'type' => ['1']]);
		
            if($datas) {
										
				$result[] = [
						'id'        => $datas['id'],
						'title'     => $datas['title'],
						'image'     => ($datas['image']!='') ? base_url().'/assets/uploads/aboutus/'.'486x300_'.$datas['image'] : '',
						'content'   => $datas['content'],
				];
						
				$json = ['1', '1 Record Found.', $result];		 
			
			} else {
				
				$json = ['0', 'No Records Found.', []];		 
				
			}			

		} else {
            $json = ['0', $validation->getErrors(), []];
        }

        echo json_encode([
            'status'         => $json[0],
            'message'       => $json[1],
            'result'         => $json[2],
        ]);

        die;			
	}
}
