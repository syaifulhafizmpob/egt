<?php
@_object($this) || exit("403 Forbidden");
$this->_notlogin();
if ( _null($this->tplname) ) $this->tplname = _tplname(__FILE__);
$this->fd = (object)null;
$this->fd->gid = "x-"._rand_text(3).time();
$fdb = (object)$this->setting->_getconfig("pengumuman");
?>
<style type="text/css">
#x-table1 th {
	text-align: right !important;
	width: 180px !important;
	vertical-align:top;
	padding-top: 5px;
}
#x-table1 textarea.text {
	width: 400px !important;
}

</style>

<form id="pcharge">
<table id='x-table1'>

<tr>
<th class="border-left border-bottom"><?php _t("Pengumuman");?></th>
<td class="value border-right border-bottom">
<textarea name="pengumuman_admin" class="text" style="width:100% !important;" id="txtadmin"><?php _E($fdb->pengumuman_admin);?></textarea>
<p style='margin-top:20px;' class='border-top'>
Kemaskini terakhir oleh <?php _E($this->user->_getname($fdb->pengumuman_admin_updateby));?> pada <?php _E($this->_output_datetime($fdb->pengumuman_admin_updatedate))?>
</p>
</td>
</tr>




<tr>
<td>&nbsp;</td>
<td style='padding-top: 10px;'><button class='button' name='btsave' style='margin-right: 5px;'><?php _t("Kemaskini");?></button></td>
</tr>
</table>
<input type="hidden" name="pengumuman_admin_updateby" value="<?php _E($this->session->data->id);?>">
<input type="hidden" name="pengumuman_admin_updatedate" value="<?php _E(date('Y-m-d H:i:s'));?>">
</form>

<script type="text/javascript">
$(document).ready(function() {
        <?php $this->_tpl("_pengumuman.js"); ?>
        var _toobar = [["cut", "copy", "paste", "separator_dots", "bold", "italic", "underline", "strike", "sub", "sup", "separator_dots", "undo", "redo", "separator_dots",
								"left", "center", "right", "justify", "separator_dots", "ol", "ul", "indent", "outdent", "separator_dots", "link", "unlink"/*, "image"*/],
                       ["formats","fontsize","fontfamily","separator","fontcolor","highlight"]];
        var _htmlbox = $("#txtadmin").css("height", 150).css("width", "100%").htmlbox({
						toolbars: _toobar,
						about: false,
						icons: "default",
						skin: "default",
						idir: _pbaseurl+"/htmlbox/images"
        });
});
</script>


