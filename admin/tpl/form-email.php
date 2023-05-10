<?php
@_object($this) || exit("403 Forbidden");
$this->_notlogin();
if ( _null($this->tplname) ) $this->tplname = _tplname(__FILE__);
$this->fd = (object)null;
$this->fd->gid = "x-"._rand_text(3).time();
$fdb = (object)$this->setting->_getconfig("email_");
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

<form id="pemail">
<table id='x-table1'>

<tr>
<th class="border-left border-bottom-none border-top"><?php _t("Tajuk");?></th>
<td class="value border-right border-top">
<input type="text" name="email_tajuk" class="text" style="width:90%;" value="<?php _E($fdb->email_tajuk);?>">
</td>
</tr>
<tr>
<th class="border-left border-bottom"><?php _t("Mesej");?></th>
<td class="value border-right border-bottom">
<textarea name="email_content" class="text" style="width:100% !important;" id="txt"><?php _E($fdb->email_content);?></textarea>
<p><h1 style='font-weight:bold;'>Pelesen:</h1>
<?php
if ( preg_match("/^admin_(peniaga|kilang)/", $this->session->data->level, $mm) ) {
	$catl = $this->display->_listemail_respondent($mm[1]);
} else {
	$catl = $this->display->_listemail_respondent();
}?>
<select name="admin_pelesen" class="text">
<option value='all'>All</option>
<?php echo $catl; ?>
</select>
</p>
<p style='margin-top:20px;' class='border-top'>
Emel terakhir oleh <?php _E($this->user->_getname($fdb->email_updateby));?> pada <?php _E($this->_output_datetime($fdb->email_updatedate))?>
</p>
</td>
</tr>


<tr>
<td>&nbsp;</td>
<td style='padding-top: 10px;'><button class='button' name='btsave' style='margin-right: 5px;'><?php _t("Hantar");?></button></td>
</tr>
</table>
<input type="hidden" name="email_updateby" value="<?php _E($this->session->data->id);?>">
<input type="hidden" name="email_updatedate" value="<?php _E(date('Y-m-d H:i:s'));?>">
</form>

<script type="text/javascript">
$(document).ready(function() {
        <?php $this->_tpl("_pengumuman.js"); ?>
        var _toobar = [["cut", "copy", "paste", "separator_dots", "bold", "italic", "underline", "strike", "sub", "sup", "separator_dots", "undo", "redo", "separator_dots",
								"left", "center", "right", "justify", "separator_dots", "ol", "ul", "indent", "outdent", "separator_dots", "link", "unlink"/*, "image"*/],
                       ["formats","fontsize","fontfamily","separator","fontcolor","highlight"]];
        var _htmlbox = $("#txt").css("height", 150).css("width", "100%").htmlbox({
						toolbars: _toobar,
						about: false,
						icons: "default",
						skin: "default",
						idir: _pbaseurl+"/htmlbox/images"
        });

	function _dosubmit_email() {
		$('#pemail').ajaxSubmit({
			url: _index,
			type: 'POST',
			method: 'POST',
			dataType: 'text',
			async: false,
			clearForm: false,
			resetForm: false,
			cache: true,
			data: { _post: 'updateconfig', _what: 'setting' },
			success: function(data) {
				if ( !_ismsg_json(data) ) {
					_gerror(data);
					return false;
				}
				data = $.evalJSON(data);
				if ( !data.success ) {
					_gid = _gfalse(data.msg);
					_ghoverclose(_gid);
					return false;
				}
				_gid = _gtrue(data.msg);
				_ghoverclose(_gid);
				window.setTimeout(function() {
					_gmsgremove();
				}, 1000);
			}
		});
		return false;
	};

	$("button[name=btsave]").button({ icons: {primary:'ui-icon-disk' }}).click(function(e) {
		e = e || window.event;
		e.preventDefault();
		return _dosubmit_email();
	});
});
</script>


