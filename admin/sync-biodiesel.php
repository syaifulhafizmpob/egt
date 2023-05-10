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
$display = new display();
$record = new record();
$survey = new survey();
$psurvey_id = $survey->_getcurrent_id();
$psurvey_data = $survey->_getinfo($psurvey_id);
$psurvey_year = $psurvey_data['year'];
$psurvey_month = ( $psurvey_data['month'] == "jun" ? "06" : "12" );
$ddb = new nwdb("10.0.2.43","egt","egt123","ebiodiesel_db");

$p1['category_id'] = "";
$p1['subcategory_id'] = "";
$p1['pass'] = _base64_encrypt('123456','abahko');
$p1['pegawai'] = "pegawai";
$p1['jawatan'] = "pegawai";
$p1['company'] = "";
$p1['nolesen'] = "";
$p1['address'] = "";
$p1['address2'] = "";
$p1['address3'] = "";
$p1['address_surat'] = "";
$p1['address_surat2'] = "";
$p1['address_surat3'] = "";
$p1['daerah_id'] = "";
$p1['state_id'] = "";
$p1['phone'] = "";
$p1['fax'] = "";
$p1['email'] = "";
$p1['desc'] = "";
$p1['status'] = "on";
$p1['lastlogin'] = "";
$p1['uagent'] = "";
$p1['lastip'] = "";
$p1['cdate'] = date('Y-m-d H:i:s');
$p1['ldate'] = date('Y-m-d H:i:s');

foreach( array("kilangbiodiesel") as $kl) {
echo "############ ".$kl."{$nl}";
$f = "/var/www/html/egunatenaga/admin/sql/ebiodiesel_db/".$kl.".sql";
if ( !file_exists($f) ) {
	echo "!!! $f not found<br>{$nl}";
	continue;
}
$sql = _file_get($f);
if ( _null($sql) ) continue;
$sql = preg_replace( array("/@MONTH/","/@YEAR/"), array($psurvey_month, $psurvey_year), $sql );
$p = array();
$dt = $ddb->get_results($sql, ARRAY_A);
$update = false;
while( $rt = @array_shift($dt) ) {
	foreach($rt as $a => $b) {
		$rt[$a] = $handle->escape($b);
	}

	if (isset($rt['daerah']) && $rt['daerah'] == "KLANG" ) $rt['daerah'] = "KELANG";
	$rt['negeri'] = preg_replace("/W.P/","Wilayah Persekutuan", $rt['negeri']);
	$rt['negeri'] = preg_replace("/Persekutuan PUTRA JAYA/","Persekutuan putrajaya", $rt['negeri']);
	if (isset($rt['status']))
		$rt['status'] = ( $rt['status'] == 1 ? "on" : "off" );
	$rt['state_id'] = $handle->display->_getstate_id($rt['negeri']);
	if ( _null($rt['state_id']) ) {
		$lt = $handle->display->_liststate();
		if ( _array($lt) ) {
			while( $lr = @array_shift($lt) ) {
				foreach($rt as $a => $b) {
					if ( strstr(strtolower($b), "kelang") || strstr(strtolower($b), "klang") || strstr(strtolower($b), "kelana jaya") ) {
						$b = "selangor";
					}
					if ( strstr(strtolower($b), "butterworth") || strstr(strtolower($b), "pinang") ) {
						$b = "pulau pinang";
					}
					if ( strstr(strtolower($b), $lr['name']) ) {
						$rt['state_id'] = $lr['id'];
						break;
					}
				}
			}
		}
	}
	   if (isset($rt['daerah']))
			$rt['daerah_id'] = $handle->display->_getdaerah_id($rt['daerah']);
	if ( _null($rt['state_id']) ) {
		echo "????? State ID NULL{$nl}";
		print_r($rt);
		continue;
	}
	unset($rt['negeri'],$rt['daerah']);
	$p = array_merge($p1,$rt);
    $update = false;
	if ( $handle->check_field("r_respondent", "nolesen", array("nolesen" => $rt['nolesen'], "category_id" => $p['category_id'] ) ) ) {	
		//echo "!!!!! Nolesen exist {$rt['nolesen']}{$nl}";
		$update = true;
	}
    if ( $update ) {
        // UNCOMMENT KALAU NAK UPDATE DATA
        //unset($p['pass']);
        //unset($p['pegawai']);
        //unset($p['jawatan']);
	    //$handle->update("r_respondent", $p, array("nolesen" => $rt['nolesen']) );
        echo "Update {$rt['nolesen']}{$nl}";
        $resid = $handle->get_var("select id from `r_respondent` where nolesen='{$rt['nolesen']}' limit 1");
        if ( _num($resid) ) {
            $record->_laporfirst($psurvey_id,$resid,$p['category_id']);
        }
    } else {
	    $handle->insert("r_respondent", $p);
	    //echo $handle->insert_id."{$nl}";
        echo "Insert {$rt['nolesen']}{$nl}";
        $record->_laporfirst($psurvey_id,$handle->insert_id,$p['category_id']);
    }
}
}
?>
