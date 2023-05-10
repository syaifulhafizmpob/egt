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
$state_id = $this->request["state_id"];
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
$alien = $this->display->_listalien();
$alien_cnt = count($alien);

$html_h = "<center><table id='x-table1' style='margin: 0px;padding:0px;width:100%;'>
<tr><td colspan='21' class='title'>Maklumat terperinci Gunatenaga  Bagi Sektor kilang Mengikut Kategori Dan Jawatan Bagi Lain-lain Bukan Warganegara {$smonth} {$syear}</td></tr>
<tr>
<th colspan='1' rowspan='2' style='vertical-align: bottom;' class='border-bottom-none border-right-none'>No. Lesen</th>
<th colspan='1' rowspan='2' style='vertical-align: bottom;' class='border-bottom-none border-right-none'>Nama Syarikat</th>
<th colspan='".( $alien_cnt + 1 )."' rowspan='1' style='vertical-align: top;' class='center'>Bilangan Pekerja</th>
</tr>


<tr>
";

foreach($alien as $n => $r) {
   $html_h .="<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left'>".ucwords($r['name'])."</td>";
}

$html_h .= "
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left'>Jumlah</td>

</tr>

";

$list1 = $this->record->_getinfo_respondent_tot($state_id,$this->survey_id,$group_id,$jawatan_id,$category_id,$subcategory_id);
$perror = false;
function _dval($val = 0 ) {
	return ( _num($val) ? $val : 0 );
}

if ( _array($list1) ) {
    $html .= $html_h;
    $skip = array();
	while( $rl = @array_shift($list1) ) {
        $dtt = $this->get_row("select company,nolesen from `".$this->respondent->table."` where `id`='".$rl['respondent_id']."'", ARRAY_A);
        if ( !_null($skip[$dtt['nolesen']]) ) continue;
        $skip[$dtt['nolesen']] = 1;
		$html .= "<tr>";
		$html .= "<td class='ll border-left border-right border-bottom'>";
		$html .= $dtt['nolesen'];
		$html .= "</td>";

		$html .= "<td class='ll border-left border-right border-bottom'>";
		$html .= ucwords($dtt['company']);
		$html .= "</td>";

        $alienp = $this->record->_getalien($rl['id']);
        $j = $n = 0;
        foreach($alien as $n => $r) {
            $lp = ucwords($r['name']);
            $n = (int)$alienp[$lp];
            $j += $n;
            $html .= "<td>".$n."</td>";
        }
         $html .= "<td>".$j."</td>";
    }
} else {
    $html .= "<tr><td colspan='".( $alien_cnt + 1 )."'>Tiada data direkodkan</td></tr>";
}

$html .= "</table></center>";
_E($html);

