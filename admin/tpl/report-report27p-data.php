<?php
@_object($this) || exit("403 Forbidden");
$this->_notlogin();
$this->survey_id = $this->request["survey_id"];
if ( _null($this->survey_id) ) exit("Invalid survey!");
$svdb = (object)$this->survey->_getinfo($this->survey_id );
$syear = ucwords($svdb->month)." ".$svdb->year;
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

function _pp($dxx, $pe, $pen,  $category_id, $obj) {
    $pl = $obj->jawatan->_list_bypeniaga_tanaman_bygroup($dxx['id']); // jawatan
    $pe2 = $pe;
    $pe3 = $pe;
    $jta = array();

    $html = "
   
    <tr>
    <th rowspan='2' style='vertical-align: bottom;'>KUMPULAN ".strtoupper($dxx['name'])."</th>
    <th style='vertical-align: top;' class='center' colspan='".$pen."' >Warganegara</th>
    <th style='vertical-align: top;' class='center' colspan='".$pen."' >Bukan Warganegara</th>
    </tr>
    <tr>
    ";
    if ( _array($pe) ) {
	    foreach($pe as $n => $rt) {
		    $html .= "<td style='vertical-align: top;' class='bold right'>".$rt['name']."</td>";
	    }
	    foreach($pe as $n => $rt) {
		    $html .= "<td style='vertical-align: top;' class='bold right'>".$rt['name']."</td>";
	    }
    }
    $html .= "</tr>";

    if ( _array($pl) ) {
        foreach( $pl as $n => $rt) {
		    $html .= "<tr><td>".$obj->jawatan->_getname($rt['jawatan_id'])."</td>";

            $jawatan_id = $rt['jawatan_id'];

            $ppe = $pe2;
            while( $rtt = @array_shift($ppe) ) {
                $subcategory_id = $rtt['id'];
                $rrdata = $obj->record->_getinfo_respondent_tot('all',$obj->survey_id,$dxx['id'],$jawatan_id,$category_id,$subcategory_id);
                $jumlah_pekerja_a = 0;
                $purata_gaji_s = 0;
                if ( _array($rrdata) ) {
                    while( $xrt = @array_shift($rrdata) ) {
                        $melayu_c = $xrt['melayu'];
                        $cina_c = $xrt['cina'];
                        $india_c = $xrt['india'];
                        $etnik_c = (int)$obj->get_var("select sum(value) from {$obj->record->table_etnik} where record_id='".$xrt['id']."'");

                        $jumlah_pekerja_c = ( $melayu_c + $cina_c + $india_c + $etnik_c );
                        $jumlah_pekerja_a += $jumlah_pekerja_c;

                        $purata_gaji = ( $xrt['gajia'] * $jumlah_pekerja_c );
                        $purata_gaji_s += $purata_gaji;
                    }
                }
                $total = @( $purata_gaji_s / $jumlah_pekerja_a );
                $html .= "<td class='right'>".round($total)."</td>";
            }

            $ppe = $pe2;
            while( $rtt = @array_shift($ppe) ) {
                $subcategory_id = $rtt['id'];
                $rrdata = $obj->record->_getinfo_respondent_tot('all',$obj->survey_id,$dxx['id'],$jawatan_id,$category_id,$subcategory_id);
                $jumlah_pekerja_b = 0;
                $purata_gaji_s = 0;
                if ( _array($rrdata) ) {
                    while( $xrt = @array_shift($rrdata) ) {
                        $indonesia_c = $xrt['indonesia'];
                        $bangladesh_c = $xrt['bangladesh'];
                        $nepal_c = $xrt['nepal'];
                        $alien_c = (int)$obj->get_var("select sum(value) from {$obj->record->table_alien} where record_id='".$xrt['id']."'");
                 
                        $jumlah_pekerja_c = ( $indonesia_c + $bangladesh_c + $nepal_c + $alien_c );
                        $jumlah_pekerja_b += $jumlah_pekerja_c;

                        $purata_gaji = ( $xrt['gajia'] * $jumlah_pekerja_c );
                        $purata_gaji_s += $purata_gaji;
                    }
                }
                $total = @( $purata_gaji_s / $jumlah_pekerja_b );
                $html .= "<td class='right'>".round($total)."</td>";
            }
		    $html .= "</tr>";
	    }
    }
    unset($dxx, $pe, $pen, $obj);
    return $html;
}

$pe = $this->display->_listsubcategory_bahan_tanaman();
$pen = count($pe);
$category_id = "04"; // bahan tanaman sawit

$html = "
<center>
<table id='x-table1' style='margin: 0px;padding:0px;width:100%;'>
 <tr><td colspan='".($pen + 1)."' class='title'>Purata Gaji Mengikut Jawatan Bagi  Kategori Bahan Tanaman - {$syear}</td></tr>
";
$list = $this->jawatan->_list_group_peniaga();
if ( _array($list) ) {
	while( $dt = @array_shift($list) ) {
        $html .= _pp($dt, $pe, $pen, $category_id, $this);
        $html .= "<tr><td colspan='".($pen + 1)."' class='border-none'>&nbsp;</td></tr>";
    }
}
$html .= "</table></center>";
_E($html);
?>


