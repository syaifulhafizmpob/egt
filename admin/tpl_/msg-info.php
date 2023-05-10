<?php
@_object($this) && !_null($this->request['id']) || exit("403 Forbidden");
$this->_notlogin();
if ( _null($this->tplname) ) $this->tplname = _tplname(__FILE__);
$fdb = (object)$this->{$this->tplname}->_getinfo($this->request['id'], true);
$rinfo = $this->respondent->_getinfo($fdb->sender_id);
?>

<div class="dialog_max_height">
<fieldset class="x-fb">
<table id="x-table">

<tr>
<th><?php _t("Tarikh/Masa");?></th>
<td class="dinfo"><?php _E($this->_output_datetime($fdb->cdate));?></td>
</tr>

<tr>
<th><?php _t("Nama Pegawai");?></th>
<td class="dinfo"><?php _E($rinfo['pegawai']);?></td>
</tr>

<tr>
<th><?php _t("Nama Syarikat");?></th>
<td class="dinfo"><?php _E($rinfo['company']);?></td>
</tr>

<tr>
<th><?php _t("No. Lesen");?></th>
<td class="dinfo"><?php _E($rinfo['nolesen']);?></td>
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
<th style="vertical-align:top;padding-top:5px;"><?php _t("Status");?></th>
<td class="dinfo" data-status='<?php _E($fdb->status);?>'>
<?php 
echo ( $fdb->status == "1" ? "Telah Diproses" : "Belum Diproses" );
if ( $fdb->status == "1" && !_null($fdb->staff_id) ) {
	echo "<br><b>Oleh:</b> ".$this->user->_getname($fdb->staff_id);
}
?></td>
</tr>

<tr>
<th><?php _t("Fail Lampiran");?></th>
<td class="dinfo"><?php echo ( !_null($fdb->file) ? "<a href='".$this->baseurl."/?_req=download&_f=".$fdb->id."' target='new'>".basename($fdb->file)."</a>" : "tiada" );?></td>
</tr>

</table>
</fieldset>
</div> <!-- /dialog_max_height -->
