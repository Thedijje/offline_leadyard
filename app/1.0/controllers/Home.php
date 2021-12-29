<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Web_Controller {

	
	public function index()
	{
		$this->_render('home/index');
	}
}
