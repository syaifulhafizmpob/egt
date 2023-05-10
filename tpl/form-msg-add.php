<?php
@_object($this) && !_null($this->request['_formid']) || exit("403 Forbidden");
$this->_notlogin();
?>

<div class="dialog_max_height">
<form id="<?php _E($this->request['_formid']);?>">

<fieldset class="x-fb">
<table id="x-table">

<tr>
<th><?php _t("Subjek");?></th>
<td>
<input type="text" class="text" name="subject" value="">
</td>
</tr>

<tr>
<th><?php _t("Mesej");?></th>
<td>
<textarea name="msg" class="text"></textarea>
</td>
</tr>

<tr>
<th><?php _t("Lampiran");?></th>
<td>
<input type="file" class="text" name="file">
</td>
</tr>

</table>
</fieldset>
<input type="hidden" name="sender_id" value="<?php _E($this->session->data->id);?>">
<input type="hidden" name="category_id" value="<?php _E($this->session->data->category_id);?>">
</form>
</div> <!-- /dialog_max_height -->


