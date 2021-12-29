<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 
 * Naming convention for funtions
 * function a_b_c
 * a = action = (new/old/delete/accept/decline/update)
 * b = entity = (offer/order/password/setting/post)
 * c = reciever = (user/admin/owner)
 */

class Mail_model extends CI_Model {

    

    
    /**
     * The view path of all mails in mail_model
     *
     * @const string
     */
    const   MAIL_VIEW = "email";
    const   DEBUG_MAIL_VIEW = "email"; // can be in a different directory while testing/development environment is turned on
    // const   DEBUG_MAIL_VIEW = "email".DIRECTORY_SEPARATOR."v2.0";
    
    
    /**
     * Active Status
     * ## Value 1
     *
     * @var integer
     */
    private $_active_status     =    1;
    
    /**
     * From which email the mail is going
     *
     * @var string
     */
    private $_mail_from         = "";
    
    /**
     * to whom the mail is going
     *
     * @var string
     */
    private $_mail_to              = "";

    /**
     * site_name which shows from where the mail came
     *
     * @var string
     */
    private $_site_name         = "";

    /**
     * Subject of the mail
     *
     * @var string
     */
    private $_subject              = "";

    /**
     * The data to be sent in Mail template
     *
     * @var array
     */
    private $_data              = array();
    
    /**
     * Variable to be set if documents are available
     *
     * @var mixed
     */
    private $_documents              = NULL;
    

    /**
     * Mail View template which contains all the message
     *
     * @var string
     */
    private $_view              = "";


    /**
     * Set this value TRUE to enable debugging
     *
     * @var boolean
     */
    
    private $_debug              = FALSE;

    /**
     * Set this value TRUE to enable debugging
     *
     * @var boolean
     */
    private $_hard_debug           = FALSE;

    /**
     * This value is being set in Testing Controller for manual testing of emails in admin panel
     *
     * @var boolean
     */
    
    public $_testing              = FALSE;

    /**
     * Set this value according to your email id to test debugging in mail
     *
     * @var string
     */
    
    // private $_debug_mail_id          = "hello@example.com";
     private $_debug_mail_id              = "hello@example.com";
    //private $_debug_mail_id          = "hello@example.com";

    
    /**
     * DB Table settings being used for values being taken from `users` table
     */
    private $_user_table        =    'users';
    private $_user_id_column        =    'user_id';
    private $_user_email_column    =    'user_email';


    /**
     * DB Table settings being used for values being taken from `admin` table while sending mail to admin
     */
    private $_admin_email        =    '';
    private $_admin_table        =    'admin';
    private $_admin_id_column    =    'id';
    private $_admin_email_column    =    'email';


    /**
     * Constructor for the Mail Model
     *
     * @access public
     * @param void
     * @return void
     */
    function __construct(){
        parent::__construct();

        
        
        //$this->_settings    =    $this->lib->config_list();
        $this->_settings    =    array(
            'email'     =>  'hello@example.com',
            'sitename'  =>  'Reevalo'
        );

        //load the language file in the variable lang
        // $this->lang->load('vsm/vsm_mail');
        // $this->mail_lang = $this->lang->line('mail');
        
        //initialise the private_variables
        //mail from by default value = no-reply@reevalo.com
        $this->_mail_from   =    $this->_settings['verification_mail'] ?? "notifications@example.com";

        //mail from by default value = albert@reevalo.com
        // $this->_mail_from   = $this->lib->get_settings('verification_mail');
        
        //by default value = dheeraj@reevalo.com(staging)
        //by default value = dheeraj@reevalo.com(staging)
        $this->_settings['email'];
        $this->_mail_to     =    $this->_settings['email'];
        $this->_admin_email =    $this->_settings['email'];
        
        //by default value = reevalo
        $this->_site_name   =    $this->_settings['sitename'];
        $this->_subject     =    "Important: Mail from ".$this->_site_name;

        //by default template for view
        $this->_view        =    "email/default_mail_template";
        
        if(ENV == 'production'){
            $this->_hard_debug    =    FALSE;
            $this->_debug        =    FALSE;
        }

    }



