<?php
/**
 * Configuration init.
 *
 * @author Mohd Nawawi Mohamad Jamili <nawawi@rutweb.com>
 * @link http://en.wikipedia.org/wiki/Bootstrapping_(computing) Bootstrapping
 * @category application
 * @update 17-Sep-2011
 */

/** Define ABSPATH as this files directory */
if ( !defined('ABSPATH') ) define('ABSPATH', dirname(realpath(__FILE__)) . '/' );

if ( !@include_once(ABSPATH.'config.php') ) exit("Configuration failed!\n");

/** library path */
if ( !defined('LIBPATH') ) define('LIBPATH', ABSPATH.'lib');

/** private setting library path */
if ( !defined('MYLIB') ) {
	if ( defined('APPNAME') ) define('MYLIB', LIBPATH.'/'.APPNAME);
}

/** tpl path */
if ( !defined('TPLPATH') ) define('TPLPATH', ABSPATH.'tpl');

/** rsc path */
if ( !defined('RSCPATH') ) define('RSCPATH', ABSPATH.'rsc');

/** ui path */
if ( !defined('UIPATH') ) define('UIPATH', ABSPATH.'ui');

/** cache path */
if ( !defined('CACHEPATH') ) define('CACHEPATH', ABSPATH.'cache');

/** static data */
if ( !defined('DATAPATH') ) define('DATAPATH', ABSPATH.'data');

/** upload */
if ( !defined('UPLOADPATH') ) define('UPLOADPATH', ABSPATH.'upload');

/** convert binary */
if ( !defined('CONVERT') ) define('CONVERT', '/usr/bin/convert');

/** session timeout in minutes. 0 to disable timeout. */
if ( !defined('SESSION_TIMEOUT') ) define('SESSION_TIMEOUT', 0);

/** Name of the session (used as cookie name) */
if ( !defined('SESSION_NAME') ) define('SESSION_NAME', '_appname');

/** session save path */
if ( !defined('SESSION_SAVEPATH') ) define('SESSION_SAVEPATH', CACHEPATH.'/session');

/** 
 * Server date/timezone 
 *
 * @link http://www.php.net/manual/en/timezones.php timezone
 */
if ( !defined('DATETIMEZONE') ) define('DATETIMEZONE', 'Asia/Kuala_Lumpur');

/** Remove whitespace for _POST, _GET and _REQUEST variable **/
if ( !defined('TRIM_GVAR') ) define('TRIM_GVAR', true);

/** Minify html,javascript and css */
if ( !defined('MINIFY') ) define('MINIFY', true);

/** define if request from jquery */
define('IS_AJAX', ( isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) || ( isset($_SERVER['HTTP_X_AJAX_REQUEST']) ) );

/**
 * Determine if loaded from web or shell.
 */
define('IS_CLI', ( php_sapi_name() == 'cli' || !isset($_SERVER['GATEWAY_INTERFACE']) ) );

/**
 * Determine if using apache web server
 */
define('IS_APACHE', ( substr(php_sapi_name(),0,6) == 'apache' ) );

/** others */
if ( !defined('XSS_PROTECT') ) define('XSS_PROTECT', true);
if ( !defined('SQL_INJECT_PROTECT') ) define('SQL_INJECT_PROTECT', true);

/**
 * Exit on error
 *
 * @access private
 */
function _exit($msg) {
	if ( !IS_CLI ) {
		if ( is_array($msg) && !empty($msg) ) {
			exit(json_encode($msg));
		}
		exit($msg);
	}
	echo "$msg\n";
	exit(1);
}

/**
 * PHP version
 *
 * @access private
 */
if ( version_compare(PHP_VERSION, '5.2.9', '<') ) {
	_exit("This software requires PHP Version 5.2.9 and above");
}

/**
 * Minimum extension must loaded
 *
 * @access private
 */

function _trydl($m) {
	if ( extension_loaded($m) ) return true;
	if ( !@ini_get('safe_mode') && @ini_get('enable_dl') && function_exists('dl') ) {
		// although we focus on linux, just give a chance to windows
		$prefix = (PHP_SHLIB_SUFFIX === 'dll') ? 'php_' : '';
		return @dl($prefix.$m.PHP_SHLIB_SUFFIX);
	}
	return false;
}

if ( !_trydl('mysql') && !_trydl('mysqli') ) {
	_exit("This software requires 'mysql' or 'mysqli' extension");
}

