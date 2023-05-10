<?php
/**
 * Core Class
 *
 * @author Mohd Nawawi Mohamad Jamili <nawawi@rutweb.com>
 * @category library
 */

class core extends nwdb {
	public function __construct() {
		if ( !defined('DB_HOST') 
			|| !defined('DB_USER') 
			|| !defined('DB_PASSWORD')
			|| !defined('DB_NAME') ) {
			$this->_close('Error on configuration!');
		}
		parent::__construct(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		$this->tplcompress = MINIFY;
		$this->_cupdate = ( defined('APPVER') ? APPVER : date("Ymd") );
		$this->tplpath = TPLPATH;
		$this->cachepath = CACHEPATH;
		$this->basepath = ABSPATH;
		$this->datapath = DATAPATH;
		$this->uploadpath = UPLOADPATH;
		$this->rscpath = RSCPATH;
		$this->uipath = UIPATH;
		$this->baseurl = _baseurl();
		$this->pbaseurl = dirname(_baseurl());
		$this->locationurl = _locationurl();
		$this->rscurl = _baseurl()."/".basename($this->rscpath);
		$this->uiurl = _baseurl()."/".basename($this->uipath);
		$this->uip = _getuserip();
		$this->uagent = _getuseragent();
		$this->islogin = false;
		$this->post = ( _array($_POST) ? $_POST : array() );
		$this->get = ( _array($_GET) ? $_GET : array() );
		$this->request = array_merge($_GET, $_POST);
		$this->islogin = false;
		$this->isadmin = false;
		$this->issuperadmin = false;
		$this->_settables();
		$this->_options();
		$this->_setlang();
	}

	public function __destruct() { 
		parent::__destruct();
		return true;
	}

	public function json_return($status, $msg, $extra = null) {
		$data = array("success"=> $status, "msg" => $msg);
		if ( _array($extra) ) {
			$data = array_merge($data, $extra);
		}
		_exit($data);
	}

	public function _settables() {
		$this->table = new stdClass();
		if ( defined('TABLE_SESSION') ) $this->table->session = TABLE_SESSION;
		if ( defined('TABLE_OPTIONS') ) $this->table->options = TABLE_OPTIONS;
		if ( defined('MYLIB') && is_dir(MYLIB) ) {
			$classname = loadclassname(MYLIB);
			if ( _array($classname) ) {
				while($objname = @array_shift($classname) ) {
					if ( $objname == __CLASS__ || $objname == "handle" ) continue;
					if ( _null($this->table->$objname) ) {
						if ( defined('TABLE_PREFIX') ) {
							$this->table->$objname = TABLE_PREFIX.$objname;
						} else {
							$this->table->$objname = $objname;
						}
					}
				}
			}
		}
	}

	public function _options() {
		$data = $this->get_results("select `param`,`value` from `".$this->table->options."` where `param` rlike '^config_' order by id ASC", ARRAY_A);
		while( $row = @array_shift($data) ) {
			$param = preg_replace("/^config_/","",$row['param']);
			$this->options->$param = $row['value'];
			if ( $param == "minify" ) {
				$this->tplcompress = ( $row['value'] == "yes" ? true : false );
			}
		}
	}

	public function _setlang() {
		$locale = $this->options->locale;
		if ( !_null($locale) ) {
			$this->options->langpref = $locale{0}.$locale{1};
			@setlocale(LC_ALL, $locale);
		}
	}

	public function _input_date($date = null) {
		if ( _null($date) ) return strftime('%Y-%m-%d', time() );
		return strftime('%Y-%m-%d', strtotime($date) );
	}

	public function _input_datetime($date = null) {
		if ( _null($date) ) return strftime('%Y-%m-%d %H:%M:%S', time() );
		return strftime('%Y-%m-%d %H:%M:%S', strtotime($date) );
	}

	public function _output_date($date, $format = "%d-%b-%Y") {
		if ( !_null($date) ) return strftime($format, strtotime($date) );
	}

	public function _output_datepicker($date, $format = "%d-%m-%Y" ) {
		if ( !_null($date) ) return strftime($format, strtotime($date) );
	}
	public function _output_datetimepicker($date, $format = "%d-%m-%Y %H:%M" ) {
		if ( !_null($date) ) return strftime($format, strtotime($date) );
	}

	public function _output_datetime($datetime = null, $format = "%d-%b-%Y %I:%M %p" ) {
		if ( _null($datetime) ) {
			$temp = time();
		} else {
			$temp = strtotime($datetime);
		}
		return strftime($format, $temp);
	}

	public function _datepicker_i18n() {
		if ( !_null($this->options->langpref) ) {
			$pref = str_replace("_", "-", $this->options->langpref);
			$file = "jquery.ui.datepicker-".$pref.".js";
			if ( file_exists($this->uipath."/i18n/".$file) ) {
				$this->_include($this->uipath."/i18n/".$file, "js", true);
			}
		}
	}

	public function _object() {
		if ( !IS_CLI ) {
			if ( !@_object($this->session) ) {
				$this->session = new session();
				$this->session->data = null;
			}

			if ( !_null($this->request[$this->session->session_name]) ) {
				session_id($this->request[$this->session->session_name]);
			}
		}

		if ( !@_object($this->nwlog) ) {
			$this->nwlog = new nwlog();
		}

		if ( defined('MYLIB') && is_dir(MYLIB) ) {
			$classname = loadclassname(MYLIB);
			if ( _array($classname) ) {
				while($objname = @array_shift($classname) ) {
					if ( $objname == __CLASS__ || $objname == "handle" ) continue;
					if ( !_null($skip) && $objname == $skip ) continue;
					if ( !@_object($this->$objname) ) {
						$this->$objname = new $objname();
					}
				}
			}
		}

		if ( !IS_CLI ) {
			if ( $this->session->check() ) {
				$this->session->data = $_SESSION[$this->session->session_name];
				$this->session->data = ( _array($this->session->data) ? (object)$this->session->data : null );
				$this->isadmin = ( !_null($this->session->data->level) && $this->session->data->level == "admin" || preg_match("/^admin_/", $this->session->data->level) || $this->session->data->level == "superadmin" ? true : false );
				$this->issuperadmin = ( !_null($this->session->data->level) && $this->session->data->level == "superadmin" ? true : false );
			}

			if ( @_object($this->session->data) ) $this->islogin = true;
		}
	}

	public function _notlogin() {
		if ( !@$this->islogin ) {
			if ( IS_AJAX ) {
				echo "<script type='text/javascript'>self.location.href=_baseurl+'/';</script>";
				exit("Session expired!");

			}
			_redirect($this->baseurl);
			exit;
		}
	}

	public function _tpl($file, $type = 'html', $once = true) {
		$file = $this->tplpath."/".$file.".php";
		if ( file_exists($file) ) {
			$this->_include($file,$type,$once);
		}
	}

	public function _include($file, $type = 'html', $once = true) {
		if ( file_exists($file) ) {
			if ( $type == 'js' ) {
				echo "\n<script type='text/javascript'>";
			} elseif ( $type == 'css' ) {
				echo "\n<style type='text/css'>";
			}
			if ( $this->tplcompress ) ob_start();
			if ( $once ) {
				include_once($file);
			} else {
				include($file);
			}
			if ( $this->tplcompress ) {
				$content = ob_get_contents();
				ob_end_clean();
				if ( $type == 'js' ) {
					echo _minify_js($content);
				} elseif ( $type == 'css' ) {
					echo _minify_css($content);
				} else {
					echo _minify_html($content);
				}
			}
			if ( $type == 'js' ) {
				echo "</script>";
			} elseif ( $type == 'css' ) {
				echo "</style>";
			}
		}
	}
}

if ( !@class_exists('qqUploadedFileXhr') ) {
	/**
	 * Handle file uploads via XMLHttpRequest
	 */
	class qqUploadedFileXhr {
		public function save($path) {    
			$input = fopen("php://input", "r");
			$temp = tmpfile();
			$realSize = stream_copy_to_stream($input, $temp);
			fclose($input);
		
			if ($realSize != $this->_getsize()) return false;
		
			$target = fopen($path, "w");        
			fseek($temp, 0, SEEK_SET);
			stream_copy_to_stream($temp, $target);
			fclose($target);
			@chmod($path,0666);
			return true;
		}

		public function _getname() {
			return $_GET['qqfile'];
		}

		public function _getsize() {
			if ( !_null($_SERVER["CONTENT_LENGTH"]) ){
				return (int)$_SERVER["CONTENT_LENGTH"];            
			} else {
				throw new Exception( _tr("Getting content length is not supported.") );
			}      
		}   
	}
}

if ( !@class_exists('qqUploadedFileForm') ) {
	/**
	 * Handle file uploads via regular form post (uses the $_FILES array)
	 */
	class qqUploadedFileForm {
		public function save($path) {
			if ( @move_uploaded_file($_FILES['qqfile']['tmp_name'], $path) ) {
				@chmod($path,0666);
				return true;
			}
			return false;
		}

		public function _getname() {
			return $_FILES['qqfile']['name'];
		}

		public function _getsize() {
			return $_FILES['qqfile']['size'];
		}
	}
}
?>
