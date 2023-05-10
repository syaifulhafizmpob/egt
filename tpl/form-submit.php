<?php
@_object($this) || exit("403 Forbidden");
$this->_notlogin();
$this->survey_id = $this->survey->_getcurrent_id();
if ( _null($this->survey_id) ) exit("Invalid survey!");
$this->fdb = (object)$this->user->_getinfo($this->session->data->id);
$svdb = (object)$this->survey->_getinfo($this->survey_id );
$syear = date('Y', strtotime($svdb->sdate));

// 47 = Pekerja di Tapak Semaian
$tapak_semaian = ( $this->fdb->category_id == '' 
                    || $this->fdb->category_id == '' 
                    || $this->fdb->category_id == '04' ? true : false );

?>

<style type="text/css">
#x-table1 th {
	border: 1px solid #bbbbbb;
}


#x-table1 td {
	vertical-align: top;
	padding-top: 5px;
}


#x-table1 td.ll_active {
	background: yellow;
	font-weight: bold;
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

</style>

<div class="ui-widget left hide" style="margin: 9px;" id="stupidmsg">
<div class="ui-state-error ui-corner-all" style="padding: 0.7em;"> 
<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;margin-top:.3em;"></span> 
<span style='font-weight: solid !important; color: #000000 !important; font-size: 18px !important;'>
Sila kembali pada "Isi borang" Kerana Maklumat Bilangan pekerja / Purata gaji  masih tidak lengkap
</span></p>
</div>
</div>

<?php
$fl = array("A","B","C","D","E","F","G","H","I","J","K","L","M");

$html .= "<center><table id='x-table1' style='margin: 0px;padding:0px;width:100%;'>
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
$list1 = $this->survey->_list_jawatan($this->fdb->category_id);
$perror = false;
function _dval($val = 0 ) {
	return ( _num($val) ? $val : 0 );
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
			$h1_a[$h1] = $gid;
			$jhc = $this->survey->_count_jawatan($gid,$cid);
			$html .= "<tr>";
			$html .= "<th colspan='22'>";
			$html .= $fl[$fln].". ".$h1;
			$html .= "</th>";
			$html .= "</tr>";
			$fln++;
		}

		$html .= "<tr>";
		$html .= "<td class='ll border-left border-right border-bottom'>";
		$html .= $this->survey->_getjawatan_name($jid);
		$html .= "</td>";

		$rcdb = (object)$this->record->_getinfo($this->session->data->id,$this->survey_id,$gid,$jid);
        $keluasan_tapak_semaian = ( _decimal($rcdb->keluasan_tapak_semaian) ? $rcdb->keluasan_tapak_semaian : ( _decimal($keluasan_tapak_semaian) ? $keluasan_tapak_semaian : 0 ) );
		$etnik = $this->record->_getetnik($rcdb->id);
		$alien = $this->record->_getalien($rcdb->id);

		if ( $gid != "1" 
			&& (  _dval($rcdb->melayu) != 0
			|| _dval($rcdb->cina) != 0
			|| _dval($rcdb->india) != 0 )
			&& _dval($rcdb->gajia) == 0 ) $perror = true;

		if ( $gid != "1" 
			&& (  _dval($rcdb->indonesia) != 0
			|| _dval($rcdb->bangladesh) != 0
			|| _dval($rcdb->nepal) != 0 )
			&& _dval($rcdb->gajib) == 0 ) $perror = true;

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
				if ( $gid != "1" && _dval($e2) != 0 && _dval($rcdb->gajia) == 0 ) $perror = true;
			}
		} else {
			$html .= "0";
		}
		$html .= "</td>";
		$html .= "<td class='border-bottom border-right right'>".$ja."</td>";
		$html .= "<td class='border-bottom border-right right'".($gid != "1" && (  _dval($rcdb->melayu) != 0
			|| _dval($rcdb->cina) != 0
			|| _dval($rcdb->india) != 0 ) && _dval($rcdb->gajia) == 0 ? " style='background-color:red;color:white;'" : "").">";
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
		$html .= "<td class='border-bottom border-right right'".($gid != "1" && (  _dval($rcdb->indonesia) != 0
			|| _dval($rcdb->bangladesh) != 0
			|| _dval($rcdb->nepal) != 0 ) && _dval($rcdb->gajib) == 0 ? " style='background-color:red;color:white;'" : "").">";
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
        $html .= "<th colspan='20'>Keluasan Tapak Semaian</th>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td class='border-bottom border-left border-right'>Keluasan (Hektar)</td>";
        $html .= "<td class='border-bottom border-right' colspan='19'>".$keluasan_tapak_semaian."</td>";
        $html .= "</tr>";
    }

}
$html .= "</table></center>";
_E($html);
?>

