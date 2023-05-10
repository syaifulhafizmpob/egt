<?php
@_object($this) && !_null($this->request['_formid']) || exit("403 Forbidden");
$this->_notlogin();
$admin_level = "";
$admin_s = false;
if ( preg_match("/^admin_(peniaga|kilang)/", $this->session->data->level, $mm) ) {
	$admin_level = $mm[1];
	$admin_s = true;
}
?>
<div class="dialog_max_height">
<form id="<?php _E($this->request['_formid']);?>">

<fieldset class="x-fb">
<legend class="x-title"><?php _t("Login");?></legend>
<table id="x-table">

<tr>
<th><?php _t("Id Pengguna");?></th>
<td><input type="text" name="login" class="text" value=""></td>
</tr>

<tr>
<th><?php _t("Peranan");?></th>
<td>
<select name="level">
<?php
foreach(array("superadmin" => "Superadmin", "admin_kilang"=>"Admin Kilang", "admin_peniaga"=> "Admin Peniaga", "staff" => "Staff") as $x => $y) {
	if ( !$this->issuperadmin ) {
		if ( $x == "superadmin" ) continue;
		if ( $admin_s && $x != "staff" && $this->session->data->level != $x ) continue;
	}
	echo "<option value='".$x."'>".$y."</option>";
}
?>
</select>
</td>
</tr>

<?php if ( $admin_s ): ?>

<tr id="grp" class="hide">
<th style='vertical-align:top; padding-top: 5px;'><?php _t("Kategori %s", ucwords($admin_level));?></th>
<td style='vertical-align:top; padding-top: 5px;'>
<?php
$catl = $this->display->_listcategory($admin_level);
if ( _array($catl) ) {
	echo "<table>";
	while( $rt = @array_shift($catl) ) {
		echo "<tr><td style='width:200px;'>".$rt['name']."</td><td style='padding-left: 5px;'><input type='checkbox' name='groups[".$rt['id']."]' value='1'></td></tr>";
	}
	echo "</table>";
}
?>
</td>
</tr>


<?php else: ?>
<tr id="grp" class="hide">
<th style='vertical-align:top; padding-top: 5px;'><?php _t("Kategori Kilang");?></th>
<td style='vertical-align:top; padding-top: 5px;'>
<?php
$catl = $this->display->_listcategory("kilang");
if ( _array($catl) ) {
	echo "<table>";
	while( $rt = @array_shift($catl) ) {
		echo "<tr><td style='width:200px;'>".$rt['name']."</td><td style='padding-left: 5px;'><input type='checkbox' name='groups[".$rt['id']."]' value='1'></td></tr>";
	}
	echo "</table>";
}
?>
</td>
</tr>

<tr id="grp" class="hide">
<th style='vertical-align:top; padding-top: 10px;'><?php _t("Kategori Peniaga");?></th>
<td style='vertical-align:top; padding-top: 10px;'>
<?php
$catl = $this->display->_listcategory("peniaga");
if ( _array($catl) ) {
	echo "<table>";
	while( $rt = @array_shift($catl) ) {
		echo "<tr><td style='width:200px;'>".$rt['name']."</td><td style='padding-left: 5px;'><input type='checkbox' name='groups[".$rt['id']."]' value='1'></td></tr>";
	}
	echo "</table>";
}
?>
</td>
</tr>

<?php endif; ?>

<tr><td class='border-bottom' colspan='2'>&nbsp;</td></tr>

<tr>
<th><?php _t("Kata Laluan");?></th>
<td><input type="password" name="npass" class="text" value=""></td>
</tr>

<tr>
<th><?php _t("Taip semula Kata Laluan");?></th>
<td><input type="password" name="rpass" class="text" value=""></td>
</tr>


<tr>
<th class='border-top'><?php _t("Status");?></th>
<td class='border-top'>
<select name="status">
<?php
foreach(array("on" => _tr("Aktif"), "off" => _tr("Nyah-Aktif") ) as $x => $y) {
	echo "<option value='".$x."'>".$y."</option>";
}
?>
</select>
</td>
</tr>

</table>
</fieldset>

<fieldset class="x-fb">
<legend class="x-title"><?php _t("Informasi");?></legend>
<table id="x-table">

<tr>
<th><?php _t("Nama Penuh");?></th>
<td><input type="text" name="name" class="text" value=""></td>
</tr>

<tr>
<th><?php _t("Emel");?></th>
<td><input type="text" name="email" class="text" value=""></td>
</tr>

<tr>
<th style="vertical-align: top; padding-top: 10px;"><?php _t("Penerangan");?></th>
<td>
<textarea name="desc"></textarea>
</td>
</tr>

</table>
</fieldset>

</form>
</div> <!-- /dialog_max_height -->
