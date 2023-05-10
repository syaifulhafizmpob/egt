<?php
@is_object($this) || exit("403 Forbidden");
$this->_notlogin();
$this->_tpl("header");
?>
<script type="text/javascript">
$(document).ready(function() {
	$("button[name=btprofile]").live("click", function(e) {
		e = e || window.event;
		e.preventDefault();
		var _id = '<?php _E($this->session->data->id);?>';
		$.ajaxSetup({async: false, global: false});
		var _pid = 'div#profile', _tpl = 'profile', _formid = 'user-update';
		$(_pid).load(_index, { _req: 'tpl', _f: _tpl, id: _id, _formid: _formid }).dialog({
				modal: true,
				width: 600,
				resizable: true,
				position: ["center", "top"],
				buttons: {
					"<?php _t("Kemaskini");?>": function() {
						$('#'+_formid).ajaxSubmit({
							url: _index,
							type: 'POST',
							method: 'POST',
							dataType: 'text',
							async: false,
							clearForm: false,
							resetForm: false,
							cache: true,
							data: { _post: 'update', _what: 'user', id: _id },
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
									$(_pid).dialog("close");
									_gmsgremove();
								}, 1000);
							}
						});
						return false;
					},
					"<?php _t("Batal");?>": function() {
						$(this).dialog("close");
					}
				},
				open: function() {
					$(this).parent().find('.ui-dialog-buttonpane button:contains("<?php _t("Batal");?>")').button({
						icons: { primary: 'ui-icon-circle-close' }
					});
					$(this).parent().find('.ui-dialog-buttonpane button:contains("<?php _t("Kemaskini");?>")').button({
						icons: { primary: 'ui-icon-disk' }
					});
				},
				close: function() {
                                        $(_pid).empty().dialog("destroy");
                                }
		});
	});

});
</script>
<div class="ui-layout-center">
</div> <!-- /ui-layout-center -->

<div class="ui-layout-north">
<div class="x-logo"></div>


<div class="x-nav">
<button class="button button_red" name="btreload" style='margin-right: 10px;'><?php _t("Refresh");?></button>
<?php if ( $this->isadmin ): ?>
<button class="button button_red" name="btadmin"><?php _t("Mentadbir");?></button>
<?php endif; ?>
<button class="button button_red" name="btprofile"><?php _t("Tukar Kata Laluan");?></button>
<button class="button button_red" name="btlogout"><?php _t("Logout");?></button>
</div>

<div class="hide" id="profile" title="<?php _t("Profil");?>"></div>
</div> <!-- /ui-layout-north -->


<div class="ui-layout-south">
<?php _E(_safe_eval($this->options->copyright)); ?> | Login dari <?php _E($this->session->data->ip);?>
&nbsp;| Login Akhir <?php _E($this->_output_datetime($this->session->data->lastlogin));?>
</div> <!-- /ui-layout-south -->

</body>
</html>
