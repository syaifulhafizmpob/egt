<?php
@_object($this) || exit("403 Forbidden");
$this->_notlogin();
if ( _null($this->request['sid']) || _null($this->request['rid']) ) exit("Invalid parameter!");

$this->survey_id = $this->request['sid'];
$this->fdb = (object)$this->respondent->_getinfo($this->request['rid']);

$this->fdb = (object)$this->user->_getinfo($this->request['rid']);
?>

<style type="text/css">
#x-table1 th {
	border: 1px solid #bbbbbb;
	padding-top: 5px;
	padding-bottom: 5px;
}
#x-table1 th:hover {
	cursor: pointer;
}

#x-table1 td {
	vertical-align: top;
	padding-top: 5px;
}
#x-table1 td.ll {
	cursor: pointer;
}

#x-table1 td.ll:hover {
	background: yellow;
	font-weight: bold;
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

#x-table1 input.text,
#x-table1 select.text {
	background: #BDBDBD;
}
</style>

<div class="ui-widget center hide" style="margin: 9px;white-space:nowrap;" id="stupidloader">
<div class="ui-state-error ui-corner-all" style="padding: 0.7em;"> 
<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;margin-top:.3em;"></span> 
<span style='font-weight: solid !important; color: #000000 !important; font-size: 18px !important;'>
Berjaya disimpan
</span></p>
</div>
</div>

<?php
$fl = array("A","B","C","D","E","F","G","H","I","J","K","L","M");
$html .= "<h1 style='color:red;font-weight:bold;'>Sila klik pada kategori pekerja dibawah ini untuk mengisi maklumat</h1>";
$html .= "<center><table id='x-table1' style='margin: 0px;padding:0px;width:100%;'>";
$list1 = $this->survey->_list_jawatan($this->fdb->category_id);
if ( _array($list1) ) {
	$h1_a = array();
	$fln = 0;
	$cid = $this->fdb->category_id;
	$ss = null;
	while( $rl = @array_shift($list1) ) {
		$gid = $rl['group_id'];
		$jid = $rl['jawatan_id'];
		$h1 = $this->survey->_getgroup_name($rl['group_id']);

		$jhc = $this->survey->_count_jawatan($gid,$cid);
		if ( _null($h1_a[$h1]) ) {
			$h1_a[$h1] = $gid;
			$ckey = _rand_text(5);
			$html .= "<tr>";
			$html .= "<th colspan='2' data-g='".$gid."'>";
			$html .= $fl[$fln].". ".$h1;
			$html .= "</th>";
			$html .= "</tr>";
			$html .= "<tr data-h='".$gid."' class='hide'>";
			$html .= "<td class='ll border-left border-right border-bottom' data-gid='".$gid."' data-id='".$jid."' data-ckey='".$ckey."'>";
			$html .= $this->survey->_getjawatan_name($jid);
			$html .= "</td>";
			$html .= "<td id='tcc".$ckey."' class='border-bottom border-right' colspan='1' rowspan='".$jhc."' style='width:85%;'>";
			$html .= "</td>";
			$html .= "</tr>";
			$fln++;
		} else {
			$html .= "<tr data-h='".$gid."' class='hide'>";
			$html .= "<td class='ll border-left border-right border-bottom' data-gid='".$gid."' data-id='".$jid."' data-ckey='".$ckey."'>";
			$html .= $this->survey->_getjawatan_name($rl['jawatan_id']);
			$html .= "</td>";
			$html .= "</tr>";
		}
	}
}
$html .= "</table></center>";
_E($html);
?>
<form id="psub" onsubmit="return false;">
<table id="x-tabler" style='margin: 10px 0px 0px 0px;padding:0px;width:100%;'>
<tr>
<th class="border-top border-bottom border-left border-right" colspan="2" style="font-size:12px;">
Saya mengaku bahawa keterangan diberi adalah benar sepanjang pengetahuan dan kepercayaan saya.
</th>
</tr>
<tr>
<th class="border-bottom border-left" style="width:200px;font-size:10px;">NAMA PEGAWAI YANG MELAPOR</th>
<td class="border-bottom border-left border-right"><input type="text" class="ptext" name="pegawai" value="<?php _E($this->fdb->pegawai);?>"></td>
</tr>
<tr>
<th class="border-bottom border-left" style="width:200px;font-size:10px;">JAWATAN RASMI</th>
<td class="border-bottom border-left border-right"><input type="text" class="ptext" name="jawatan" value="<?php _E($this->fdb->jawatan);?>"></td>
</tr>
<tr>
<th class="border-bottom border-left" style="width:200px;font-size:10px;">EMEL</th>
<td class="border-bottom border-left border-right"><input type="text" class="ptext" name="email" value="<?php _E($this->fdb->email);?>"></td>
</tr>
<tr>
<th class="border-bottom border-left" style="width:200px;font-size:10px;">TELEPHONE</th>
<td class="border-bottom border-left border-right"><input type="text" class="ptext" name="phone" value="<?php _E($this->fdb->phone);?>"></td>
</tr>
<tr>
<th class="border-bottom border-left" style="width:200px;font-size:10px;">FAX</th>
<td class="border-bottom border-left border-right"><input type="text" class="ptext" name="fax" value="<?php _E($this->fdb->fax);?>"></td>
</tr>
<tr id="pbut" class="hide">
<td colspan="2" class="border-bottom-none border-left-none border-right-none" style="padding-left:0px;">
<button name="psave" class="button button_red" style="font-size:11px;">Kemaskini</button>
<button name="preset" class="button button_red" style="font-size:11px;" onclick="this.form.reset();">Reset</button>
</td>
</tr>
</table>
</form>