    /**
     * Send Function takes the private variables already set and send the email
     *
     * @access private
     * @param void
     * @return true
     */
    private function _send(){

         //dd($this->_view); 

        $mdata['name']        =    $this->_site_name;
        $mdata['from']        =    $this->_mail_from;
        $mdata['to']        =    $this->_mail_to;
        //dd($this->_view);
        //dd($this->_data); die;
       
        //die;
        $this->_data['config']          =   $this->_settings;
        $mdata['message']    =   $this->load->view( $this->_view, $this->_data, TRUE);
        $mdata['subject']   =   $this->_subject;


        //strictly for debugging purposes
        if(ENV != 'production'){
            if($this->_debug!=false){
                
                $mdata['to']            =    $this->_debug_mail_id;
                if(admin_user('email')){
                    $mdata['to']            =    admin_user('email');
                }
                
                $mdata['subject']        =    "Test Mail: ".$this->_subject;
            }

            if($this->_hard_debug){
                dd($this->_data,TRUE);
                echo $mdata['message'];
                dd($mdata['subject'] );                
            }
        }
        
        if($this->_testing){
            echo '<div class="col-md-12">';
            echo "<br>Subject: ".$mdata['subject'];
            echo "<hr>Message:<br>";
            echo $mdata['message'];
            echo '</div>';
            exit();
        }

        $email_send            =    $this->lib->send_formatted_mail($mdata, $this->_documents);
        return true;

    }
    

    /**
     * function to call view inside each function, the name of view will be same as the function name itself.
     *
     * @access private
     * @param string optional
     * @return void
     */
    private function _view($custom_view_template = NULL){
        // dd($custom_view_template);
        $view_path = self::MAIL_VIEW;
        if(ENV == 'development'){
            $view_path = $this->_testing ? self::DEBUG_MAIL_VIEW : self::MAIL_VIEW;
        }

        if($custom_view_template === NULL)
            $this->_view    =   $view_path.DIRECTORY_SEPARATOR.debug_backtrace()[1]['function'];
        else
            $this->_view    =   $view_path.DIRECTORY_SEPARATOR.$custom_view_template;
    }


    /**
     * function to set email id from user_id
     *
     * @access private
     * @param integer required user id of the email to set to
     * @return void
     */
    private function _to_user($email_address){
        if(!$email_address){
            return false;
        }

        $this->_mail_to     =     $email_address;
    }
    




    public function test_email()
    {

        $this->_view();

        $this->_data        =   array();
       
        $this->_subject                =    "This is custom test".time();
        $this->_data['config']          =   $this->_settings;
        $this->_mail_to                =    "hello@example.com";
        
        return $this->_send();
        
    }

    /**
     * 
     * Mail sent to user on signup
     * @param user_id string
     * @return bool 
     * 
     * */
    public function user_signup($user_id)
    {
        $userInfo   =   $this->user->detail($user_id);

        if(!$userInfo){
            return false;
        }

        $this->_data['user_info']   =   $userInfo;
        $this->_subject             =   "Welcome to Reevalo ".$userInfo->first_name;
        $this->_mail_to             =   $userInfo->email_id;
        $this->_view();
        return $this->_send();

    }


    public function change_password($user_id)
    {
        $userInfo   =   $this->user->detail($user_id);

        if(!$userInfo){
            return false;
        }

        $this->_data['user_info']   =   $userInfo;
        $this->_subject             =   "Your password changed successfully";
        $this->_mail_to             =   $userInfo->email_id;
        $this->_view();
        return $this->_send();
    }


    public function email_verification($user_id, $verificatin_link)
    {
        $userInfo   =   $this->user->detail($user_id);

        if(!$userInfo){
            return false;
        }


        $this->_data['user_info']   =   $userInfo;
        $this->_data['verificatin_link']  =   $verificatin_link;
        $this->_subject             =   "Verify your email address: ".$this->_settings['sitename'];
        $this->_mail_to             =   $userInfo->email_id;
        $this->_view();
        return $this->_send();
    }


    public function reset_password($user_id, $reset_link)
    {
        $userInfo   =   $this->user->detail($user_id);

        if(!$userInfo){
            return false;
        }


        $this->_data['user_info']   =   $userInfo;
        $this->_data['reset_link']  =   $reset_link;
        $this->_subject             =   "Reset password instruction: ".$this->_settings['sitename'];
        $this->_mail_to             =   $userInfo->email_id;
        $this->_view();
        return $this->_send();
    }

}