<?php
@_object($this) || exit("403 Forbidden");
$this->_notlogin();
$this->survey_id = $this->request["survey_id"];
if ( _null($this->survey_id) ) exit("Invalid survey!");
$svdb = (object)$this->survey->_getinfo($this->survey_id );
$smonth = ucwords($svdb->month);
$syear = $svdb->year;
$state_id = $this->request["state_id"];

$state_name = $this->display->_getstate_name($state_id);
$state_name = ( !_null($state_name) ? " di Negeri ".ucfirst($state_name)." " : " " );

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
$fl = array("A","B","C","D","E","F","G","H","I","J","K","L","M");

$html_h = "<center><table id='x-table1' style='margin: 0px;padding:0px;width:100%;'>
<tr><td colspan='20' class='title'>Maklumat Gunatenaga Bagi Peniaga Minyak Mengikut Jawatan{$state_name}- {$smonth} {$syear}</td></tr>
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

$ja1 = 0;
$ja2 = 0;
$ja3 = 0;
$ja4 = 0;
$ja5 = 0;
$ja6 = 0;
$ja7 = 0;
$ja8 = 0;
$ja9 = 0;
$ja10 = 0;
$ja11 = 0;
$ja12 = 0;
$ja13 = 0;
$ja14 = 0;
$ja15 = 0;
$ja16 = 0;
$ja17 = 0;
$ja18 = 0;
$ja19 = 0;

