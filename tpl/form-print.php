<?php
@_object($this) || exit("403 Forbidden");
if ( _null($this->request['nl']) ) {
	$this->_notlogin();
}
if ( _null($this->request['sid']) || _null($this->request['rid']) ) exit("Invalid parameter!");

$this->survey_id = $this->request['sid'];
$this->fdb = (object)$this->user->_getinfo($this->request['rid']);
$svdb = (object)$this->survey->_getinfo($this->request['sid']);
$syear = date('Y', strtotime($svdb->sdate));
$ftitle = $this->survey->_formtitle($this->request['sid'],$this->request['rid']);
$submit_done_date = $this->record->_submit_done_date($this->request['rid'],$this->request['sid']);
// 47 = Pekerja di Tapak Semaian
$tapak_semaian = ( $this->fdb->category_id == '' 
                   || $this->fdb->category_id == '' 
                    || $this->fdb->category_id == '04' ? true : false );

if ( !_null($this->request['dopdf']) ) {
	$url = "http://127.0.0.1/?_req=tpl&_f=form-print&sid=".$this->request['sid']."&rid=".$this->request['rid']."&nl=1";
	if ( defined('WKHTMLTOPDF') && file_exists(WKHTMLTOPDF) ) {
		$title = preg_replace("/\s+/","_",$ftitle);
		header("Content-Type: application/pdf; charset=UTF8");
                header("Content-Disposition: inline; filename=\"" . $title . ".pdf\"");
		system(WKHTMLTOPDF." -g -O Landscape \"$url\" -");
		exit;
	}
	exit("WKHTMLTOPDF not defined!");
}

$ctype = $this->display->_getcategory_type($this->fdb->category_id);

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
/*@media print
{
  .page-break  { display:block; page-break-before:always; }
}*/
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
<table style='margin: 0px;padding:0px;width:100%;'>
<tr>
<th style="width:170px;font-size:13px;">NAMA KILANG/SYARIKAT</th>
<td style="font-size:13px;">: <?php _E(strtoupper($this->fdb->company));?></td>
</tr>
<tr>
<th style="width:170px;font-size:13px;">NO. LESEN MPOB</th>
<td style="font-size:13px;">: <?php _E(strtoupper($this->fdb->nolesen));?></td>
</tr>
<tr>
<th style="width:170px;font-size:13px;">NEGERI PREMIS</th>
<td style="font-size:13px;">: <?php _E(strtoupper($this->display->_getstate_name($this->fdb->state_id)));?></td>
</tr>
<?php if ( !_null($submit_done_date) ): ?>
<tr>
<th style="width:170px;font-size:13px;">TARIKH HANTAR</th>
<td style="font-size:13px;">: <?php _E($this->_output_date($submit_done_date,"%d-%m-%Y"));?></td>
</tr>
<?php endif; ?>
<tr>
<th colspan="2" class="center" style="font-size:13px;"><?php _E(strtoupper($ftitle));?></th>
</tr>
</table>
<br>
<?php
$fl = array("A","B","C","D","E","F","G","H","I","J","K","L","M");

$html_h = "<table style='margin: 0px;padding:0px;width:100%;'>
<tr>
<th colspan='1' rowspan='3' style='vertical-align: middle;' class='border-top border-left border-bottom border-right-none'>Jawatan</th>
<th colspan='8' rowspan='1' style='vertical-align: top;' class='border-top border-left border-bottom border-right-none center'>Warganegara Tempatan</th>
<th colspan='8' rowspan='1' style='vertical-align: top;' class='border-top border-left border-bottom border-right-none center'>Warganegara Asing</th>
<th colspan='3' rowspan='1' style='vertical-align: top;' class='border-top border-left border-right border-bottom center'>Bilangan Pekerja</th>
</tr>
<tr>

<td colspan='5' rowspan='1' style='vertical-align: top;' class='border-left border-bottom center'>Bilangan Pekerja</td>
<td colspan='3' rowspan='1' style='vertical-align: top;' class='border-left border-bottom border-right-none center'>Pendapatan</td>
<td colspan='5' rowspan='1' style='vertical-align: top;' class='border-left border-bottom center'>Bilangan Pekerja</td>
<td colspan='3' rowspan='1' style='vertical-align: top;' class='border-left border-bottom border-right-none center'>Pendapatan</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left border-bottom'>Kekurangan</td>
<td colspan='2' rowspan='1' style='vertical-align: top;' class='border-left border-bottom border-right'>Keperluan Tambahan</td>
</tr>

<tr>

<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left border-bottom'>Melayu</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left border-bottom'>Cina</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left border-bottom'>India</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left border-bottom'>Lain-lain</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left border-bottom'>Jumlah</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left border-bottom'>Purata Gaji (RM)</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left border-bottom'>Purata Elaun (RM)</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left border-bottom'>Jumlah</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left border-bottom'>Indonesia</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left border-bottom'>Bangladesh</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left border-bottom'>Nepal</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left border-bottom'>Lain-lain</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left border-bottom'>Jumlah</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left border-bottom'>Purata Gaji (RM)</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left border-bottom'>Purata Elaun (RM)</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left border-bottom'>Jumlah</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left border-bottom right'>{$syear}</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left border-bottom right'>".( (int)$syear + 1)."</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left border-bottom border-right right'>".( (int)$syear + 2)."</td>
</tr>


