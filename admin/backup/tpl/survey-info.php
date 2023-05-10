<?php
@_object($this) && !_null($this->request['id']) || exit("403 Forbidden");
$this->_notlogin();
if ( _null($this->tplname) ) $this->tplname = _tplname(__FILE__);
$fdb = (object)$this->{$this->tplname}->_getinfo($this->request['id'], true);
?>

<div class="dialog_max_height">
<fieldset class="x-fb">
<table id="x-table">

<tr>
<th><?php _t("Tahun");?></th>
<td class="dinfo"><?php _E($fdb->year);?></td>
</tr>

<tr>
<th><?php _t("Bulan");?></th>
<td class="dinfo"><?php _E(strtoupper($fdb->month));?></td>
</tr>

<tr>
<th><?php _t("Tarikh Mula");?></th>
<td class="dinfo"><?php _E($this->_output_datepicker($fdb->sdate));?></td>
</tr>

<tr>
<th><?php _t("Tarikh Tamat");?></th>
<td class="dinfo"><?php _E($this->_output_datepicker($fdb->edate));?></td>
</tr>
<?php /*
<tr>
<th><?php _t("Penerangan");?></th>
<td class="dinfo">
<?php echo ( !_null($fdb->desc) ? $fdb->desc : _tr("-not set-") );?>
</td>
</tr>
*/ ?>

<tr>
<th><?php _t("Aktif");?></th>
<td class="dinfo">
<?php
$status = array("on" => _tr("On"), "off" => _tr("Off") );
_E($status[$fdb->status]);
?>
</td>
</tr>

</table>
</fieldset>
</div> <!-- /dialog_max_height -->
