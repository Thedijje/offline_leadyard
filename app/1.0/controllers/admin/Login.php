<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends Admin_Controller {

    function __construct(){
        parent::__construct();
        $this->load->helper('security');
        
    }

	public function index()
	{
        
        $data['title']  =   "Admin login";
       
        
		$this->_public_render('login/login', $data);
    }

}