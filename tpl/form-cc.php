<?php
@_object($this) || exit("403 Forbidden");
$this->_notlogin();
if ( _null($this->request['_sid']) && _null($this->request['_jid']) && _null($this->request['_gid']) ) exit("Invalid request!");
$this->fdb = (object)$this->user->_getinfo($this->session->data->id);
$svdb = (object)$this->survey->_getinfo($this->request['_sid']);
$rcdb = (object)$this->record->_getinfo($this->session->data->id,$this->request['_sid'],$this->request['_gid'],$this->request['_jid']);
$fname="formcc".$this->request['_sid'].$this->request['_gid'].$this->request['_jid'];
$syear = $svdb->year;
$smonth = $this->survey->_getmonth($this->request['_sid']);
$etnik = $this->record->_getetnik($rcdb->id);
$alien = $this->record->_getalien($rcdb->id);
$gid = $this->request['_gid'];
$perror = false;
$perror2 = false;

// 47 = Pekerja di Tapak Semaian
$tapak_semaian = ( $this->fdb->category_id == '' 
                    || $this->fdb->category_id == '' 
                    || $this->fdb->category_id == '04' ? true : false );

// 14 = Tapak Semaian/Peniaga Biji, Anak Benih
$tapak_semaian_wajib = ( $this->fdb->subcategory_id == 14 ? true : false );


function _dval($val = 0 ) {
	return ( _num($val) ? $val : 0 );
}

if ( $gid != "1" 
	&& (  _dval($rcdb->melayu) != 0
		|| _dval($rcdb->cina) != 0
		|| _dval($rcdb->india) != 0 )
		&& _dval($rcdb->gajia) == 0 ) $perror = true;

if ( $gid != "1" 
	&& (  _dval($rcdb->indonesia) != 0
		|| _dval($rcdb->bangladesh) != 0
		|| _dval($rcdb->nepal) != 0 )
		&& _dval($rcdb->gajib) == 0 ) $perror2 = true;

?>
<style type="text/css">
table.xcc {}
table.xcc th,
table.xcc td {
	white-space: nowrap;
}
table.xcc th {
	cursor: default !important;
}
li.lai {
	
}
li.lai:hover {
	cursor: default !important;
}
li.lai a,a:link,a:visited,a:focus {
	text-decoration: none;
}
li.lai a:hover {
	text-decoration: underline;
}
.etnik, .alien, .pika, .pikb {}
</style>
<form id="<?php _E($fname);?>" onsubmit="return false;">
<button name="freset" class="button button_red" style="font-size:11px;margin-left:2px;">Reset</button>
<button name="fsave" class="button button_red" style="font-size:11px;margin-left:0px;">Simpan</button>

<table id='x-table1' class="xcc" style='text-align: left; width: 100%; padding:0px; margin:2px;'>
<tr>
<th colspan='8' rowspan='1' style='vertical-align: top;' class='border-left border-bottom border-right center'>Warganegara Tempatan</th>
</tr>
<tr>
<td colspan='5' rowspan='1' class='border-left border-bottom center'>Bilangan Pekerja</td>
<td colspan='3' rowspan='1' style='vertical-align: top;' class='border-left border-bottom border-right center'>Pendapatan</td>
</tr>
<tr>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left border-bottom'>Melayu</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left border-bottom'>Cina</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left border-bottom'>India</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left border-bottom'>Lain-lain</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left border-bottom'>Jumlah</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left border-bottom'>Purata Gaji (RM)</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left border-bottom'>Purata Elaun (RM)</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left border-bottom border-right'>Jumlah</td>
</tr>
<tr>
<td style='vertical-align: top; padding-top:5px;' class='border-left border-bottom'>
<input type="text" class="text pika" name="melayu" value="<?php _E($rcdb->melayu);?>" style="width:50px;">
</td>
<td style='vertical-align: top; padding-top:5px;' class='border-left border-bottom'>
<input type="text" class="text pika" name="cina" value="<?php _E($rcdb->cina);?>" style="width:50px;">
</td>
<td style='vertical-align: top; padding-top:5px;' class='border-left border-bottom'>
<input type="text" class="text pika" name="india" value="<?php _E($rcdb->india);?>" style="width:50px;">
</td>
<td style='vertical-align: top; padding-top:5px;' class='border-left border-bottom'>

<?php
$et = $this->display->_listetnik();
$ett = $et;
?>