if ( _array($list1) ) {
	$h1_a = array();
	$fln = 0;
	while( $rl = @array_shift($list1) ) {
        $cid = $rl['category_id'];
		$gid = $rl['group_id'];
		$jid = $rl['jawatan_id'];
		$h1 = $this->jawatan->_getgroup_name($rl['group_id']);
		if ( _null($h1_a[$h1]) ) {
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

        $rrdata = $this->record->_getinfo_respondent_fix($state_id,$this->survey_id,$gid,$jid,$cid);
        $melayu = 0;
        $cina = 0;
        $india = 0;
        $local_j = 0;
        $etnik = 0;
        $indonesia = 0;
        $bangladesh = 0;
        $nepal = 0;
        $alien_j = 0;
        $alien = 0;
        $purata_gaji_a = 0;
        $purata_gaji_b = 0;
        $purata_elaun_a = 0;
        $purata_elaun_b = 0;
        $jumlah_pekerja_a = 0;
        $jumlah_pekerja_b = 0;

        $bp1 = 0;
        $bp2 = 0;
        $bp3 = 0;

        if ( _array($rrdata) ) {
            while( $xrt = @array_shift($rrdata) ) {
                $melayu_c = $xrt['melayu'];
                $cina_c = $xrt['cina'];
                $india_c = $xrt['india'];
                $etnik_c = (int)$this->get_var("select sum(value) from {$this->record->table_etnik} where record_id='".$xrt['id']."'");

                $jumlah_pekerja_c = ( $melayu_c + $cina_c + $india_c + $etnik_c );
                $purata_gaji_c = ( $xrt['gajia'] * $jumlah_pekerja_c );
                $purata_elaun_c = ( $xrt['elauna'] * $jumlah_pekerja_c );
                $purata_gaji_a += $purata_gaji_c;
                $purata_elaun_a += $purata_elaun_c;
                $jumlah_pekerja_a += $jumlah_pekerja_c;

                $melayu += $melayu_c;
                $cina += $cina_c;
                $india += $india_c;
                $etnik += $etnik_c;

                $indonesia_c = $xrt['indonesia'];
                $bangladesh_c = $xrt['bangladesh'];
                $nepal_c = $xrt['nepal'];
                $alien_c = (int)$this->get_var("select sum(value) from {$this->record->table_alien} where record_id='".$xrt['id']."'");
 
                $jumlah_pekerja_c = ( $indonesia_c + $bangladesh_c + $nepal_c + $alien_c );
                $purata_gaji_c = ( $xrt['gajib'] * $jumlah_pekerja_c );
                $purata_elaun_c = ( $xrt['elaunb'] * $jumlah_pekerja_c );
                $purata_gaji_b += $purata_gaji_c;
                $purata_elaun_b += $purata_elaun_c;
                $jumlah_pekerja_b += $jumlah_pekerja_c;

                $indonesia += $indonesia_c;
                $bangladesh += $bangladesh_c;
                $nepal += $nepal_c;
                $alien += $alien_c;

                $bp1 += $xrt['bp1'];
                $bp2 += $xrt['bp2'];
                $bp3 += $xrt['bp3'];
            }
        }

        $purata_gaji_aa = @( $purata_gaji_a / $jumlah_pekerja_a );
        $purata_gaji_bb = @( $purata_gaji_b / $jumlah_pekerja_b );

        $purata_elaun_aa = @( $purata_elaun_a / $jumlah_pekerja_a );
        $purata_elaun_bb = @( $purata_elaun_b / $jumlah_pekerja_b );

		$html .= "<td class='border-bottom border-right right'>";
		$html .= _dval($melayu);
        $ja1 += _dval($melayu);
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>";
		$html .= _dval($cina);
        $ja2 += _dval($cina);
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>";
		$html .= _dval($india);
        $ja3 += _dval($india);
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>";
	    $html .= _dval($etnik);
        $ja4 += _dval($etnik);
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>".$jumlah_pekerja_a."</td>";
        $ja5 += $jumlah_pekerja_a;
		$html .= "<td class='border-bottom border-right right'>";
		$html .= @round($purata_gaji_aa);
        $ja6 += @round($purata_gaji_aa);
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>";
		$html .= @round($purata_elaun_aa);
        $ja7 += @round($purata_elaun_aa);
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>";
		$html .= @round($purata_gaji_aa + $purata_elaun_aa);
        $ja8 += @round($purata_gaji_aa + $purata_elaun_aa);
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>";
		$html .= _dval($indonesia);
        $ja9 += _dval($indonesia);
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>";
		$html .= _dval($bangladesh);
        $ja10 += _dval($bangladesh);
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>";
		$html .= _dval($nepal);
		$ja11 += _dval($nepal);
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>";
        $html .= $alien;
        $ja12 += $alien;
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>".$jumlah_pekerja_b."</td>";
        $ja13 += $jumlah_pekerja_b;
		$html .= "<td class='border-bottom border-right right'>";
		$html .= @round($purata_gaji_bb);
        $ja14 += @round($purata_gaji_bb);
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>";
		$html .= @round($purata_elaun_bb);
        $ja15 += @round($purata_elaun_bb);
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>";
		$html .= @round($purata_gaji_bb + $purata_elaun_bb);
		$ja16 += @round($purata_gaji_bb + $purata_elaun_bb);
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>";
		$html .= _dval($bp1);
        $ja17 += _dval($bp1);
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>";
		$html .= _dval($bp2);
        $ja18 += _dval($bp2);
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>";
		$html .= _dval($bp3);
        $ja19 += _dval($bp3);
		$html .= "</td>";

		$html .= "</tr>";

	}
}
$html .= "<tr>";
$html .= "<td class='right'>Jumlah</td>";
$html .= "<td class='right'>".$ja1."</td>";
$html .= "<td class='right'>".$ja2."</td>";
$html .= "<td class='right'>".$ja3."</td>";
$html .= "<td class='right'>".$ja4."</td>";
$html .= "<td class='right'>".$ja5."</td>";
$html .= "<td class='right'>".$ja6."</td>";
$html .= "<td class='right'>".$ja7."</td>";
$html .= "<td class='right'>".$ja8."</td>";
$html .= "<td class='right'>".$ja9."</td>";
$html .= "<td class='right'>".$ja10."</td>";
$html .= "<td class='right'>".$ja11."</td>";
$html .= "<td class='right'>".$ja12."</td>";
$html .= "<td class='right'>".$ja13."</td>";
$html .= "<td class='right'>".$ja14."</td>";
$html .= "<td class='right'>".$ja15."</td>";
$html .= "<td class='right'>".$ja16."</td>";
$html .= "<td class='right'>".$ja17."</td>";
$html .= "<td class='right'>".$ja18."</td>";
$html .= "<td class='right'>".$ja19."</td>";
$html .= "</tr>";
$html .= "</table></center>";
_E($html);
