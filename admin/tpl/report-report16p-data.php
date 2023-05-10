<?php
@_object($this) || exit("403 Forbidden");
$this->_notlogin();
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

$category_name = $this->display->_getcategory_name($category_id);
$category_name = ( !_null($category_name) ? " ".$category_name." " : " " );
$group_name = $this->display->_getcategory_name($group_id);
$group_name = ( !_null($group_name) ? " ".$group_name." " : " " );
$jawatan_name = $this->jawatan->_getname($jawatan_id);
$jawatan_name = ( !_null($jawatan_name) ? " ".$jawatan_name." " : " " );

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

$html_h = "<center><table id='x-table1' style='margin: 0px;padding:0px;width:100%;'>
<tr><td colspan='".( $isbahantanaman ? "23" : "21" )."' class='title'>Maklumat Gunatenaga Di Sektor Peniaga Mengikut Kategori{$category_name} Bagi Jawatan{$jawatan_name}Mengikut Negeri - {$smonth} {$syear}</td></tr>
<tr>
<th colspan='1' rowspan='3' style='vertical-align: bottom;' class='border-bottom-none border-right-none'>Negeri</th>
";
if ( $isbahantanaman ) {
$html_h .= "<th colspan='1' rowspan='3' style='vertical-align: bottom;' class='border-bottom-none border-right-none'>Keluasan<br>Nurseri<br>Hektar</th>";
}
$html_h .= "
<th colspan='8' rowspan='1' style='vertical-align: top;' class='border-left border-bottom border-right-none center'>Warganegara Tempatan</th>
<th colspan='8' rowspan='1' style='vertical-align: top;' class='border-left border-bottom border-right-none center'>Warganegara Asing</th>
<th colspan='1' rowspan='3' style='vertical-align: bottom;' class='border-bottom-none border-right-none'>Jumlah<br>Pekerja</th>
";
if ( $isbahantanaman ) {
$html_h .= "<th colspan='1' rowspan='3' style='vertical-align: bottom;' class='border-bottom-none border-right-none'>Bilangan<br>Pekerja<br>Per<br>Hektar</th>";
}
$html_h .= "
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
$list1 = $this->display->_liststate();
$perror = false;
function _dval($val = 0 ) {
	return ( _num($val) ? $val : 0 );
}

if ( _array($list1) ) {
	while( $rl = @array_shift($list1) ) {
        $state_id = $rl['id'];

		$html .= "<tr>";
		$html .= "<td class='ll border-left border-left border-bottom'>";
		$html .= ucwords($rl['name']);
		$html .= "</td>";
        $rrdata = $this->record->_getinfo_respondent_tot($state_id,$this->survey_id,$group_id,$jawatan_id,$category_id,$subcategory_id);
        $keluasan_tapak_semaian = 0;
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

                $keluasan_tapak_semaian += $xrt['keluasan_tapak_semaian'];
            }
        }

        $purata_gaji_aa = @( $purata_gaji_a / $jumlah_pekerja_a );
        $purata_gaji_bb = @( $purata_gaji_b / $jumlah_pekerja_b );

        $purata_elaun_aa = @( $purata_elaun_a / $jumlah_pekerja_a );
        $purata_elaun_bb = @( $purata_elaun_b / $jumlah_pekerja_b );

        if ( $isbahantanaman ) {
		    $html .= "<td class='border-bottom border-right right'>";
		    $html .= ( _decimal($keluasan_tapak_semaian) ? $keluasan_tapak_semaian : 0 );
		    $html .= "</td>";
        }

		$html .= "<td class='border-bottom border-right right'>";
		$html .= _dval($melayu);
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>";
		$html .= _dval($cina);
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>";
		$html .= _dval($india);
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>";
	    $html .= _dval($etnik);
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>".$jumlah_pekerja_a."</td>";
		$html .= "<td class='border-bottom border-right right'>";
		$html .= @round($purata_gaji_aa);
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>";
		$html .= @round($purata_elaun_aa);
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>";
		$html .= @round($purata_gaji_aa + $purata_elaun_aa);
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>";
		$html .= _dval($indonesia);
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>";
		$html .= _dval($bangladesh);
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>";
		$html .= _dval($nepal);
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>";
        $html .= $alien;
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>".$jumlah_pekerja_b."</td>";
		$html .= "<td class='border-bottom border-right right'>";
		$html .= @round($purata_gaji_bb);
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>";
		$html .= @round($purata_elaun_bb);
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>";
		$html .= @round($purata_gaji_bb + $purata_elaun_bb);
		$html .= "</td>";
        $ta = @($jumlah_pekerja_a + $jumlah_pekerja_b );
		$html .= "<td class='border-bottom border-right right'>".$ta."</td>";
        $tb = @( $ta / $keluasan_tapak_semaian );
		if ( $isbahantanaman ) $html .= "<td class='border-bottom border-right right'>".( !_null($tb) ? $tb : 0 )."</td>";
		$html .= "<td class='border-bottom border-right right'>";
		$html .= _dval($bp1);
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>";
		$html .= _dval($bp2);
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>";
		$html .= _dval($bp3);
		$html .= "</td>";

		$html .= "</tr>";

	}
}
$html .= "</table></center>";
_E($html);

