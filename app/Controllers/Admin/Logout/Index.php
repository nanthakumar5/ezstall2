<?php

namespace App\Controllers\Admin\Logout;

use App\Controllers\BaseController;

class Index extends BaseController
{
	public function index()
	{
		$this->session->remove('adminsession');
		return redirect()->to(getAdminUrl());
	}
}
