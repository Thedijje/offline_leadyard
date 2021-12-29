<?php
class Helper_model extends CI_Model
{

	function cli_only()
	{

		$is_cli = $this->input->is_cli_request();
		if (!$is_cli) {
			$msg = "Somebody from IP--> " . $_SERVER['REMOTE_ADDR'] . " Tried to open http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			$this->notification->slack_notify($msg);
			not_found();
			exit();
		}
	}


	public function get_settings($name, $output = NULL)
	{
		/*
		Fetching data from config table of database	
		*/
		$query			=	$this->db->get_where('config', array('name' => $name), 1);
		$res				=	$query->result();
		foreach ($res as $details) {
			if ($output == 1) {
				echo $details->value;
			} else {
				return $details->value;
			}
		}
	}

	public function config_list()
	{
		// $this->db->cache_off();
		$query			=	$this->db->select('*')->from('config')->get();
		// $this->db->cache_on();
		$config			=	$query->result();
		foreach ($config as $row) {
			$conf[$row->name]	=	$row->value;
		}
		$list_config	=	$conf;
		return $list_config;
	}

	public function login_check()
	{
		/*
		Prevent pages from getting access if protected by login
		*/
		$email			=	$this->session->userdata('email');
		$admin_id		=	$this->session->userdata('admin_id');
		$logged_in		=	$this->session->userdata('logged_in');
		if (($email != "") && ($admin_id != "") && ($logged_in != "")) {
			// We programmer rocks :DESC
			// lets stay silent
			// No action here, enjoy pizza :D
		} else {
			$this->session->set_flashdata(array('msg' => 'Please Login to continue', 'type' => 'warning'));
			redirect(base_url('admin/login'));
		}
	}

	public function upload_file_s3($path, $name, $key = NULL, $image = true)
	{

		$this->load->helper('string');
		if (!isset($path)) {
			log_message('error', 'Image upload path not defined');
			return FALSE;
		}
		if (!isset($name)) {
			log_message('error', 'File name not defined');
			return false;
		}

		if (!$_FILES[$name]['name'] or $_FILES[$name]['name'] == '') {
			log_message('error', '$_FILES array undefined or not set while uploading file ' . current_url());
			return false;
		}

		$target_dir =	$path;

		if (!is_dir($path)) {
			mkdir($path, 777, true);
		}

		$time			=	md5(base64_encode(time() . random_string('alnum', 5)));

		if ($key === NULL) {
			$origin_path		=	$_FILES[$name]["tmp_name"];
			$target_file		=	$target_dir . $time . "_" . rand(0, 10) . "." . strtolower(get_file_ext($_FILES[$name]["name"]));
		} else {
			$origin_path		=	$_FILES[$name]["tmp_name"][$key];
			$target_file		=	$target_dir . $time . "_" . rand(0, 10) . "." . strtolower(get_file_ext($_FILES[$name]["name"][$key]));
		}

		if (!move_uploaded_file($origin_path, $target_file)) {
			log_message('error', 'Directory does not exist, please create directory or defined valid path in first argument');
			return FALSE;
		}

		if ($image) {
			$resized_file	=	$this->image_resize($target_file, 1000, 1000);
			if (!$resized_file) {
				$resized_file	=	$target_file;
			}
		} else {
			$resized_file	=	$target_file;
		}

		if ($_FILES[$name]) {
			if ($key === NULL) {
				$response	= s3_upload_file($target_file, $resized_file);
			} else {
				$response	= s3_upload_file($target_file, $resized_file);
			}

			if (!$response['status']) {
				log_message('error', 'File Upload failed to s3 with error ' . $response['value']);
				return FALSE;
			}

			if (!file_exists($resized_file)) {
				log_message('error', 'File not found on local location');
				return FALSE;
			}

			gc_collect_cycles();

			unlink($resized_file);

			return $response['value'];
		} else {
			log_message('error', 'File not found, please check if form has attribute enctype=multipart/form-data');
			return FALSE;
		}
	}

