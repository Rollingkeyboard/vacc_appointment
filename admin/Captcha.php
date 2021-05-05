<?php 
/**
* Captcha
*/
class Captcha
{
	private $code_number;
	private $width;
	private $height;
	private $img;
	private $line_flag;
	private $pixel_flag;
	private $font_size;
	private $code;
	private $string;
	private $font;
	function __construct($code_number = 4,$height = 50,$width = 150,$font_size = 20,$line_flag = true,$pixel_flag = true)
	{
		$this->string = 'qwertyupmkjnhbgvfcdsxa123456789';
		$this->code_number = $code_number;
		$this->height = $height;
		$this->width = $width;
		$this->line_flag = $line_flag;
		$this->pixel_flag = $pixel_flag;
		$this->font = dirname(__FILE__).'/fonts/consola.ttf';
		$this->font_size = $font_size;
	}

	public function create_image(){
		$this->img = imagecreate($this->width, $this->height);
		imagecolorallocate($this->img,mt_rand(0,100),mt_rand(0,100),mt_rand(0,100));
	}

	public function create_code(){
		$strlen = strlen($this->string)-1;
		for ($i=0; $i < $this->code_number; $i++) { 
			$this->code .= $this->string[mt_rand(0,$strlen)];
		}

		$diff = $this->width/$this->code_number;
		for ($i=0; $i < $this->code_number; $i++) { 
			$txtColor = imagecolorallocate($this->img,mt_rand(100,255),mt_rand(100,255),mt_rand(100,255));
			imagettftext($this->img, $this->font_size, mt_rand(-30,30), $diff*$i+mt_rand(3,8), mt_rand(20,$this->height-10), $txtColor, $this->font, $this->code[$i]);
		}
	}

	public function create_lines(){
		for ($i=0; $i < 4; $i++) { 
			$color = imagecolorallocate($this->img,mt_rand(0,155),mt_rand(0,155),mt_rand(0,155));
			imageline($this->img,mt_rand(0,$this->width),mt_rand(0,$this->height),mt_rand(0,$this->width),mt_rand(0,$this->height),$color); 
		}
	}

	public function create_pixel(){
		for ($i=0; $i < 100; $i++) { 
			$color = imagecolorallocate($this->img,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
			imagesetpixel($this->img,mt_rand(0,$this->width),mt_rand(0,$this->height),$color);
		}
	}

	public function show()
	{
		$this->create_image();
		$this->create_code();
		if ($this->line_flag) {
			$this->create_lines();
		}
		if ($this->pixel_flag) {
			$this->create_pixel();
		}
		$_SESSION['code'] = $this->code;
		header('Content-type:image/png');
		imagepng($this->img);
		imagedestroy($this->img);
	}

	public function get_code(){
		return $this->code;
	}
}
session_start();
$captcha = new Captcha();
$captcha->show();