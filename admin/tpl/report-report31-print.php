<?php
@_object($this) || exit("403 Forbidden");
//$this->_notlogin();
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

if ( !_null($this->request['dopdf']) ) {
	$url = $this->baseurl."/?_req=tpl&_f=report-report31-print&survey_id=".$this->request['survey_id']."&status=".$this->request['status']."&state_id=".$this->request['state_id']."&category_id=".$this->request['category_id']."&subcategory_id=".$this->request["subcategory_id"]."&noprint=1&nl=1";
	if ( defined('WKHTMLTOPDF') && file_exists(WKHTMLTOPDF) ) {
		$title = preg_replace("/\s+/","_",$ftitle);
		header("Content-Type: application/pdf; charset=UTF8");
                header("Content-Disposition: inline; filename=\"report31.pdf\"");
		system(WKHTMLTOPDF." -g -O Landscape \"$url\" -");
		exit;
	}
	exit("WKHTMLTOPDF not defined!");
}
if ( !_null($this->request['doexcel']) ) {
    header("Content-Type: application/ms-excel; charset=UTF8");
    header("Content-Disposition: inline; filename=\"report31.xls\"");
}

if ($category_id=='02' or $category_id=='03' or $category_id=='04')
  $sector = 'Peniaga' ;
    else
  $sector ='Kilang';
?>
<html>
<head>
<meta http-equiv=Content-Type content="text/html; charset=UTF-8">
<title><?php _E($this->options->apptitle);?></title>
<link rel="shortcut icon" type="image/x-icon" href="<?php _E($this->baseurl);?>/favicon.ico">
<style type="text/css" media="print"> 
body, td{font-family:arial,sans-serif;font-size:80%} a:link, a:active, a:visited{color:#0000CC} img{border:0} pre { white-space: pre; white-space: -moz-pre-wrap; white-space: -o-pre-wrap; white-space: pre-wrap; word-wrap: break-word; width: 800px; overflow: auto;}
</style>
<style>
table {
	margin: 0px;
        padding: 6px;
        width: 100%;
}
th, td {
        padding: 2px 10px 2px 10px;
}

th {
        vertical-align: middle;
        text-align: left;
        font-weight: bold;
        font-size: 12px;
        font-family: arial;
        background: #ffffff;
        color: #000000;
}

td {
        vertical-align: top;
        text-align: left;
        font-size: 12px;
        font-family: arial;
}

.center {
        text-align: center;
}
.right {
        text-align: right;
}
.left {
        text-align: left;
}
.border {
	border: 1px solid #000000;
}

.border-top {
	border-top: 1px solid #000000;
}

.border-bottom {
	border-bottom: 1px solid #000000;
}

.border-left {
	border-left: 1px solid #000000;
}

.border-right {
	border-right: 1px solid #000000;
}

.border-none {
	border: 0px solid transparent !important;
}

.border-top-none {
	border-top: 0px solid transparent !important;
}

.border-bottom-none {
	border-bottom: 0px solid transparent !important;
}

.border-left-none {
	border-left: 0px solid transparent !important;
}

.border-right-none {
	border-right: 0px solid transparent !important;
}

span.anote {
        padding: 0px;
        font-weight: bold;
}

table { 
  border-spacing:0;
  border-collapse:collapse;
}

.bold {
        font-weight: bold;
}
@media print
{
  .page-break  { display:block; page-break-before:always; }
}
</style>
<?php if ( _null($this->request['noprint']) ): ?>
<script type="text/javascript"> 
function doprint(){
        document.body.offsetHeight;
        setTimeout(function() {
                window.print();
        }, 500);
};
</script>
<?php endif;?> 
</head> 
<body <?php if ( _null($this->request['noprint']) ): ?>onload="doprint();"<?php endif;?>>
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
<center>
<table id='x-table1' style='margin: 0px;padding:0px;width:100%;'>
<tr><td colspan='1' class='title'>Senarai Pemegang Lesen Bagi Sektor {$sector} Mengikut Kategori {$category_name} , Status {$list_status[$status]} {$state_name}  - {$syear}</td></tr>
<tr>
<th style='vertical-align: top;'>Email</th>
</tr>";
//echo $survey_id; echo $status; echo $state_id; echo $category_id; echo $subcategory_id;
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
</body>
</html>

