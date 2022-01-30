<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends Web_Controller {

	
	public function index()
	{
		echo $this->_render('home', array(), true);
	}


	public function qr()
	{
		$this->load->library('qr');
		$text = $_GET['text'] ?? "Hi, QR code is awesome";
		$this->qr->generate($text);
	}
}
