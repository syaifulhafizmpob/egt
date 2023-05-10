<?php
/**
 * minify css/javascript
 *
 * @author Mohd Nawawi Mohamad Jamili <nawawi@rutweb.com>
 * @category application
 * @update 13-Aug-2011
 */

define('MYPATH', dirname(realpath(__FILE__)) . '/' );
if ( file_exists(MYPATH.'config-sub.php') ) {
	if ( !@include_once(MYPATH.'config-sub.php') ) exit("Bootstrapping (sub) failed!\n");
}
if ( !defined('ABSPATH') ) define('ABSPATH', MYPATH);
if ( !@include_once(ABSPATH.'bootstrap.php') ) exit("Bootstrapping failed!\n");
set_autoload(MYLIB);
$handle = new handle();

if ( $handle->request['t'] != 'css' && $handle->request['t'] != 'js' 
	&& _null($handle->request['d']) 
	&& _null($handle->request['f']) ) {
	header("HTTP/1.0 503 Not Implemented");
	exit("503 Not Implemented");
}

$cachepath = $handle->cachepath."/minify";
if ( !is_dir($cachepath) ) {
	_mkdir($cachepath,0777,true);
}

$type = $handle->request['t'];
$bdir = $handle->request['d'];
$elements = explode(',', $handle->request['f']);

$lastmodified = 0;
$files = array();
while( list(,$element) = each($elements) ) {
	$path = rtrim($handle->basepath,'/').'/'.$bdir.'/'.$element;
	if ( ($type == 'js' && substr($path, -3) != '.js') || 
		($type == 'css' && substr($path, -4) != '.css')) {
		header("HTTP/1.0 403 Forbidden");
		exit("403 Forbidden");	
	}
	if ( !file_exists($path) ) continue;
	$lastmodified = @max($lastmodified, filemtime($path));
	$files[] = $path;
}

// Send Etag hash
$hash = $lastmodified . '-' . md5($handle->request['f']);
header("Etag: \"" . $hash . "\"");
$contenttype = ( $type == "css" ? "text/css" : "application/x-javascript" );

if (isset($_SERVER['HTTP_IF_NONE_MATCH']) &&  stripslashes($_SERVER['HTTP_IF_NONE_MATCH']) == '"' . $hash . '"') {
	// Return visit and no modifications, so do not send anything
	header("HTTP/1.0 304 Not Modified");
	header('Content-Length: 0');
} else {
	$cachefile = $cachepath.'/'.$hash.'.'.$type;
	if ( file_exists($cachefile) ) {
		header("Content-Type: ".$contenttype);
		header("Content-Length: " . filesize($cachefile));
		@readfile($cachefile);
		exit;
	}
	
	if ( _array($files) ) {
		$contents = '';
		while( $file = array_shift($files) ) {
			if ( $type == "js" ) $contents .= _minify_js( _file_get($file) )."\n";
			if ( $type == "css" ) $contents .= _minify_css( _file_get($file) )."\n";
		}
		if ( !_null($contents) ) _file_put($cachefile,$contents,false,0666);
		header("Content-Type: ".$contenttype);
		header('Content-Length: ' . strlen($contents));
		exit($contents);
	}
}	

?>
