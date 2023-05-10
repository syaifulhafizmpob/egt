<?php
/**
 * Main API.
 *
 * This is a main API that used in all Classes and Functions.<br />
 * This file included in bootstrap.php.
 *
 * @author Mohd Nawawi Mohamad Jamili <nawawi@rutweb.com>
 * @category library
 */
/**
 * update:
 * 14-July-2011: fix _strip_html_tags()
 * 26-Jan-2012 : add _valid_login_name()
 * 27-Jan-2012 : fix _nullsqldate()
 * 20-Mar-2012 : add _equal()
 * 23-Mar-2012 : add _query_escape()
 * 01-Apr-2012 : _ieneedfix($version) add version paramater
 * 18-Apr-2012 : _valid_common_text()
 */

/**
 * Determine if a variable is set and is NULL or empty.
 *
 * The following variable are considered to be true:<br/>
 * 1) It has been assigned the constant NULL<br />
 * 2) It has not been set to any value yet<br />
 * 3) It has not exist yet<br />
 * 4) It has been assigned to empty value<br />
 *
 * @uses is_null()
 * @param string $str The variable being evaluated.
 * @return bool Return TRUE if match, FALSE otherwise.
 */
function _null($str) {
        if ( @is_null($str) || "$str"=="" ) return true;
        return false;
}

/**
 * Determine if a variable is number.
 *
 * @uses preg_match()
 * @param int $num The variable being evaluated.
 * @return bool Return TRUE if match, FALSE otherwise.
 */
function _num($num) {
	return @preg_match("/^\d+$/",$num);
}

/**
 * Check whether a variable is an array and elements in an array is not empty.
 *
 * @uses is_array()
 * @uses empty()
 * @param array $array The variable being evaluated.
 * @return bool Return TRUE if match, FALSE otherwise.
 */
function _array($array) {
        if ( @is_array($array) && !empty($array) ) return true;
        return false;
}

/**
 * Check whether a variable is an object and elements in an object is not empty.
 *
 * @uses is_object()
 * @uses empty()
 * @param object $object The variable being evaluated.
 * @return bool Return TRUE if match, FALSE otherwise.
 */
function _object($object) {
	return @is_object($object);
}

/**
 * Determine if a variable is in decimal format.
 *
 * @uses preg_match()
 * @param int $num The variable being evaluated.
 * @return bool Return TRUE if match, FALSE otherwise.
 */
function _decimal($num) {
	return @preg_match('/^\d+(\.\d+)?$/', $num);
}

/**
 * Determine if a variable is in md5 format.
 *
 * @uses preg_match()
 * @param string $str The variable being evaluated.
 * @return bool Return TRUE if match, FALSE otherwise.
 */
function _is_md5($str) {
	return preg_match("/^[A-Fa-f0-9]{32}$/", $str);
}

/**
 * Check value to find if it was serialized.
 *
 * @link http://codex.wordpress.org/Function_Reference/is_serialized
 * @param mixed $data Value to check if was serialized.
 * @return bool Return TRUE if serialized, FALSE otherwise.
 */
