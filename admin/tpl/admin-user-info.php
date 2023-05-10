<?php
@_object($this) && !_null($this->request['id']) || exit("403 Forbidden");
$this->_notlogin();
$fdb = (object)$this->user->_getinfo($this->request['id']);
?>
<div class="dialog_max_height">
<fieldset class="x-fb">
<legend class="x-title"><?php _t("Login");?></legend>
<table id="x-table">

<tr>
<th><?php _t("Id Pengguna");?></th>
<td class="dinfo"><?php _E($fdb->login);?></td>
</tr>

<tr>
<th><?php _t("Peranan");?></th>
<td class="dinfo">
<?php _E($fdb->level); ?>
</td>
</tr>

<tr>
<th><?php _t("Status");?></th>
<td class="dinfo">
<?php
$status = array("on" => _tr("Aktif"), "off" => _tr("Nyah-Aktif") );
_E($status[$fdb->status]);
?>
</td>
</tr>



</table>
</fieldset>

<fieldset class="x-fb">
<legend class="x-title"><?php _t("Informasi");?></legend>
<table id="x-table">

<tr>
<th><?php _t("Nama Penuh");?></th>
<td class="dinfo"><?php _E($fdb->name);?></td>
</tr>

<tr>
<th><?php _t("Emel");?></th>
<td class="dinfo"><?php _E($fdb->email);?></td>
</tr>

<tr>
<th><?php _t("Penerangan");?></th>
<td class="dinfo">
<?php echo ( !_null($fdb->desc) ? $fdb->desc : _tr("-not set-") );?>
</td>
</tr>

</table>
</fieldset>
</div> <!-- /dialog_max_height -->

