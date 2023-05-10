<?php
@_object($this) && !_null($this->request['_formid']) && !_null($this->request['id']) || exit("403 Forbidden");
$this->_notlogin();
$fdb = (object)$this->bangsa->_getinfo($this->request['id']);
?>

<div class="dialog_max_height">
<form id="<?php _E($this->request['_formid']);?>">

<fieldset class="x-fb">

<table id="x-table">

<tr>
<th><?php _t("Bangsa");?></th>
<td><input type="text" name="name" class="text" value="<?php _E($fdb->name);?>"></td>
</tr>

</table>
</fieldset>


</form>
</div> <!-- /dialog_max_height -->
