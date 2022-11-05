<?php

namespace App\Controllers\Site\Logout;

use App\Controllers\BaseController;

class Index extends BaseController
{
	public function index()
	{
		$this->session->remove('sitesession');
		return redirect()->to(base_url());
	}
}