<select class="text" id="lainsela" style="width:90px !important;">
<option value='_null'>Pilih</option>
<?php
if ( _array($ett) ) {
	while( $ep = @array_shift($ett) ) {
		echo "<option value='".$ep['id']."'>".$ep['name']."</option>";
	}
}?>
</select>
<ul style='list-style-type:none;margin:0px;padding:0px;'>
<?php
if ( _array($et) ) {
	while( $ep = @array_shift($et) ) {
		echo "<li style='text-align:right;' data-ida='".$ep['id']."' class='lai hide'>".$ep['name']." <input type='text' class='text pika etnik' name='etnik[".$ep['name']."]' value='".( !_null($etnik[$ep['name']]) ? $etnik[$ep['name']] : "0")."' style='width:50px;'><a href='#la' data-tooltip='Buang'>[x]</a> </li>";
		if ( _dval($etnik[$ep['name']]) != 0 && _dval($rcdb->gajia) == 0 ) $perror = true;
	}
}
?>
</ul>

</td>

<td style='vertical-align: top; padding-top:5px;' class='border-left border-bottom'>
<input type="text" class="text" name="juma1" value="" style="width:50px;">
</td>

<td style='vertical-align: top; padding-top:5px;' class='border-left border-bottom' data-tooltip="Purata Gaji bagi semua pekerja pada bulan <?php echo ucfirst($smonth)." ".$syear;?>">
<img src="<?php _E($this->baseurl);?>/rsc/info.jpg"><input type="text" class="text" name="gajia" value="<?php _E($rcdb->gajia);?>" style="width:50px;<?php echo ( $perror ? "border:2px solid red;" : "");?>">
</td>

<td style='vertical-align: top; padding-top:5px;' class='border-left border-bottom' data-tooltip="Purata Elaun bagi semua pekerja pada bulan <?php echo ucfirst($smonth)." ".$syear;?>">
<img src="<?php _E($this->baseurl);?>/rsc/info.jpg"><input type="text" class="text" name="elauna" value="<?php _E($rcdb->elauna);?>" style="width:50px;">
</td>

<td style='vertical-align: top; padding-top:5px;' class='border-left border-bottom border-right'>
<input type="text" class="text" name="juma" value="<?php echo (int)($rcdb->gajia + $rcdb->elauna);?>" style="width:50px;">
</td>


</tr>
</table>
<br>
<table id='x-table1' class="xcc" style='text-align: left; width: 100%; padding:0px; margin:2px;'>
<tr>
<th colspan='8' rowspan='1' style='vertical-align: top;' class='border-left border-bottom border-right center'>Warganegara Asing</th>
</tr>
<tr>
<td colspan='5' rowspan='1' class='border-left border-bottom center'>Bilangan Pekerja</td>
<td colspan='3' rowspan='1' style='vertical-align: top;' class='border-left border-bottom border-right center'>Pendapatan</td>
</tr>
<tr>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left border-bottom'>Indonesia</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left border-bottom'>Bangladesh</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left border-bottom'>Nepal</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left border-bottom'>Lain-lain</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left border-bottom'>Jumlah</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left border-bottom'>Purata Gaji (RM)</td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left border-bottom'>Purata Elaun (RM)</td>

<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left border-bottom border-right'>Jumlah</td>
</tr>
<tr>
<td style='vertical-align: top; padding-top:5px;' class='border-left border-bottom'>
<input type="text" class="text pikb" name="indonesia" value="<?php _E($rcdb->indonesia);?>" style="width:50px;">
</td>
<td style='vertical-align: top; padding-top:5px;' class='border-left border-bottom'>
<input type="text" class="text pikb" name="bangladesh" value="<?php _E($rcdb->bangladesh);?>" style="width:50px;">
</td>
<td style='vertical-align: top; padding-top:5px;' class='border-left border-bottom'>
<input type="text" class="text pikb" name="nepal" value="<?php _E($rcdb->nepal);?>" style="width:50px;">
</td>
<td style='vertical-align: top; padding-top:5px;' class='border-left border-bottom'>
<?php
$et = $this->display->_listalien();
$ett = $et;
?>
<select class="text" id="lainselb" style="width:90px !important;">
<option value='_null'>Pilih</option>
<?php
if ( _array($ett) ) {
	while( $ep = @array_shift($ett) ) {
		echo "<option value='".$ep['id']."'>".$ep['name']."</option>";
	}
}?>
</select>
<ul style='list-style-type:none;margin:0px;padding:0px;'>
<?php
if ( _array($et) ) {
	while( $ep = @array_shift($et) ) {
		echo "<li style='text-align:right;' data-idb='".$ep['id']."' class='lai hide'>".$ep['name']." <input type='text' class='text pikb alien' name='alien[".$ep['name']."]' value='".( !_null($alien[$ep['name']]) ? $alien[$ep['name']] : "0")."' style='width:50px;'><a href='#lb' data-tooltip='Buang'>[x]</a> </li>";
		if ( _dval($alien[$ep['name']]) != 0 && _dval($rcdb->gajib) == 0 ) $perror = true;
	}
}
?>
<li>
</li>
</ul>

