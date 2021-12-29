<?php

include FCPATH.'vendor/autoload.php';

class Api_Controller extends REST_Controller
{

    /**
     * ### holds the 'login' key from 'login_lang'
     */
    protected $_lang;

    /**
     * #### default: Some error occured, please try again later
     * @var string
     */
    protected $_error;

    /**
     * #### default: Request Completed Successfully
     * @var string
     */
    protected $_message;

    /**
     * #### blank array to store response data
     * @var mixed
     */
    protected $_data;

    /**
     * ### set value as 'logout' to logout any user in a case
     * @var string
     */
    protected $_logout = "";

    /**
     * ### this array id used to set user limit to default use all aspects of application
     * @var string
     */
    protected $_user_limit = array();

    /**
     * ---IMPORTANT! DO NOT CHANGE VALUE UNTIL NECESSARY---
     * ### by default set to false, will dd(response_data) before sending
     * @var boolean
     */
    protected $_hard_debug = FALSE;


    /**
     * ### default: 200
     * @var integer
     */
    private $_http_ok = REST_Controller::HTTP_OK;

    /**
     * ### default: 200
     * @var integer
     */
    private $_http_error = REST_Controller::HTTP_OK;
    // private $_http_error = REST_Controller::HTTP_INTERNAL_SERVER_ERROR;


    /**
     * Constructor to initialise all values
     */
    function __construct()
    {
        parent::__construct();
        
        $this->load->database();
        //to access lang() function 
        // $this->load->helper('language');
        $this->load->model(array(
            'Helper_model'            =>    'lib',
            
        ));
        $this->_error       =       "Some error occured, please try again later";
        $this->_message     =       "Request Completed Successfully";
        $this->_data        =       array();
        $this->_settings    =       $this->lib->config_list();
        $this->_user_limit  =       array('status' => false, 'message' => 'This section is available for public use.');

        $this->_lang        =    array();
        
    }





    /**
     * #### _bye(param = false) to invoke successful response of API
     * Note: if param TRUE, Will dd(response_data)
     * @param boolean to enable debug mode
     * 
     *  */
    protected function _out()
    {

        //----------------------------DONT CHANGE ANYTHING BELOW --------------------
        //SUCCESS RETURNS FOR API

        
        $this->_lang = empty($this->_lang) ? new stdClass : $this->_lang;
        // (object)
        $response_data        =    array(
            'language'   =>    $this->_lang,
            'message'    =>    $this->_message,
            'data'       =>    $this->_data,
            'response'   =>    "",
            'status'     =>    'success',
            'time'       =>    time()
        );

        $this->response($response_data, $this->_http_ok);
    }

    /**
     * 
     * #### _error_occured(param = false) to invoke in case of invalid/error response of API
     * Note: if param TRUE, Will dd(response_data)
     * @param boolean to enable debug mode, will dd(response_data)
     * 
     *  */
    protected function _error_occured($message_in_data = TRUE, $debug = FALSE)
    {
        (array) $this->_data;
        if ($message_in_data === TRUE) {
            $this->_data = array_merge($this->_data, array('message' => $this->_error));
        }

        $this->_lang = array();

        //----------------------------DONT CHANGE ANYTHING BELOW --------------------
        //WORKS WHEN ERROR OCCURS

        $response_data        =    array(
            'language'    =>    $this->_lang,
            'message'    =>    $this->_error,
            'data'        =>    $this->_data,
            'response'    =>    "",
            'status'    =>    "error",
            'time'        =>    time()
        );

        if ($this->_hard_debug === TRUE) {
            //    ********** WARNING! ********* HARD DEBUGGING ENABLED
            dd($response_data);
        } else if ($debug === TRUE) {
            log_message('error', implode($response_data));
            dd($response_data);
        }
        // $this->_profiler_on();
        $this->response($response_data, $this->_http_error);
    }

    /**
     * Sweet little function to return language stored in core/Api_Controller
     * @param boolean to change loaded language file
     * @param boolean to enable debug mode
     */
    public function _load_lang($extended_class = FALSE, $debug = FALSE)
    {

        if ($extended_class === FALSE || $extended_class == "") {
            $extended_class  = $this->router->fetch_class();
        }

        $this->load->language(strtolower($extended_class));

        if ($debug === TRUE) {
            echo "The class loaded is " . $extended_class . ".\n";
            dd($this->lang->line(strtolower($extended_class)));
        }

        $this->_lang     =    $this->lang->line(strtolower($extended_class));
    }


    public function _logout_user()
    {

        $response_data        =    array(
            'language'    =>    $this->_lang,
            'message'    =>    $this->_error,
            'data'        =>    $this->_data,
            'response'    =>    "logout",
            'status'    =>    "error",
            'time'        =>    time()
        );
        $this->response($response_data);
    }

    
}



class Web_Controller extends CI_Controller{
    function __construct()
    {
        parent::__construct();

        $this->load->database();
        
        $this->load->model(
            array(
                'login_model'=>'login'
                )
            );
            
        $this->_user_config =   $this->settings->get_settings();
       
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

        
        $this->load->model(
            array(
                'login_model'=>'login'
                )
            );
            
        $this->login->check_admin_login();
            
        $this->_user_config =   $this->settings->get_settings();
       
        $this->_config  =   $this->lib->config_list();

       //dd($this->session->all_userdata());

        
    }

    private $_config;

    public $_user_config;

    private $front_view_path = 'admin/';


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