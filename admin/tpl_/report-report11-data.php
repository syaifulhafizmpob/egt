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
$html = "";
$html .= "
<h1></h1>
<center>
<table id='x-table1' style='margin: 0px;padding:0px;width:100%;'>
<tr><td colspan='4' class='title'>Jumlah Gunatenaga Mengikut Sektor dan Kewarganegaraan, {$syear}</td></tr>
<tr>
<th style='vertical-align: top;'>Sektor</th>
<th style='vertical-align: top;' class='right'>Warganegara</th>
<th style='vertical-align: top;' class='right'>Bukan Warganegara</th>
<th style='vertical-align: top;' class='right'>Jumlah</th>
</tr>";

$pl = $this->display->_listcategory("peniaga");
$jum_local = 0;
$jum_alien = 0;
$jum_jum = 0;
if ( _array($pl) ) {
	while( $rt = @array_shift($pl) ) {
		$dt = $this->record->_report1($rt['id'],$this->survey_id);
		$local = ((int)$dt['local']+(int)$dt['local2']);
		$alien = ((int)$dt['alien']+(int)$dt['alien2']);
		$jum = $local + $alien;
		$html .= "<tr>";
		$html .= "<td>".$rt['name']."</td>";
		$html .= "<td class='right'>".$local."</td>";
		$html .= "<td class='right'>".$alien."</td>";
		$html .= "<td class='right'>".$jum."</td>";
		$html .= "</tr>";
		$jum_local += $local;
		$jum_alien += $alien;
		$jum_jum += $jum;
	}
}

$html .= "
<tr>
<td style='vertical-align: top;font-weight:bold;' class='right'>Jumlah</td>
<td style='vertical-align: top;' class='right'>".$jum_local."</td>
<td style='vertical-align: top;' class='right'>".$jum_alien."</td>
<td style='vertical-align: top;' class='right'>".$jum_jum."</td>
</tr></table></center>";

_E($html);
?>


