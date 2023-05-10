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
$pl = $this->display->_listcategory("kilang");
$pe = $this->display->_listalien();
$pl2 = $pl;
$pe2 = $pe;
$pe3 = $pe;
$pen = count($pl);
$jta = array();

$html = "";
$html .= "
<center>
<table id='x-table1' style='margin: 0px;padding:0px;width:100%;'>
<tr><td colspan='".( $pen + 1 )."' class='title'>Bilangan Gunatenaga Bagi Bukan Warganegara Mengikut Sektor dan Negara - {$syear}</td></tr>
<tr>
<th rowspan='2' style='vertical-align: middle;'>Negara Asal</th>
<th style='vertical-align: top;text-align:center;' colspan='".( $pen + 1 )."'>Sektor</th>
</tr>
<tr>
";
if ( _array($pl) ) {
	while( $rt = @array_shift($pl) ) {
		$html .= "<td style='vertical-align: top;' class='bold right'>".$rt['name']."</td>";
	}
}
$html .= "
<td style='vertical-align: top;' class='bold right'>Jumlah</td>
</tr>";

if ( _array($pl2) ) {
    foreach( array("indonesia","bangladesh","nepal") as $bn ) {
        $jt = 0;
        $html .= "<tr>";
        $html .= "<td>".ucfirst($bn)."</td>";
        foreach( $pl2 as $n => $rt ) {
            $dt = (int)$this->record->_report3($rt['id'],$bn,$this->survey_id);
            $html .= "<td class='right'>".$dt."</td>";
            $jt += $dt;
		    $jta[$rt['id']] += $dt;
        }
        $html .= "<td class='right'>".$jt."</td>";
        $html .= "</tr>";
    }
}
if ( _array($pe) ) {
    foreach( $pe as $n => $rtt ) {
        $bn = $rtt['name'];
        $jt = 0;
        $html .= "<tr>";
        $html .= "<td>".ucfirst($bn)."</td>";
        foreach( $pl2 as $n => $rt ) {
            $dt = (int)$this->record->_report3($rt['id'],$bn,$this->survey_id);
            $html .= "<td class='right'>".$dt."</td>";
            $jt += $dt;
            $jta[$rt['id']] += $dt;
        }
        $html .= "<td class='right'>".$jt."</td>";
        $html .= "</tr>";
    }		
}

$html .= "
<tr>
<td style='vertical-align: top;font-weight:bold;' class='right'>Jumlah</td>";
if ( _array($jta) ) {
    $jat = 0;
    foreach($jta as $b => $n) {
        $html .= "<td class='right'>".(int)$n."</td>";
        $jat += $n;
    }
    $html .= "<td class='right'>".$jat."</td>";
}
$html .= "</tr></table></center>";


_E($html);
?>


