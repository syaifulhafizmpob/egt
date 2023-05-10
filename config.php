<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


/** MySQL database name */
define('DB_NAME', 'egunatenaga');

/** MySQL database username */
define('DB_USER', 'egt');

/** MySQL database password */
define('DB_PASSWORD', 'egt2018');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Global Application version update (cache preventive) */
define('APPVER','20131223a');

/** Global Application name (lowercase) */
define('APPNAME','users');

/** session timeout in minutes. 0 to disable timeout. */
define('SESSION_TIMEOUT', 0);

/** Name of the session (used as cookie name) */
define('SESSION_NAME', '_respondent');

/** table prefix */
define('TABLE_PREFIX', 'r_');

/** table session */
define('TABLE_SESSION', 'r_respondent');

/** table options */
define('TABLE_OPTIONS', 'r_setting');

/** table log */
define('TABLE_LOGS', 'r_respondent_logs');

/** 
 * Server date/timezone 
 *
 * @link http://www.php.net/manual/en/timezones.php timezone
 */
define('DATETIMEZONE', 'Asia/Kuala_Lumpur');

/** Remove whitespace for _POST, _GET and _REQUEST variable **/
define('TRIM_GVAR', true);

/** Minify html,javascript and css */
define('MINIFY', true);

/** webkit html2pdf, wkhtmltopdf-amd64 for 64bit and wkhtmltopdf-i386 for 32bit */
define('WKHTMLTOPDF', ABSPATH."/bin/wkhtmltopdf-amd64");
?>
