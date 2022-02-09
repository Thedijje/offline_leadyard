<?php 
// require 
defined('BASEPATH') OR exit('No direct script access allowed');
Class Login extends Api_Controller{


	/**
	 * Constructor to initialise all values
	 */
	function __construct(){

		parent::__construct();

		
		$this->db->cache_delete('APIv30', 'login');
		
	}
	
	
	// -------------------------------------Main functions start------------------------
	
	public function index_get(){
		
		$this->_load_lang();
		//	Dynamic Images and logos
		$this->_lang['onboard']['Logo']	=	base_url(APP_SPLASH_LOGO);
		// $this->_lang['onboard']['Logo']	=	base_url($this->_settings['app_splash']);
		$this->_lang['onboard']['MobiHub_Logo']	=	base_url($this->_settings['logo-wide']);

		$this->_message = "Language and images";
		// $this->output->enable_profiler(true);
		//$this->output->enable_profiler(TRUE);
		$this->_bye();

	}

	public function validate_post(){


		// $this->_load_lang();
		
		//	Dynamic Images and logos
		// $this->_lang	=	$this->_lang['after_login'];

		$data 		=	$this->input->post();
		$data 		=	$this->security->xss_clean($data);

		if(!$data){
			$this->_error = 'Invalid request';
			$this->_error_occured();
		}
		$username = trim($data['username']);
		$password = trim($data['password']);
		if(!isset($username) OR !isset($password)){
			$this->_error = 'Email or password not set';
			$this->_error_occured();
		}

		if($username=='' OR $password==''){
			$this->_error = 'Email or password can not be empty';
			$this->_error_occured();
		}

		$check_login	=	$this->login->check_api_login($data);
		
		if(!$check_login){
			$this->login->failed_attempt($username);
			$this->_error = 'Username/Password incorrect, Please try again';
			$this->_error_occured();
		}

		if($check_login->user_status	==	8){
			$this->_error = 'Your account is in compliance check status, you will be able to login once account gets activated';
			$this->_error_occured();
		}

		// $this->load->model('membership_model', 'membership');
		// $requested_plan_row	=	$this->membership->upgrade_request($check_login->user_id);
		
		if($check_login->user_is_verified == 0){
			
			$this->_error = 'Please verify your email id before logging into your account or Contact Admin';
			$this->_error_occured();

			// if(!$requested_plan_row){
			// 	$request_id 	=	$this->membership_model->new_upgrade_request($user_id,$requested_plan);

			// 	log_message('error', __METHOD__.": User Upgrade Plan Request not found for user #".$check_login->user_id);
			// 	$this->_error = 'There seems to be some problem with your account, please contact Admin';
			// 	$this->_error_occured();
			// }else if($check_login->user_is_verified	==	0 && $requested_plan_row->ur_plan_id == 3){
			// }
			
		}

		$user_id 		=	$check_login->user_id;
		$user_email 	=	$check_login->user_email;
		$last_password	=	$check_login->last_password_update;
		$user_status	=	1;	// All set, good to go

		$this->load->model('payment_model','payment');

		if(	$check_login->user_status==3 ){
			$check_payment	=	$this->payment->check_user_payment($check_login->user_id);
			if(!$check_payment){
				$user_status 	=	3;
			}
		}

		$is_vsm 		=	$this->users->is_vsm($user_id) ? 1 : 0; 
		$get_token		=	$this->login->create_api_token($user_id, $user_email, $last_password, $is_vsm);

		$user_info	=	array(
			'user_id'			=>	base64_encode($check_login->user_id),
			'user_name' 		=>	$check_login->user_first_name.' '.$check_login->user_last_name,
			'user_email'		=>	$check_login->user_email,
			'user_company'		=>	$check_login->user_company,
			'user_country_id'	=>	base64_encode($check_login->user_country),
			'user_city_id'		=>	$check_login->user_city ?? 0,
			'user_company_id'	=>	base64_encode($check_login->company_id),
			'user_status'		=>	$check_login->user_status,
			'user_dp'			=>	base_url($check_login->user_display_pic),
			'user_mobile'		=>	$check_login->user_mobile,
			'last_active'		=>	(int)$check_login->user_last_active ?? (int)0,
			'user_membership'	=>	$check_login->user_membership,
		);

		// $this->load->model('vsm/vsm_user_model','vsm_user');
		$user_info['is_vsm'] = $is_vsm ? true : false;

		if(isset($data['device_token'])){
			$device_type 	=	$data['device_type'];
			$device_token 	=	$data['device_token'];
			$timezone 		=	$data['user_timezone'] ?? '';

			$this->lib->update('users',array('user_device_type'=>$device_type,'user_notifcation_token'=>$device_token,'user_timezone'=>$timezone),'user_id',$user_id);
			$device_info		=	array(
				'ud_user_id'	=>	$user_id,
				'ud_device_type'=>	$data['device_type'],
				'ud_notif_token'=>	$data['device_token'],
				'ud_device_name'=>	$data['device_name'] ?? 'Unknown',
				'ud_device_os'	=>	$data['device_os'] ?? $data['device_type'],
				'ud_status'		=>	1,
				'ud_registered_at'	=>	time()
			);
			$this->login->save_device($device_info);
			$this->session->set_userdata('user_timezone',$timezone);
		}

		$new_user 	=	new stdClass;
		$new_user->by_admin		=	FALSE;
		$new_user->default_dp	=	FALSE;
		$new_user->no_following	=	FALSE;
		$new_user->company_edit	=	FALSE;

		
		if($check_login->user_created_by!=0 AND $check_login->last_password_update==''){
			
			// Created by admin and password not updated
			$new_user->by_admin = TRUE;	
		}

		$user_default_dp 	=	$this->_settings['user_default_avatar'];
		if($check_login->user_display_pic == $user_default_dp){

			//	User dp is not there, user will be redirected to change dp
			$new_user->default_dp = TRUE;
		}

		$this->load->model('follow_model');
		$following	=	$this->follow_model->get_following($check_login->user_id);
		if(empty($following)){

			//	User is not following anyone, sending to groups
			$new_user->no_following = TRUE;
		}

		if($new_user->no_following==FALSE AND $new_user->default_dp AND $new_user->by_admin==TRUE){
			$new_user 	=	new stdClass;
		}


		$company_info 	=	$this->lib->get_row_array('company',array('company_id'=>$check_login->company_id));

		if(!$company_info || $company_info->company_address=='' || $company_info->company_zip=='' || $company_info->company_city=='' || $company_info->company_state=='' || $company_info->company_country=='' || $company_info->company_logo=='' || $company_info->company_logo=='static/images/placeholder/company.png'){
			$new_user->company_edit	=	TRUE;
		}

		// membership expired on
		$this->load->model('membership_model' 	,'membership');
		$membership 		=	$this->membership->is_expiring($user_id);
		$expiring_in_days	=	15;
		if($membership){
			$expiring_in 		=	$membership->mu_expire_on - time();
			$expiring_in_days	=	$expiring_in/(3600*24);
		}

		$this->login->record_login($user_id,1,true,0);

		$this->_message 	=	'Login Successful';
		$this->_data 		=	array(	
			'message'		=>	$this->_message,
			'auth_token'	=>	$get_token,
			'user_info'		=>	$user_info,
			'new_user'		=>	$new_user,
			'user_status'	=>	(int)$user_status,
			'expiring_in'	=>	$expiring_in_days
		);
		$this->_bye();
	}


	// ----------------deprecated------------------------------
	// public function test_login_get($user_id=1){
	// 		$this->login->del_oldest_device($user_id);
	// }


	// ----------------deprecated------------------------------
	// public function logout_get(){
	// 	$this->session->unset_userdata('app_user');
	// 	unset($_SESSION['app_user']);
		
	// 	$this->_message 	=	'Successfully Logged out';
	// 	// $this->_data 		=	array(
	// 	// 	'message'		=>	$this->_message,
	// 	// 	'auth_token'	=>	""
	// 	// );
	// 	$this->_bye();
	// }

	public function token_expire_get(){
		return $this->login->token_expire();
	}


	// ----------------deprecated------------------------------
	// public function upgrade_banner_get($user_id){
	// 	$this->load->model('membership_model');
	// 	$banners 	=	$this->membership_model->upgrade_banner_mobile(base64_decode($user_id));

	// 	$this->_message		=	"Ads";
	// 	$this->_data		=	$banners;
	// 	$this->_bye();
	// }

	public function forget_password_post(){
		$this->load->helper('string_helper');
		$data		=	$this->input->post();

		if(!$data OR $data['email']==''){
			$this->_error = 'Invalid request';
			$this->_error_occured();
		}
		$email_or_username	=	$data['email'];
		$check_email		=	$this->lib->get_row_array('users',array('user_email'=>$email_or_username,'user_status'=>1));
		

		$check_user			=	$check_email;
		if(!$check_email){

			//	check if user sent username
			$check_username		=	$this->lib->get_row_array('users',array('username'=>$email_or_username,'user_status'=>1));
			if(!$check_username){
				log_message('error','User tried to request  forget password with wrong email/username # '.$email_or_username.' From IP '.$this->input->ip_address());
				$this->_error = 'Please check your registered email for password reset instruction.';
				$this->_error_occured();
			}

			$check_user	=	$check_username;
			
			
		}


		$token_plain 	=	random_string('nozero',6);
		$reset_token	=	base64_encode($token_plain);
		$email 			=	base64_encode($check_user->user_email);
		$pass_token		=	base64_encode(time().'--'.$email.'--'.$reset_token);
		$save_token	 	=	$this->lib->update('users',array('pass_reset_token'=>$pass_token),'user_email',$email_or_username);
		$this->session->set_userdata('app_pass_token',$token_plain);

		$reset_email 	=	$this->login->forget_password_email($check_user,$token_plain,$pass_token);

		$message			=	"Hello ".$check_user->username.", OTP to reset your Mobi-Hub password is ".$token_plain.". Please do not share with anyone.";

		$this->notification->send_sms($check_user->user_mobile, $message);

		log_message('error','Message and mail sent to user # '.$check_user->username.' through ### API ### to reset password with token '.$token_plain);

		if(!$save_token){
			$this->_error = 'Unable to initiate reset request due to server issue, please try again soon';
			$this->_error_occured();
			// $this->_error_occured();
		}

		$this->_message		=	'Please check your email for further password reset instruction';
		$this->_data 		=	array('user_id'=>base64_encode($check_user->user_id));
		$this->_bye();
	}

	public function reset_password_post(){
		
		$data 			=	$this->input->post();
		if(!$data OR $data['user_id']=='' OR $data['new_password']=='' OR $data['otp']==''){
			$this->_error = 'Empty userid, password or otp';
			$this->_error_occured();
		}
		$current_user 	=	base64_decode($data['user_id']);
		$otp			=	base64_decode($data['otp']);
		$user_info 		=	$this->lib->get_row_array('users',array('user_id'=>$current_user,'user_status'=>1,'pass_reset_token!='=>''));
		$token_array 	=	explode('--',base64_decode($user_info->pass_reset_token));
		$token_plain 	=	base64_decode($token_array[2]);
		
		//log_message('error','Sess token '.$token_plain);
		if($otp!=$token_plain){
			log_message('error','OTP not matched for User ID#'.$user_info->username.' OTP supplied # '.$otp.' Actual OTP #'.$token_plain);
			$this->_error = 'OTP did not matched';
			$this->_error_occured();
		}

		$password 		=	password_hash(base64_decode($data['new_password']),PASSWORD_DEFAULT);

		$update_pass 	=	$this->lib->update('users',array('user_password'=>$password,'last_password_update'=>time()),'user_id',$current_user);
		if(!$update_pass){
			$this->_error = 'Unable to update password at moment';
			$this->_error_occured();
		}

		$this->_message		=	'Password successfuly Updated';
		$message			=	"Hello ".$user_info->user_first_name.". You have successfully reset your password. If it's not done by you, please contact us and let us know immediately";
		$this->notification->send_sms($user_info->user_mobile, $message);
		// $this->_data 		=	array('message'=> $this->_message);
		$this->_bye();
		
	}

	public function not_found_get(){
		$this->_error = 'Invalid/unknown request';
		$this->_error_occured();
	}

	public function not_found(){
		$this->_error = 'Invalid/unknown request';
		$this->_error_occured();
	}

	
}