if ( !defined('IS_MYSQLI') ) define('IS_MYSQLI', class_exists('mysqli') ? true : false );

if ( !_trydl('gettext') ) {
	function gettext($str) {
		return $str;
	}
	function _($str) {
		return gettext($str);
	}
}

function _gd_get_info() {
	if ( imagetypes() & IMG_PNG && imagetypes() & IMG_GIF && imagetypes() & IMG_JPG ) {
		return true;
	}
	return false;
}

$extension = array('zlib', 'SPL', 'pcre', 'json', 'filter', 'sockets', 'session', 'gd', 'xml');
while( $m = @array_shift($extension) ) {
        if ( !_trydl($m) ) {
		_exit("This software requires '$m' extension");
	}
	if ( $m == 'gd' && !_gd_get_info() ) {
		_exit("This software requires '$m' extension with supported type of image png, gif and jpg");
	}
}

/**
 * Default timezone
 */
if ( !defined('DATETIMEZONE') ) define('DATETIMEZONE', 'Asia/Kuala_Lumpur');
date_default_timezone_set(DATETIMEZONE);

/**
 * Set output buffering
 *
 * @access private
 */
if (@ini_get('output_buffering') == 0) {
	@ini_set('output_buffering',4096);
}

/**
 * Turn register globals off.
 *
 * @access private
 * @return null Will return null if register_globals PHP directive was disabled
 */