	public function upload_file($path, $name, $key = NULL)
	{
		$this->load->helper('string');
		/*
		Upload file helper function	
		*/
		if (!isset($path)) {
			/*
			Need to set path like /directory/image/	
			*/
			log_message('error', 'Image upload path not defined');
			return FALSE;
		}
		if (!isset($name)) {
			/*
			name of input type file is missing
			*/

			log_message('error', 'File name not defined');
			return false;
		}

		if (!$_FILES[$name]['name'] or $_FILES[$name]['name'] == '') {
			log_message('error', '$_FILES array undefined or not set while uploading file ' . current_url());
			return false;
		}

		$target_dir =	$path;

		$time			=	md5(base64_encode(time() . random_string('alnum', 5)));

		if ($key === NULL) {
			$target_file	=	$target_dir . $time . "_" . rand(0, 10) . "." . strtolower(get_file_ext($_FILES[$name]["name"]));
			// $target_file	=	$target_dir.$time."_".rand(0,10).".jpg";
		} else {
			$target_file	=	$target_dir . $time . "_" . rand(0, 10) . "." . strtolower(get_file_ext($_FILES[$name]["name"][$key]));
			// $target_file	=	$target_dir.$time."_".rand(0,10).".jpg";
		}

		$uploadOk = 1;
		// $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

		if ($_FILES[$name]) {
			if ($key === NULL) {
				$origin_path 	=	$_FILES[$name]["tmp_name"];
			} else {
				$origin_path 	=	$_FILES[$name]["tmp_name"][$key];
			}


			if (move_uploaded_file($origin_path, $target_file)) {

				// if(ENV == 'development'){
				//$target_file = img_to_jpg($target_file);
				// }
				return $target_file;
			} else {

				log_message('error', 'Directory does not exist, please create directory or defined valid path in first argument');
				return FALSE;
			}
		} else {
			log_message('error', 'File not found, please check if form has attribute enctype=multipart/form-data');
			return FALSE;
		}
	}

	public function alert_message()
	{
		/*
	place this function where you want to show alert messages	
	*/
		if ($this->session->userdata('msg')) {
			$type 	=	$this->session->userdata('type');
			if ($type == 'danger') {
				$this->output->set_header('Feedback: error');
			}


			$icon = 'info-circle';

			if($type=='danger'){
				$icon=	'times-circle';
			}

			if($type=='warning'){
				$icon=	'warning';
			}

			if($type=='success'){
				$icon=	'check-circle';
			}
?>
			<div id="fading_div" class="animated flash delay-3s alert alert-<?php echo $type; ?>">
				<i class="fa fa-<?php echo $icon;?> fa-lg"></i> <?php echo $this->session->userdata('msg'); ?>
				<span onclick="$('#fading_div').fadeOut('slow')" class="pull-right" style="cursor:pointer"><i class="fa fa-times"></i></span>
			</div>
		<?php
		}
	}



	public function set_flash_msg(string $msg = '', string $type = 'info')
	{
		if ($msg == '') {
			return false;
		}

		$this->session->set_flashdata(array(
			'type' => $type,
			'msg' => $msg
		));

		return true;
	}


	public function display_alert($msg, $type = NULL, $icon = NULL)
	{
		if (!$msg) {
			return FALSE;
			exit();
		}
		if ($type == NULL) {
			$type = 'info';
		}

		if ($icon == NULL) {
			$icon = 'info-circle';
		}
		?>
		<div class="alert alert-<?php echo $type; ?>" align="center">
			<i class="fa fa-<?php echo $icon; ?> fa-lg"></i> <?php echo $msg; ?>
		</div>
<?php
	}


	public function redirect_msg($msg, $type = 'info', $url)
	{
		if (!$msg or !$url) {
			return false;
		}

		$this->session->set_flashdata(array('msg' => $msg, 'type' => $type));
		redirect(base_url($url));
		exit();
	}

	public function google_recaptcha($path, $mode)
	{
		$secrate_key = $this->settings_model->get_settings('google_recaptcha_secrete_key');
		$captcha = $_POST['g-recaptcha-response'];
		if (!$captcha) {
			$this->session->set_flashdata(array('msg' => 'Please go through captcha', 'type' => 'danger'));
			redirect(base_url($path));
			exit();
		}
		if ($mode == 'local') {
			return TRUE;
			exit();
		}
		$response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $secrate_key . "&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']), true);

		if ($response['success'] == FALSE) {
			$this->session->set_flashdata(array('msg' => 'We are not able to verify you as human, try again if you are!', 'type' => 'warning'));
			redirect(base_url($path));
			exit();
		} else {
			return TRUE;
		}
	}

