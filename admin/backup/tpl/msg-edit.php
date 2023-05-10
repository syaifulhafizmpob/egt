<?php
@_object($this) && !_null($this->request['_formid']) && !_null($this->request['id']) || exit("403 Forbidden");
$this->_notlogin();
if ( _null($this->tplname) ) $this->tplname = _tplname(__FILE__);
$fdb = (object)$this->{$this->tplname}->_getinfo($this->request['id'], true);
?>

<div class="dialog_max_height">
<form id="<?php _E($this->request['_formid']);?>">
<fieldset class="x-fb">
<table id="x-table">

<tr>
<th><?php _t("Tarikh/Masa");?></th>
<td class="dinfo"><?php _E($this->_output_datetime($fdb->cdate));?></td>
</tr>

<tr>
<th><?php _t("Nama Pegawai");?></th>
<td class="dinfo"><?php _E($fdb->name);?></td>
</tr>

<tr>
<th><?php _t("Nama Syarikat");?></th>
<td class="dinfo"><?php _E($fdb->company);?></td>
</tr>

<tr>
<th><?php _t("No. Lesen");?></th>
<td class="dinfo"><?php _E($fdb->lesen);?></td>
</tr>

<tr>
<th><?php _t("Subjek");?></th>
<td class="dinfo"><?php _E($fdb->subject);?></td>
</tr>


<tr>
<th><?php _t("Mesej");?></th>
<td class="dinfo">
<?php _E($fdb->msg);?>
</td>
</tr>

<tr>
<th><?php _t("Status");?></th>
<td>
<select name="status">
<?php
foreach( array("2" => _tr("Belum Diproses"), "1" => _tr("Telah Diproses") ) as $x => $y ) {
	echo "<option value='".$x."'".( $fdb->status == $x ? " selected" : "").">".$y."</option>";
}
?>
</select>
</td>
</tr>


</table>
</fieldset>
<input type="hidden" name="rid" value="<?php _E($fdb->sender_id);?>">
</form>
</div> <!-- /dialog_max_height -->
