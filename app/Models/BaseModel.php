<?php
namespace App\Models;

use Config\Custom;

class BaseModel 
{
    public function __construct()
    {
        $this->db = db_connect();
        $this->config = new Custom;
	}
}
