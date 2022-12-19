<?php

namespace App\Controllers\Api\Myaccount\Currentreservation;

use App\Controllers\BaseController;
use App\Models\Booking;

class Index extends BaseController
{

	public function __construct()
    {
    	$this->booking = new Booking();
    }

    public function index()
    {
    	$post = $this->request->getPost();
        $validation = \Config\Services::validation();
        $validation->setRules(
            [
                'userid'     => 'required',
            ],

            [
                'userid' => [
                    'required' => 'userid is required.',
                ],
            ]
        );

       // if ($validation->withRequest($this->request)->run()) {echo "Fasd";die;

        	$userdetail 	= getSiteUserDetails();
			$userid 		= ($userdetail['type']=='4' || $userdetail['type']=='6') ? $userdetail['parent_id'] : getSiteUserID();
			print_r($userid);die;
			$allids 		= getStallManagerIDS($userid);
			array_push($allids, $userid);
			$data['bookings'] = $this->booking->getBooking('all', ['booking', 'event', 'users', 'barnstall', 'rvbarnstall', 'feed', 'shaving', 'payment','paymentmethod'], ['userid'=> $allids, 'gtenddate'=> $date, 'start' => $offset, 'length' => $perpage], ['orderby' => 'b.id desc']);
		/*}else {
            $json = ['0', $validation->getErrors(), []];
        }*/
    }
}