<table id="x-tabler" style='margin: 10px 0px 0px 0px;padding:0px;width:100%;'>
<tr>
<th class="border-top border-bottom border-left border-right" colspan="2" style="font-size:12px;">
Saya mengaku bahawa keterangan diberi adalah benar sepanjang pengetahuan dan kepercayaan saya.
</th>
</tr>
<tr>
<th class="border-bottom border-left" style="width:200px;font-size:10px;">NAMA PEGAWAI YANG MELAPOR</th>
<td class="border-bottom border-left border-right"><?php _E($this->fdb->pegawai);?></td>
</tr>
<tr>
<th class="border-bottom border-left" style="width:250px;">JAWATAN RASMI</th>
<td class="border-bottom border-left border-right"><?php _E($this->fdb->jawatan);?></td>
</tr>
<tr>
<th class="border-bottom border-left" style="width:250px;">EMEL</th>
<td class="border-bottom border-left border-right"><?php _E($this->fdb->email);?></td>
</tr>
<tr>
<th class="border-bottom border-left" style="width:250px;">TELEFON</th>
<td class="border-bottom border-left border-right"><?php _E($this->fdb->phone);?></td>
</tr>
<tr>
<th class="border-bottom border-left" style="width:250px;">FAKS</th>
<td class="border-bottom border-left border-right"><?php _E($this->fdb->fax);?></td>
</tr>
<?php if ( $this->session->data->adminview ): 
$ainfo = $this->user->_admin_getinfo($this->session->data->id);
?>
<tr>
<th class="border-bottom border-left" style="width:250px;">TARIKH HANTAR</th>
<td class="border-bottom border-left border-right"><?php _E($this->_output_datepicker($ainfo['sdate']));?></td>
</tr>
<?php endif; ?>
<tr>
<td colspan="2" class="border-bottom-none border-left-none border-right-none left" style="padding-left:0px;">
<button name="pprint" class="button button_nav">Cetak</button>
<button name="ppdf" class="button button_nav">PDF</button>
<button name="ppost" class="button button_nav">Hantar Borang</button>
<?php if ( $this->session->data->adminview ): ?>
<button name="ppost2" class="button button_nav">Simpan Data</button>
<?php endif; ?>
</td>
</tr>
</table>


<script type="text/javascript">
$(document).ready(function() {
	$("div.ui-tabs").css("width","1600px");
	function _docetak(pdf) {
		pdf = pdf || false;
		var _url = _baseurl+"/?_req=tpl&_f=form-print&sid=<?php _E($this->survey_id);?>&rid=<?php _E($this->session->data->id);?>";
		if ( pdf ) {
			_url += "&noprint=1&dopdf=1";
		}
		_popupnewin(_url,"print");
	};

	function _cetakbox() {
		var _pid = "#dialog";
		var msg = "Sila klik pada butang \"Cetak\" untuk mencetak borang yang telah berjaya dihantar";
		var title= "BORANG TELAH BERJAYA DIHANTAR";
		var _height = $(document).height() - 20;
		var $pid = $(_pid);
		$pid.html(msg).dialog({
			width: 400,
			modal: true,
			position: ["center", 150],
			title: title,
			buttons: {
				"Cetak": function() {
					_winreload();
					_docetak();
					$pid.dialog("close");
				}
			},
			open: function() {
				var $this = $(this);
				_dialog_maxheight($this,_height);
				$this.parent().find('.ui-dialog-buttonpane button:contains("Cetak")').button({
					icons: { primary: 'ui-icon-print' }
				});
			},
			close: function() {
				$pid.empty().dialog("destroy");
				setTimeout( _winreload, 700);
			}
		});
	};

	$("button[name=pprint]").button({ icons: {primary:'ui-icon-print' }}).click(function(e) {
		e = e || window.event;
		e.preventDefault();
		_docetak();
	});
	$("button[name=ppdf]").button({ icons: {primary:'ui-icon-document' }}).click(function(e) {
		e = e || window.event;
		e.preventDefault();
		_docetak(true);
	});
	$("button[name=ppost]").button({ icons: {primary:'ui-icon-check' }}).click(function(e) {
		e = e || window.event;
		e.preventDefault();
	<?php if ( $perror ): ?>
		$("div#stupidmsg").center({absolute: true, fixed: true}).zindex().show();
		return false;
	<?php endif; ?>

    <?php if ( _null($this->fdb->pegawai) ||
                _null($this->fdb->jawatan) ||
                _null($this->fdb->email) ||
                _null($this->fdb->phone) ||
                _null($this->fdb->jawatan) ): ?>
    _gnotice("Sila isikan maklumat pegawai dengan lengkap!");
    <?php else: ?>
		_confirm("<?php _t("Adakah anda pasti ingin menghantar borang ini?");?>", function() {
                        $.post(_index, {  _post: 'submit', _what: 'record', survey_id: '<?php _E($this->survey_id);?>', respondent_id: '<?php _E($this->session->data->id);?>', category_id: '<?php _E($this->session->data->category_id);?>' }, function(data) {
                                if ( !_ismsg_json(data) ) {
                                        _gerror(data);
                                        return false;
                                }
                                data = $.evalJSON(data);
                                if ( !data.success ) {
                                        _gid = _gfalse(data.msg);
                                        _ghoverclose(_gid);
                                        return false;
                                }
				_cetakbox();
                        },"text");
                },null,"PENGESAHAN HANTAR BORANG");

    <?php endif; ?>

	});

	<?php if ( $perror ): ?>
	$("div#stupidmsg").center({top: "35%", absolute: true, fixed: true}).zindex().show();
	<?php endif; ?>

	$("button[name=ppost2]").button({ icons: {primary:'ui-icon-check' }}).click(function(e) {
		e = e || window.event;
		e.preventDefault();
        $.post(_index, {  _post: 'submit', _what: 'record', survey_id: '<?php _E($this->survey_id);?>', respondent_id: '<?php _E($this->session->data->id);?>', category_id: '<?php _E($this->session->data->category_id);?>' }, function(data) {
            if ( !_ismsg_json(data) ) {
                _gerror(data);
                return false;
            }
            data = $.evalJSON(data);
            if ( !data.success ) {
                _gid = _gfalse(data.msg);
                _ghoverclose(_gid);
                return false;
            }
            _gtrue("Data dikemaskini");
        },"text");
    });
});
</script>