<script type="text/javascript">
$(document).ready(function() {
		function _checkpik() {
			var _pika = false, _pikb = false;
			var _gajia = $.trim($("form#"+formname+" input[name=gajia]").attr("value"));
			var _gajib = $.trim($("form#"+formname+" input[name=gajib]").attr("value"));
			if ( _gajia > 0 ) {
				var _oka = true;
				$("form#"+formname+" input.pika").each(function() {
					var _val = $.trim($(this).attr("value"));
					if (  _val != 0 && _val != "" ) {
						_oka = false;
					}
				});
				_pika = _oka;
			}
			if ( _gajib > 0 ) {
				var _okb = true;
				$("form#"+formname+" input.pikb").each(function() {
					var _val = $.trim($(this).attr("value"));
					if (  _val != 0 && _val != "" ) {
						_okb = false;
					}
				});
				_pikb = _okb;
			}
			if ( _pika ) {
				alert("Sila isikan bilangan pekerja Warganegara Tempatan");
				return false;
			}
			if ( _pikb ) {
				alert("Sila isikan bilangan pekerja Warganegara Asing");
				return false;
			}
			return true;
		};
	function _saveform() {
		_ajaxloader(false);
		$("form#"+formname).ajaxSubmit({
			url: _index,
			type: 'POST',
			method: 'POST',
			dataType: 'text',
			async: false,
			clearForm: false,
			resetForm: false,
			cache: true,
			data: { _post: 'update', _what: 'record' },
			beforeSend: function() {
				if ( !_checkpik() ) {
					return false;
				}
				_stupidloader(true);
				_ajaxloader(false);
			},
			success: function() {
				_stupidloader(false);
				_ajaxloader(false);
			},
			complete: function() {
				_stupidloader(false);
				_ajaxloader(false);
			},
			success: function(data) {
				if ( !_ismsg_json(data) ) {
					_gerror(data);
					return false;
				}
				data = $.evalJSON(data);
				if ( !data.success ) {
					_gid = _gfalse(data.msg);
					_ghoverclose(_gid);
					_clear();
					return false;
				}
			}
		});
		return false;
	};

	$("button[name=freset]").live("click",function(e) {
		e = e || window.event;
		e.preventDefault();
		$("form#"+formname).find('input:text').not(':button, :submit, :reset, :hidden').val('0').removeAttr('checked').removeAttr('selected');
		return _saveform();
	});
	$("button[name=fsave]").live("click",function(e) {
		e = e || window.event;
		e.preventDefault();
		return _saveform();
	});

	var _stupidloader_timer = null;
	function _stupidloader(show) {
		show = show || false;
		if ( _stupidloader_timer !== null ) {
			window.clearTimeout(_stupidloader_timer);
			_stupidloader_timer = null;
		}
		$("#stupidloader").center({top: "10%", absolute: true}).zindex();
		if ( show ) {
			$("#stupidloader").show();
		} else {
			_stupidloader_timer = window.setTimeout(function() {
				$("#stupidloader").css("z-index","1").hide();
			}, 900);
		}
	};

	function _fillform() {
		$("form#"+formname).find('input:text').each(function() {
			var _val = $.trim($(this).attr("value"));
			if ( _val === "" ) {
				$(this).val("0");
			}
			$(this).click(function() {
				$(this).select();
			});

			$(this).keydown(function(e) {
				e = e || window.event;
				var knum = document.all ? e.keyCode : e.which;
				var $this = $(this), $focusables = $(':focusable'), current = $focusables.index(this), $next;
				if (knum == '13') {
					$next = $focusables.eq(current+1).length ?$focusables.eq(current+1) : $focusables.eq(0);
					$next.focus();
				}
			});
		});
	};

	function _loadform(obj,arg) {
		_saveform();
		$.ajaxSetup({
				global: false,
				async: true,
				cache: true,
				beforeSend: function() {
					_ajaxloader(false);
				},
				success: function() {
					_ajaxloader(false);
				},
				complete: function() {
					_ajaxloader(false);
				}
			});
		$(obj).load(_index, arg, function() {
			_fillform();
			_fixjs();
		});
	};
	var _firstload = true;
	$("td.ll").click(function(e) {
		e = e || window.event;
		e.preventDefault();
		if ( !_firstload  && !_checkpik() ) {
			return false;
		}
		$("td.ll").removeClass("ll_active");
		$(this).addClass("ll_active");
		var sid = "<?php _E($this->survey_id );?>";
		var jid = $(this).attr("data-id");
		var gid = $(this).attr("data-gid");
		var ckey = $(this).attr("data-ckey");
		if ( _firstload ) {
			_firstload = false;
			_page("td#tcc"+ckey, { _req: 'tpl', _f: "form-cc", _jid: jid, _gid: gid, _sid: sid, _ckey: ckey }, _fillform );
		} else {
			_loadform("td#tcc"+ckey, { _req: 'tpl', _f: "form-cc", _jid: jid, _gid: gid, _sid: sid, _ckey: ckey });
		}
	});
	$("th[data-g]").click(function(e) {
		e = e || window.event;
		e.preventDefault();
		var _id = $(this).attr("data-g");
		$("tr[data-h]").hide();
		$("tr[data-h="+_id+"]").show();
		$("tr[data-h="+_id+"] > td.ll").first().trigger("click");
	});

	/* update respondent info */
	function _actrespondent() {
		$("tr#pbut").hide();
		$("form#psub").find('input:text').each(function() {
			$(this).keydown(function(e) {
				e = e || window.event;
				$("tr#pbut").show();
			});
		});
	};
	_actrespondent();
	$("button[name=psave]").button({ icons: {primary:'ui-icon-check' }}).click(function(e) {
		e = e || window.event;
		e.preventDefault();
		_ajaxloader(false);
		$("form#psub").ajaxSubmit({
			url: _index,
			type: 'POST',
			method: 'POST',
			dataType: 'text',
			async: false,
			clearForm: false,
			resetForm: false,
			cache: true,
			data: { _post: 'update_info', _what: 'user', id: '<?php _E($this->request['rid']);?>' },
			beforeSend: function() {
				_stupidloader(false);
				_ajaxloader(false);
			},
			success: function() {
				_stupidloader(false);
				_ajaxloader(false);
			},
			complete: function() {
				_stupidloader(false);
				_ajaxloader(false);
			},
			success: function(data) {
				if ( !_ismsg_json(data) ) {
					_gerror(data);
					return false;
				}
				data = $.evalJSON(data);
				if ( !data.success ) {
					_gid = _gfalse(data.msg);
					_ghoverclose(_gid);
					_clear();
					return false;
				}
				_stupidloader(true);
				$("tr#pbut").hide();
			}
		});
		return false;
	});

	$("button[name=preset]").button({ icons: {primary:'ui-icon-cancel' }}).click(function(e) {
		$("tr#pbut").hide();
	});
});
</script>
