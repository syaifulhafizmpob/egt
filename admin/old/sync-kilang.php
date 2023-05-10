<?php
define('MYPATH', dirname(realpath(__FILE__)) . '/' );
if ( file_exists(MYPATH.'config-sub.php') ) {
	if ( !@include_once(MYPATH.'config-sub.php') ) exit("Bootstrapping (sub) failed!\n");
}
if ( !defined('ABSPATH') ) define('ABSPATH', MYPATH);
if ( !@include_once(ABSPATH.'bootstrap.php') ) exit("Bootstrapping failed!\n");
set_autoload(MYLIB);
$handle = new handle();
$handle->_process();
$display = new display();
$record = new record();
$ddb = new nwdb("161.142.157.38","egt","egt123","ekilang_main");

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

foreach( array("pl91","pl101","pl102","pl104", "pl111") as $kl) {
echo "############ ".$kl."\n";
$f = "/opt/web/egunatenaga/admin/sql/ekilang_main/".$kl.".sql";
if ( !file_exists($f) ) {
	echo "!!! $f not found";
	continue;
}
$sql = _file_get($f);
if ( _null($sql) ) continue;
$p = array();
$dt = $ddb->get_results($sql, ARRAY_A);
while( $rt = @array_shift($dt) ) {
	foreach($rt as $a => $b) {
		$rt[$a] = $handle->escape($b);
	}

	if ( !_null($rt['code']) ) {
		$rt['daerah_id'] = $display->_getdaerah_id_bycode($rt['code']);
	}
	unset($rt['code']);
	if ( $handle->check_field("r_respondent", "nolesen", array("nolesen" => $rt['nolesen']) ) ) {	
		echo "!!!!! Nolesen exist {$rt['nolesen']}\n";
		continue;
	}
	if ( $rt['daerah'] == "KLANG" ) $rt['daerah'] = "KELANG";
	$rt['negeri'] = preg_replace("/W.P/","Wilayah Persekutuan", $rt['negeri']);
	$rt['negeri'] = preg_replace("/Persekutuan PUTRA JAYA/","Persekutuan putrajaya", $rt['negeri']);
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
	$rt['daerah_id'] = $handle->display->_getdaerah_id($rt['daerah']);
	if ( _null($rt['state_id']) ) {
		echo "????? State ID NULL\n";
		print_r($rt);
		continue;
	}
	unset($rt['negeri'],$rt['daerah']);
	$p = array_merge($p1,$rt);
	$handle->insert("r_respondent", $p);
	echo $handle->insert_id."\n";
	$record->_laporfirst('1',$handle->insert_id,$p['category_id']);
}
}
?>
