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


	public function qr_logo()
	{
		$data = isset($_GET['data']) ? $_GET['data'] : 'https://wa.me/918826810280';
		$size = isset($_GET['size']) ? $_GET['size'] : '400x400';
		// $logo = isset($_GET['logo']) ? $_GET['logo'] : false;
		$logo = isset($_GET['logo']) ? $_GET['logo'] : 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/6b/WhatsApp.svg/1021px-WhatsApp.svg.png';

		//header('Content-type: image/png');
		// Get QR Code image from Google Chart API
		// http://code.google.com/apis/chart/infographics/docs/qr_codes.html
		$QR = imagecreatefrompng('https://chart.googleapis.com/chart?cht=qr&chld=H|1&chs='.$size.'&chl='.urlencode($data));
		if($logo !== FALSE){
			$logo = imagecreatefromstring(file_get_contents($logo));

			$QR_width = imagesx($QR);
			$QR_height = imagesy($QR);

			/*


			QR code with color 

			imagetruecolortopalette($QR,true, 255);
			$index = imagecolorclosest($QR, 0, 0, 0); // GET BLACK COLOR REVERSE 255 to  GET WHITECOLOR 
			imagecolorset($QR, $index, 95, 201, 89); // SET COLOR TO BLUE
			
			*/
			
			$logo_width = imagesx($logo);
			$logo_height = imagesy($logo);
			
			// Scale logo to fit in the QR Code
			$logo_qr_width = $QR_width/3;
			$scale = $logo_width/$logo_qr_width;
			$logo_qr_height = $logo_height/$scale;
			
			imagecopyresampled($QR, $logo, $QR_width/3, $QR_height/3, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
		}
		
		$qr_path = 'public/qr/';
		$qr_file = $qr_path.'qr_'.time().'.png';
		imagepng($QR,$qr_file);
		//imagedestroy($QR);
		echo "<img src='".base_url($qr_file)."'>";
	}
}
