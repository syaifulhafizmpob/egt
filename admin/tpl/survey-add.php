<?php
@_object($this) && !_null($this->request['_formid']) || exit("403 Forbidden");
$this->_notlogin();
?>

<div class="dialog_max_height">
<form id="<?php _E($this->request['_formid']);?>">

<fieldset class="x-fb">
<table id="x-table">

<tr>
<th><?php _t("Tahun");?></th>
<td>
<select name="year" class="text">
<?php
for($x=2010;$x<=2025;$x++) {
	echo "<option value='".$x."'".( $x == date('Y') ? " selected" : "").">".$x."</option>";
}
?>
</select>
</td>
</tr>

<tr>
<th><?php _t("Bulan");?></th>
<td>
<select name="month">
<?php
foreach(array("jun","disember") as $x) {
	echo "<option value='".$x."'>".strtoupper($x)."</option>";
}
?>
</select>
</td>
</tr>

<tr>
<th><?php _t("Tarikh Mula");?></th>
<td><input type="text" name="sdate" class="text datepicker" value="<?php _E($this->_output_datepicker(date('d-m-Y')));?>"></td>
</tr>

<tr>
<th><?php _t("Tarikh Tamat");?></th>
<td><input type="text" name="edate" class="text datepicker" value="<?php _E($this->_output_datepicker(date('d-m-Y',strtotime("+6 months"))));?>"></td>
</tr>

<tr>
<th class='border-top'><?php _t("Aktif");?></th>
<td class='border-top'>
<select name="status">
<?php
foreach(array("on" => _tr("On"), "off" => _tr("Off") ) as $x => $y) {
	echo "<option value='".$x."'>".$y."</option>";
}
?>
</select>
</td>
</tr>

<?php /*
<tr>
<th style="vertical-align: top; padding-top: 10px;"><?php _t("Penerangan");?></th>
<td>
<textarea name="desc"></textarea>
</td>
</tr>
*/?>

</table>
</fieldset>

</form>
</div> <!-- /dialog_max_height -->


