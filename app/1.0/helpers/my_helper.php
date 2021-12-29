<?php 
	function json_response($status='',$type='',$msg=''){
		$response	=	array(
			'status'	=>	$status,
			'type'		=>	$type,
			'msg'		=>	$msg,
		);

		return json_encode($response);
	}

	function debug_log($msg){
		$enable=TRUE;
		if($enable==TRUE){
			log_message('error',$msg);
		}
	}
	function dd($var,$no_exit=false){
		echo "<pre>";
		print_r($var);
		echo "</pre>";
		if($no_exit==false){
			exit();
		}
	}

	function check_website($url){
		if (!preg_match("/^((https?|ftp|smtp):\/\/)?(www.)?[a-z0-9-]+(\.[-a-z]{2,}){1,3}(\?[a-zA-Z0-9-_]+=[a-zA-Z0-9-%]+&?)?$/", $url)) {
			return false;
		}
		return true;
	}

	function get_file_ext($filename){
		$ftype	=	explode('.', $filename);
		$last_n =	count($ftype) - 1;
		return $ftype[$last_n];
	}
	
	function dmy_to_unix($dmy_date){
		if(!$dmy_date){
		return false;	
		}
		$ex_date	=	explode('-',$dmy_date);
		$date		=	$ex_date[0];
		$month		=	$ex_date[1];
		$yr			=	'20'.$ex_date[2];
		return strtotime($yr.'/'.$month.'/'.$date);
	}
	
	function format_status($status){
		if(!$status){
			return 'light';
		}
		if($status==1 OR $status==8 OR $status==10 OR $status== 14 OR $status==15){
			return 'success';
		}elseif($status==11){
			return 'default';
		}elseif($status==3 OR $status==6 OR $status==7){
			return 'warning';
		}elseif($status==2 OR $status==4 OR $status==5 OR $status==9 OR $status==13){
			return 'danger';
		}else{
			return 'info';
		}

		
	}

	/**
	 *	vsm_app_user 
	 *	## This function gives details saved for VSM user for APIs
	 *	
	 *	@param key (default : null) 
	 *
	 * 
	 *	- 	user_id
	 *	- 	user_first_name
	 *	-	user_last_name
	 *	-	username
	 *	-	user_email
	 *	-	user_dp
	 *	-	user_status
	 *	-	password_update
	 *	-	user_membership
	 *	-	user_is_vsm
	 *
	 * */
	function vsm_app_user($key=NULL){
		if(app_user('user_is_vsm')!=1){
			return false;
		}

		return app_user($key);
	}

	function app_user($key=NULL){
		
		//user_id, user_email, user_name, user_status
		if(isset($_SESSION['app_user']) AND $_SESSION['app_user']!=''){
			if($key==NULL){
				return $_SESSION['app_user'];
			}
			if(array_key_exists($key, $_SESSION['app_user'])){
				return $_SESSION['app_user'][$key];
			}
		}
		return false;
	}



	function formatted_text($text)
	{

		$text	=	strtolower($text);

		if($text=='stopped'){
			$color 	=	"red";
		}

		if($text=='critical'){
			$color 	=	"orange";
		}

		if($text=='ok'){
			$color 	=	"green";
			
		}

		return "<span style='color:".$color."'>".ucfirst($text)."</span>";
	}


	function status_class($status)
	{
		$status	=	strtolower($status);
		$color 	=	"info";


		if($status=='stopped'){
			$color 	=	"danger";
		}

		if($status=='critical'){
			$color 	=	"warning";
		}

		if($status=='ok'){
			$color 	=	"success";
			
		}

		return $color;
	}


	function status_icon($status)
	{
		$status	=	strtolower($status);
		$color 	=	"info-circle";


		if($status=='stopped'){
			$color 	=	"times-circle";
		}

		if($status=='critical'){
			$color 	=	"warning";
		}

		if($status=='ok'){
			$color 	=	"check-circle";
			
		}

		return $color;
	}

	
	
	function country_flag($country_name){
		$flag_path_jpg 	=	'static/images/flags/flag-of-'.str_replace(' ', '-', $country_name).'.jpg';
		$flag_path_png 	=	'static/images/flags/flag-of-'.str_replace(' ', '-', $country_name).'.png';
		if(!file_exists($flag_path_png)){

			if(!file_exists($flag_path_jpg)){
				$flag_path = base_url("static/images/flags/glob.png");
			}else{
				$flag_path = base_url($flag_path_jpg);
			}
		}else{
			$flag_path 	=	base_url($flag_path_png);
		}
		return $flag_path;
	}





	function admin_user($key=NULL){
		//id,name,email, is_login, last_login
		if(isset($_SESSION['admin']) AND $_SESSION['admin']!=''){
			if($key==NULL){
				return $_SESSION['admin'];
			}
			if(array_key_exists($key, $_SESSION['admin'])){
				return $_SESSION['admin'][$key];
			}
		}
		return false;
	}

	/**
	 *	vsm_user 
	 *	## This function gives details saved for VSM user 
	 *	
	 *	@param key (default : null) 
	 *
	 * 
	 *	- 	user_id
	 *	- 	user_first_name
	 *	-	user_last_name
	 *	-	username
	 *	-	user_email
	 *	-	user_dp
	 *	-	user_status
	 *	-	password_update
	 *	-	user_membership
	 *	-	user_is_vsm
	 *
	 * */
	function vsm_user($key=NULL){
		if(webapp_user('user_is_vsm')!=1){
			return false;
		}

		return webapp_user($key);
	}

	function webapp_user($key=NULL){
		//user_id, user_fisrt_name, user_last_name, username, user_email, user_status, user_membership
		if(isset($_SESSION['webapp_user']) AND $_SESSION['webapp_user']!=''){
			if($key==NULL){
				return $_SESSION['webapp_user'];
			}
			if(array_key_exists($key, $_SESSION['webapp_user'])){
				return $_SESSION['webapp_user'][$key];
			}
		}
		return false;
	}

	function image_info($path,$type='h'){
		if(!file_exists($path)){
			return "";
		}

		$size	= 	getimagesize($path);
		if($type=='h'){
			return $size[1];
		}else{
			return $size[0];
		}


	}

	function build_query_string($data){
		$params = $data;

		$paramsJoined = array();

		foreach($params as $param => $value) {
		   $paramsJoined[] = "$param=$value";
		}

		return $query = implode('&', $paramsJoined);
	}

	function active_class($url='/'){
	}

	function validate_username($str) {
	}

	function get_server_cpu_usage(){
		$load[0] 	=	1;
		if(function_exists('sys_getloadavg')){
			$load = sys_getloadavg();
		}
		return $load[0];
 
	}

	function get_server_memory_usage(){
		$free = shell_exec('free');
		$free = (string)trim($free);
		$free_arr = explode("\n", $free);
		if($free_arr[0] == "");
			return 1;
		$mem = explode(" ", $free_arr[1]);
		$mem = array_filter($mem);
		$mem = array_merge($mem);
		$memory_usage = $mem[2]/$mem[1]*100;
		return $memory_usage;
	}

	function image_fix_orientation($path)
	{
	    $image = @imagecreatefromjpeg($path);
	    $exif = exif_read_data($path);
	    if (empty($exif['Orientation']))
	    {
	        return false;
	    }
	    switch ($exif['Orientation'])
	    {
	        case 3:
	            $image = imagerotate($image, 180, 0);
	            break;
	        case 6:
	            $image = imagerotate($image, - 90, 0);
	            break;
	        case 8:
	            $image = imagerotate($image, 90, 0);
	            break;
	    }

	    imagejpeg($image, $path);

	    return true;
	}

	function currency_symbol($cur){
		if(!$cur){
			return false;
		}

		$cur	=	strtoupper($cur);

		$currencies = array(
		'USD'=>'$', // US Dollar
    	'EUR'=> '€', // Euro
    	'CRC'=> '₡', // Costa Rican Colón
    	'GBP'=> '£', // British Pound Sterling
    	'ILS'=> '₪', // Israeli New Sheqel
	    'INR'=> '₹', // Indian Rupee
	    'JPY'=> '¥', // Japanese Yen
	    'KRW'=> '₩', // South Korean Won
	    'NGN'=> '₦', // Nigerian Naira
	    'PHP'=> '₱', // Philippine Peso
	    'PLN'=> 'zł', // Polish Zloty
	    'PYG'=> '₲', // Paraguayan Guarani
	    'THB'=> '฿', // Thai Baht
	    'UAH'=> '₴', // Ukrainian Hryvnia
		'VND'=> '₫', // Vietnamese Dong)
		'AED'=>	'د.إ',	//..United Arab Emirates dirham 
		'HKD'=>	'HK$'
	);

		if(array_key_exists($cur,$currencies)){
			return $currencies[$cur];
		}else{
			return $cur;
		}
	}

	function check_online($user_last_active){
		$current_time 	=	time();
		if($user_last_active<($current_time-10)){
		?>
			<i class="fa fa-circle text-default " aria-hidden="true"></i>
		<?php
		}else{
		?>
			<i class="fa fa-circle text-success " aria-hidden="true"></i>
		<?php
		}
	}

	function decode_currency($text){
		$cur_unicode 	=	array('\u20AC','\u00A3','\u00A5','\243','\u20ac','\u20b9','\245','&amp;');
		$cur_symbol 	=	array('€','£','¥','£','€','₹','¥','&');
		return str_replace($cur_unicode, $cur_symbol, $text);
		
	}


	function calculate_total($unit_cost,$qty=1,$vat=0){
		return ($qty*$unit_cost)+(($qty*$unit_cost*($vat/100)));
	}

	function create_dir($file){
		if(!empty($file))
		{
			if(file_exists($file)){

				//if (!is_dir($file)) { //if file is already present, but it's not a dir
				if (!is_dir($file)) {
					//do something with file - delete, rename, etc.
					@unlink($file); //for example
					@mkdir($file, 0777);
				}
				else{
					//rmdir($file);
					//mkdir($file, 0777);
					@chmod($file, 0777);
				}
			}else{ //no file exists with this name
				@mkdir($file,0777,TRUE);
			}
		}
	}


	function slack_notify($msg){
		// notify_telegram($msg);
		$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://hooks.slack.com/services/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",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_POSTFIELDS => "{\"text\": \"$msg\"}",
			  CURLOPT_HTTPHEADER => array(
			    "cache-control: no-cache"),
			));
			$response = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);
			if ($err) {
			  log_message('error',"cURL Error #:" . $err);
			}
	}

	function secure_login($username,$password){
		return base64_encode($username.'---'.base64_encode(base64_encode($password)));
	}

	function get_credential($token){
		$decode 	=	base64_decode($token);
		$saparate 	=	explode('---', $decode);
		
		$username 	=	$saparate[0];
		$password	=	base64_decode(base64_decode($saparate[1]));
		return array($password,$username);
	}

	function show_msg($msg,$type='info',$icon='info-circle'){
		?>
		<span class="text-<?php echo $type?>"><i class="fa fa-<?php echo $icon?>"></i> <?php echo $msg;?></span>
		<?php
	}

	function bucket_url($uri=''){
		
		if(!is_string($uri)){

			return base_url().IMG_PLACEHOLDER;
		
		}

		$CI =& get_instance();
		if($uri!=''){

			if(env('SERVER_NAME')!=='staging.mobi-hub.com' || env('SERVER_NAME')!=='app.mobi-hub.com'){
				//return $CI->_settings['bucket_url'].$uri;
			}

			if(!strpos(uri_string(), 'APIv30')){
				return $CI->_settings['bucket_url'].$uri;
			}

			if(remote_file_exists($CI->_settings['bucket_url'] . $uri)){
				return $CI->_settings['bucket_url'].$uri;
            }
		}
		return base_url().IMG_PLACEHOLDER;
	}


	function bucket_name(){
		
		$CI =& get_instance();
		return $CI->_settings['bucket_name'];
    
	}
	

	function not_found()
    {
        $CI =& get_instance();


        $data 	=	array(
			'title'				=>	'404 Not found',
			'meta_desription'	=>	'',
			'meta_keyword'		=>	'',
			'page_title'		=>	''
			);
		$CI->output->set_status_header('404');
		
		$logged_in	=	webapp_user('username');
		if($logged_in){
			
			$user_id			=	webapp_user('user_id');
			$data['user_info']	=	$CI->users->user_info($user_id);
			$segment 			=	$CI->uri->segment(1);
			
			$CI->load->view('front/includes/header',$data);
			$CI->load->view('front/error/404_inner',$data);
			$CI->load->view('front/includes/footer',$data);
			
			// if($segment=='vsm'){
			// 	$data['config'] 	=   $CI->lib->config_list();
			// 	$CI->load->view('vsm/includes/vsm_header',$data);
			// 	$CI->load->view('vsm/error/404',$data);
			// 	$CI->load->view('vsm/includes/vsm_footer',$data);
				
			// }else{

			// }

		}else{
			$CI->load->view('front/includes/public_header',$data);
			$CI->load->view('front/error/404',$data);
			$CI->load->view('front/includes/public_footer',$data);
		}

		$refer_by	=	"None";
		if(isset($_SERVER['HTTP_REFERER'])) {
			$refer_by =  $_SERVER['HTTP_REFERER'];
		}
     	log_message('error','404 Not found URL #'.current_url().' | refered by '.$refer_by);
        echo $CI->output->get_output();
        exit;
	}
	

	function not_found_admin(){
		$CI =& get_instance();


        $data 	=	array(
			'title'				=>	'404 Not found : Admin',
			'meta_desription'	=>	'',
			'meta_keyword'		=>	'',
			'page_title'		=>	''
			);
		$CI->output->set_status_header('404');
		
		$logged_in	=	admin_user('id');
		
		$CI->load->view('admin/error/404',$data);
		$refer_by	=	"None";
		if(isset($_SERVER['HTTP_REFERER'])) {
			$refer_by =  $_SERVER['HTTP_REFERER'];
		}

     	log_message('error','404 Not found URL #'.current_url().' | refered by '.$refer_by);
        echo $CI->output->get_output();
        exit;
	}

    function post_privacy($public=1){
    		if($public==1){
    			echo "<i title='Public post' class='fa fa-globe fa-fw'></i> ";
    		}else{
    			echo "<i title='Visible to followers' class='fa fa-user-plus fa-fw'></i> ";
    		}
	}
	
	function membership_badge($membership){
		if($membership==1){
			echo "<i title='Verified user' class='fa fa-check-circle fa-fw'></i> ";
		}
	}

    function is_weak_password($password){
    	if(in_array($password, $config['weak_password'])){
    		return true;
    	}else{
    		return false;
    	}
    }


    function signup_adword(){
    	if(ENV=='production' && $_SERVER['SERVER_NAME']=='app.mobi-hub.com'){
    		?>
			<!-- Event snippet for Web Signup conversion page -->
			<script>
			gtag('event', 'conversion', {'send_to': 'AW-814217813/9OWNCJ2_4n4Q1fSfhAM'});
			</script>
    		<?php
    	}
    }

    function marketplace_privacy($type){
		//return false;
    	if($type==1){
    		echo "<i title='Verified Membership' class='fa fa-check-circle fa-fw'></i> ";
    	}elseif($type==2){
    		//echo "<i title='Verified Membership' class='fa fa-check-circle fa-fw'></i> ";
    	}else{
    		echo "<i title='Public Membership' class='fa fa-globe fa-fw'></i> ";
    	}
    }

	/*
	 * level type
	 * @author: sandeep
	 * @param: string
	 * @return: string
	 */
	function get_label($type){
		if(empty($type)){
			$label 	= '';
		}
		if($type=='general'){
			$label	= 'info';
		}
		
		if($type=='add'){
		  	$label	= 'success';
		}
		
		if($type=='update'){
		  	$label	= 'warning';
		}
		
		if($type=='delete'){
		  	$label	= 'danger';
		}
		return $label;
	  }

    function rating_star($num){
		if(!$num){
			return "";
		}
		$num 	=	ceil($num);
		$total =	5;
		for($i=1;$i<=$num;$i++){
			echo "<i class='fa fa-star fa-fw'></i>";
		}
		
		for($j=1;$j<=$total-$num;$j++){
			echo "<i class='fa fa-star-o fa-fw'></i>";
		}
	}

	function user_timezone(){
		$u_timezone 	=	$_SESSION['user_timezone'] ?? '';
		if($u_timezone!=''){
			date_default_timezone_set($u_timezone);
		}
	}

	function static_url($url=''){
		
		return BUCKET.$url;

	}


	function get_location($ip){
		$url 	= "ipinfo.io/".$ip."/geo?token=26a94548ad8671";
		$ch 	= curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FRESH_CONNECT, true); 
		$info	= curl_exec($ch);
		$data	= json_decode($info);
		return $data; 
	}
	
	function prd($data){
		if(!empty($data)){
			echo "<pre>";
			print_r($data);
			echo "</pre>";
		}else{
			echo "No Data";
		}
		exit();

	}

	function pr($data){
		if(!empty($data)){
			echo "<pre>";
			print_r($data);
			echo "</pre>";
		}else{
			echo "No Data";
		}
		exit();
	}


	function parse_message($msg){
		//$msg = htmlentities($msg);
		
		$msg = preg_replace("/\\\\u([0-9A-F]{1,4})/i", "&#x$1;", htmlentities($msg));
		$msg = mb_convert_encoding($msg, "UTF-16", "HTML-ENTITIES");
		$msg = mb_convert_encoding($msg, 'utf-8', 'utf-16');
		$msg = stripslashes($msg);
		//$msg = stripslashes($msg);
		$msg = auto_link($msg,'both',TRUE);
		return $msg;
	}

	function img_to_jpg($filepath) {
		if(!getimagesize($filepath)){
			return $filepath;
		}
		$type = exif_imagetype($filepath);
	 
	    $allowedTypes = array(
	        1,  // [] gif
	        2,  // [] jpg
	        3,  // [] png
	        6   // [] bmp
	    );
	    
	    if (!in_array($type, $allowedTypes)) {
			log_message('error',"File ".$filepath." not in allowed types");
			return $filepath;
	    }

	    switch ($type) {
	        case 1 :
			    $im = imageCreateFromGif($filepath);
				break;
			case 2 :
			    $im = imageCreateFromJpeg($filepath);
				break;
			case 3 :
			    $im = imageCreateFromPng($filepath);
				break;
			case 6 :
			    $im = imageCreateFromBmp($filepath);
				break;
			default:
				break;
		}//end switch
		
	    if(!$im){
	 		log_message('error',"File ".$filepath.", temporary file could not be created");
	 		return $filepath;
		}
		if(getimagesize($filepath)['mime'] == 'image/jpeg'){
			return $filepath;
		}

	    $file_array 			= 	explode('.', $filepath);
	    $ext_pos 				=	count($file_array) - 1;

	    $file_array[$ext_pos] 	=	'jpg';
		$target_file			= 	implode(".", $file_array);
		
		
		list($width, $height) = getimagesize($filepath);

		$output = imagecreatetruecolor($width, $height);
		$white = imagecolorallocate($output,  255, 255, 255);
		imagefilledrectangle($output, 0, 0, $width, $height, $white);
		imagecopy($output, $im, 0, 0, 0, 0, $width, $height);

	 	if(!imagejpeg($output, $target_file, 100)){
	 		log_message('error',"File ".$filepath." not converted to jpg");
			imagedestroy($im);
	 		return $filepath;
	 	}

		// free memory associated with initial image & image resource
	    if(!unlink($filepath)){
	    	log_message('error',"File could not be deleted: ".$filepath." not converted to jpg");
			imagedestroy($im);
	 		return $filepath;
	    }

		return $target_file;
	}//end imageCreatefromany


	function reg_data($key = ""){
		if($key == ""):
			return $_SESSION['registration'];
		endif;
		return $_SESSION['registration'][$key] ?? false;
	}

	
	function save_reg_data($key, $value){
		$_SESSION['registration'][$key] = $value;
	}
    
	function user_data($id=null){
		$CI =& get_instance();
		$res = $CI->db->from('users')->where("user_id",$id)->get()->row();
		return !empty($res)?$res:false;
	}	
	

	
	function clear_session_data($key = ''){
		if( $key == '' ){
			//session_destroy();
			if(!session_id()) session_start();
			// Unset all of the session variables.
			session_destroy();
        }else{
			unset($_SESSION[$key]);
        }
		return true;
	}

	
