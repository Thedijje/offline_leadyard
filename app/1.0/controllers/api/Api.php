<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include FCPATH.'vendor/autoload.php';

class Api extends Api_Controller {

	
	public function index_get()
	{
		$this->response(array('name'=>'Awesome developer'));
	}
}
