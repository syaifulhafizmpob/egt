<?php
@_object($this) && !_null($this->request['_formid']) && !_null($this->request['id']) || exit("403 Forbidden");
$this->_notlogin();
$fdb = (object)$this->user->_getinfo($this->request['id']);
?>
<div class="dialog_max_height">
<form id="<?php _E($this->request['_formid']);?>">

<fieldset class="x-fb">
<legend class="x-title"><?php _t("Informasi");?></legend>
<table id="x-table">

<tr>
<th><?php _t("Nama Penuh");?></th>
<td><input type="text" name="name" class="text" value="<?php _E($fdb->name);?>"></td>
</tr>

<tr>
<th style="vertical-align: top; padding-top: 10px;"><?php _t("Penerangan");?></th>
<td>
<textarea name="desc"><?php _E($fdb->desc);?></textarea>
</td>
</tr>

</table>
</fieldset>

<fieldset class="x-fb">
<legend class="x-title"><?php _t("Kata Laluan");?></legend>
<table id="x-table">

<tr>
<th><?php _t("Id Pengguna ");?></th>
<td><?php _E($this->session->data->login);?></td>
</tr>

<tr>
<th><?php _t("Kata Laluan lama");?></th>
<td><input type="password" name="opass" class="text" value=""></td>
</tr>

<tr>
<th><?php _t("Kata Laluan baharu");?></th>
<td><input type="password" name="npass" class="text" value=""></td>
</tr>

<tr>
<th><?php _t("Taip semula Kata Laluan");?></th>
<td><input type="password" name="rpass" class="text" value=""></td>
</tr>

</table>
</fieldset>

</form>
</div> <!-- /dialog_max_height -->


