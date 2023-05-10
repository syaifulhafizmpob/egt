<?php
@_object($this) && !_null($this->request['_formid']) || exit("403 Forbidden");
$this->_notlogin();
?>
<div class="dialog_max_height">
<form id="<?php _E($this->request['_formid']);?>">

<fieldset class="x-fb">

<table id="x-table">

<tr>
<th><?php _t("Nama jawatan");?></th>
<td><input type="text" name="name" class="text" value=""></td>
</tr>

</table>
</fieldset>


</form>
</div> <!-- /dialog_max_height -->
