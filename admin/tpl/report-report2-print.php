<?php
@_object($this) || exit("403 Forbidden");
//$this->_notlogin();
$this->survey_id = $this->request["survey_id"];
if ( _null($this->survey_id) ) exit("Invalid survey!");
$svdb = (object)$this->survey->_getinfo($this->survey_id );
$syear = ucwords($svdb->month)." ".$svdb->year;

if ( !_null($this->request['dopdf']) ) {
	$url = $this->baseurl."/?_req=tpl&_f=report-report2-print&survey_id=".$this->request['survey_id']."&noprint=1&nl=1";
	if ( defined('WKHTMLTOPDF') && file_exists(WKHTMLTOPDF) ) {
		$title = preg_replace("/\s+/","_",$ftitle);
		header("Content-Type: application/pdf; charset=UTF8");
                header("Content-Disposition: inline; filename=\"report2.pdf\"");
		system(WKHTMLTOPDF." -g -O Landscape \"$url\" -");
		exit;
	}
	exit("WKHTMLTOPDF not defined!");
}
if ( !_null($this->request['doexcel']) ) {
    header("Content-Type: application/ms-excel; charset=UTF8");
    header("Content-Disposition: inline; filename=\"report2.xls\"");
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
$pl = $this->display->_listcategory("kilang");
$pe = $this->display->_listetnik();
$pl2 = $pl;
$pe2 = $pe;
$pe3 = $pe;
$pen = count($pl);
$jta = array();

$html = "";
$html .= "
<center>
<table id='x-table1' style='margin: 0px;padding:0px;width:100%;'>
<tr><td colspan='".( $pen + 1 )."' class='title'>Bilangan Gunatenaga Mengikut Sektor Dan Bangsa / Etnik - {$syear}</td></tr>
<tr>
<th rowspan='2' style='vertical-align: middle;'>Etnik</th>
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
    foreach( array("melayu","india","cina") as $bn ) {
        $jt = 0;
        $html .= "<tr>";
        $html .= "<td>".ucfirst($bn)."</td>";
        foreach( $pl2 as $n => $rt ) {
            $dt = (int)$this->record->_report2($rt['id'],$bn,$this->survey_id);
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
            $dt = (int)$this->record->_report2($rt['id'],$bn,$this->survey_id);
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
</body>
</html>

