<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {

    private $admin_table_name     =   "admin";

    /**
     * Constructor function
     */
    function __construct(){
        parent::__construct();
        
    }


    public function validate($data,$fields){
        
        foreach($fields as $key=>$value):
        if(!empty($data[$value])){
            
            $members['member_'.$value]=$data[$value];
            
        }else{
            $this->lib->redirect_msg(ucfirst($value).' should not be empty','warning','signup');
        }
        
        endforeach;
        return $members;
    }



    public function login_validate($data){
        if($data['email']=='' OR $data['password']==''){
            return false;    
        }
        $data['status']    =    1;
        
        $ins['email'] =$data['email'];
        $ins['status']=$data['status'];
        $check        =    $this->lib->get_row_array($this->admin_table_name,array('email'=>$data['email'],'status'=>$data['status']));
        
        $verify_password = password_verify($data['password'],$check->password);
        if($verify_password){
            $admin     =    $check;
            $sess    =    array(
                'id'            =>    $admin->id,
                'name'          =>    $admin->name,
                'email'         =>    $admin->email,
                'role'          =>    $admin->role,
                'is_login'      =>    TRUE,
                'admin_timezone'=>    $data['user_timezone'],
                'last_login'    =>    time()
            );
        
        $this->session->set_userdata('admin_user',$sess);
        $this->lib->update($this->admin_table_name ,array('last_login'=>time()),'id',$admin->id);
        return TRUE;    
            
        }else{
        return FALSE;
        }
        
    }
    
    public function check_admin_login(){
        
        $adm_data    =      $this->session->userdata('admin_user');
        $ajax        =      $this->input->is_ajax_request();
        
        if(!isset($adm_data) OR !$adm_data['is_login'] OR !$adm_data['email']){
            if($ajax){
                $this->lib->display_alert('Your session expired, redirecting you to login page! Please wait...','danger','warning');
                ?>
                <meta http-equiv="refresh" content="3;url=<?php echo base_url('login?redirect='.uri_string());?>">
                <?php
                exit();
            }


            $this->lib->redirect_msg('To access this page, you need to login first!','danger','login?redirect='.uri_string());

        }
        $u_timezone     =    $_SESSION['admin']['admin_timezone'] ?? '';
        if($u_timezone!=''){
            date_default_timezone_set($u_timezone);
        }
        $this->lib->update($this->admin_table_name, array('admin_last_activity'=>time()),'email',$adm_data['email']);
        
    }

    

    public function token_validate(string $token)
    {
        if($token==''){
            return false;
        }



        $token_decode	=	base64_decode($token);
		$token_keys 	=	explode('--',$token_decode);
		$request_time 	=	$token_keys['0'];
		$user_email 	=	base64_decode($token_keys['1']);
		$plain_otp 		=	base64_decode($token_keys['2']);	
        


        $current_time 	= 	time();
		
		
		if(($current_time-$request_time)>7200){
			$this->lib->redirect_msg('Password Reset Time Expired','danger','login');
        }

        
        //validate token
		$token_validate = 	$this->lib->get_row_array($this->admin_table_name,array('email'=>$user_email,'password_token'=>$reset_token));
		
		if(!$token_validate){
            return false;
        }


        return $token_validate;
    }


    public function update_password(array $data)
    {
        if(empty($data)){
            return false;
        }


        $new_password 			=	password_hash($data['new_password'],PASSWORD_DEFAULT);
    
        $input 	=	array('password'=>$new_password,'password_token'=>'');



        $update = $this->lib->update($this->admin_table_name,$input,'id',$user_id);
        

		if(!$update){
			return false;
		}
    
    }

}