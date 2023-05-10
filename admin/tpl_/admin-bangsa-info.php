<?php
@_object($this) && !_null($this->request['id']) || exit("403 Forbidden");
$this->_notlogin();
$fdb = (object)$this->bangsa->_getinfo($this->request['id']);
?>
<div class="dialog_max_height">
<fieldset class="x-fb">
<table id="x-table">

<tr>
<th><?php _t("Bangsa");?></th>
<td class="dinfo"><?php _E($fdb->name);?></td>
</tr>

</table>
</fieldset>

</div> <!-- /dialog_max_height -->

