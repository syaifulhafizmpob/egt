<?php
@_object($this) && !_null($this->request['_formid']) && !_null($this->request['id']) || exit("403 Forbidden");
$this->_notlogin();
$fdb = (object)$this->jawatan->_getinfo($this->request['id']);
?>

<div class="dialog_max_height">
<form id="<?php _E($this->request['_formid']);?>">

<fieldset class="x-fb">

<table id="x-table">

<tr>
<th><?php _t("Kategori");?></th>
<td>
<select name="category_id">
<?php
$list = $this->display->_listcategory();
if ( _array($list) ) {
	while( $dt = @array_shift($list) ) {
		echo "<option value='".$dt['id']."'".( $fdb->category_id == $dt['id'] ? " selected" : "").">".utf8_ucwords($dt['name'])."</option>";
	}
}
?>
</select>
</td>
</tr>

<tr>
<th><?php _t("Kategori");?></th>
<td>
<select name="group_id">
<?php
$list = $this->jawatan->_list_group();
if ( _array($list) ) {
	while( $dt = @array_shift($list) ) {
		echo "<option value='".$dt['id']."'".( $fdb->group_id == $dt['id'] ? " selected" : "").">".utf8_ucwords($dt['name'])."</option>";
	}
}
?>
</select>
</td>
</tr>

<tr>
<th><?php _t("Jawatan");?></th>
<td>
<select name="jawatan_id">
<?php
$list = $this->jawatan->_list_name();
if ( _array($list) ) {
	while( $dt = @array_shift($list) ) {
		echo "<option value='".$dt['id']."'".( $fdb->jawatan_id == $dt['id'] ? " selected" : "").">".utf8_ucwords($dt['name'])."</option>";
	}
}
?>
</select>
</td>
</tr>

</table>
</fieldset>


</form>
</div> <!-- /dialog_max_height -->
