<?php
@_object($this) || exit("403 Forbidden");
$this->_notlogin();
$this->survey_id = $this->request["survey_id"];
if ( _null($this->survey_id) ) exit("Invalid survey!");
$svdb = (object)$this->survey->_getinfo($this->survey_id );
$syear = strtoupper($svdb->month)." ".$svdb->year;
?>

<style type="text/css">
#x-table1 {
	border-spacing:0;
  	border-collapse:collapse;
}
#x-table1 th {
	border-spacing:0;
  	border-collapse:collapse;
	border: 1px solid #bbbbbb;
}


#x-table1 td {
	vertical-align: top;
	padding-top: 5px;
	border: 1px solid #bbbbbb;
}

#x-table1 .border-top {
        border-top: 1px solid #bbbbbb;
}

#x-table1 .border-bottom {
        border-bottom: 1px solid #bbbbbb;
}

#x-table1 .border-left {
        border-left: 1px solid #bbbbbb;
}

#x-table1 .border-right {
        border-right: 1px solid #bbbbbb;
}
#x-table1 td.title {
	font-weight:bold;
	border: none;
	padding-left: 0px;
	font-size: 14px;
	text-align:left;
}
</style>

<?php
$pl = $this->display->_listcategory("peniaga");
$pln = count($pl);
$pl2 = $pl;
$pl3 = $pl;
$pl4 = $pl;
$pl5 = $pl;
$pl6 = $pl;
$pl7 = $pl;
$pl8 = $pl;
$jt = array();

$jmelayu = 0;
$jcina = 0;
$jindia = 0;
$jlain = 0;
$jlocal = 0;
$jalien = 0;
$jjum = 0;

$html = "";
$html .= "
<h1></h1>
<center>
<table id='x-table1' style='margin: 0px;padding:0px;width:100%;'>
<tr><td colspan='".($pln + 2)."' class='title'>Gunatenaga Warganegara Mengikut Bangsa Dan Sektor, {$syear}</td></tr>
<tr>
<th style='vertical-align: top;'>Bangsa</th>";
if ( _array($pl) ) {
	while( $rt = @array_shift($pl) ) {
		$html .= "<th style='vertical-align: top;' class='right'>".$rt['name']."</th>";

	}
}
$html .= "
<th style='vertical-align: top;' class='right'>Jumlah</th>
</tr>";

$html .= "<tr><td>Melayu</td>";
if ( _array($pl2) ) {
	while( $rt = @array_shift($pl2) ) {
		$dt = (int)$this->record->_report3($rt['id'],"melayu",$this->survey_id);
		$jmelayu += $dt;
		$jt[$rt['id']] += $dt;
		$html .= "<td class='right'>".$dt."</td>";
	}
	$html .= "<td class='right'>".$jmelayu."</td>";
}
$html .= "</tr>";

$html .= "<tr><td>Cina</td>";
if ( _array($pl3) ) {
	while( $rt = @array_shift($pl3) ) {
		$dt = (int)$this->record->_report3($rt['id'],"cina",$this->survey_id);
		$jcina += $dt;
		$jt[$rt['id']] += $dt;
		$html .= "<td class='right'>".$dt."</td>";
	}
	$html .= "<td class='right'>".$jcina."</td>";
}
$html .= "</tr>";

$html .= "<tr><td>India</td>";
if ( _array($pl4) ) {
	while( $rt = @array_shift($pl4) ) {
		$dt = (int)$this->record->_report3($rt['id'],"india",$this->survey_id);
		$jindia += $dt;
		$jt[$rt['id']] += $dt;
		$html .= "<td class='right'>".$dt."</td>";
	}
	$html .= "<td class='right'>".$jindia."</td>";
}
$html .= "</tr>";

$html .= "<tr><td>Lain-lain</td>";
if ( _array($pl5) ) {
	while( $rt = @array_shift($pl5) ) {
		$dt = (int)$this->record->_report3($rt['id'],"lain",$this->survey_id);
		$jlain += $dt;
		$jt[$rt['id']] += $dt;
		$html .= "<td class='right'>".$dt."</td>";
	}
	$html .= "<td class='right'>".$jlain."</td>";
}
$html .= "</tr>";

$html .= "<tr><td>Jumlah Warganegara</td>";
if ( _array($pl6) ) {
	while( $rt = @array_shift($pl6) ) {
		$dt = (int)$this->record->_report3($rt['id'],"local",$this->survey_id);
		$jlocal += $dt;
		$jt[$rt['id']] += $dt;
		$html .= "<td class='right'>".$dt."</td>";
	}
	$html .= "<td class='right'>".$jlocal."</td>";
}
$html .= "</tr>";

$html .= "<tr><td>Bukan Warganegara</td>";
if ( _array($pl7) ) {
	while( $rt = @array_shift($pl7) ) {
		$dt = (int)$this->record->_report3($rt['id'],"alien",$this->survey_id);
		$jalien += $dt;
		$jt[$rt['id']] += $dt;
		$html .= "<td class='right'>".$dt."</td>";
	}
	$html .= "<td class='right'>".$jalien."</td>";
}
$html .= "</tr>";

$html .= "
<tr>
<td style='vertical-align: top;font-weight:bold;' class='right'>Jumlah</td>";
if ( _array($pl8) ) {
	$jat = 0;
	while( $rt = @array_shift($pl8) ) {
		$at = $jt[$rt['id']];
		$jat += $at;
		$html .= "<td class='right'>".$at."</td>";
	}
	$html .= "<td class='right'>".$jat."</td>";
}
$html .= "</tr></table></center>";
_E($html);

?>


