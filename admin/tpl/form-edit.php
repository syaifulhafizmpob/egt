<?php
@_object($this) && !_null($this->request['_formid']) && !_null($this->request['id']) || exit("403 Forbidden");
$this->_notlogin();
if ( _null($this->tplname) ) $this->tplname = _tplname(__FILE__);
$fdb = (object)$this->{$this->tplname}->_getinfo2($this->request['id'], true); 
?>

<div class="dialog_max_height">
<form id="<?php _E($this->request['_formid']);?>">
<fieldset class="x-fb">
<table id="x-table">

<tr>
<th><?php _t("Status");?></th>
<td>
<select name="status">
<?php
foreach( $this->form->pstatus as $x => $y ) {
	echo "<option value='".$x."'".( $fdb->status == $x ? " selected" : "").">".$y."</option>";
}
?>
</select>
</td>
</tr>


</table>
</fieldset>
</form>
</div> <!-- /dialog_max_height -->