";

$html .= $html_h;
$list1 = $this->survey->_list_jawatan($this->fdb->category_id);
$perror = false;
function _dval($val = "" ) {
	return ( _num($val) ? $val : "0" );
}

$keluasan_tapak_semaian = 0;
if ( _array($list1) ) {
	$h1_a = array();
	$fln = 0;
	$cid = $this->fdb->category_id;
	while( $rl = @array_shift($list1) ) {
		$gid = $rl['group_id'];
		$jid = $rl['jawatan_id'];
		$h1 = $this->survey->_getgroup_name($rl['group_id']);
		if ( _null($h1_a[$h1]) ) {
            $pbreak = false;
            if ( _null($this->request['noprint']) || !_null($this->request['dopdf']) ) {
                if ( $fln == 1 || $fln == 2 ) {
                    if ( $ctype == "kilang" ) {
                        $hg = 80;
                    } else {
                        $hg = 100;
                        if ( $fln == 2 ) $hg = $hg + 40;
                    }
                    $pbreak = true;
                    for($tt = 0; $tt <= $hg; $tt++) {
                        $html .= "<tr><td colspan='2'></td></tr>";
                    }
                    $html .= $html_h;
                }
            }
			$h1_a[$h1] = $gid;
			$jhc = $this->survey->_count_jawatan($gid,$cid);
			$html .= "<tr>";
			$html .= "<th colspan='21' class='".($pbreak ? "border-top " : "")."border-bottom border-left border-right'>";
			$html .= $fl[$fln].". ".$h1;
			$html .= "</th>";
			$html .= "</tr>";
			$fln++;
		}

		$html .= "<tr>";
		$html .= "<td class='ll border-left border-right border-bottom'>";
		$html .= $this->survey->_getjawatan_name($jid);
		$html .= "</td>";

		$rcdb = (object)$this->record->_getinfo($this->request['rid'],$this->survey_id,$gid,$jid);
        $keluasan_tapak_semaian = ( _decimal($rcdb->keluasan_tapak_semaian) ? $rcdb->keluasan_tapak_semaian : ( _decimal($keluasan_tapak_semaian) ? $keluasan_tapak_semaian : 0 ) );

		$etnik = $this->record->_getetnik($rcdb->id);
		$alien = $this->record->_getalien($rcdb->id);

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
			foreach($etnik as $e1 => $e2) {
				$ja += $e2;
				$html .= "<span style='white-space:nowrap;'>".$e1.": ".$e2."</span><br>";
			}
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
			foreach($alien as $e1 => $e2) {
				$jb += $e2;
				$html .= "<span style='white-space:nowrap;'>".$e1.": ".$e2."</span><br>";
			}
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

    if ( $tapak_semaian && _decimal($keluasan_tapak_semaian) ) {
        $html .= "<tr>";
        $html .= "<th colspan='20' class='border-top border-left border-bottom border-right'>Keluasan Tapak Semaian</th>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td class='border-bottom border-left border-right'>Keluasan (Hektar)</td>";
        $html .= "<td class='border-bottom border-right' colspan='19'>".$keluasan_tapak_semaian."</td>";
        $html .= "</tr>";
    }
}
$html .= "</table>";
_E($html);
?>
<div class='page-break'></div>
<table style='margin: 10px 0px 0px 0px;padding:0px;width:100%;'>
<tr>
<th colspan="2" style="font-size:13px;">
Saya mengaku bahawa keterangan diberi adalah benar sepanjang pengetahuan dan kepercayaan saya.
</th>
</tr>
<tr>
<th style="width:250px;font-size:13px;">NAMA PEGAWAI YANG MELAPOR</th>
<td style="font-size:13px;">: <?php _E(strtoupper($this->fdb->pegawai));?></td>
</tr>
<tr>
<th style="width:250px;font-size:13px;">JAWATAN RASMI</th>
<td style="font-size:13px;">: <?php _E(strtoupper($this->fdb->jawatan));?></td>
</tr>
<tr>
<th style="width:250px;font-size:13px;">EMEL</th>
<td style="font-size:13px;">: <?php _E(strtoupper($this->fdb->email));?></td>
</tr>
<tr>
<th style="width:250px;font-size:13px;">TELEFON</th>
<td style="font-size:13px;">: <?php _E(strtoupper($this->fdb->phone));?></td>
</tr>
<tr>
<th style="width:250px;font-size:13px;">FAKS</th>
<td style="font-size:13px;">: <?php _E(strtoupper($this->fdb->fax));?></td>
</tr>
</table>

</body>
</html>