if(!function_exists('mime_content_type')) {

    function mime_content_type($filename) {

        $mime_types = array(

            'txt' => 'text/plain',
            // 'htm' => 'text/html',
            // 'html' => 'text/html',
            // 'php' => 'text/html',
            // 'css' => 'text/css',
            // 'js' => 'application/javascript',
            // 'json' => 'application/json',
            // 'xml' => 'application/xml',
            // 'swf' => 'application/x-shockwave-flash',
            // 'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            // 'ico' => 'image/vnd.microsoft.icon',
            // 'tiff' => 'image/tiff',
            // 'tif' => 'image/tiff',
            // 'svg' => 'image/svg+xml',
            // 'svgz' => 'image/svg+xml',

            // archives
            // 'zip' => 'application/zip',
            // 'rar' => 'application/x-rar-compressed',
            // 'exe' => 'application/x-msdownload',
            // 'msi' => 'application/x-msdownload',
            // 'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            // 'mp3' => 'audio/mpeg',
            // 'qt' => 'video/quicktime',
            // 'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            // 'psd' => 'image/vnd.adobe.photoshop',
            // 'ai' => 'application/postscript',
            // 'eps' => 'application/postscript',
            // 'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            // 'rtf' => 'application/rtf',
            // 'xls' => 'application/vnd.ms-excel',
            // 'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            // 'odt' => 'application/vnd.oasis.opendocument.text',
            // 'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        $ext = strtolower(array_pop(explode('.',$filename)));
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        }
        elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        }
        else {
            return 'application/octet-stream';
        }
    }
}

