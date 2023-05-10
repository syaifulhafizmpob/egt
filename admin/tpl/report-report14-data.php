<?php
@_object($this) || exit("403 Forbidden");
$this->_notlogin();
$this->survey_id = $this->request["survey_id"];
if ( _null($this->survey_id) ) exit("Invalid survey!");
$svdb = (object)$this->survey->_getinfo($this->survey_id );
$syear = ucwords($svdb->month)." ".$svdb->year;

if ( $this->request["cat_id"] != 'all' ) {
    $category_name = $this->display->_getcategory_name($this->request["cat_id"]);
    $category_name = ( !_null($category_name) ? " ".$category_name." di Sektor " : " " );
} else {
    $category_name = ", Jumlah Keseluruhan di Sektor ";
}


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
$pe = $this->jawatan->_list_group_kilang();
$pen = count($pe);

$html = "";
$html .= "
<h1></h1>
<center>
<table id='x-table1' style='margin: 0px;padding:0px;width:100%;'>
<tr><td colspan='".( $pen * 3 + 2)."' class='title'>Bilangan Pekerja Bagi Warganegara dan Bukan Warganegara Mengikut Negeri dan Kategori{$category_name}Pekerjaan - {$syear}</td></tr>
<tr>
<th rowspan='2' style='vertical-align: top;'>Sektor</th>
<th style='vertical-align: top;' class='center' colspan='".$pen."'>Warganegara</th>
<th style='vertical-align: top;' class='center' colspan='".$pen."'>Bukan Warganegara</th>
<th style='vertical-align: top;' class='center' colspan='".$pen."'>Jumlah Keseluruhan</th>
<th style='vertical-align: top;' rowspan='2' class='center'>Jumlah Ikut Negeri</th>
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
    foreach($pe as $x => $rt) {
		$html .= "<td style='vertical-align: top;' class='bold right'>".$rt['name']."</td>";
	}
    $html .= "</tr>";
}

if ( _array($pl) ) {
	while( $rt = @array_shift($pl) ) {
		$html .= "<tr>";
		$html .= "<td>".ucwords($rt['name'])."</td>";
        $ja1 = 0;
        $ja2 = array();
        foreach($pe as $x => $rta) {
		    $total = $this->record->_report14($this->request['cat_id'], $rta['id'], $this->survey_id, $rt['id'], 'local');
            $ja1 = @round($total);
		    $html .= "<td class='right'>".$ja1."</td>";
            $ja2[$rta['name']] += $ja1;
        }
        foreach($pe as $x => $rta) {
		    $total = $this->record->_report14($this->request['cat_id'], $rta['id'], $this->survey_id, $rt['id'], 'alien');
            $ja1 = @round($total);
		    $html .= "<td class='right'>".$ja1."</td>";
            $ja2[$rta['name']] += $ja1;
        }

        $tt = 0;
        foreach($ja2 as $x => $xx) {
            $html .= "<td class='right'>".$xx."</td>";
            $tt += $xx;
        }
        $html .= "<td class='right'>".$tt."</td>";
		$html .= "</tr>";
	}
}

$html .= "
</table></center>";

_E($html);
?>