function _is_serialized( $data ) {
	// if it isn't a string, it isn't serialized
	if ( !is_string( $data ) )
		return false;
	$data = trim( $data );
	if ( 'N;' == $data )
		return true;
	if ( !preg_match( '/^([adObis]):/', $data, $badions ) )
		return false;
	switch ( $badions[1] ) {
		case 'a' :
		case 'O' :
		case 's' :
			if ( preg_match( "/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data ) )
				return true;
			break;
		case 'b' :
		case 'i' :
		case 'd' :
			if ( preg_match( "/^{$badions[1]}:[0-9.E-]+;\$/", $data ) )
				return true;
			break;
	}
	return false;
}

/**
 * Determine if a variable is in email format.
 *
 * @uses preg_match()
 * @param string $email The variable being evaluated.
 * @return bool Return TRUE if match, FALSE otherwise.
 */
function _check_email($email, $strict = false) {
        if ( filter_var($email, FILTER_VALIDATE_EMAIL) ) {
		if ( $strict ) {
			if ( function_exists("getmxrr") ) {
				list($prefix, $domain) = split("@",$_email);
				return @getmxrr($domain, $maxhost);
			}
		}
		return true;
        }
        return false;
}

/**
 * glob wrapper. If error return empty array.
 *
 * @uses glob()
 * @param string $pat The pattern. No tilde expansion or parameter substitution is done.
 * @param int $flag default to GLOB_BRACE.
 * @return mixed Returns an array containing the matched files/directories, an empty array if no file matched or FALSE on error.
 */
function _glob($pat,$flag=GLOB_BRACE) {
	$return = glob($pat,$flag);
	if ( $return != false ) return $return;
	return array();
}

/**
 * Convert byte size into human readable size
 *
 * @link http://en.wikipedia.org/wiki/Kibibyte
 * @uses count()
 * @param string $size The variable being evaluated.
 * @param string $unit string Optional: unit to convert.
 * @param string $retstring output format.
 * @return mixed formatted string.
 */
function _byteconvert($size, $unit = null, $retstring = null) {
        $sizes = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB');
        $ii = count($sizes) - 1;
        $unit = array_search($unit, $sizes);
        if ( $unit === null || $unit === false ) {
                $unit = $ii;
        }
        if ( $retstring === null ) {
                $retstring = '%01.2f %s';
        }
        $i = 0;
        while ( $unit != $i && $size >= 1024 && $i < $ii ) {
                $size /= 1024;
                $i++;
        }
        return sprintf($retstring, $size, $sizes[$i]);
}

/**
 * Deletes a file.
 *
 * Deletes filename or filename that match with pattern.
 *
 * @uses unlink()
 * @uses basename()
 * @uses is_dir()
 * @uses file_exists()
 * @uses clearstatcache()
 * @see _glob()
 * @param string $file Path to the file or pattern match.
 * @return bool Return TRUE if match, FALSE otherwise.
 */
function _unlink($file) {
        clearstatcache();
	if ( basename($file) == '/' ) return false;
        if ( is_file($file) ) {
                return @unlink($file);
        }
        $files = _glob($file);
        if ( _array($files) ) {
                foreach( $files as $ln ) {
                        if ( is_dir($ln) ) {
                                _unlink("$ln/*");
                        } else {
                                if ( file_exists($ln) ) {
					unlink($ln);
				}
                        }
                }
                return true;
        }
        return false;
}

function _rmrf($file) {
        clearstatcache();
	if ( basename($file) == '/' ) return false;
	if ( file_exists("/bin/rm") ) {
		@exec("/bin/rm -rf $file",$output,$ret);
		return ( $ret == 0 ? true : false );
	}
	return @_unlink($file);
}

/**
 * Reads entire file into an array.
 *
 * Reads entire file into an array with skip empty lines 
 * and ignore newline character.<br />
 * Can read zlib compressed file automatically if detected.
 *
 * @uses file
 * @param string $file file
 * @return mixed Returns the file in an array. Upon failure, returns FALSE.
 */
function _file($file) {
	clearstatcache();
	if ( file_exists($file) ) {
		return file("compress.zlib://$file",FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
	}
	return array();
}

/**
 * Makes directory wrapper.
 */
function _mkdir($path, $mode = 0755, $recursive = true) {
	$umask = umask(0);
	$ret = @mkdir($path, $mode, $recursive);
	umask($umask);
	return $ret;
}

/**
 * Write/Append a string to a file. Serialized if string is an array.
 *
 * @uses file_put_contents()
 * @uses dirname()
 * @uses is_array()
 * @uses serialize()
 * @see _mkdir()
 * @uses umask()
 * @uses chmod()
 * @see _array()
 * @param string $filename Path to the file where to write the data.
 * @param mixed $text The data to write. Can be either a string or an array.
 * @param bool $append If TRUE data will append to end of file.
 * @param int $chmod File permission mode parameters.
 * @param bool $compress If TRUE data will compress using zlib.
 * @return bool Return TRUE if success, FALSE otherwise.
 */
function _file_put($filename, $text, $append=false, $chmod=0600, $compress = false) {
	$dirname = dirname($filename);

	if ( @is_array($text) || @is_object($text) ) {
		if ( _array($text) || _object($text) ) {
			$text=serialize($text);
		} else {
			$text="";
		}
	}

	$filenamec = $filename;
	if ( $compress ) {
		$filenamec = "compress.zlib://{$filename}";
	}

	$stat = false;
	if ( $append ) {
		$stat = @file_put_contents($filenamec,$text,FILE_APPEND);
	} else {
		if ( !_null($dirname) && !file_exists($dirname) ) {
			_mkdir($dirname,0700,true);
		}
		if ( $compress ) {
			$stat = @file_put_contents($filenamec, $text);
		} else {
			$stat = @file_put_contents($filenamec, $text, LOCK_EX);
		}
	}
	if ( $stat ) {
		@chmod($filename, $chmod);
		return true;
	}
	return false;
}

/**
 * Reads entire file into a string. Unserialized if string is an array.
 *
 * @uses file_get_contents()
 * @uses unserialize()
 * @uses trim()
 * @uses file_exists()
 * @see _is_serialized()
 * @see _array()
 * @param string $file Name of the file to read.
 * @return mixed Return can be either a string or an array and NULL otherwise.
 */
function _file_get($file) {
	clearstatcache();
	if ( file_exists($file) ) {
		$buff = trim(@file_get_contents("compress.zlib://$file"));
		if ( _is_serialized($buff) ) {
			$buff = unserialize($buff);
			if ( _array($buff) ) {
				if ( !_null($buff['nwobject']) ) {
					unset($buff['nwobject']);
					$buff = (object)$buff;
				}
				return $buff;
			}
		}
		return $buff;
	}
	return null;
}


/**
 * Retrieve the name of the function that called class/function.
 *
 * @uses debug_backtrace()
 * @uses in_array()
 * @used array_reverse()
 * @see _array()
 * @see _null() 
 * @param mixed $exclude name to exlude
 * @return string The name of the calling function
 */
function _get_caller($exclude = null) {
	if ( !is_callable('debug_backtrace') ) return null;
	$bt = debug_backtrace();
	$caller = array();
	$bt = array_reverse( $bt );
	foreach ( (array) $bt as $call ) {
		if ( @$call['class'] == __CLASS__ ) continue;
		if ( _array($exclude) && 
			(in_array(@$call['class'], $exclude) || in_array(@$call['function'], $exclude)) ) continue;
		if ( !is_array($exclude) && !_null($exclude) ) {
			if (@$call['class'] == $exclude || @$call['function'] == $exclude ) continue;
		}
		$function = $call['function'];
		if ( isset( $call['class'] ) ) $function = $call['class'] . "->$function";
		$caller[] = $function;
	}
	$caller = join( ', ', $caller );
	return $caller;
}

/**
 * Redirect to another url.
 *
 * @param string $url url location
 */
function _redirect($url) {
	if ( php_sapi_name() != 'cli' ) {
		if ( @ini_get('output_buffering') == 0 && headers_sent() ) {
			$_data  = '<script type="text/javascript">';
			$_data .= 'self.location.href="'.$url.'";';
			$_data .= '</script>';
			$_data .= '<noscript><meta http-equiv="refresh" content="0;url='.$url.'" /></noscript>';
			exit($_data);
		}
		header("Location: $url");
		exit;
	}
}

/**
 * no cache header.
 */
function _nocache($header = array()) {
	if ( php_sapi_name() != 'cli' ) {
		if ( @ini_get('output_buffering') == 0 && headers_sent() ) return null;
		$future_expire = false;
		if ( _array($header) ) {
			if ( !_null($header['future_expire']) ) {
				$future_expire = $header['future_expire'] ? $header['future_expire'] : false;
				unset($header['future_expire']);
			}
			foreach($header as $tag => $val) {
				header($tag.': '.$val);
			}
		}
		if ( !$future_expire ) {
			header('Expires: Thu, 21 Jan 1978 00:00:00 GMT');
			header('Last-Modified: '.@gmdate('D, d M Y H:i:s').' GMT');
			header('Cache-Control: no-store, no-cache, must-revalidate');
			header('Cache-Control: post-check=0, pre-check=0',false);
			header('Pragma: no-cache');
			header('Expires: -1');
		} else {
			$offset = 60 * 60;
			$expire = "expires: " . gmdate ("D, d M Y H:i:s", time() + $offset) . " GMT";
			header($expire);
		   	header("cache-control: must-revalidate");
		}
	}
}

/**
 * Get user ip.
 *
 * @return string null | ip address
 */
function _getuserip() {
	if ( php_sapi_name() != 'cli' ) {
		if ( !_array($_SERVER) ) return null;
		$_userip = null;

		// 18/09/2011 - cloudflare
		if ( !_null($_SERVER['HTTP_CF_CONNECTING_IP']) ) {
			$_userip = $_SERVER['HTTP_CF_CONNECTING_IP'];
		} elseif ( !_null($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
			$_userip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} elseif ( !_null($_SERVER['HTTP_CLIENT_IP']) ) {
			$_userip = $_SERVER['HTTP_CLIENT_IP']; 
		} else {
			$_userip = $_SERVER['REMOTE_ADDR']; 
		}
		if ( preg_match("/,\s(\S+)$/", $_userip, $mm) ) {
			$_userip = $mm[1];
		}
		return ( !_null($_userip) ? trim($_userip) : null );
	}
	return null;
}

/**
 * Get user agent.
 *
 * @link http://php.net/manual/en/reserved.variables.server.php $_SERVER['HTTP_USER_AGENT']
 * @return string User agent|null
 */
function _getuseragent() {
	if ( php_sapi_name() != 'cli' ) {
		return ( !_null($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null);
	}
	return null;
}

/**
 * Generate a random string.
 *
 * @uses count()
 * @uses rand()
 * @param int $len maximum string to return
 * @return string Random string
 */
function _rand_text($len=10) {
	$h = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'w','A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'W');
	$hlen = count($h);
	$c = "";
	for($x=0; $x < $len; $x++) {
		$r = rand(0, $hlen - 1);
		$c = $c.$h[$r];
	}
	return $c;
}

/**
 * Encrypt string with base64 encoding
 *
 * @uses base64_encode()
 * @uses strlen()
 * @uses chr()
 * @uses ord()
 * @uses strtr()
 * @param string $string string to encrypt
 * @param string $epad salt string
 * @return string encrypted string
 */
function _base64_encrypt($string, $epad='!!$$!!') {
        $mykey = '!!$'.$epad.'!!';
        $pad = base64_decode($mykey);
        $encrypted='';
        for( $i = 0; $i < strlen($string); $i++ ) {
                $encrypted .= chr( ord($string[$i]) ^ ord($pad[$i]) );
        }
        return strtr(base64_encode($encrypted), "=/","-_");
}

/**
 * Decrypt _base64_encrypt() encrypted string
 *
 * @see _base64_encrypt()
 * @uses base64_decode()
 * @uses strlen()
 * @uses chr()
 * @uses ord()
 * @uses strtr()
 * @param string $string encrypted string
 * @param string $epad salt string
 * @return string decrypted string
 */
function _base64_decrypt($string, $epad='!!$$!!') {
        $mykey = '!!$'.$epad.'!!';
        $pad = base64_decode($mykey);
        $encrypted = base64_decode(strtr($string, "-_","=/"));
        $decrypted = '';
        for( $i = 0; $i < strlen($encrypted); $i++) {
                $decrypted .= chr( ord($encrypted[$i]) ^ ord($pad[$i]) );
        }
        return $decrypted;
}

function _rmdir($dir) {
	if ( $dir == '/' ) return false;
	if (!file_exists($dir)) return true;
	if (!is_dir($dir)) return unlink($dir);
	foreach( scandir($dir) as $item ) {
		if ( $item == '.' || $item == '..' ) continue;
		if ( !_rmdir($dir.DIRECTORY_SEPARATOR.$item) ) return false;
	}
	return rmdir($dir);
}

function _chmod($path, $mode) { 
	if ( !is_dir($path) ) return @chmod($path, $mode);
	$dh = opendir($path);
	while ( ($file = readdir($dh) ) !== false ) { 
		if ( $file != '.' && $file != '..' ) {
			$fullpath = $path.'/'.$file; 
			if ( is_link($fullpath) ) { 
				return false;
			} elseif ( !is_dir($fullpath) && !@chmod($fullpath, $mode) ) {
				return false;
			} elseif ( !_chmod($fullpath, $mode) ) { 
		        	return false;
			}
		}
	}
	closedir($dh);
	return @chmod($path, $mode);
}

function _basehost() {
	if ( !_array($_SERVER) || _null($_SERVER["HTTP_HOST"]) ) return null;
	$schema = ( !_null($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] = "on") ? "https://": "http://";
	$host = $_SERVER["HTTP_HOST"];
	return $schema.$host;
}

function _baseurl() {
	if ( php_sapi_name() != 'cli' && !_null($_SERVER['SCRIPT_NAME']) ) {
		$base = substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], "/")+1);
		if ( $base != '/') $base = rtrim($base,'/');
		$host = _basehost();
		$base = ( !_null($host) ? ( ( $base != '/' ) ? $host.$base : $host."/".$base ) : ( ( $base != '/' ) ? $base : "" ) );
		return rtrim($base,'/');
	}
	return null;
}

function _locationurl() {
	if ( php_sapi_name() != 'cli' ) {
		if ( !_null($_SERVER['REQUEST_URI']) && function_exists('_basehost') ) {
			$base = _basehost().$_SERVER['REQUEST_URI'];
			return rtrim($base,'/');
		}
		return _baseurl();
	}
	return null;
}


function _t($str) {
	$arg=array();
        for ( $i=1; $i < func_num_args(); $i++ ) {
		$arg[] = func_get_arg($i);
	}
	if ( _array($arg) ) {
		if ( ($xstr=vsprintf( _($str), $arg)) ) {
			echo "$xstr";
			return;
		}
	}
	echo _($str);
}

function _tr($str) {
	$arg=array();
        for ( $i=1; $i < func_num_args(); $i++ ) {
		$arg[] = func_get_arg($i);
	}
	if ( _array($arg) ) {
		if ( ($xstr=vsprintf( _($str), $arg)) ) {
			return "$xstr";
		}
	}
	return _($str);
}

function _E($str) {
	echo $str;
}

function _CE($str) {
	if ( IS_CLI ) echo $str."\n";
}

function _apache_deny($path) {
        if ( (substr(php_sapi_name(),0,6) == 'apache') && is_dir($path) && !file_exists($path."/.htaccess") ) {
                @_file_put($path."/.htaccess","Options -Indexes\nDeny from all\n", false, 0666);
        }
}

function _minify_css($code) {
	$handle = new Minify_CSS();
	if ( @is_object($handle) ) {
		$css = $handle->minify($code);
		return ( !_null($css) ? $css : $code );
	}
	return $code;
}

function _minify_js($code) {
	$handle = new JavaScriptPacker($code, 'None', true, false);
	if ( @is_object($handle) ) {
		$js = $handle->pack();
		return ( !_null($js) ? $js : $code );
	}
	return $code;
}

function _minify_html($code) {
	$handle = new Minify_HTML($code,array('cssMinifier' => '_minify_css', 'jsMinifier' => '_minify_js') );
	if ( @is_object($handle) ) {
		$html = $handle->process();
		return ( !_null($html) ? $html : $code );
	}
	return $code;
}

function _exec($cmd) {
        $spec = array(
                0 => array("pipe", "r"),
                1 => array("file", "/dev/null", "w"),
                2 => array("file", "/dev/null", "w"),
        );
        $proc = proc_open($cmd,$spec,$pipes);
        if ( is_resource($proc))  {
                $stdin = $pipes[0];
                $stdout = $pipes[1];
                $stderr = $pipes[2];
                @fclose($stdin);
                @fclose($stdout);
                @fclose($stderr);
                return proc_close($proc);
        }
        return 1;
}

function _check_upload_filename($file, $max_file_length = 260) {
	$valid_chars_regex = '.A-Z0-9_ !@#$%^&()+={}\[\]\',~`-';
	$file_name = preg_replace('/[^'.$valid_chars_regex.']|\.+$/i', "", basename($file) );
	if ( strlen($file_name) == 0 || strlen($file_name) > $max_file_length ) {
		return false;
	}
	return true;
}

function _get_upload_max_size() {
	$post_max_size = ini_get('post_max_size');
	$unit = strtoupper(substr($post_max_size, -1));
	$multiplier = ($unit == 'M' ? 1048576 : ($unit == 'K' ? 1024 : ($unit == 'G' ? 1073741824 : 1)));
	$size = $multiplier*(int)$post_max_size;
	return $size;
}

function _check_upload_post_max_size() {
	$size = _get_upload_max_size();
	if ((int)$_SERVER['CONTENT_LENGTH'] > $size) {
		return false;
	}
	return true;
}

/**
 * Check if browser used Internet Explorer below than 9
 */
function _ieneedfix($num = 9) {
        if ( !_null($_SERVER["HTTP_USER_AGENT"]) ) {
                if ( preg_match("/MSIE\s+(\d+)/", $_SERVER["HTTP_USER_AGENT"], $mm ) ) {
                        if ( $mm[1] < $num ) return true;
                }
        }
	return false;
}

function _parse_str($str, $delim = ',') {
        $ret = array();
        $data = explode($delim,$str);
        while( $list = array_shift($data) ) {
                list($key, $value) = explode("=", $list);
                $ret[$key] = $value;
        }
        return $ret;
}

function _cut_str($str, $len = 20, $exact = false) {
	if ( utf8_strlen($str) >= $len ) {
		if ( $exact ) {
			$str = utf8_substr($str,0,$len);
		} else {
			$str = utf8_substr($str,0,$len - 3)."...";
		}
	}
	return $str;
}

/**
 * html_entity_decode wrapper for default options ENT_COMPAT and UTF-8
 */
function _html_entity_decode($text) {
	return html_entity_decode($text, ENT_COMPAT, "UTF-8");
}

/**
 * htmlspecialchars wrapper for default options ENT_COMPAT and UTF-8
 */
function _htmlspecialchars($str) {
	return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/**
 * Remove HTML tags, including invisible text such as style and
 * script code, and embedded objects.
 *
 * @uses strip_tags()
 * @uses preg_replace()
 * @uses str_replace()
 * @uses html_entity_decode()
 * @param string $text The input string.
 * @return string Returns the stripped string.
 */
function _strip_html_tags($text) {
	$text = preg_replace(
			array(
				'#<!-.*?-\s*>#s',
				'#<\s*head[^>]*?>.*?<\s*/\s*head\s*>#si',
				'#<\s*script[^>]*?>.*?<\s*/\s*script\s*>#si',
				'#<\s*style[^>]*?>.*?<\s*/\s*style\s*>#si',
				'#<\s*object[^>]*?>.*?<\s*/\s*object\s*>#si',
				'#<\s*embed[^>]*?>.*?<\s*/\s*embed\s*>#si',
				'#<\s*applet[^>]*?>.*?<\s*/\s*applet\s*>#si',
				'#<\s*noscript[^>]*?>.*?<\s*/\s*noscript\s*>#si',
				'#\n#si', '#\r#si',
				'#<\s*noembed[^>]*?>.*?<\s*/\s*noembed\s*>#si'
			),
			array(' ',' ',' ',' ',' ',' ',' ', ' ', ' ', '\1','\1'),
			$text
		);
	do {
		$count = 0;
		$text = preg_replace('/(<)([^>]*?<)/' , '&lt;$2' , $text , -1 , $count);
	} while ($count > 0);
	$text = strip_tags($text);
	$text = str_replace('>' , '&gt;' , $text);
	return trim($text);
}

function _obj_caller() {
	if ( !@_object($GLOBALS['_pt']) || !$GLOBALS['_pt']->dbready ) return false;
	return true;
}

function _tplname($name) {
	$name = basename($name);
	if ( preg_match("/(.*?)(\-\S+)?\.php$/", $name, $mm) ) {
		return $mm[1];
	}
	return $name;
}

function _timetostr($time) {
	$diff = time() - $time;
	$after = $diff < 0;
	$diff = abs($diff);
	if ( $diff == 0 ) return _tr("now");
	$periods = array(
			_tr("year") => 30879000,
			_tr("month") => 2592000,
			_tr("week") => 604800,
			_tr("day") => 86400,
			_tr("hour") => 3600,
			_tr("minute") => 60,
			_tr("second") => 1
		);
	foreach($periods as $key => $period) {
		if ( $diff >= $period ) {
			$val = round($diff / $period);
			$string = $val . ' ' . $key . ($val > 1 ? 's' : '');
			if ( $after ) {
				if ( $val == 24 ) {
					$string = _tr("tomorrow");
				} else {
					$string = _tr("in %s",$string);
				}
			} else {
				$string .= _tr(" ago");
			}
			return $string;
		}
	}
}

function _safe_eval($content) {
	
	$allowfunc = "explode,implode,date,time,round,trunc,rand,ceil,floor,srand,";
	$allowfunc .= "strtolower,strtoupper,substr,strstr,stristr,strpos,print,print_r,echo,preg_match";

	$allowed = explode(',', $allowfunc );
	$errors = array();
	$tokens = token_get_all($content); 
	$vcall = '';
	
	foreach($tokens as $token) {
		if ( _array($token)) {
			$id = $token[0];
			switch ($id) {
				case(T_VARIABLE): { $vcall .= 'v'; break; }
				case(T_CONSTANT_ENCAPSED_STRING): { $vcall .= 'e'; break; }
				case(T_STRING): { $vcall .= 's'; }
				case(T_REQUIRE_ONCE): case(T_REQUIRE): case(T_NEW): case(T_RETURN):
				case(T_BREAK): case(T_CATCH): case(T_CLONE): case(T_EXIT):
				case(T_PRINT): case(T_GLOBAL): case(T_ECHO): case(T_INCLUDE_ONCE):
				case(T_INCLUDE): case(T_EVAL): case(T_FUNCTION): case(T_GOTO):
				case(T_USE): case(T_DIR): {
					if ( array_search($token[1], $allowed) === false) {
						return "Illegal call: ".$token[1];
					}
				}
			}
		}
		else $vcall .= $token;
	}
	
	// check for dynamic functions
	if ( stristr($vcall, 'v(') !='' ) return "Illegal dynamic function call";
	
	ob_start();
	eval("?>".$content."<?php ");
	$data = ob_get_contents();
	ob_end_clean();
	return $data;
}

function _nullsqldate($date) {
	if ( !_null($date) && $date != '0000-00-00' && $date != '0000-00-00 00:00:00' && @strtotime($date) > 0 ) return false;
	return true;
}

function _datediff(Datetime $date_start, Datetime $date_stop, $symbol = false) {
        $start = $date_start->format('U');
        $stop = $date_stop->format('U');
        $invert = ( $start > $stop ? "-" : "+" );
        $days = round(abs($stop - $start) / (60*60*24));
        return ( $symbol ? $invert.$days : $days );
}

function _valid_login_name($str) {
	if ( preg_match("/^\d+/", $str) ) return false;
	for($x=0;$x < strlen($str); $x++) {
		if ( !preg_match("/[a-z0-9\.]/i", $str{$x}) ) {
			return false;
		}
	}
	return true;
}

function _valid_login_name_space($str) {
	if ( preg_match("/^\d+/", $str) ) return false;
	for($x=0;$x < strlen($str); $x++) {
		if ( !preg_match("/[a-z0-9\. ]/i", $str{$x}) ) {
			return false;
		}
	}
	return true;
}

/** useful for username and similar */
function _valid_common_text($str) {
	for($x=0;$x < strlen($str); $x++) {
		if ( !preg_match("/[a-z0-9\. \`\']/i", $str{$x}) ) {
			return false;
		}
	}
	return true;
}

/* Note: parameter sometime need to convert to string: _only_number((string)$str) */
function _only_number($str) {
	$num = "";
	for($x=0;$x < strlen($str) + 1; $x++) {
		if ( preg_match("/[0-9]/", $str{$x}) ) {
			$num .= $str{$x};
		}
	}
	return trim($num);
}

/* Note: parameter sometime need to convert to string: _only_number_letter((string)$str) */
function _only_number_letter($str) {
	$num = "";
	for($x=0;$x < strlen($str) + 1; $x++) {
		if ( preg_match("/[a-z0-9]/i", $str{$x}) ) {
			$num .= $str{$x};
		}
	}
	return trim($num);
}

/* match in-case sensitive */
function _equal($first,$second) {
	return ( strtoupper($first) === strtoupper($second) );
}

/* use in $this->query: most common with LIKE command */
function _query_escape($text) {
	$sstr = addslashes($text);
	$pat[0] = "/\\\\/";
	$rep[0] = "\\\\\\\\";
	$pat[1] = "/\\'/";
	$rep[1] = "\'";
	$pat[2] = '/\\"/';
	$rep[2] = '\"';
	$sstr = preg_replace($pat, $rep, $sstr);
	return $sstr;
}
?>
