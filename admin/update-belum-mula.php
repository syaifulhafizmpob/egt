<?php
define('MYPATH', dirname(realpath(__FILE__)) . '/' );
if ( file_exists(MYPATH.'config-sub.php') ) {
	if ( !@include_once(MYPATH.'config-sub.php') ) exit("Bootstrapping (sub) failed!\n");
}
if ( !defined('ABSPATH') ) define('ABSPATH', MYPATH);
if ( !@include_once(ABSPATH.'bootstrap.php') ) exit("Bootstrapping failed!\n");
$nl = ( IS_CLI ? "\n" : "<br>" );
set_autoload(MYLIB);
$handle = new handle();
$handle->_process();
$record = new record();
$survey = new survey();
$psurvey_id = $survey->_getcurrent_id();
$survey = new survey();
$psurvey_id = $survey->_getcurrent_id();
$psurvey_data = $survey->_getinfo($psurvey_id);
$psurvey_year = $psurvey_data['year'];
$psurvey_month = ( $psurvey_data['month'] == "jun" ? "06" : "12" );

echo "For Survey: ".strtoupper($psurvey_data['month']." ".$psurvey_year)."\n";
$list = $handle->get_results("select id,category_id from r_respondent where YEAR(ldate) = '{$psurvey_year}'", ARRAY_A);
if ( _array($list) ) {
    while( $rt = array_shift($list) ) {

        $respondent_id = $rt['id'];
        $category_id = $rt['category_id'];

        $save = array();

        $sql = "select id from r_record_meta where survey_id='{$psurvey_id}' ";
        $sql .= "and respondent_id='{$respondent_id}' and category_id='{$category_id}'";
        if ( !$handle->query($sql) ) {
            $save['status'] = "belum_mula";
            $save['submit'] = "no";
            $save['survey_id'] = $psurvey_id;
            $save['respondent_id'] = $respondent_id;
            $save['category_id'] = $category_id;
            //$save['sdate'] = date('Y-m-d H:i:s');
            $save['cdate'] = date('Y-m-d H:i:s');
            if ( $handle->insert('r_record_meta', $save) ) {
                echo "OK: set belum_mula -> {$respondent_id}\n";
            } else {
                echo "OK2: {$respondent_id}\n";
            }
        } else {
            echo "SKIP: sudah set belum_mula -> {$respondent_id}\n";
        }
    }
}

exit(0);
