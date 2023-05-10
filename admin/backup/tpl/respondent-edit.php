<?php
@_object($this) && !_null($this->request['_formid']) && !_null($this->request['id']) || exit("403 Forbidden");
$this->_notlogin();
$fdb = (object)$this->respondent->_getinfo($this->request['id']);
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
		echo "<option value='".$dt['id']."' data-type='".$dt['type']."'".( $fdb->category_id == $dt["id"] ? " selected" : "").">".utf8_ucwords($dt['name'])."</option>";
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
		echo "<option value='".$dt['id']."'".( $fdb->subcategory_id == $dt["id"] ? " selected" : "").">".utf8_ucwords($dt['name'])."</option>";
	}
}
?>
</select>
</td>
</tr>

<tr>
<th><?php _t("Nama syarikat");?></th>
<td><input type="text" name="company" class="text" value="<?php _E($fdb->company);?>"></td>
</tr>

<tr>
<th><?php _t("No. Lesen MPOB");?></th>
<td><input type="text" name="nolesen" class="text" value="<?php _E($fdb->nolesen);?>"></td>
</tr>

<tr>
<th><?php _t("Alamat Surat Menyurat");?></th>
<td><input type="text" name="address_surat" class="text" value="<?php _E($fdb->address_surat);?>"></td>
</tr>

<tr>
<th></th>
<td><input type="text" name="address_surat2" class="text" value="<?php _E($fdb->address_surat2);?>"></td>
</tr>

<tr>
<th></th>
<td><input type="text" name="address_surat3" class="text" value="<?php _E($fdb->address_surat3);?>"></td>
</tr>

<tr>
<th><?php _t("Alamat Premis");?></th>
<td><input type="text" name="address" class="text" value="<?php _E($fdb->address);?>"></td>
</tr>

<tr>
<th></th>
<td><input type="text" name="address2" class="text" value="<?php _E($fdb->address2);?>"></td>
</tr>

<tr>
<th></th>
<td><input type="text" name="address3" class="text" value="<?php _E($fdb->address3);?>"></td>
</tr>

<tr>
<th><?php _t("Negeri");?></th>
<td>
<select name="state_id">
<?php
$list = $this->display->_liststate(true);
if ( _array($list) ) {
	while( $dt = @array_shift($list) ) {
		echo "<option value='".$dt['id']."'".( $fdb->state_id == $dt["id"] ? " selected" : "").">".utf8_ucwords($dt['name'])."</option>";
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
<?php
echo $this->display->_listdaerah_bystate_select($fdb->state_id,$fdb->daerah_id);
?>
</select>
</td>
</tr>

<!--
<tr>
<th><?php _t("Poskod");?></th>
<td><input type="text" name="postcode" class="text" value="<?php _E($fdb->postcode);?>"></td>
</tr>
-->

<tr>
<th><?php _t("Pegawai Melapor");?></th>
<td><input type="text" name="pegawai" class="text" value="<?php _E($fdb->pegawai);?>"></td>
</tr>

<tr>
<th><?php _t("Jawatan Rasmi");?></th>
<td><input type="text" name="jawatan" class="text" value="<?php _E($fdb->jawatan);?>"></td>
</tr>

<tr>
<th><?php _t("Telefon");?></th>
<td><input type="text" name="phone" class="text" value="<?php _E($fdb->phone);?>"></td>
</tr>

<tr>
<th><?php _t("Faks");?></th>
<td><input type="text" name="fax" class="text" value="<?php _E($fdb->fax);?>"></td>
</tr>

<tr>
<th><?php _t("Emel");?></th>
<td><input type="text" name="email" class="text" value="<?php _E($fdb->email);?>"></td>
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
