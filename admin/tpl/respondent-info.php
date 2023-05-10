<?php
@_object($this) && !_null($this->request['id']) || exit("403 Forbidden");
$this->_notlogin();
$fdb = (object)$this->respondent->_getinfo($this->request['id']);
$type = $this->display->_getcategory_type($fdb->category_id);
?>
<div class="dialog_max_height">

<fieldset class="x-fb">
<legend class="x-title"><?php _t("Informasi");?></legend>
<table id="x-table">

<tr>
<th><?php _t("Kategori");?></th>
<td class="dinfo">
<?php _E(utf8_ucwords($this->display->_getcategory_name($fdb->category_id)));?>
</td>
</tr>

<?php if ( $type == "peniaga" ): ?>
<tr>
<th><?php _t("Sub Kategori");?></th>
<td class="dinfo">
<?php _E(utf8_ucwords($this->display->_getsubcategory_name($fdb->subcategory_id)));?>
</td>
</tr>
<?php endif; ?>

<tr>
<th><?php _t("Nama syarikat");?></th>
<td class="dinfo"><?php _E($fdb->company);?></td>
</tr>

<tr>
<th><?php _t("No. Lesen MPOB");?></th>
<td class="dinfo"><?php _E($fdb->nolesen);?></td>
</tr>

<tr>
<th><?php _t("Alamat Surat Menyurat");?></th>
<td class="dinfo">
<?php _E($fdb->address_surat);?><br>
<?php _E($fdb->address_surat2);?><br>
<?php _E($fdb->address_surat3);?>
</td>
</tr>

<tr>
<th><?php _t("Alamat Premis");?></th>
<td class="dinfo">
<?php _E($fdb->address);?><br>
<?php _E($fdb->address2);?><br>
<?php _E($fdb->address3);?>
</td>
</tr>

<tr>
<th><?php _t("Daerah");?></th>
<td class="dinfo">
<?php _E(utf8_ucwords($this->display->_getdaerah_name($fdb->daerah_id)));?>
</td>
</tr>

<tr>
<th><?php _t("Negeri");?></th>
<td class="dinfo">
<?php _E(utf8_ucwords($this->display->_getstate_name($fdb->state_id)));?>
</td>
</tr>

<!--
<tr>
<th><?php _t("Poskod");?></th>
<td class="dinfo"><?php _E($fdb->postcode);?></td>
</tr>
-->

<tr>
<th><?php _t("Pegawai Melapor");?></th>
<td class="dinfo"><?php _E($fdb->pegawai);?></td>
</tr>

<tr>
<th><?php _t("Jawatan Rasmi");?></th>
<td class="dinfo"><?php _E($fdb->jawatan);?></td>
</tr>

<tr>
<th><?php _t("Telefon");?></th>
<td class="dinfo"><?php _E($fdb->phone);?></td>
</tr>

<tr>
<th><?php _t("Faks");?></th>
<td class="dinfo"><?php _E($fdb->fax);?></td>
</tr>

<tr>
<th><?php _t("Emel");?></th>
<td class="dinfo"><?php _E($fdb->email);?></td>
</tr>

</table>
</fieldset>

<fieldset class="x-fb">
<legend class="x-title"><?php _t("Login");?></legend>
<table id="x-table">

<tr>
<th class='border-top'><?php _t("Status");?></th>
<td class='border-top'>
<?php
$stat = array("on" => _tr("Aktif"), "off" => _tr("Nyah-Aktif") );
_E($stat[$fdb->status]);
?>
</td>
</tr>

<tr>
<th><?php _t("Kata Laluan");?></th>
<td class="dinfo">
<?php
echo _base64_decrypt($fdb->pass,'abahko');
?>
</td>
</tr>

</table>
</fieldset>

</div> <!-- /dialog_max_height -->
