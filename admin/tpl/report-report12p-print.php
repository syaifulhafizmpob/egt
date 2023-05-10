<?php
@_object($this) || exit("403 Forbidden");
//$this->_notlogin();
$this->survey_id = $this->request["survey_id"];
if ( _null($this->survey_id) ) exit("Invalid survey!");
$svdb = (object)$this->survey->_getinfo($this->survey_id );
$smonth = ucwords($svdb->month);
$syear = $svdb->year;
$jname = $this->jawatan->_getgroup_name($this->request["group_id"]);

if ( !_null($this->request['dopdf']) ) {
	$url = $this->baseurl."/?_req=tpl&_f=report-report12p-print&survey_id=".$this->request['survey_id']."&group_id=".$this->request["group_id"]."&noprint=1&nl=1";
	if ( defined('WKHTMLTOPDF') && file_exists(WKHTMLTOPDF) ) {
		$title = preg_replace("/\s+/","_",$ftitle);
		header("Content-Type: application/pdf; charset=UTF8");
                header("Content-Disposition: inline; filename=\"report12p.pdf\"");
		system(WKHTMLTOPDF." -g -O Landscape \"$url\" -");
		exit;
	}
	exit("WKHTMLTOPDF not defined!");
}
if ( !_null($this->request['doexcel']) ) {
    header("Content-Type: application/ms-excel; charset=UTF8");
    header("Content-Disposition: inline; filename=\"report12p.xls\"");
}
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
<h1></h1>
<center>
<table id='x-table1' style='margin: 0px;padding:0px;width:100%;'>
<tr><td colspan='4' class='title'>Bilangan Kekurangan dan Keperluan Tambahan Pekerja Mengikut Sektor".( !_null($jname) ? " Untuk Kategori {$jname}" : "")." - {$smonth} {$syear}, {$smonth} ".((int)$syear + 1)." dan {$smonth} ".((int)$syear + 2)."</td></tr>
<tr>
<th rowspan='2' style='vertical-align: top;'>Sektor</th>
<th rowspan='2 style='vertical-align: middle;' class='right'>Kekurangan Pekerja {$syear}</th>
<th colspan='2' rowspan='1' style='vertical-align: top; text-align: center;'>Bilangan Keperluan Pekerja</th>
</tr>
<tr>
<th style='vertical-align: top;' class='right'>".((int)$syear + 1)."</th>
<th style='vertical-align: top;' class='right'>".((int)$syear + 2)."</th>
</tr>";

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
		$html .= "<td class='right'>".$bp3."</td>";
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
</body>
</html>


