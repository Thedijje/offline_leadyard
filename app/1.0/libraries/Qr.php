<?php
include(__DIR__.'/phpqrcode/qrlib.php');
class Qr
{
    private $qr_path='public/qr/';
    public function generate($text='code data text')
    {
        $filename = $this->qr_path.time().'_filename.png';
        QRcode::png($text, $filename,QR_ECLEVEL_L,4);

        echo "<img src='".base_url($filename)."'>";
    }
    
}
