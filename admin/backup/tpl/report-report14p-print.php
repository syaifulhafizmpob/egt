<?php
@_object($this) || exit("403 Forbidden");
//$this->_notlogin();
$this->survey_id = $this->request["survey_id"];
if ( _null($this->survey_id) ) exit("Invalid survey!");
$svdb = (object)$this->survey->_getinfo($this->survey_id );
$smonth = ucwords($svdb->month);
$syear = $svdb->year;
$state_id = $this->request["state_id"];

if ( !_null($this->request['dopdf']) ) {
	$url = $this->baseurl."/?_req=tpl&_f=report-report14p-print&survey_id=".$this->request['survey_id']."&noprint=1&nl=1";
	if ( defined('WKHTMLTOPDF') && file_exists(WKHTMLTOPDF) ) {
		$title = preg_replace("/\s+/","_",$ftitle);
		header("Content-Type: application/pdf; charset=UTF8");
                header("Content-Disposition: inline; filename=\"report14p.pdf\"");
		system(WKHTMLTOPDF." -g -O Landscape \"$url\" -");
		exit;
	}
	exit("WKHTMLTOPDF not defined!");
}
if ( !_null($this->request['doexcel']) ) {
    header("Content-Type: application/ms-excel; charset=UTF8");
    header("Content-Disposition: inline; filename=\"report14p.xls\"");
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
$fl = array("A","B","C","D","E","F","G","H","I","J","K","L","M");

$html_h = "<center><table id='x-table1' style='margin: 0px;padding:0px;width:100%;'>
<tr><td colspan='20' class='title'>Maklumat Gunatenaga Bagi Peniaga Minyak Mengikut Jawatan {$smonth} {$syear}</td></tr>
<tr>
<th colspan='1' rowspan='3' style='vertical-align: top; padding-top:5px;' class='border-bottom-none border-right-none'>Jawatan</th>
<th colspan='8' rowspan='1' style='vertical-align: top;' class='border-left border-bottom border-right-none center'>Warganegara Tempatan</th>
<th colspan='8' rowspan='1' style='vertical-align: top;' class='border-left border-bottom border-right-none center'>Warganegara Asing</th>
<th colspan='3' rowspan='1' style='vertical-align: top;' class='center'>Bilangan Pekerja</th>
</tr>
<tr>

<td colspan='5' rowspan='1' style='vertical-align: top;' class='border-left border-bottom center'>Bilangan Pekerja</td>
<td colspan='3' rowspan='1' style='vertical-align: top;' class='border-left border-bottom border-right-none center'>Pendapatan</td>
<td colspan='5' rowspan='1' style='vertical-align: top;' class='border-left border-bottom center'>Bilangan Pekerja</td>
<td colspan='3' rowspan='1' style='vertical-align: top;' class='border-left border-bottom center'>Pendapatan</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left border-bottom right'>Kekurangan</td>
<td colspan='2' rowspan='1' style='vertical-align: top;' class='border-left border-bottom border-right right'>Keperluan Tambahan</td>
</tr>

<tr>

<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left'>Melayu</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left'>Cina</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left'>India</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left'>Lain-lain</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left'>Jumlah</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left'>Purata Gaji (RM)</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left'>Purata Elaun (RM)</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left'>Jumlah</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left'>Indonesia</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left'>Bangladesh</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left'>Nepal</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left'>Lain-lain</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left'>Jumlah</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left'>Purata Gaji (RM)</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left'>Purata Elaun (RM)</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left'>Jumlah</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left right'>{$syear}</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left right'>".( (int)$syear + 1)."</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left border-right right'>".( (int)$syear + 2)."</td>
</tr>


";

$html .= $html_h;
$list1 = $this->jawatan->_list_bypeniaga_minyak();
$perror = false;
function _dval($val = 0 ) {
	return ( _num($val) ? $val : 0 );
}

if ( _array($list1) ) {
	$h1_a = array();
	$fln = 0;
	while( $rl = @array_shift($list1) ) {
		$gid = $rl['group_id'];
		$jid = $rl['jawatan_id'];
		$h1 = $this->jawatan->_getgroup_name($rl['group_id']);
		if ( _null($h1_a[$h1]) ) {
            /*$pbreak = false;
            if ( $fln == 2 ) {
                $pbreak = true;
                for($tt = 0; $tt <= 5; $tt++) {
                    $html .= "<tr><td colspan='2'></td></tr>";
                }
                $html .= $html_h;
            }*/
			$h1_a[$h1] = $gid;
			$html .= "<tr>";
			$html .= "<th colspan='22'>";
			$html .= $fl[$fln].". ".$h1;
			$html .= "</th>";
			$html .= "</tr>";
			$fln++;
		}

		$html .= "<tr>";
		$html .= "<td class='ll border-left border-right border-bottom'>";
		$html .= $this->jawatan->_getname($jid);
		$html .= "</td>";

        $rrdata = $this->record->_getinfo_respondent($state_id,$this->survey_id,$gid,$jid);
        $etnik = $alien = array();
        $rcdb = new stdClass();
        $rcdb->melayu = $rcdb->cina = $rcdb->india = 0;
        $rcdb->indonedia = $rcdb->bangladesh = $rcdb->nepal = 0;
        $rcdb->gajia = $rcdb->gajib = $rcdb->elauna = $rcdb->elaunb = 0;
        $rcdb->bp1 = $rcdb->bp2 = $rcdb->bp3 = 0;
        if ( _array($rrdata) ) {
            while( $xrt = @array_shift($rrdata) ) {
                $etnik[$xrt['id']] = $this->record->_getetnik($xrt['id']);
		        $alien[$xrt['id']] = $this->record->_getalien($xrt['id']);
                $rcdb->melayu += $xrt['melayu'];
                $rcdb->cina += $xrt['cina'];
                $rcdb->india += $xrt['india'];
                $rcdb->indonesia += $xrt['indonesia'];
                $rcdb->bangladesh += $xrt['bangladesh'];
                $rcdb->nepal += $xrt['nepal'];
            }
        }

		$html .= "<td class='border-bottom border-right right'>";
		$html .= _dval($rcdb->melayu);
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>";
		$html .= _dval($rcdb->cina);
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>";
		$html .= _dval($rcdb->india);
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>";
		$ja = ( _dval($rcdb->melayu) + _dval($rcdb->cina) + _dval($rcdb->india) );
		if ( _array($etnik) ) {
            $pn = false;
            while( $ett = @array_shift($etnik) ) {
			    foreach($ett as $e1 => $e2) {
				    $ja += $e2; $pn = true;
				    $html .= "<span style='white-space:nowrap;'>".$e1.": ".$e2."</span><br>";
			    }
            }
            if ( !$pn) $html .= "0";
		} else {
			$html .= "0";
		}
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>".$ja."</td>";
		$html .= "<td class='border-bottom border-right right'>";
		$html .= _dval($rcdb->gajia);
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>";
		$html .= _dval($rcdb->elauna);
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>";
		$html .= (_dval($rcdb->gajia) + _dval($rcdb->elauna));
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>";
		$html .= _dval($rcdb->indonesia);
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>";
		$html .= _dval($rcdb->bangladesh);
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>";
		$html .= _dval($rcdb->nepal);
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>";
		$jb = ( _dval($rcdb->indonesia) + _dval($rcdb->bangladesh) + _dval($rcdb->nepal) );
		if ( _array($alien) ) {
            $pn = false;
            while( $ell = @array_shift($alien) ) {
			    foreach($ell as $e1 => $e2) {
				    $jb += $e2; $pn = true;
				    $html .= "<span style='white-space:nowrap;'>".$e1.": ".$e2."</span><br>";
			    }
            }
            if ( !$pn ) $html .= "0";
		} else {
			$html .= "0";
		}
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>".$jb."</td>";
		$html .= "<td class='border-bottom border-right right'>";
		$html .= _dval($rcdb->gajib);
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>";
		$html .= _dval($rcdb->elaunb);
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>";
		$html .= (_dval($rcdb->gajib) + _dval($rcdb->elaunb));
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>";
		$html .= _dval($rcdb->bp1);
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>";
		$html .= _dval($rcdb->bp2);
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>";
		$html .= _dval($rcdb->bp3);
		$html .= "</td>";

		$html .= "</tr>";

	}
}
$html .= "</table></center>";
_E($html);
?>
</body>
</html>

