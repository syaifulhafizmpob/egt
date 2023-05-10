<?php
/**
 * Image manipulation Class
 *
 * @author Mohd Nawawi Mohamad Jamili <nawawi@rutweb.com>
 * @category library
 */

class nwimage {
	public $image = null;
	public $info = array(
				'type' => null,
				'mime' => null,
				'width' => null,
				'height' => null,
			);
	private $cachepath = null;
	private $cache_timeout = 1; // hours

	public function __construct($file = null) {
		$this->info = (object)$this->info;
		if ( defined('CACHEPATH') ) {
			$this->cachepath = CACHEPATH."/images";
			if ( !is_dir($this->cachepath) ) {
				if ( file_exists($this->cachepath) ) @unlink($this->cachepath);
				_mkdir($this->cachepath,0777,true);
			} else {
				_chmod($this->cachepath, 0777);
			}
		}
		if ( !_null($file) ) $this->load($file);
	}

	public function __destruct() {
		if ( @is_resource($this->image) ) {
			imagedestroy($this->image);
		}
	}

	public function load($file) {
		if ( !file_exists($file) ) return null;
		$this->file = $file;
		$info = @getimagesize($file);
		if ( _array($info) ) {
			$this->info->width = $info[0];
			$this->info->height = $info[1];
			$this->info->type = $info[2];
			$this->info->mime = $info['mime'];
		}
	}

	private function store_cache($file,$data, $isfile = false, $size = false ) {
		if ( $this->cachepath && !_null($this->cachepath) && is_dir($this->cachepath) ) {
			$file = $this->cachepath."/".$file;
			if ( $isfile ) {
				if ( @_exec(CONVERT." -colorspace RGB -thumbnail {$size} {$data} {$file}") == 0 ) {
					$this->get_cache($file);
				}
			}
			return _file_put($file,$data,false,0666);
		}
		return false;
	}

	private function get_cache($file, $printmime = true) {
		if ( $this->cachepath && !_null($this->cachepath) && is_dir($this->cachepath) ) {
			$file = $this->cachepath."/".$file;
			if ( file_exists($file) ) {
				clearstatcache();
				if ( file_exists($file) && _num($this->cache_timeout) && (time() - filemtime($file)) > ($this->cache_timeout*3600) ) {
					@unlink($file);
				} else {
					if ( $printmime ) {
						$this->load($file);
						_nocache( array('Content-type' => $this->info->type) );
					}
					@readfile($file);
					exit;
				}
			}
		}
	}

	public function string($width, $height, $string, $fontsize = 5) {
		$this->image = imagecreatetruecolor($width, $height);
		imagefill($this->image, 0, 0, 0xFFFFFF);
		imagestring($this->image, $fontsize, 2, 2, $string, 0x000000);
		_nocache( array('Content-type' => 'image/jpeg') );
		exit(imagejpeg($this->image));
	}

	public function resize($width,$height, $printmime = true) {

		$this->get_cache(md5($this->file)."-".$width."x".$height, $printmime);

		$xscale = $this->info->width / $width;
		$yscale = $this->info->height / $height;

		if ( $yscale > $xscale ) {
			$width = round($this->info->width * (1/$yscale));
			$height = round($this->info->height * (1/$yscale));
		} else {
			$width = round($this->info->width * (1/$xscale));
			$height = round($this->info->height * (1/$xscale));
		}

		if (class_exists('Imagick')) {
			$image = new Imagick($this->file);
			if ( @method_exists($image, "adaptiveResizeImage") ) {
				$image->adaptiveResizeImage($width,$height, false);

				$this->store_cache(md5($this->file)."-".$width."x".$height, $image);

				if (  $printmime ) _nocache( array('Content-type' => $this->info->type) );
				exit($image);
			}
		}
		if ( $printmime ) _nocache( array('Content-type' => $this->info->type) );
		if ( defined('CONVERT') && file_exists(CONVERT) ) {
			ob_start();
			@passthru(CONVERT." -colorspace RGB -thumbnail {$width}x{$height} {$this->file} -");
			$content = ob_get_contents();
			ob_end_clean();
			$this->store_cache(md5($this->file)."-".$width."x".$height, $content);
			exit($content);
		}
		$this->show($printmime);
		
	}

	public function resizeToHeight($height, $printmime = true) {
		$ratio = $height /  $this->info->height;
		$width = $this->info->width * $ratio;
		$this->resize($width,$height,$printmime);
	}

	public function resizeToWidth($width, $printmime = true) {
		$ratio = $width / $this->info->width;
		$height = $this->info->height * $ratio;
		$this->resize($width,$height,$printmime);
	}

	public function scale($scale, $printmime = true) {
		$width = round($this->info->width * $scale/100);
		$height = round($this->info->height * $scale/100);
		$this->resize($width, $height, $printmime);
	}

	public function show($printmime = true) {
		if ( $printmime ) _nocache( array('Content-type' => $this->info->type, 'future_expire' => true) );
		@readfile($this->file);
		exit;
	}

	public function htmlembed() {
		if ( !_null($this->file) ) {
			$imgbinary = fread(fopen($this->file, "r"), filesize($this->file));
			$data = "data:".$this->info->mime.";base64,".base64_encode($imgbinary);
			return $data;
		}
	}

	public function _imagetranstowhite($trans) {
                $w = imagesx($trans);
                $h = imagesy($trans);
                $white = imagecreatetruecolor($w, $h);
                $bg = imagecolorallocate($white, 255, 255, 255);
                imagefill($white, 0, 0, $bg);
                imagecopy($white, $trans, 0, 0, 0, 0, $w, $h);
                return $white;
        }

        public function png2jpg($input, $output = null, $quality ='100') {
                $png = imagecreatefrompng($input);
                $jpg = $this->_imagetranstowhite($png);

                if ( !_null($output) ) {
                        imagejpeg($jpg, $output, $quality);
                } else {
                        imagejpeg($jpg, $input."-tmp", $quality);
                        rename($input."-tmp",$input);
                }
                imagedestroy($png);
        }

}
?>
