<?php
@_object($this) || exit("403 Forbidden");
//$this->_notlogin();
$this->survey_id = $this->request["survey_id"];
if ( _null($this->survey_id) ) exit("Invalid survey!");
$svdb = (object)$this->survey->_getinfo($this->survey_id );
$smonth = ucwords($svdb->month);
$syear = $svdb->year;
$category_id = $this->request["catid"];
$isbahantanaman = ( $category_id == "04" ? true : false );
$subcategory_id = $this->request["subcatid"];
$group_id = $this->request["group_id"];
$jawatan_id = $this->request["jawatan_id"];
$state_id = $this->request["state_id"];

$state_name = $this->display->_getstate_name($state_id);
$state_name = ( !_null($state_name) ? " di Negeri ".ucfirst($state_name)." " : " " );
$category_name = $this->display->_getcategory_name($category_id);
$category_name = ( !_null($category_name) ? " ".$category_name." " : " " );
$group_name = $this->display->_getcategory_name($group_id);
$group_name = ( !_null($group_name) ? " ".$group_name." " : " " );
$jawatan_name = $this->jawatan->_getname($jawatan_id);
$jawatan_name = ( !_null($jawatan_name) ? " ".$jawatan_name." " : " " );

if ( !_null($this->request['dopdf']) ) {
    $p = http_build_query($this->request);
    $p = preg_replace("/undefined/","",$p);
    $p = preg_replace("/\&dopdf=1/","",$p);
    $url = $this->baseurl."/?".$p."&noprint=1&nl=1";
	if ( defined('WKHTMLTOPDF') && file_exists(WKHTMLTOPDF) ) {
		$title = preg_replace("/\s+/","_",$ftitle);
		header("Content-Type: application/pdf; charset=UTF8");
                header("Content-Disposition: inline; filename=\"report18p.pdf\"");
		system(WKHTMLTOPDF." -g -O Landscape \"$url\" -");
		exit;
	}
	exit("WKHTMLTOPDF not defined!");
}
if ( !_null($this->request['doexcel']) ) {
    header("Content-Type: application/ms-excel; charset=UTF8");
    header("Content-Disposition: inline; filename=\"report18p.xls\"");
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
$etnik = $this->display->_listetnik();
$etnik_cnt = count($etnik);

$html_h = "<center><table id='x-table1' style='margin: 0px;padding:0px;width:100%;'>
<tr><td colspan='".( $isbahantanaman ? "22" : "21" )."' class='title'>Maklumat terperinci Gunatenaga Bagi Sektor Peniaga Mengikut Kategori{$category_name}Dan Jawatan{$jawatan_name}Bagi Lain-lain Warganegara{$state_name}- {$smonth} {$syear}</td></tr>
<tr>
<th colspan='1' rowspan='2' style='vertical-align: bottom;' class='border-bottom-none border-right-none'>No. Lesen</th>
<th colspan='1' rowspan='2' style='vertical-align: bottom;' class='border-bottom-none border-right-none'>Nama Syarikat</th>
";
if ( $isbahantanaman ) {
$html_h .= "<th colspan='1' rowspan='2' style='vertical-align: bottom;' class='border-bottom-none border-right-none'>Keluasan<br>Nurseri<br>Hektar</th>";
}
$html_h .= "
<th colspan='".( $etnik_cnt + 1 )."' rowspan='1' style='vertical-align: top;' class='center'>Bilangan Pekerja</th>
</tr>


<tr>
";

foreach($etnik as $n => $r) {
   $html_h .="<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left'>".ucwords($r['name'])."</td>";
}

$html_h .= "
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left'>Jumlah</td>

</tr>

";

$list1 = $this->record->_getinfo_respondent_tot($state_id,$this->survey_id,$group_id,$jawatan_id,$category_id,$subcategory_id);

$etnik_a = array();
if ( _array($list1) ) {
    $html .= $html_h;
    $skip = array();
    $keluasan_tapak_semaian_j = $keluasan_tapak_semaian = 0;
	while( $rl = @array_shift($list1) ) {
        $dtt = $this->get_row("select company,nolesen from `".$this->respondent->table."` where `id`='".$rl['respondent_id']."'", ARRAY_A);
        if ( _null($dtt['nolesen']) ) continue;
        if ( !_null($skip[$dtt['nolesen']]) ) continue;
        $skip[$dtt['nolesen']] = 1;
		$html .= "<tr>";
		$html .= "<td class='ll border-left border-right border-bottom'>";
		$html .= $dtt['nolesen'];
		$html .= "</td>";

		$html .= "<td class='ll border-left border-right border-bottom'>";
		$html .= ucwords($dtt['company']);
		$html .= "</td>";

        if ( $isbahantanaman ) {
            $keluasan_tapak_semaian = ( _decimal($rl['keluasan_tapak_semaian']) ? $rl['keluasan_tapak_semaian'] : 0 );
            $keluasan_tapak_semaian_j += $keluasan_tapak_semaian;
		    $html .= "<td class='ll border-left border-right border-bottom'>";
		    $html .= $keluasan_tapak_semaian;
		    $html .= "</td>";
        }

        $etnikp = $this->record->_getetnik($rl['id']);
        $j = $n = 0;
        foreach($etnik as $n => $r) {
            $lp = ucwords($r['name']);
            $n = (int)$etnikp[$lp];
            $etnik_a[$r['name']] += $n;
            $j += $n;
            $html .= "<td>".$n."</td>";
        }
        $etnik_a['__jumlah'] += $j;
        $html .= "<td>".$j."</td></tr>";
    }
    $html .= "<tr>";
    $html .= "<td colspan='2' class='right'>Jumlah</td>";
    $html .= "<td>".$keluasan_tapak_semaian_j."</td>";
    foreach($etnik_a as $nn => $cc) {
        $html .= "<td>".$cc."</td>";

    }

    $html .= "</tr>";
} else {
    $html .= "<tr><td colspan='".( $etnik_cnt + 1 )."'>Tiada data direkodkan</td></tr>";
}

$html .= "</table></center>";
_E($html);
?>
</body>
</html>

