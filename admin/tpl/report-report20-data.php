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

if ( $this->request["cat_id"] != 'all' ) {
    $category_name = $this->display->_getcategory_name($this->request["cat_id"]);
    $category_name = ( !_null($category_name) ? " ".$category_name." " : " " );
} else {
    $category_name = ", Jumlah Keseluruhan ";
}
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
<tr><td colspan='12' class='title'>Bilangan Gunatenaga Mengikut Jawatan Dan Kaum (Bagi Warganegara) Dan Negara Asal{$category_name}{$state_name}- {$smonth} {$syear}</td></tr>
";

function _hheader($jawatan) {
return "
<tr>
<th colspan='1' rowspan='2' style='vertical-align: bottom;' class='border-bottom-none border-right-none'>KATEGORI<br>".strtoupper($jawatan)."</th>
<th colspan='4' rowspan='1' style='vertical-align: top;' class='border-left border-bottom border-right-none center'>Warganegara</th>
<th colspan='1' rowspan='2' style='vertical-align: bottom;' class='border-left right'>Jumlah</th>
<th colspan='4' rowspan='1' style='vertical-align: top;' class='border-left border-bottom border-right center'>Bukan Warganegara</th>
<th colspan='1' rowspan='2' style='vertical-align: bottom;' class='border-left right'>Jumlah</th>
<th colspan='1' rowspan='2' style='vertical-align: bottom; padding-top:5px;' class='border-bottom-none border-right right'>
Jumlah Besar
</td>
</tr>
<tr>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left right'>Melayu</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left right'>Cina</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left right'>India</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left right'>Lain-lain</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left right'>Indonesia</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left right'>Bangladesh</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left right'>Nepal</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left right'>Lain-lain</td>
</tr>
";
}

$html .= $html_h;

if ( $this->request["cat_id"] != 'all' ) {
    $list1 = $this->jawatan->_list_bykilang_cat($this->request["cat_id"]);
} else {
    $list1 = $this->jawatan->_list_bykilang_all();
}

$perror = false;
function _dval($val = 0 ) {
	return ( _num($val) ? $val : 0 );
}


$h1_a = array();

