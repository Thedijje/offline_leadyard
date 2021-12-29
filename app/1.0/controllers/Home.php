<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Web_Controller {

	
	public function index()
	{
		echo $this->_render('home', array(), true);
	}
}
