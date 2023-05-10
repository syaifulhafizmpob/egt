<?php
@_object($this) && !_null($this->tplname) || exit("403 Forbidden");
$this->_notlogin();
?>
	var _gridid7  = '<?php _E($this->fd->gid);?>7';
	var _gridtpl7 = 'form-msg';
	var _what7 = "msg";

	/* search */
	$("#"+_gridid7+" input[name=sstr]").live('keydown',function(e) {
		e = e || window.event;
		var knum = document.all ? e.keyCode : e.which;
		if (knum == '13') {
			e.preventDefault();
			_loadgrid();
		}
	});

	$("#"+_gridid7+" button[name=btsearch]").live("click", function(e) {
		e = e || window.event;
		e.preventDefault();
		_loadgrid();
	});

	/* grid */
	$("#"+_gridid7+" button[name=btreloadgrid]").live("click", function(e) {
                e = e || window.event;
                e.preventDefault();
                _page("#"+_gridid7, { _req: 'tpl', _f: _gridtpl7 }, _tabfunc );
        });
	$("#"+_gridid7+" select[name=statusopt]").live("change", function(e) {
		_loadgrid();
        });
	function _loadgrid(_pr, _pg, _showall) {
		_pr = _pr || 0;
		_pg = _pg || 0;
		_showall = _showall || 0;

		var _sstr = $.trim($("#"+_gridid7+" input[name=sstr]").attr("value"));
		var _sopt = $.trim($("#"+_gridid7+" select[name=sopt]").attr("value"));
		var _statusopt = $.trim($("#"+_gridid7+" select[name=statusopt]").attr("value"));
		if ( !$.support.placeholder ) {
			if ( _sstr == $("#"+_gridid7+" input[name=sstr]").attr("placeholder") ) {
				_sstr = "";
			}
		};
		$.ajaxSetup({
			async: true,
			global: false
		});
		if ( _pr == 0 && _pg == 0 ) {
			var _pag = $.trim($("#"+_gridid7+" input[data-paggingval]").attr("data-paggingval")) || null;
			if ( _pag !== null ) {
				_pag = $.evalJSON(_pag);
				var _pr = $.trim($("#"+_gridid7+" input[data-paggingval]").attr('value'));
				if ( _pr !== '' ) {
					_pr = parseInt(_pr) - 1;
					_pr = _pr < 0 ? 0 : _pr;
				}
				_pg = _pag._pg;
			}
			_page("#"+_gridid7, { _req: 'tpl', _f: _gridtpl7, sstr: _sstr, sopt: _sopt, statusopt: _statusopt, showall: _showall }, _tabfunc );
		} else {
			_page("#"+_gridid7, { _req: 'tpl', _f: _gridtpl7, _pr: _pr, _pg: _pg, sstr: _sstr, sopt: _sopt, statusopt: _statusopt, showall: _showall }, _tabfunc );
		}
	};

	$("#"+_gridid7+" [data-pagging]").live("click",function(e) {
		e = e || window.event;
		e.preventDefault();
		var data = $(this).attr('data-pagging');
		if ( data === 'showall' ) {
			_loadgrid(0,0,1);
		} else {
			data = $.evalJSON(data);
			_loadgrid(data._pr, data._pg);
		}
	});
	$("#"+_gridid7+" input[data-paggingval]").live("keydown", function(e) {
		e = e || window.event;
		var knum = document.all ? e.keyCode : e.which;
		if (knum == '13') {
			e.preventDefault();
			var data = $(this).attr('data-paggingval');
			data = $.evalJSON(data);
			var _pr = $.trim($(this).attr('value'));
			if ( _pr !== '' ) {
				_pr = parseInt(_pr) - 1;
				_pr = _pr < 0 ? 0 : _pr;
				data._pr = _pr;
				if ( data._pr > data._pg ) {
					data._pr = 0;
					$(this).attr('value', '1');
				}
				_loadgrid(data._pr, data._pg);
			}
		}
	});

	/* delete */
	$("#"+_gridid7+" img[data-del]").live("click", function(e) {
		e = e || window.event;
		e.preventDefault();
		var _id = $(this).attr('data-del');
		$.ajaxSetup({
			async: false,
			cache: true, global: false
		});
		_confirm("<?php _t("Adakah anda pasti anda mahu memadam?");?>", function() {
			$.post(_index, {  _post: 'delete', _what: _what7, id: _id }, function(data) {
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
				_loadgrid();
			},"text");
		});
	});
	$("#"+_gridid7+" img[data-click=mdel]").live("click", function(e) {
		e = e || window.event;
		e.preventDefault();
		var _id = new Array();
		$('input[type=checkbox][name^=del]:checked').each(function() {
			var _name = $(this).attr("name");
			var _p = _name.match(/del\[(\d+)\]/);
			_id.push(_p[1]);
		});
		if ( _id.length > 0 ) {
			_confirm("<?php _t("Operasi ini akan membuang");?> "+_id.length+" <?php _t("data");?>", function() {
				$.ajaxSetup({
					async: false,
					cache: true, global: false
				});
				$.post(_index, { _post: 'delete', _what: _what7, id: _id}, function(data) {
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
					_loadgrid();
				},"text");
			});
		}
	});

	/* add */
	$("#"+_gridid7+" button[name=btadd]").live("click", function(e) {
		e = e || window.event;
		e.preventDefault();
		$.ajaxSetup({async: false, global: false});
		var _pid = '#'+_gridid7+'-add', _tpl = _gridtpl7+'-add', _formid = '<?php _E($this->fd->gid);?>-save';
		var _width = 600;
		var _height = $(document).height() - 120;
		$(_pid).load(_index, { _req: 'tpl', _f: _tpl, _formid: _formid }).dialog({
				title: "<?php _t("Hantar Mesej");?>",
				modal: true,
				width: _width,
				resizable: true,
				position: ["center", "top"],
				buttons: {
					"<?php _t("Simpan");?>": function() {
						$('#'+_formid).ajaxSubmit({
							url: _index,
							type: 'POST',
							method: 'POST',
							dataType: 'text',
							async: false,
							clearForm: false,
							resetForm: false,
							cache: true,
							data: { _post: 'save', _what: _what7 },
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
								_loadgrid();
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
					_dialog_maxheight($(this),_height);
					$(this).parent().find('.ui-dialog-buttonpane button:contains("<?php _t("Batal");?>")').button({
						icons: { primary: 'ui-icon-circle-close' }
					});
					$(this).parent().find('.ui-dialog-buttonpane button:contains("<?php _t("Simpan");?>")').button({
						icons: { primary: 'ui-icon-disk' }
					});
				},
				close: function() {
                                        $(_pid).empty().dialog("destroy");
                                }

		});
	});

	/* edit */
	$("#"+_gridid7+" img[data-cgroup]").live("click", function(e) {
		e = e || window.event;
		e.preventDefault();
		var _id = $(this).attr("data-cgroup") || null;
		if ( _id ) {
			$("#"+_gridid7+" img[data-edit="+_id+"]").trigger("click");
		}
	});
	$("#"+_gridid7+" img[data-edit]").live("click", function(e) {
		e = e || window.event;
		e.preventDefault();
		var _id = $(this).attr("data-edit");
		$.ajaxSetup({async: false, global: false});
		var _pid = '#'+_gridid7+'-edit', _tpl = _gridtpl7+'-edit', _formid = '<?php _E($this->fd->gid);?>-update';
		var _width = 600;
		var _height = $(document).height() - 120;
		$(_pid).load(_index, { _req: 'tpl', _f: _tpl, id: _id, _formid: _formid }).dialog({
				title: "<?php _t("Ubah");?>",
				modal: true,
				width: _width,
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
							data: { _post: 'update', _what: _what7, id: _id },
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
								_loadgrid();
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
					_dialog_maxheight($(this),_height);
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

	/* info */
	$("#"+_gridid7+" td.info").live("click", function(e) {
		e = e || window.event;
		e.preventDefault();
		var _id = $(this).parent().attr('data-id') || null;
		$.ajaxSetup({async: false, global: false});
		var _pid = '#'+_gridid7+'-info', _tpl = _gridtpl7+'-info';
		var _width = 700;
		var _height = $(document).height() - 120;
		$(_pid).load(_index, { _req: 'tpl', _f: _tpl, id: _id }).dialog({
				title: "<?php _t("Informasi Mesej");?>",
				modal: true,
				width: _width,
				resizable: true,
				position: ["center", "top"],
				buttons: {
					"<?php _t("Tutup");?>": function() {
						$(this).dialog("close");
					}
				},
				open: function() {
					_dialog_maxheight($(this),_height);
					$(this).parent().find('.ui-dialog-buttonpane button:contains("<?php _t("Tutup");?>")').button({
						icons: { primary: 'ui-icon-circle-close' }
					});
					$(this).parent().find('.ui-dialog-buttonpane button:contains("<?php _t("Telah Diproses");?>")').button({
						icons: { primary: 'ui-icon-pencil' }
					});
				},
				close: function() {
                                        $(_pid).empty().dialog("destroy");
                                }
		});
	});

	$("#"+_gridid7+" img[data-dl]").live("click", function(e) {
		e = e || window.event;
		e.preventDefault();
		var _id = $(this).attr("data-dl");
		_popupnewin(_baseurl+"/?_req=download&_f="+_id,"new");
	});