function _unregister_globals() {
        if ( !ini_get('register_globals') ) return null;
	$skip = array('GLOBALS', '_GET', '_POST', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
	$input = array_merge($_GET, $_POST, $_COOKIE, $_SERVER, $_ENV, $_FILES, isset($_SESSION) && is_array($_SESSION) ? $_SESSION : array());
	foreach ( $input as $k => $v ) {
		if ( !in_array($k, $skip) && isset($GLOBALS[$k]) ) {
			$GLOBALS[$k] = null;
			unset($GLOBALS[$k]);
		}
	}
}
_unregister_globals();

/**
 * Recursive array_map
 *
 * @uses array_map()
 * @param string $func function
 * @param array $arr array
 * @return array
 */
function array_map_recursive($func, $arr) {
	$new = array();
	foreach($arr as $key => $value) {
		$new[$key] = (is_array($value) ? array_map_recursive($func, $value) : ( is_array($func) ? call_user_func_array($func, $value) : $func($value) ) );
	}
	return $new;
}

function _escape_html($str) {
	return htmlspecialchars($str, ENT_QUOTES, 'UTF-8', false);
}

function _remove_sql_inject($str) {
    $str = urldecode($str);
    $pat[] = "/'\s+AND\s+extractvalue.*/i";
    $pat[] = "/'\s+and\(.*/i";
    $pat[] = "/select\s+.*?\s+from.*/i";
    $pat[] = "/(rand|user|version|database)\(.*/i";
    $pat[] = "/union\(.*/i";
    $pat[] = "/CONCAT\(.*/i";
    $pat[] = "/CONCAT_WS\(.*/i";
    $pat[] = "/ORDER\s+BY.*/i";
    $pat[] = "/UNION\s+SELECT.*/i";
    $pat[] = "/'\s+union\s+select\+.*/i";
    $pat[] = "/GROUP_CONCAT.*/i";
    $pat[] = "/delete\s+from.*/i";
    $pat[] = "/update\s+.*?\s+set=.*/i";
    $pat[] = "/'\s+and\s+\S+\(.*/i";
    $pat[] = "/'\s+and\s+\S+\s+\(.*/i";
    return preg_replace($pat,"", $str);
}

if ( !IS_CLI ) {
	/**
	 * disable get_magic_quotes_gpc/magic_quotes_sybase
	 *
	 * @access private
	 */
	if ((function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) || @ini_get('magic_quotes_sybase')) {
		$_COOKIE = array_map_recursive('stripslashes', $_COOKIE);
		$_GET = array_map_recursive('stripslashes', $_GET);
		$_POST = array_map_recursive('stripslashes', $_POST);
		$_REQUEST = array_map_recursive('stripslashes', $_REQUEST);
	}
	if (@ini_get('magic_quotes_runtime') && function_exists('set_magic_quotes_runtime')) {
		@set_magic_quotes_runtime(0);
	}

	if ( TRIM_GVAR ) {
		$_GET = array_map_recursive('trim', $_GET);
		$_POST = array_map_recursive('trim', $_POST);
		$_REQUEST = array_map_recursive('trim', $_REQUEST);
	}

	if ( defined('SQL_INJECT_PROTECT') && SQL_INJECT_PROTECT) {
		if ( !empty($_GET) ) $_GET = array_map_recursive('_remove_sql_inject', $_GET);
		if ( !empty($_POST) ) $_POST = array_map_recursive('_remove_sql_inject', $_POST);
		if ( !empty($_REQUEST) ) $_REQUEST = array_map_recursive('_remove_sql_inject', $_REQUEST);
		if ( !empty($_COOKIE) ) $_COOKIE = array_map_recursive('_remove_sql_inject', $_COOKIE);
	}

	if ( defined('XSS_PROTECT') && XSS_PROTECT) {
		if ( !empty($_GET) ) $_GET = array_map_recursive('_escape_html', $_GET);
		if ( !empty($_POST) ) $_POST = array_map_recursive('_escape_html', $_POST);
		if ( !empty($_REQUEST) ) $_REQUEST = array_map_recursive('_escape_html', $_REQUEST);
		if ( !empty($_COOKIE) ) $_COOKIE = array_map_recursive('_escape_html', $_COOKIE);
	}

	/** zlib output compression: default 4kb if on/1 */
	ini_set('zlib.output_compression', 1);

	/** zlib output compression level: -1 = server decide, 0-9 = custom */
	ini_set('zlib.output_compression_level', -1);

	/** Set separator as like default */
	@ini_set('arg_separator.output','&');
	@ini_set('arg_separator.input','&');
}

/** library loading */
if ( !defined('LIB_UTF8_PATH') ) {
	define('LIB_UTF8_PATH',LIBPATH."/utf8");
}
if ( !@include_once(LIB_UTF8_PATH."/utf8.php") ) _exit("Loading utf8 library failed!");
lib_utf8_load();

if ( !@include_once(LIBPATH."/core/functions.php") ) _exit("Loading API library failed!");

/* debug: internal use */
function _adebug($data,$append = false) {
	_file_put("/tmp/TEST",( array($data) ? print_r($data,true) : $data ),$append,0666);
}

function _preload_images($dir = null) {
	$_dir =( !_null($dir) ? $dir : "rsc,ui,ui/images,ui/images/images" );
	$fd = CACHEPATH."/img-preload.list";
	$data = _file_get($fd);
	if ( _null($data) ) {
		$data = json_encode(glob("{".$_dir."}/{*.gif,*.png,*.jpg}", GLOB_BRACE));
		@_file_put($fd,$data,false,0666);
	}
	$js = "";
	$js .= "(function() { if ( preload_image instanceof Function ) {";
	$js .= "preload_image(".$data.");";
	$js .= "} })();";
	return $js;
}

function set_autoload($path) {
	if ( _array($GLOBALS['_AUTOLOAD']) ) {
		if ( !in_array($path, $GLOBALS['_AUTOLOAD']) ) {
			array_push($GLOBALS['_AUTOLOAD'], $path);
		}
	} else {
		$GLOBALS['_AUTOLOAD'] = array($path);
	}
}

/** class name **/
function loadclassname($path) {
        $list = _glob($path."/class-*.php");
        $returns = array();
        if ( _array($list) ) {
                while($fn = @array_shift($list) ) {
			$f = basename($fn);
                        if ( preg_match("/^class\-(\S+)\.php$/", $f, $mm ) ) {
				$returns[] = $mm[1];     
			}
                }
        }
        return $returns;
}

/** class autoload */
function autoload($name) {
	$files = array(
		LIBPATH.'/core/class-'.$name.'.php',
		LIBPATH.'/minify/class-'.$name.'.php'
	);
	if ( _array($GLOBALS['_AUTOLOAD']) ) {
		foreach($GLOBALS['_AUTOLOAD'] as $path ) {
			$fn = $path.'/class-'.$name.'.php';
			array_push($files, $fn);
		}
		unset($path);
	}
	while($file = array_shift($files) ) {
		if ( file_exists($file) ) {
			if ( !@include_once($file) ) _exit("Loading class '".$name."' failed!");
		}
	}
}
spl_autoload_register('autoload');

?>
