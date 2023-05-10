<?php
@_object($this) || exit("403 Forbidden");
$this->_notlogin();
$survey_id = $this->request["survey_id"];
if ( _null($survey_id) ) exit("Invalid survey!");
$status = $this->request["status"];
$state_id = $this->request["state_id"]; 
$category_id = $this->request["category_id"];  
$subcategory_id  = $this->request["subcategory_id"];
$svdb = (object)$this->survey->_getinfo($survey_id );
$syear = ucwords($svdb->month)." ".$svdb->year;
$category_name = $this->display->_getcategory_name($category_id);
$category_name = ( !_null($category_name) ? " ".$category_name." " : " " );
$state_name = $this->display->_getstate_name($state_id);
$state_name = ( !_null($state_name) ? " di Negeri ".ucfirst($state_name)." " : " " );

$list_status = array(
                        "belum_mula" => "Belum Mula",
                        "mula_lapor" => "Mula Lapor",
                        "telah_diterima" => "Telah diterima",
                        "telah_diproses" => "Telah diproses",
                        "terkecuali" => "Terkecuali"
                );
				


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

if ($category_id=='02' or $category_id=='03' or $category_id=='04')
  $sector = 'Peniaga' ;
    else
  $sector ='Kilang';

$html = "";
$html .= "
<center>
<table id='x-table1' style='margin: 0px;padding:0px;width:100%;'>
<tr><td colspan='1' class='title'>Senarai Pemegang Lesen Bagi Sektor {$sector} Mengikut Kategori {$category_name} , Status {$list_status[$status]} {$state_name}  - {$syear}</td></tr>
<tr>
<th style='vertical-align: top;'>Email</th>
</tr>";

$pl = $this->record->_report31($survey_id,$status,$state_id,$category_id,$subcategory_id);
if ( _array($pl) ) {
    $x = 0;
	while( $rt = @array_shift($pl) ) {
        $x++;
		$html .= "<tr>";

		$html .= "<td>{$rt['nolesen']}&lt;{$rt['email']}&gt;</td>";

		$html .= "</tr>";
	}
}

$html .= "</table></center>";

_E($html);
?>