</td>

<td style='vertical-align: top; padding-top:5px;' class='border-left border-bottom'>
<input type="text" class="text" name="jumb1" value="" style="width:50px;">
</td>

<td style='vertical-align: top; padding-top:5px;' class='border-left border-bottom' data-tooltip="Purata Gaji bagi semua pekerja pada bulan <?php echo ucfirst($smonth)." ".$syear;?>">
<img src="<?php _E($this->baseurl);?>/rsc/info.jpg"><input type="text" class="text" name="gajib" value="<?php _E($rcdb->gajib);?>" style="width:50px;<?php echo ( $perror2 ? "border:2px solid red;" : "");?>">
</td>

<td style='vertical-align: top; padding-top:5px;' class='border-left border-bottom' data-tooltip="Purata Elaun bagi semua pekerja pada bulan <?php echo ucfirst($smonth)." ".$syear;?>">
<img src="<?php _E($this->baseurl);?>/rsc/info.jpg"><input type="text" class="text" name="elaunb" value="<?php _E($rcdb->elaunb);?>" style="width:50px;">
</td>

<td style='vertical-align: top; padding-top:5px;' class='border-left border-bottom border-right'>
<input type="text" class="text" name="jumb" value="<?php echo (int)($rcdb->gajib + $rcdb->elaunb);?>" style="width:50px;">
</td>

</tr>
</table>

<br>
<table id='x-table1' style='text-align: left; width: 100%; padding:0px; margin:2px;'>
<tr>
<th colspan='4' rowspan='1' style='vertical-align: top;'>Bilangan Pekerja</th>
</tr>
<tr>
<td colspan='1' rowspan='1' style='vertical-align: top;width:100px;' class='border-left border-bottom'>Kekurangan</td>
<td colspan='3' rowspan='1' style='vertical-align: top;' class='border-left border-bottom border-right'>Keperluan Tambahan</td>
</tr>
<tr>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left border-bottom'><?php _E($syear);?></td>
<td colspan='1' rowspan='1' style='vertical-align: top; width:100px;' class='border-left border-bottom'><?php echo ( (int)$syear + 1);?></td>
<td colspan='1' rowspan='1' style='vertical-align: top;' class='border-left border-bottom border-right'><?php echo ( (int)$syear + 2);?></td>
</tr>
<tr>
<td style='vertical-align: top; padding-top:5px;' class='border-left border-bottom'>
<input type="text" class="text" name="bp1" value="<?php _E($rcdb->bp1);?>" style="width:50px;">
</td>

<td style='vertical-align: top; padding-top:5px;' class='border-left border-bottom'>
<input type="text" class="text" name="bp2" value="<?php _E($rcdb->bp2);?>" style="width:50px;">
</td>

<td data-semaianwajib='<?php echo ( $tapak_semaian_wajib ? 1 : 0 );?>' style='vertical-align: top; padding-top:5px;' class='border-left border-bottom border-right'>
<input type="text" class="text" name="bp3" value="<?php _E($rcdb->bp3);?>" style="width:50px;">
</td>
</tr>
</table>


<input type="hidden" name="survey_id" value="<?php _E($this->request['_sid']);?>">
<input type="hidden" name="group_id" value="<?php _E($this->request['_gid']);?>">
<input type="hidden" name="jawatan_id" value="<?php _E($this->request['_jid']);?>">
<?php if ( $tapak_semaian ): ?>
<input type="hidden" id="isemai" name="keluasan_tapak_semaian" value="<?php _E($rcdb->keluasan_tapak_semaian);?>">
<?php endif; ?>
</form>
<script type="text/javascript">

formname = "<?php _E($fname);?>";

