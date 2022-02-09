<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends Admin_Controller {

    function __construct(){
        parent::__construct();
        $this->load->helper('security');
        
    }

	public function index()
	{
        
        $data['title']  =   "Welcome to dashboard";
       
        
		$this->_render('dashboard/dashboard', $data);
    }

}