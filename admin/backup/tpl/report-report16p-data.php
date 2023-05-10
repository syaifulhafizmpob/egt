<?php
@_object($this) || exit("403 Forbidden");
$this->_notlogin();
$this->survey_id = $this->request["survey_id"];
if ( _null($this->survey_id) ) exit("Invalid survey!");
$svdb = (object)$this->survey->_getinfo($this->survey_id );
$smonth = ucwords($svdb->month);
$syear = $svdb->year;
$category_id = $this->request["catid"];
$subcategory_id = $this->request["subcatid"];
$group_id = $this->request["group_id"];
$jawatan_id = $this->request["jawatan_id"];
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
<tr><td colspan='20' class='title'>Maklumat Gunatenaga Di Sektor Peniaga Mengikut Jawatan Dan Negeri {$smonth} {$syear}</td></tr>
<tr>
<th colspan='1' rowspan='3' style='vertical-align: bottom;' class='border-bottom-none border-right-none'>Negeri</th>
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
$list1 = $this->display->_liststate();
$perror = false;
function _dval($val = 0 ) {
	return ( _num($val) ? $val : 0 );
}

if ( _array($list1) ) {
	while( $rl = @array_shift($list1) ) {
        $state_id = $rl['id'];

		$html .= "<tr>";
		$html .= "<td class='ll border-left border-right border-bottom'>";
		$html .= ucwords($rl['name']);
		$html .= "</td>";

        $rrdata = $this->record->_getinfo_respondent_tot($state_id,$this->survey_id,$group_id,$jawatan_id,$category_id,$subcategory_id);
        $etnik = $alien = array();
        $rcdb = new stdClass();
        $rcdb->melayu = $rcdb->cina = $rcdb->india = 0;
        $rcdb->indonedia = $rcdb->bangladesh = $rcdb->nepal = 0;
        $rcdb->gajia = $rcdb->gajib = $rcdb->elauna = $rcdb->elaunb = 0;
        $rcdb->bp1 = $rcdb->bp2 = $rcdb->bp3 = 0;
        if ( _array($rrdata) ) {
            while( $xrt = @array_shift($rrdata) ) {
                $etnik = array_merge($this->record->_getetnik($xrt['id']), $etnik);
		        $alien = array_merge($this->record->_getalien($xrt['id']), $alien);
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
				if ( $gid != "1" && _dval($e2) != 0 && _dval($rcdb->gajib) == 0 ) $perror = true;
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
}
$html .= "</table></center>";
_E($html);

