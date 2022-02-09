<?php

include FCPATH.'vendor/autoload.php';




class Web_Controller extends CI_Controller{
    function __construct()
    {
        parent::__construct();

        $this->load->database();
       
        $this->_config  =   $this->lib->config_list();
        
    }

    private $_config;

    public $_user_config;

    private $front_view_path = 'front/';


    public function _render($view, $data=array(), $return=false)
    {
        $data['_config']        =   $this->_config;
        $data['_user_config']   =   $this->_user_config;

        if($return){
            return $this->load->view($this->front_view_path.$view, $data, true);
        }

        
        $data['heading']    =   (isset($data['heading']) AND $data['heading']!='') ?? ($this->uri->segment(2)=='index' OR $this->uri->segment(2)=='') ?  ucfirst($data['heading']) : ucfirst($this->uri->segment(1)).' '.ucfirst($this->uri->segment(2));
        $data['title']      =   (isset($data['title'])) ? $data['title'].' - '.$this->uri->segment(1) : $data['heading'].' - '.ucfirst($this->uri->segment(1));


        $this->load->view($this->front_view_path.'includes/header', $data);
        $this->load->view($this->front_view_path.$view, $data);
        $this->load->view($this->front_view_path.'includes/footer', $data);

    }



}



class Admin_Controller extends CI_Controller{
    function __construct()
    {
        parent::__construct();
        $this->load->database();

            
        $this->load->helper('my');
            
        
       
        $this->_config  =   $this->lib->config_list();

       //dd($this->session->all_userdata());

        
    }

    private $_config;

    public $_user_config;

    private $front_view_path = 'admin/';


    public function _render($view, $data=array(), $return=false)
    {
        return $this->_return_view($view, $data, $return);

    }


    public function _public_render($view='admin/public', $data=array(), $return=false)
    {
        $this->front_view_path  =   'admin/public/';

        return $this->_render($view, $data, $return);
    }


    private function _return_view($view, $data=array(), $return=false)
    {
        // Check login here


        $data['_config']        =   $this->_config;

        if($return){
            return $this->load->view($this->front_view_path.$view, $data, true);
        }

        $data['current_class']  =   $this->router->fetch_class();
        $data['current_method']  =   $this->router->fetch_method();
        
        $data['heading']    =   (isset($data['heading']) AND $data['heading']!='') ?? ($this->uri->segment(2)=='index' OR $this->uri->segment(2)=='') ?  ucfirst($data['heading']) : ucfirst($this->uri->segment(1)).' '.ucfirst($this->uri->segment(2));
        $data['title']      =   (isset($data['title'])) ? $data['title'].' - '.$this->uri->segment(1) : $data['heading'].' - '.ucfirst($this->uri->segment(1));

       
        $this->load->view($this->front_view_path.'includes/header', $data);
        $this->load->view($this->front_view_path.$view, $data);
        $this->load->view($this->front_view_path.'includes/footer', $data);
    }



}





class Cli_Controller extends CI_Controller{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->check_cli();
    }


    /*
    *   Function will check if cli version is not called in production version without cli mode
    */
    private function check_cli()
    {
        if(ENV=='production'){
            if(!$this->input->is_cli_request()){
                show_404();
            }
        }
    }




}