if (!function_exists('read_more')) {

    function read_more($string, $limit = 300, $dash = true, $read_more = false) {
        $real_conten = $string;
        $string = strip_tags($string);
        if (strlen($string) > $limit) {
            $string = substr($string, 0, $limit);
            // $string = substr($stringCut, 0, strrpos($stringCut, ' '));
            if ($dash) {
                $string .= '...';
            }

            $return_html = html_entity_decode($string);
            if ($read_more) {
                $return_html = $return_html . read_more_poup($real_conten);
            }
        } else {
            $return_html = html_entity_decode($string);
        }

        return $return_html;
    }

}
function is_image($filename){
	
	$allowed_types = array ( 'image/gif', 'image/jpeg', 'image/png' ,'image/jpg'  ,'image/flv','image/bmp','image/tiff','image/svg+xml' );
	$type = mime_content_type($filename);
	if(in_array($type,$allowed_types)) {
		return true;
	}
	return false;
}


function this_url($url=''){
	/*
	*	returns current URL without index.php
	*
	*/
	return str_replace('/index.php/','/',current_url()).$url;
}

/**
 * generates User DP
 * By using initials of name
 * @param name string default ='M H'
 * @param size integer default = 128
 * @param color string code for color, default '007fbd'
 * @param rounded bool default true
 * 
 **/