	protected function send_ses_mail($data, $attachment = NULL)
	{

		//	Filter email which are bounced

		
		$this->load->library('smtp_email');

		
		$this->smtp_email->username		=	env('SMTP_USER');
		$this->smtp_email->password		=	env('SMTP_PASSWORD');
		$this->smtp_email->hostname		=	env('SMTP_HOST');

		$this->smtp_email->from			=	array($data['from'], $data['name']);

		$this->smtp_email->to			=	array($data['to'], 'You');

		$this->smtp_email->message 		=	$data['message'];

		$this->smtp_email->attachment	=	$attachment ?? null;

		$this->smtp_email->subject		=	$data['subject'];


		$mail = $this->smtp_email->send();

		if ($mail->result == 'success') {
			log_message('error', 'INFO: Message sent to :' . $data['to'] . ' via AWS | Subject : "' . $data['subject'] . '"');
			return TRUE;
		} else {


			//$this->logs->email($data, 5, $mail->debug);	//	Data with 5/failed status

			
			$log_message 	=	'Message not sent to :' . $data['to'] . ' via AWS | Subject : "' . $data['subject'] . '" Email Debugger info # ' . json_encode($mail);

			log_message('error', $log_message);

			//$this->notification->slack_notify($log_message);
			return FALSE;
			exit();
		}
	}

	public function send_formatted_mail($data, $attachment = NULL)
	{

		$this->load->library('email');
		$this->email->clear();

		if (
			empty($data['name'])
			or empty($data['subject'])
			or empty($data['to'])
			or empty($data['message'])
		) {
			log_message('error', 'Empty paraters for email' . current_url());
			return FALSE;
		}

		if (ENV == 'production') :
		// TO send mail via SES uncomment the line below
		//return send_ses_mail($data,$attachment);
		endif;
		return $this->send_ses_mail($data, $attachment);



		$ip = $this->input->ip_address();
		if (strpos($ip, '192')) {
			/*	Do not run on local machine	*/
			//return true;
		}
		$uri_string = $this->uri->segment(2);

		$config['useragent']        =   'Mobi-Hub';
		$config['mailpath']         =   '/usr/sbin/sendmail'; // or "/usr/sbin/sendmail"
		$config['protocol']         =   'smtp';
		$config['smtp_host']        =   'localhost';
		$config['smtp_port']        =   '25';

		$config['mailtype']			=	'html';
		$config['charset']			=	'utf-8';
		$config['dsn']				=	TRUE;
		$config['wordwrap']			=	TRUE;
		$config['newline']			=	"\r\n";
		$config['crlf'] 			= 	"\r\n";


		ini_set('sendmail_from', $data['from']);



		$this->email->initialize($config);
		$this->email->to($data['to']);
		$this->email->from($data['from'], $data['name']);
		$this->email->subject($data['subject']);
		$this->email->message($data['message']);
		$this->email->reply_to($this->_settings['verification_mail'], 'Mobi-Hub');


		if ($attachment != NULL) {
			if (!is_array($attachment)) {
				$this->email->attach(FCPATH . $attachment);
			} else {
				foreach ($attachment as $key => $attach) {
					$this->email->attach(FCPATH . $attach);
				}
			}
		}

		if ($mail = $this->email->send()) {
			if ($mail) {
				log_message('error', 'Message sent to :' . $data['to'] . ' | Subject : "' . $data['subject'] . '"');
				return TRUE;
			} else {
				log_message('error', 'message not sent to :' . $data['to'] . ' Email Debugger info # ' . implode(' | ', $this->email->print_debugger()));
				$this->notification->slack_notify('Email not sent to :' . $data['to'] . ' Email Debugger info # ' . implode(' | ', $this->email->print_debugger()));
				return FALSE;
				exit();
			}
		}
		// $mail = $this->email->send();

	}

	public function image_resize($upload_path, $width = 1000, $height = NULL, $target = '', bool $strict = false)
	{
		if (!file_exists($upload_path)) {



			log_message('error', 'Failed to resize image as image not exist # ' . $upload_path);
			return false;
		}


		if ($target == '') {
			$target	=	$upload_path;
		}



		// Just need path to grab image and resize followed by overwriting
		$this->load->library('image_lib');
		ini_set('memory_limit', '-1');	// this will prevent memory overload by PHP, 

		$config['source_image']		=	$upload_path;
		$config['new_image']		=	$target;

		$config['image_library'] 	=	'gd2';

		$img_prop 					=	getimagesize($upload_path);

		if ($img_prop[0] > $width) {

			$config['width'] = $width;
		}

		if ($img_prop[1] > $height) {

			$config['height'] = $height;
		}

		if ($strict == true) {
			$config['width'] = $width;
			$config['height'] = $height;
		}

		$this->image_lib->initialize($config);

		$confirm = $this->image_lib->resize();

		if ($confirm) {
			return $config['new_image'];
		} else {
			log_message('error', 'Error in image resizing');
			return FALSE;
		}

		/*
			Reference
			http://stackoverflow.com/questions/11193346/image-resizing-codeigniter
			*/
	}