if ( _array($list1) ) {

	while( $rl = @array_shift($list1) ) {
        $cid = ( $this->request["cat_id"] == 'all' ? 'kilang' : $rl['category_id'] );
		$gid = $rl['group_id'];
		$jid = $rl['jawatan_id'];
		$h1 = $this->jawatan->_getgroup_name($gid);
		if ( !_array($h1_a[$h1]) ) {
			$h1_a[$h1]['h'] .= "<tr>";
            $h1_a[$h1]['h'] .= _hheader($h1);
			$h1_a[$h1]['h'] .= "</tr>";
            for($x=1;$x <= 11;$x++) {
                $h1_a[$h1]["ja".$x] = 0;
            }
		}

		$h1_a[$h1]['h'] .= "<tr>";
		$h1_a[$h1]['h'] .= "<td class='ll border-left border-right border-bottom'>";
		$h1_a[$h1]['h'] .= $this->jawatan->_getname($jid);
		$h1_a[$h1]['h'] .= "</td>";

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

                $melayu += $melayu_c;
                $cina += $cina_c;
                $india += $india_c;
                $etnik += $etnik_c;

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
            }
        }


		$h1_a[$h1]['h'] .= "<td class='border-bottom border-right right'>";
		$h1_a[$h1]['h'] .= _dval($melayu);
        $h1_a[$h1]['ja1'] += _dval($melayu);

		$h1_a[$h1]['h'] .= "</td>";
		$h1_a[$h1]['h'] .= "<td class='border-bottom border-right right'>";
		$h1_a[$h1]['h'] .= _dval($cina);
        $h1_a[$h1]['ja2'] += _dval($cina);

		$h1_a[$h1]['h'] .= "</td>";
		$h1_a[$h1]['h'] .= "<td class='border-bottom border-right right'>";
		$h1_a[$h1]['h'] .= _dval($india);
        $h1_a[$h1]['ja3'] += _dval($india);

		$h1_a[$h1]['h'] .= "</td>";
		$h1_a[$h1]['h'] .= "<td class='border-bottom border-right right'>";
	    $h1_a[$h1]['h'] .= _dval($etnik);
        $h1_a[$h1]['ja4'] += _dval($etnik);

		$h1_a[$h1]['h'] .= "</td>";
		$h1_a[$h1]['h'] .= "<td class='border-bottom border-right right'>".$jumlah_pekerja_a."</td>";
        $h1_a[$h1]['ja5'] += $jumlah_pekerja_a;

		$h1_a[$h1]['h'] .= "<td class='border-bottom border-right right'>";
		$h1_a[$h1]['h'] .= _dval($indonesia);
        $h1_a[$h1]['ja6'] += _dval($indonesia);

		$h1_a[$h1]['h'] .= "</td>";
		$h1_a[$h1]['h'] .= "<td class='border-bottom border-right right'>";
		$h1_a[$h1]['h'] .= _dval($bangladesh);
        $h1_a[$h1]['ja7'] += _dval($bangladesh);

		$h1_a[$h1]['h'] .= "</td>";
		$h1_a[$h1]['h'] .= "<td class='border-bottom border-right right'>";
		$h1_a[$h1]['h'] .= _dval($nepal);
		$h1_a[$h1]['ja8'] += _dval($nepal);

		$h1_a[$h1]['h'] .= "</td>";
		$h1_a[$h1]['h'] .= "<td class='border-bottom border-right right'>";
        $h1_a[$h1]['h'] .= $alien;
        $h1_a[$h1]['ja9'] += $alien;

		$h1_a[$h1]['h'] .= "</td>";
		$h1_a[$h1]['h'] .= "<td class='border-bottom border-right right'>".$jumlah_pekerja_b."</td>";
        $h1_a[$h1]['ja10'] += $jumlah_pekerja_b;

        $jj = ( $jumlah_pekerja_a + $jumlah_pekerja_b );
		$h1_a[$h1]['h'] .= "<td class='border-bottom border-right right'>".$jj."</td>";

        $h1_a[$h1]['ja11'] += $jj;

		$h1_a[$h1]['h'] .= "</tr>";

	}
}

if ( _array($h1_a) ) {
    $tt=array();
    foreach($h1_a as $n => $aa ) {
        $html .= $aa['h'];
        $html .= "<tr>";
        $html .= "<td class='right'>Jumlah</td>";
        for($x=1;$x <= 11;$x++) {
            $html .= "<td class='right'>".$aa["ja".$x]."</td>";
            $tt[$x] += $aa["ja".$x];
        }
        $html .= "</tr>";
        $html .= "<tr><td colspan='12' class='border-none'>&nbsp;</td></tr>";
    }

    $html .= "
<tr><td colspan='12' class='border-none bold'>JUMLAH KESELURUHAN</td></tr>
<tr>
<th colspan='1' rowspan='2' style='vertical-align: bottom;' class='border-bottom-none border-right-none'>STATUS KEWARGANEGARAAN</th>
<th colspan='4' rowspan='1' style='vertical-align: top;' class='border-left border-bottom border-right-none center'>Warganegara</th>
<th colspan='1' rowspan='2' style='vertical-align: bottom;' class='border-left right'>Jumlah</th>
<th colspan='4' rowspan='1' style='vertical-align: top;' class='border-left border-bottom border-right center'>Bukan Warganegara</th>
<th colspan='1' rowspan='2' style='vertical-align: bottom;' class='border-left right'>Jumlah</th>
<th colspan='1' rowspan='2' style='vertical-align: bottom; padding-top:5px;' class='border-bottom-none border-right right'>
Jumlah Besar
</td>
</tr>
<tr>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left right'>Melayu</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left right'>Cina</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left right'>India</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left right'>Lain-lain</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left right'>Indonesia</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left right'>Bangladesh</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left right'>Nepal</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left right'>Lain-lain</td>
</tr>
";
        $html .= "<tr>";
        $html .= "<td class='right'>Jumlah</td>";
        for($x=1;$x <= 11;$x++) {
            $html .= "<td class='right'>".$tt[$x]."</td>";
        }
        $html .= "</tr>";
}

$html .= "</table></center>";
_E($html);

