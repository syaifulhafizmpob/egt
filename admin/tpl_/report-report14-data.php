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
$pe = $this->display->_listetnik();
$pe2 = $pe;
$pe3 = $pe;
$pen = count($pe);

$jta = array();

$html = "";
$html .= "
<center>
<table id='x-table1' style='margin: 0px;padding:0px;width:100%;'>
<tr><td colspan='".($pen + 2 + 3)."' class='title'>Jumlah Gunatenaga Mengikut Sektor dan Etnik, {$syear}</td></tr>
<tr>
<th rowspan='2'style='vertical-align: top;'>Sektor</th>
<th style='vertical-align: top;' colspan='".($pen + 1 + 3)."' >Etnik</th>
</tr>
<tr>
";
if ( _array($pe) ) {
	$html .= "<td style='vertical-align: top;' class='bold right'>Melayu</td>";
	$html .= "<td style='vertical-align: top;' class='bold right'>Cina</td>";
	$html .= "<td style='vertical-align: top;' class='bold right'>India</td>";
	while( $rt = @array_shift($pe) ) {
		$html .= "<td style='vertical-align: top;' class='bold right'>".$rt['name']."</td>";
	}
}
$html .= "
<td style='vertical-align: top;' class='bold right'>Jumlah</td>
</tr>";

if ( _array($pl) ) {
	while( $rt = @array_shift($pl) ) {
		$jt = 0;
		$html .= "<tr><td>".$rt['name']."</td>";
		$ppe = $pe2;
		$dt = (int)$this->record->_report4($rt['id'],'melayu',$this->survey_id);
		$jt += $dt;
		$jta['melayu'] += $dt;
		$html .= "<td class='right'>".$dt."</td>";
		$dt = (int)$this->record->_report4($rt['id'],'cina',$this->survey_id);
		$jt += $dt;
		$jta['cina'] += $dt;
		$html .= "<td class='right'>".$dt."</td>";
		$dt = (int)$this->record->_report4($rt['id'],'india',$this->survey_id);
		$jt += $dt;
		$jta['india'] += $dt;
		$html .= "<td class='right'>".$dt."</td>";
		if ( _array($ppe) ) {
			while( $rtt = @array_shift($ppe) ) {
				$dt = (int)$this->record->_report4($rt['id'],$rtt['name'],$this->survey_id);
				$jt += $dt;
				$jta[$rtt['id']] += $dt;
				$html .= "<td class='right'>".$dt."</td>";
			}
		}
		$html .= "<td class='right'>".$jt."</td>";
		$html .= "</tr>";		
	}
}

$html .= "
<tr>
<td style='vertical-align: top;font-weight:bold;' class='right'>Jumlah</td>";
if ( _array($pe3) ) {
	$jat = 0;
	$at = $jta['melayu'];
	$jat += $at;
	$html .= "<td class='right'>".$at."</td>";
	$at = $jta['cina'];
	$jat += $at;
	$html .= "<td class='right'>".$at."</td>";
	$at = $jta['india'];
	$jat += $at;
	$html .= "<td class='right'>".$at."</td>";
	while( $rtt = @array_shift($pe3) ) {
		$at = $jta[$rtt['id']];
		$jat += $at;
		$html .= "<td class='right'>".$at."</td>";
	}
	$html .= "<td class='right'>".$jat."</td>";
}
$html .= "</tr></table></center>";

_E($html);
?>