function default_user_dp($name='R',$rounded='true',$size=128,$color='')
{
	if($color==''):

		$color =	"f39c12";
	
	endif;
	return "https://ui-avatars.com/api/?name=".urlencode($name)."&size=".$size."&rounded=".$rounded."&color=".$color;
}



/**
 * Will return font-awesome icon for the file name
 * @param $file_name string
 * @return icon class if available or blank file icon 
 * 
*/
function file_icon($file_name){

    $ext = pathinfo($file_name, PATHINFO_EXTENSION);
    //return $ext;
    if($ext=='pdf'){
        return 'file-pdf-o';
    }

    if($ext=='doc' || $ext=='docx'){
        return 'file-word-o';
    }

    if($ext=='jpg' || $ext=='jpeg' || $ext=='png' || $ext=='gif' ){
        return 'file-image-o';
    }

    if($ext=='xls' || $ext=='xlsx' || $ext=='csv'){
        return 'file-excel-o';
    }

    return 'file-o';


}


function filter_file($file_array = NULL, $filter_extension= NULL){
	if($file_array == NULL){
		return false;
	}
	if($filter_extension == NULL){
		return $file_array;
	}
	$new_array	=	array();
	foreach ($file_array as $file) {
		$ext = pathinfo($file, PATHINFO_EXTENSION);
		if($ext == $filter_extension){
			$new_array[]	=	$file;
		}
	}
	return $new_array;
}

 function match_record($fields,$new_data,  $old_data){
	$array=array();         
	 foreach($new_data as $key =>$val){             
		 if($val!=$old_data[$key]){ 
			$array['fields_name'][$key]= $fields[$key];             
			$array['old_values'][$key]=$old_data[$key]; 
			$array['new_values'][$key]=$val; 

		}
	 }       
	 return $array;
}

function verified_badge_blue($year=0)
{
	if($year==0){
		$year =	date('Y',time());
	}	

	$badge_path 	=	'static/front/images/verified_image/blue/';

	return $badge_path . $year . '.png';
}

/**
 * Function to show loading icon with default text
 * @param text string default Please wait...
 * @param icon_size string lg 2x 3x 4x 5x
 *  */
function show_loader($text='Please wait..', $icon_size=''){

    echo "<p class='text-center'><i class='fa fa-spinner fa-spin ".$icon_size." '></i> ".$text."</p>";

}

function remote_file_exists($url)
{
	
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
	if( $httpCode == 200 ){return true;}
	return false;
}




function gravatar_url($email='user@example.com', $size=100)
{
	$url = "https://www.gravatar.com/avatar/".md5(strtolower($email))."?s=".$size;
	return $url;
}