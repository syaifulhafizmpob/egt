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

function _pp($dxx, $pe, $pen, $obj) {
    $pl = $obj->jawatan->_list_bykilang($dxx['id']);
    $pe2 = $pe;
    $pe3 = $pe;
    $jta = array();

    $html = "
   
    <tr>
    <th rowspan='2' style='vertical-align: bottom;'>KUMPULAN ".strtoupper($dxx['name'])."</th>
    <th style='vertical-align: top;' class='center' colspan='".$pen."' >Warganegara</th>
    <th style='vertical-align: top;' class='center' colspan='".$pen."' >Bukan Warganegara</th>
    </tr>
    <tr>
    ";
    if ( _array($pe) ) {
	    foreach($pe as $n => $rt) {
		    $html .= "<td style='vertical-align: top;' class='bold right'>".$rt['name']."</td>";
	    }
	    foreach($pe as $n => $rt) {
		    $html .= "<td style='vertical-align: top;' class='bold right'>".$rt['name']."</td>";
	    }
    }
    $html .= "</tr>";

    if ( _array($pl) ) {
        foreach( $pl as $n => $rt) {
		    $html .= "<tr><td>".$obj->jawatan->_getname($rt['jawatan_id'])."</td>";
		    $ppe = $pe2;
		    $jt = 0;
		    if ( _array($ppe) ) {
			    while( $rtt = @array_shift($ppe) ) {
				    $local = (int)$obj->record->_report6($rtt['id'],$dxx['id'],$rt['jawatan_id'],$obj->survey_id);
				    $html .= "<td class='right'>".$local."</td>";
				    $alien = (int)$obj->record->_report7($rtt['id'],$dxx['id'],$rt['jawatan_id'],$obj->survey_id);
				    $html .= "<td class='right'>".$alien."</td>";
			    }
		    }
		    $html .= "</tr>";		
	    }

    }
    unset($dxx, $pe, $pen, $obj);
    return $html;
}

$pe = $this->display->_listcategory("kilang");
$pen = count($pe);

$html = "
<center>
<table id='x-table1' style='margin: 0px;padding:0px;width:100%;'>
 <tr><td colspan='".($pen + 1)."' class='title'>Purata Gaji Mengikut Jawatan Bagi Setiap Kategori Kumpulan - {$syear}</td></tr>
";
$list = $this->jawatan->_list_group();
if ( _array($list) ) {
	while( $dt = @array_shift($list) ) {
        if ( $dt["id"] == 5 ) continue;
        $html .= _pp($dt, $pe, $pen, $this);
        $html .= "<tr><td colspan='".($pen + 1)."' class='border-none'>&nbsp;</td></tr>";
    }
}
$html .= "</table></center>";
_E($html);
?>