$(document).ready(function() {
	$("div.ui-tabs").css("width","100%");
	$("button[name=freset]").button({ icons: {primary:'ui-icon-close' }});
	$("button[name=fsave]").button({ icons: {primary:'ui-icon-check' }});
	$("#"+formname+" input[name=juma]").live("click blur", function() {
		var gajia = parseInt($("input[name=gajia]").attr("value"));
		var elauna = parseInt($("input[name=elauna]").attr("value"));
		var juma = parseInt( gajia + elauna );
		if ( isNaN(juma) || juma < 0 ) {
			juma = 0;
		}
		$(this).attr("value", juma);
	});
	/*$("input[name=juma]").trigger("blur");*/

	$("#"+formname+" input[name=gajia],#"+formname+" input[name=elauna]").bind("keyup", function() {
		var gajia = parseInt($("#"+formname+" input[name=gajia]").attr("value"));
		var elauna = parseInt($("#"+formname+" input[name=elauna]").attr("value"));
		var juma = parseInt( gajia + elauna );
		if ( isNaN(juma) || juma < 0 ) {
			juma = 0;
		}
		$("#"+formname+" input[name=juma]").attr("value", juma);
	});

	$("#"+formname+" input[name=jumb]").live("click blur", function() {
		var gajib = parseInt($("#"+formname+" input[name=gajib]").attr("value"));
		var elaunb = parseInt($("#"+formname+" input[name=elaunb]").attr("value"));
		var jumb = parseInt( gajib + elaunb );
		if ( isNaN(jumb) || jumb < 0 ) {
			jumb = 0;
		}
		$(this).attr("value", jumb);
	});
	/*$("input[name=jumb]").trigger("blur");*/

	$("#"+formname+" input[name=gajib],#"+formname+" input[name=elaunb]").bind("keyup", function() {
		var gajib = parseInt($("#"+formname+" input[name=gajib]").attr("value"));
		var elaunb = parseInt($("#"+formname+" input[name=elaunb]").attr("value"));
		var jumb = parseInt( gajib + elaunb );
		if ( isNaN(jumb) || jumb < 0 ) {
			jumb = 0;
		}
		$("#"+formname+" input[name=jumb]").attr("value", jumb);
	});

	$(".xcc input[type=text]").keypress(function(e) {
        /*var c = $(this).attr("value");*/
        return isNumberKey(e);
	});

	$("select#lainsela").change(function(e) {
		e = e || window.event;
		e.preventDefault();
		var _id = $(this).attr("value");
		$("li[data-ida="+_id+"]").show();
		$("select#lainsela option[value=_null]").attr("selected","selected");
	});

	$("a[href=#la]").click(function(e) {
		e = e || window.event;
		e.preventDefault();
		$(this).parent().hide();
		$(this).prev().attr("value","0");
		$("input[name=juma1],input[name=jumb1]").trigger("blur");
	});

	$("input.etnik").each(function() {
		var val = $.trim($(this).attr("value"));
		if ( val != "" && val != 0 ) {
			$(this).parent().show();
		}
	});
	$("input.alien").each(function() {
		var val = $.trim($(this).attr("value"));
		if ( val != "" && val != 0 ) {
			$(this).parent().show();
		}
	});
	$("select#lainselb").change(function(e) {
		e = e || window.event;
		e.preventDefault();
		var _id = $(this).attr("value");
		$("li[data-idb="+_id+"]").show();
		$("select#lainselb option[value=_null]").attr("selected","selected");
	});

	$("a[href=#lb]").click(function(e) {
		e = e || window.event;
		e.preventDefault();
		$(this).parent().hide();
		$(this).prev().attr("value","0");
		$("input[name=juma1],input[name=jumb1]").trigger("blur");
	});

	$("#"+formname+" input[name=juma1]").live("click blur", function() {
		var juma1 = 0;
		$(this).attr("value", juma1);
		$("#"+formname+" input.pika, #"+formname+" input.etnik").each(function() {
			var _n = parseInt($(this).attr("value"));
			if ( !isNaN(_n) && _n > 0 ) {
				juma1 += _n;
			}
		});
		$(this).attr("value", juma1);
	});
	$("#"+formname+" input[name=juma1]").trigger("blur");

	$("#"+formname+" input[name=jumb1]").live("click blur", function() {
		var jumb1 = 0;$(this).attr("value", jumb1);
		$("#"+formname+" input.pikb, #"+formname+" input.alien").each(function() {
			var _n = parseInt($(this).attr("value"));
			if ( !isNaN(_n) && _n > 0 ) {
				jumb1 += _n;
			}
		});
		$(this).attr("value", jumb1);
	});
	$("#"+formname+" input[name=jumb1]").trigger("blur");

	$("#"+formname+" input.pika, #"+formname+" input.etnik").each(function() {
		$(this).bind("keyup", function() {
			$("#"+formname+" input[name=juma1]").trigger("blur");
		});
	});

	$("#"+formname+" input.pikb, #"+formname+" input.alien").each(function() {
		$(this).bind("keyup", function() {
			$("#"+formname+" input[name=jumb1]").trigger("blur");
		});
	});

});
</script>
