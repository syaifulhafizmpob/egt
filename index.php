<?php
/**
 * Index page.
 *
 * @author Mohd Nawawi Mohamad Jamili <nawawi@rutweb.com>
 * @category application
 * @update 21-Jun-2011
 */
define('MYPATH', dirname(realpath(__FILE__)) . '/' );
if ( file_exists(MYPATH.'config-sub.php') ) {
	if ( !@include_once(MYPATH.'config-sub.php') ) exit("Bootstrapping (sub) failed!\n");
}
if ( !defined('ABSPATH') ) define('ABSPATH', MYPATH);
if ( !@include_once(ABSPATH.'bootstrap.php') ) exit("Bootstrapping failed!\n");
set_autoload(MYLIB);
_nocache(array('future_expire' => true));
$handle = new handle();
$handle->_process();
$handle->_index();
?>
