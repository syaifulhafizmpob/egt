<?php
@_object($this) && !_null($this->request['_formid']) || exit("403 Forbidden");
$this->_notlogin();
?>
<div class="dialog_max_height">
<form id="<?php _E($this->request['_formid']);?>">

<fieldset class="x-fb">
<legend class="x-title"><?php _t("Informasi");?></legend>
<table id="x-table">

<tr>
<th><?php _t("Kategori");?></th>
<td>
<select name="category_id">
<?php
$list = $this->display->_listcategory_respondent();
if ( _array($list) ) {
	while( $dt = @array_shift($list) ) {
		echo "<option value='".$dt['id']."' data-type='".$dt['type']."'>".utf8_ucwords($dt['name'])."</option>";
	}
}
?>
</select>
</td>
</tr>

<tr class='hide'>
<th><?php _t("Sub Kategori");?></th>
<td>
<select name="subcategory_id">
<option value="" data-def='1'>Sila pilih</option>
<?php
$list = $this->display->_listsubcategory("peniaga");
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
<th><?php _t("Nama syarikat");?></th>
<td><input type="text" name="company" class="text" value=""></td>
</tr>

<tr>
<th><?php _t("No. Lesen MPOB");?></th>
<td><input type="text" name="nolesen" class="text" value=""></td>
</tr>

<tr>
<th><?php _t("Alamat Surat Menyurat");?></th>
<td><input type="text" name="address_surat" class="text" value=""></td>
</tr>

<tr>
<th></th>
<td><input type="text" name="address_surat2" class="text" value=""></td>
</tr>

<tr>
<th></th>
<td><input type="text" name="address_surat3" class="text" value=""></td>
</tr>

<tr>
<th><?php _t("Alamat Premis");?></th>
<td><input type="text" name="address" class="text" value=""></td>
</tr>

<tr>
<th></th>
<td><input type="text" name="address2" class="text" value=""></td>
</tr>

<tr>
<th></th>
<td><input type="text" name="address3" class="text" value=""></td>
</tr>

<tr>
<th><?php _t("Negeri");?></th>
<td>
<select name="state_id">
<?php
$list = $this->display->_liststate(true);
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
<th><?php _t("Daerah");?></th>
<td>
<select name="daerah_id">
</select>
</td>
</tr>

<!--
<tr>
<th><?php _t("Poskod");?></th>
<td><input type="text" name="postcode" class="text" value=""></td>
</tr>
-->

<tr>
<th><?php _t("Pegawai Melapor");?></th>
<td><input type="text" name="pegawai" class="text" value=""></td>
</tr>

<tr>
<th><?php _t("Jawatan Rasmi");?></th>
<td><input type="text" name="jawatan" class="text" value=""></td>
</tr>

<tr>
<th><?php _t("Telefon");?></th>
<td><input type="text" name="phone" class="text" value=""></td>
</tr>

<tr>
<th><?php _t("Faks");?></th>
<td><input type="text" name="fax" class="text" value=""></td>
</tr>

<tr>
<th><?php _t("Emel");?></th>
<td><input type="text" name="email" class="text" value=""></td>
</tr>

</table>
</fieldset>

<fieldset class="x-fb">
<legend class="x-title"><?php _t("Login");?></legend>
<table id="x-table">


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

</form>
</div> <!-- /dialog_max_height -->
