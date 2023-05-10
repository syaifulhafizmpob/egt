<?php
@_object($this) || exit("403 Forbidden");
$this->_notlogin();
$this->survey_id = $this->request["survey_id"];
if ( _null($this->survey_id) ) exit("Invalid survey!");
$svdb = (object)$this->survey->_getinfo($this->survey_id );
$smonth = ucwords($svdb->month);
$syear = $svdb->year;
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
$html = "";
$html .= "
<h1></h1>
<center>
<table id='x-table1' style='margin: 0px;padding:0px;width:100%;'>
<tr><td colspan='4' class='title'>Bilangan Kekurangan dan Keperluan Tambahan Pekerja Mengikut Sektor ".( !_null($jname) ? "Untuk Kategori {$jname}" : "").", {$smonth} {$syear}, {$smonth} ".((int)$syear + 1)." dan {$smonth} ".((int)$syear + 2)."</td></tr>
<tr>
<th rowspan='2' style='vertical-align: top;'>Sektor</th>
<th rowspan='2 style='vertical-align: middle;' class='right'>Kekurangan Pekerja {$syear}</th>
<th colspan='2' rowspan='1' style='vertical-align: top; text-align: center;'>Bilangan Keperluan Pekerja</th>
</tr>
<tr>
<th style='vertical-align: top;' class='right'>".((int)$syear + 1)."</th>
<th style='vertical-align: top;' class='right'>".((int)$syear + 2)."</th>
</tr>
";

$pl = $this->display->_listcategory("peniaga");
$jum_bp1 = 0;
$jum_bp2 = 0;
$jum_bp3 = 0;
$jum_jum = 0;
if ( _array($pl) ) {
	while( $rt = @array_shift($pl) ) {
		$dt = $this->record->_report12p($rt['id'],$this->request["group_id"],$this->survey_id);
		$bp1 = (int)$dt['bp1'];
		$bp2 = (int)$dt['bp2'];
		$bp3 = (int)$dt['bp3'];
		$jum_bp1 += $bp1;
		$jum_bp2 += $bp2;
		$jum_bp3 += $bp3;
		$html .= "<tr>";
		$html .= "<td>".$rt['name']."</td>";
		$html .= "<td class='right'>".$bp1."</td>";
		$html .= "<td class='right'>".$bp2."</td>";
		$html .= "<td class='right'>".$bp2."</td>";

		$html .= "</tr>";
		$jum_jum += $jum;
	}
}

$html .= "
<tr>
<td style='vertical-align: top;font-weight:bold;' class='right'>Jumlah</td>
<td style='vertical-align: top;' class='right'>".$jum_bp1."</td>
<td style='vertical-align: top;' class='right'>".$jum_bp2."</td>
<td style='vertical-align: top;' class='right'>".$jum_bp3."</td>
</tr></table></center>";

_E($html);
?>


