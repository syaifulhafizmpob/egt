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
<tr><td colspan='8' class='title'>Jumlah Gunatenaga di sektor Kilang dan Pusat Simpanan Mengikut Sektor dan Etnik, {$syear}</td></tr>
<tr>
<th colspan='1' rowspan='2' style='vertical-align: middle;'>Sektor</th>
<th colspan='7' rowspan='1' style='vertical-align: top;' class='center'>Etnik</th>
</tr>
<tr>
<th style='vertical-align: top;' class='right'>Iban</th>
<th style='vertical-align: top;' class='right'>Kadazan</th>
<th style='vertical-align: top;' class='right'>Sungai</th>
<th style='vertical-align: top;' class='right'>Melanau</th>
<th style='vertical-align: top;' class='right'>Bidayuh</th>
<th style='vertical-align: top;' class='right'>Lain-lain</th>
<th style='vertical-align: top;' class='right'>Jumlah</th>
</tr>
";
$pl = $this->display->_listcategory("kilang");
$jum_jum = 0;
if ( _array($pl) ) {
	while( $rt = @array_shift($pl) ) {
		$dt = $this->record->_report1($rt['id'],$this->survey_id);

        $iban = (int)$dt['iban'];
        $kadazan = (int)$dt['kadazan'];
        $melanau = (int)$dt['melanau'];
        $sungai = (int)$dt['sungai'];
		$bidayuh = (int)$dt['bidayuh'];
        $lain = 0;
        $etk = $this->display->_listetnik();
        while( $et = @array_shift($etk) ) {
            $n = strtolower($et['name']);
            if ( $n != "iban" && $n != "kadazan" && $n != "melanau" && $n != "sungai" && $n != "bidayuh" ) {
                if ( !_null($dt[$n]) ) $lain += (int)$dt[$n];
            }
            unset($n);
        }

		$jum = $iban + $melanau + $kadazan + $sungai + $bidayuh + $lain;
		$html .= "<tr>";
		$html .= "<td>".ucwords($rt['name'])."</td>";
		$html .= "<td class='right'>".$iban."</td>";
		$html .= "<td class='right'>".$kadazan."</td>";
		$html .= "<td class='right'>".$melanau."</td>";
		$html .= "<td class='right'>".$sungai."</td>";
		$html .= "<td class='right'>".$bidayuh."</td>";
		$html .= "<td class='right'>".$lain."</td>";
		$html .= "<td class='right'>".$jum."</td>";
		$html .= "</tr>";
        $jum_1 += $iban;
        $jum_2 += $kadazan;
        $jum_3 += $melanau;
        $jum_4 += $sungai;
		$jum_5 += $bidayuh;
		$jum_6 += $lain;
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
<td style='vertical-align: top;' class='right'>".$jum_jum."</td>
</tr></table></center>";

_E($html);
?>


