<?php
@_object($this) || exit("403 Forbidden");
$this->_notlogin();
$this->survey_id = $this->request["survey_id"];
if ( _null($this->survey_id) ) exit("Invalid survey!");
$svdb = (object)$this->survey->_getinfo($this->survey_id );
$syear = ucwords($svdb->month)." ".$svdb->year;
$jname = $this->jawatan->_getgroup_name($this->request["group_id"]);
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
$pl = $this->jawatan->_list_bykilang($this->request['group_id']);
$pe = $this->display->_listcategory("kilang");
$pe2 = $pe;
$pe3 = $pe;
$pen = count($pe);
$jta = array();

$html = "";
$html .= "
<center>
<table id='x-table1' style='margin: 0px;padding:0px;width:100%;'>
<tr><td colspan='".($pen + 1)."' class='title'>Purata Pendapatan Pekerja Bagi Bukan Warganegara Mengikut Sektor Untuk Kategori {$jname}, {$syear}</td></tr>
<tr>
<th rowspan='2'style='vertical-align: top;'>{$jname}</th>
<th style='vertical-align: top;' class='center' colspan='".($pen + 1)."' >Purata Gaji Seorang Sebulan (RM)</th>
</tr>
<tr>
";
if ( _array($pe) ) {
	while( $rt = @array_shift($pe) ) {
		$html .= "<td style='vertical-align: top;' class='bold right'>".$rt['name']."</td>";
	}
}
//$html .= "
//<td style='vertical-align: top;' class='bold right'>Jumlah</td>
//</tr>";

if ( _array($pl) ) {
	while( $rt = @array_shift($pl) ) {
		$html .= "<tr><td>".$this->jawatan->_getname($rt['jawatan_id'])."</td>";
		$ppe = $pe2;
		$jt = 0;
		if ( _array($ppe) ) {
			while( $rtt = @array_shift($ppe) ) {
				$dt = (int)$this->record->_report11($rtt['id'],$this->request["group_id"],$rt['jawatan_id'],$this->survey_id);
				$jt += $dt;
				$jta[$rtt['id']] += $dt;
				$html .= "<td class='right'>".$dt."</td>";
			}
			//$html .= "<td class='right'>".$jt."</td>";
		}
		$html .= "</tr>";		
	}
}

/*$html .= "
<tr>
<td style='vertical-align: top;font-weight:bold;' class='right'>Jumlah</td>";
if ( _array($pe3) ) {
	$jat = 0;
	while( $rtt = @array_shift($pe3) ) {
		$at = $jta[$rtt['id']];
		$jat += $at;
		$html .= "<td class='right'>".$at."</td>";
	}
	$html .= "<td class='right'>".$jat."</td>";
}
$html .= "</tr>";*/
$html .= "</table></center>";

_E($html);
?>


