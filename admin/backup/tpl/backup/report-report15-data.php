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
$pl = $this->display->_liststate();
$pe = $this->jawatan->_list_group();
$pen = count($pe);

$html = "";
$html .= "
<h1></h1>
<center>
<table id='x-table1' style='margin: 0px;padding:0px;width:100%;'>
<tr><td colspan='".( $pen * 2 + 1)."' class='title'>Purata Gaji Bagi Warganegara dan Bukan Warganegara Mengikut Negeri dan Kategori Pekerjaan {$syear}</td></tr>
<tr>
<th rowspan='2' style='vertical-align: top;'>Sektor</th>
<th style='vertical-align: top;' class='center' colspan='".$pen."'>Warganegara</th>
<th style='vertical-align: top;' class='center' colspan='".$pen."'>Bukan Warganegara</th>
</tr>
";
if ( _array($pe) ) {
    $html .= "<tr>";
    foreach($pe as $x => $rt) {
		$html .= "<td style='vertical-align: top;' class='bold right'>".$rt['name']."</td>";
	}
    foreach($pe as $x => $rt) {
		$html .= "<td style='vertical-align: top;' class='bold right'>".$rt['name']."</td>";
	}
    $html .= "</tr>";
}

if ( _array($pl) ) {
	while( $rt = @array_shift($pl) ) {
		$html .= "<tr>";
		$html .= "<td>".$rt['name']."</td>";
        foreach($pe as $x => $rta) {
		    $total = $this->record->_report15($rt['id'], $rta['id'], $this->survey_id, 'local');
		    $html .= "<td class='right'>".round($total,2)."</td>";
        }
        foreach($pe as $x => $rta) {
		    $total = $this->record->_report15($rt['id'], $rta['id'], $this->survey_id, 'alien');
		    $html .= "<td class='right'>".round($total,2)."</td>";
        }
		$html .= "</tr>";
	}
}

$html .= "
</table></center>";

_E($html);
?>