	public function resize_s3_image($path, $width = 500, $height = null)
	{
		if ($path == '') {
			return false;
		}

		// $path 	=	bucket_url($path);
		if (!file_get_contents(bucket_url($path))) {
			log_message('error', 'Error in resizing from s3, file does not exist ' . current_url());
		}

		$tmp_path 	=	FCPATH . 'writable/tmp_resize/' . basename($path);

		if (!file_put_contents($tmp_path, file_get_contents(bucket_url($path)))) {

			log_message('error', 'Failed to download file for resizing ' . current_url());
			return false;
		}


		$this->image_resize($tmp_path, 100, $height);



		if (!s3_upload_file($path, $tmp_path)) {
			log_message('error', 'Failed to upload file to s3 after resizing ' . current_url());
			return false;
		}

		unlink($tmp_path);

		return $path;
	}


	public function update_sitedata($var, $val)
	{
		log_message('error', 'Variable loaded' . $var);
		log_message('error', 'value loaded' . $val);
		$data = array(
			'value' => $val
		);

		$query = $this->db->where('var', $var);
		$query = $this->db->update('config', $data);

		if ($query) {
			log_message('error', 'query success');
			return TRUE;
		} else {
			log_message('error', 'query failed');
			return FALSE;
		}
	}




	public function del($table, $col, $val)
	{
		/*
	These are database CRUD helper,
	in case of error, please refer error log or use native codeigniter function
	*/

		if (($table == NULL) or ($col == NULL) or ($val == NULL)) {
			log_message('error', 'Missing table, col, or value');
			return FALSE;
		}

		$query		=	$this->db->where($col, $val);
		$query		=	$this->db->delete($table);
		if ($query) {
			return TRUE;
		} else {
			log_message('error', 'Unable to delete from ' . $table . " given attribute " . $col . " with value " . $val);
			return FALSE;
		}
	}

	public function update($table, $data, $col, $val)
	{
		if (($data == NULL) or ($col == NULL) or ($val == NULL) or ($table == NULL)) {
			log_message('error', 'Missing table, col, or value');
			return FALSE;
			exit();
		}

		$query	=	$this->db->where($col, $val);
		$query	=	$this->db->update($table, $data);
		//echo $this->db->last_query();die("hi");
		if ($query) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function get_by_id($table, $col, $value, $limit = null, $order = NULL, $col_get = NULL)
	{
		if ($col_get != NULL) {
			$col_get = implode(',', $col_get);
			$this->db->select($col_get);
		}
		if ($order != NULL) {

			foreach ($order as $key => $value1) {
				$query = $this->db->order_by($key, $value1);
			}
		}
		$query =	$this->db->get_where($table, array($col => $value));

		if ($query->num_rows() > 0) {
			return $query->result();
		} else {

			return FALSE;
		}
	}

	public function get_multi_where($table, $where, $limit = null, $count = FALSE, $order = NULL, $group = NULL)
	{


		if ($order != NULL) {
			foreach ($order as $key => $value) {
				$this->db->order_by($key, $value);
			}
		}

		if (!empty($group)) {
			$this->db->group_by($group);
		}

		$query =	$this->db->get_where($table, $where, $limit);
		//return $this->db->last_query();
		if ($query->num_rows() > 0) {
			if ($count) {
				return $query->num_rows();
			} else {
				return $query->result();
			}
		} else {

			return FALSE;
		}
	}

	public function get_allby_id($table, $key, $value)
	{
		$query = $this->db->get_where($table, array($key => $value));
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return false;
		}
	}

