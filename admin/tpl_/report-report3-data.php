<?php
@_object($this) || exit("403 Forbidden");
$this->_notlogin();
$this->survey_id = $this->request["survey_id"];
if ( _null($this->survey_id) ) exit("Invalid survey!");
$svdb = (object)$this->survey->_getinfo($this->survey_id );
$syear = ucwords($svdb->month)." ".$svdb->year;
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
<tr><td colspan='9' class='title'>Jumlah Gunatenaga Bukan Warganegara di sektor Kilang dan Pusat Simpan Mengikut Negara Asal, {$syear}</td></tr>
<tr>
<th colspan='1' rowspan='2' style='vertical-align: middle;'>Sektor</th>
<th colspan='8' rowspan='1' style='vertical-align: top;' class='center'>Bukan Warganegara</th>
</tr>
<tr>
<th style='vertical-align: top;' class='right'>Indonesia</th>
<th style='vertical-align: top;' class='right'>India</th>
<th style='vertical-align: top;' class='right'>Nepal</th>
<th style='vertical-align: top;' class='right'>Bangladesh</th>
<th style='vertical-align: top;' class='right'>Vietnam</th>
<th style='vertical-align: top;' class='right'>Myanmar</th>
<th style='vertical-align: top;' class='right'>Lain-lain</th>
<th style='vertical-align: top;' class='right'>Jumlah</th>
</tr>
";
$pl = $this->display->_listcategory("kilang");
$jum_jum = 0;
if ( _array($pl) ) {
	while( $rt = @array_shift($pl) ) {
		$dt = $this->record->_report1($rt['id'],$this->survey_id);

        $indonesia = (int)$dt['indonesia'];
        $india = (int)$dt['india'];
        $nepal = (int)$dt['nepal'];
        $bangladesh = (int)$dt['bangladesh'];
		$vietnam = (int)$dt['vietnam'];
		$myanmar = (int)$dt['myanmar'];
        $lain = 0;
        $etk = $this->display->_listalien();
        while( $et = @array_shift($etk) ) {
            $n = strtolower($et['name']);
            if ( $n != "india" && $n != "myanmar" && $n != "vietnam" ) {
                if ( !_null($dt[$n]) ) $lain += (int)$dt[$n];
            }
            unset($n);
        }

		$jum = $indonesia + $india + $nepal + $bangladesh + $vietnam + $myanmar + $lain;
		$html .= "<tr>";
		$html .= "<td>".ucwords($rt['name'])."</td>";
		$html .= "<td class='right'>".$indonesia."</td>";
		$html .= "<td class='right'>".$india."</td>";
		$html .= "<td class='right'>".$nepal."</td>";
		$html .= "<td class='right'>".$bangladesh."</td>";
		$html .= "<td class='right'>".$vietnam."</td>";
		$html .= "<td class='right'>".$myanmar."</td>";
		$html .= "<td class='right'>".$lain."</td>";
		$html .= "<td class='right'>".$jum."</td>";
		$html .= "</tr>";
        $jum_1 += $indonesia;
        $jum_2 += $india;
        $jum_3 += $nepal;
        $jum_4 += $bangladesh;
		$jum_5 += $vietnam;
		$jum_6 += $myanmar;
		$jum_7 += $lain;
		$jum_jum += $jum;
	}
}

$html .= "
<tr>
<td style='vertical-align: top;font-weight:bold;' class='right'>Jumlah Besar</td>
<td style='vertical-align: top;' class='right'>".$jum_1."</td>
<td style='vertical-align: top;' class='right'>".$jum_2."</td>
<td style='vertical-align: top;' class='right'>".$jum_3."</td>
<td style='vertical-align: top;' class='right'>".$jum_4."</td>
<td style='vertical-align: top;' class='right'>".$jum_5."</td>
<td style='vertical-align: top;' class='right'>".$jum_6."</td>
<td style='vertical-align: top;' class='right'>".$jum_7."</td>
<td style='vertical-align: top;' class='right'>".$jum_jum."</td>
</tr></table></center>";

_E($html);
?>


