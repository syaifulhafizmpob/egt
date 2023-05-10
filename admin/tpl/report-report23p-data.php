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

function _pp($dxx, $pe, $pen, $obj) {
    $pl = $obj->jawatan->_list_bypeniaga($dxx['id']);
    $pe2 = $pe;
    $pe3 = $pe;
    $jta = array();

    $html = "
   
    <tr>
    <th rowspan='2' style='vertical-align: bottom;'>KUMPULAN ".strtoupper($dxx['name'])."</th>
    <th style='vertical-align: top;' class='center' colspan='4' >Warganegara</th>
    <th style='vertical-align: top;' class='center' colspan='4' >Bukan Warganegara</th>
	<th rowspan='2' style='vertical-align: bottom;' class='center' >Jumlah Besar</th>
    </tr>
    <tr>
    ";
    if ( _array($pe) ) {
	    foreach($pe as $n => $rt) {
		    $html .= "<td style='vertical-align: top;' class='bold right'>".$rt['name']."</td>";
	    }
		$html .= "<th style='vertical-align: top;' class='bold right'>Jumlah</th>";
	    foreach($pe as $n => $rt) {
		    $html .= "<td style='vertical-align: top;' class='bold right'>".$rt['name']."</td>";
	    }
		$html .= "<th style='vertical-align: top;' class='bold right'>Jumlah</th>";
    }
    $html .= "</tr>";

    if ( _array($pl) ) {
        foreach( $pl as $n => $rt) {
		    $html .= "<tr><td>".$obj->jawatan->_getname($rt['jawatan_id'])."</td>";
						
		    $ppe = $pe2;
		    $jt = 0;
		    if ( _array($ppe) ) {
			    $jumlah_pekerja_a = 0;
			    while( $rtt = @array_shift($ppe) ) {

                    $dta = $obj->record->_report4p($rtt['id'],$dxx['id'],$rt['jawatan_id'],$obj->survey_id);
                    $local = 0;
                    if ( _array($dta) ) {
                        while($dt = @array_shift($dta) ) {
				            $local += ((int)$dt['local']+(int)$dt['local2']);							
                        }
                    }
				    $html .= "<td class='right'>".$local."</td>";
				 $jumlah_pekerja_a += $local;	
                }
				
				 $html .= "<td class='right'><b>".$jumlah_pekerja_a."</b></td>";
				 
                $ppe = $pe2;
				$jumlah_pekerja_b = 0;
			    while( $rtt = @array_shift($ppe) ) {
				    $dta = $obj->record->_report5p($rtt['id'],$dxx['id'],$rt['jawatan_id'],$obj->survey_id);
                    $alien = 0;
                    if ( _array($dta) ) {
                        while( $dt = @array_shift($dta) ) {
				            $alien += ((int)$dt['alien']+(int)$dt['alien2']);
                        }
                    }
				    $html .= "<td class='right'>".$alien."</td>";
				  $jumlah_pekerja_b += $alien;		
					
			    }
				 $html .= "<td class='right'><b>".$jumlah_pekerja_b."</b></td>";
				 $jumlah_pekerja_c = $jumlah_pekerja_a + $jumlah_pekerja_b;
				 $html .= "<td class='right'><b>".$jumlah_pekerja_c."</b></td>";
		    }
		    $html .= "</tr>";	

			  
			
	    }
    }
    unset($dxx, $pe, $pen, $obj);
    return $html;
}


// ikin add 08062016
function _pp_total($dxx, $pe, $pen, $obj) {
    $pl = $obj->jawatan->_list_bypeniaga($dxx['id']);
    $pe2 = $pe;
    $pe3 = $pe;
    $jta = array();

    

		    $html .= "<tr><td style='vertical-align: top;' class='bold right'>Jumlah</td>";
						
		    $ppe = $pe2;
		    $jt = 0;
		    if ( _array($ppe) ) {
			    $jumlah_pekerja_a = 0;
			    while( $rtt = @array_shift($ppe) ) {

                    $dta = $obj->record->_report4total($rtt['id'],$dxx['id'],$obj->survey_id);
                    $local = 0;
                    if ( _array($dta) ) {
                        while($dt = @array_shift($dta) ) {
				            $local += ((int)$dt['local']+(int)$dt['local2']);
							
                        }
                    }
				    $html .= "<td class='right'>".$local."</td>";
				 $jumlah_pekerja_a += $local;	
                }
				
				 $html .= "<td class='right'><b>".$jumlah_pekerja_a."</b></td>";
				 
                $ppe = $pe2;
				$jumlah_pekerja_b = 0;
			    while( $rtt = @array_shift($ppe) ) {
				    $dta = $obj->record->_report5total($rtt['id'],$dxx['id'],$rt['jawatan_id'],$obj->survey_id);
                    $alien = 0;
                    if ( _array($dta) ) {
                        while( $dt = @array_shift($dta) ) {
				            $alien += ((int)$dt['alien']+(int)$dt['alien2']);
                        }
                    }
				    $html .= "<td class='right'>".$alien."</td>";
				  $jumlah_pekerja_b += $alien;		
					
			    }
				 $html .= "<td class='right'><b>".$jumlah_pekerja_b."</b></td>";
				 $jumlah_pekerja_c = $jumlah_pekerja_a + $jumlah_pekerja_b;
				 $html .= "<td class='right'><b>".$jumlah_pekerja_c."</b></td>";
		    }
		    $html .= "</tr>";	

			  
			
    unset($dxx, $pe, $pen, $obj);
    return $html;
}

//start main code

$pe = $this->display->_listcategory("peniaga");
$pen = count($pe); 

$html = "

<center>
<table id='x-table1' style='margin: 0px;padding:0px;width:100%;'>
 <tr><td colspan='".($pen + 1)."' class='title'>Bilangan Gunatenaga Mengikut Jawatan  Bagi Setiap Kategori Kumpulan - {$syear}</td></tr>
";
$list = $this->jawatan->_list_group_peniaga();
if ( _array($list) ) {
	while( $dt = @array_shift($list) ) {
        //if ( $dt["id"] == 5 ) continue;
        $html .= _pp($dt, $pe, $pen, $this);
		$html .= _pp_total($dt, $pe, $pen, $this);
        $html .= "<tr><td colspan='".($pen + 1)."' class='border-none'>&nbsp;</td></tr>";
    }
}
$html .= "</table></center>";
_E($html);
?>