	public function get_row_array($table, $where, $order = NULL, $limit = 1)
	{

		if (empty($table)) {
			return false;
		}

		if (!empty($order) && is_array($order)) {
			foreach ($order as $key => $value1) {
				$query = $this->db->order_by($key, $value1);
			}
		}

		$query =	$this->db->get_where($table, $where, $limit);

		if (empty($query->num_rows()) || $query->num_rows() == 0) {
			return false;
		} else if ($query->num_rows() > 0) {
			$row = $query->row();
			if (isset($row)) {
				return $row;
			}
		}
	}


	public function get_row($table, $col, $value, $col_get = NULL)
	{
		if (!$table or !$col or !$value) {
			return FALSE;
		}
		$query =	$this->db->get_where($table, array($col => $value), 1);
		if ($query->num_rows() > 0) {
			$data = $query->row_array();
			if ($col_get) {
				return $data[$col_get];
			} else {
				return $data;
			}
		} else {
			return "";
		}
	}

	public function row_pluck(string $table, array $condition, array $col)
	{
		$this->db->select(implode(',', $col) ?? '*');

		$this->db->where($condition);

		$query 	=	$this->db->get($table);

		if (!$query) {
			return false;
		}

		return $query->row();
	}

	public function get_table($table, $order = NULL, $group = NULL, $limit = NULL, $col_get = NULL)
	{
		/*
			$this->helper_model->get_table('user',array('id'=>'asc'));
			will produce
			SELECT  * FROM `USER` ORDER BY `ID` ASC;
	*/
		if ($col_get != NULL) {
			$col_get = implode(',', $col_get);
			$this->db->select($col_get);
		}

		if (!empty($order)) {

			foreach ($order as $key => $value) {
				$query = $this->db->order_by($key, $value);
			}
		}

		if (!empty($group)) {
			$this->db->group_by($group);
		}

		if (!empty($limit)) {
			$this->db->limit($limit);
		}

		$query = $this->db->get($table);
		if ($query->num_rows() > 0) {
			return $query->result();
		} else {
			return FALSE;
		}
	}

	public function count_data($table, $condition = NULL, $value = NULL)
	{

		if ($condition != NULL && $value != NULL) {
			$query = $this->db->get_where($table, array($condition => $value));
			if ($query) {
				return $query->num_rows();
			} else {
				return FALSE;
			}
		} else {
			$query = $this->db->get($table);
			if ($query) {
				echo  $query->num_rows();
			}
		}
	}

	public function count_multiple($table, $condition = NULL)
	{
		if ($condition != NULL) {
			$query = $this->db->get_where($table, $condition);
			if ($query) {
				return $query->num_rows();
			} else {
				return 0;
			}
		} else {
			$query = $this->db->get($table);
			if ($query) {
				return $query->num_rows();
			}
		}
	}

	public function search($table, $keyword, $attribute, $condition = NULL)
	{
		$this->db->from($table);
		foreach ($attribute as $key => $values) {
			$this->db->or_like($values, $keyword);
		}
		if ($condition != NULL) {
			$this->db->where($condition);
		}
		$query	=	$this->db->get();
		if ($query) {
			return $query->result();
		}
	}

	public function change_password($table, $data)
	{
		/*
				Please do not use this function, under testing it is	
				*/

		// Valid for current user password only



		$id = $this->session->userdata('uid');
		$newdata = array(
			'password' => sha1($pdata['newpass'])
		);
		$query	=	$this->db->where('id', $id);
		$query	=	$this->db->update($table, $newdata);

		if ($query) {
			return TRUE;
		} else {
			log_message('error', 'Database query failed while updating password ' . mysql_error());
			return FALSE;
		}
	}
	public function isJson($string = null)
	{
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}


	/**
	 * Generate PDF
	 * @param string html
	 * @param string filename
	 * @param string filepath
	 * 
	 */
	public function generatePDF($html = '', $filename = '', $filepath = '')
	{
		if ($html == '' || $filename == '' || $filepath == '') {
			log_message('error', 'Invalid parameter for generating pdf' . current_url());
			return false;
		}



		$this->load->library('pdfgenerator');
		$filepath	=	FCPATH . $filepath;
		//generate($html, $filename='', $filepath=FALSE, $stream=TRUE, $paper = 'A4', $orientation = "portrait")
		$generated_pdf 	=	$this->pdfgenerator->generate($html, $filename, $filepath, FALSE, $paper = 'A4', $orientation = "portrait");

		if (!$generated_pdf) {
			return false;
		}

		return $filepath . $filename;
	}
}
