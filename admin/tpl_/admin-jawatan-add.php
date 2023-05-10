<?php
@_object($this) && !_null($this->request['_formid']) || exit("403 Forbidden");
$this->_notlogin();
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
		echo "<option value='".$dt['id']."'>".utf8_ucwords($dt['name'])."</option>";
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
		echo "<option value='".$dt['id']."'>".utf8_ucwords($dt['name'])."</option>";
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
		echo "<option value='".$dt['id']."'>".utf8_ucwords($dt['name'])."</option>";
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
