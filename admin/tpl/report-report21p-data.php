<?php
@_object($this) || exit("403 Forbidden");
$this->_notlogin();
$this->survey_id = $this->request["survey_id"];
if ( _null($this->survey_id) ) exit("Invalid survey!");
$svdb = (object)$this->survey->_getinfo($this->survey_id );
$smonth = ucwords($svdb->month);
$syear = $svdb->year;
$category_id = "04";
$subcategory_id = 14; /* tapak semaian */
$group_id = 5;
$jawatan_id = 47;

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
<tr><td colspan='4' class='title'>Bilangan Gunatenaga Di Tapak Semaian Bagi Jawatan Pekerja Am Di Tapak Semaian Mengikut Negeri Dan Keluasan - {$smonth} {$syear}</td></tr>
<tr>
<th style='vertical-align: top;' class='border-bottom-none border-right-none'>Negeri</th>
<th style='vertical-align: top;' class='border-bottom-none border-right-none'>Bilangan Pekerja Di Tapak Semaian</th>
<th style='vertical-align: top;' class='border-bottom-none border-right-none'>Keluasan Hektar</th>
<th style='vertical-align: top;' class='border-bottom-none border-right'>Bilangan Pekerja / Hektar</th>
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

        $jumlah_pekerja_a = 0;
        $jumlah_pekerja_b = 0;


        if ( _array($rrdata) ) {
            while( $xrt = @array_shift($rrdata) ) {
                $melayu_c = $xrt['melayu'];
                $cina_c = $xrt['cina'];
                $india_c = $xrt['india'];
                $etnik_c = (int)$this->get_var("select sum(value) from {$this->record->table_etnik} where record_id='".$xrt['id']."'");

                $jumlah_pekerja_c = ( $melayu_c + $cina_c + $india_c + $etnik_c );
                $jumlah_pekerja_a += $jumlah_pekerja_c;

                $indonesia_c = $xrt['indonesia'];
                $bangladesh_c = $xrt['bangladesh'];
                $nepal_c = $xrt['nepal'];
                $alien_c = (int)$this->get_var("select sum(value) from {$this->record->table_alien} where record_id='".$xrt['id']."'");
 
                $jumlah_pekerja_c = ( $indonesia_c + $bangladesh_c + $nepal_c + $alien_c );
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

        $keluasan_tapak_semaian = ( _decimal($keluasan_tapak_semaian) ? $keluasan_tapak_semaian : 0 );
        $pt = @( $jumlah_pekerja_a + $jumlah_pekerja_b );
        $ptk = @( $pt / $keluasan_tapak_semaian );
		$html .= "<td class='border-bottom border-right right'>".$pt."</td>";
		$html .= "<td class='border-bottom border-right right'>".$keluasan_tapak_semaian."</td>";
		$html .= "<td class='border-bottom border-right right'>".( _decimal($ptk) ? $ptk : 0 )."</td>";
		$html .= "</tr>";

	}
}
$html .= "</table></center>";
_E($html);

