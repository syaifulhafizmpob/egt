<?php
@_object($this) && !_null($this->tplname) || exit("403 Forbidden");
$this->_notlogin();
?>
	var _gridid  = '<?php _E($this->fd->gid);?>';
	var _gridtpl = '<?php _E($this->tplname);?>';

	/* search */
	$("#"+_gridid+" input[name=sstr]").live('keydown',function(e) {
		e = e || window.event;
		var knum = document.all ? e.keyCode : e.which;
		if (knum == '13') {
			e.preventDefault();
			_loadgrid();
		}
	});

	$("#"+_gridid+" button[name=btsearch]").live("click", function(e) {
		e = e || window.event;
		e.preventDefault();
		_loadgrid();
	});

	/* grid */
	$("#"+_gridid+" button[name=btreloadgrid]").live("click", function(e) {
                e = e || window.event;
                e.preventDefault();
                _page("#"+_gridid, { _req: 'tpl', _f: _gridtpl+'-list' }, _tabfunc );
        });

	$("#"+_gridid+" select[name=state_id], #"+_gridid+" select[name=category_id], #"+_gridid+" select[name=subcategory_id], #"+_gridid+" select[name=status], #"+_gridid+" select[name=survey_id]").live("change", function(e) {
		_loadgrid();
	});

	function _loadgrid(_pr, _pg, _showall) {
		_pr = _pr || 0;
		_pg = _pg || 0;
		_showall = _showall || 0;

		var _sstr = $.trim($("#"+_gridid+" input[name=sstr]").attr("value"));
		var _sopt = $.trim($("#"+_gridid+" select[name=sopt]").attr("value"));
		var _stateid = $.trim($("#"+_gridid+" select[name=state_id]").attr("value"));
		var _categoryid = $.trim($("#"+_gridid+" select[name=category_id]").attr("value"));
		var _subcategoryid = $.trim($("#"+_gridid+" select[name=subcategory_id]").attr("value"));
		var _status = $.trim($("#"+_gridid+" select[name=status]").attr("value"));
		var _svid = $("#"+_gridid+" select[name=survey_id]").attr("value");

		if ( !$.support.placeholder ) {
			if ( _sstr == $("#"+_gridid+" input[name=sstr]").attr("placeholder") ) {
				_sstr = "";
			}
		};
		$.ajaxSetup({
			async: true,
			global: false
		});
		if ( _pr == 0 && _pg == 0 ) {
			var _pag = $.trim($("#"+_gridid+" input[data-paggingval]").attr("data-paggingval")) || null;
			if ( _pag !== null ) {
				_pag = $.evalJSON(_pag);
				var _pr = $.trim($("#"+_gridid+" input[data-paggingval]").attr('value'));
				if ( _pr !== '' ) {
					_pr = parseInt(_pr) - 1;
					_pr = _pr < 0 ? 0 : _pr;
				}
				_pg = _pag._pg;
			}
			_page("#"+_gridid, { _req: 'tpl', _f: _gridtpl+'-list', sstr: _sstr, sopt: _sopt, state_id: _stateid, category_id: _categoryid,subcategory_id: _subcategoryid, status: _status, showall: _showall,survey_id: _svid }, _tabfunc );
		} else {
			_page("#"+_gridid, { _req: 'tpl', _f: _gridtpl+'-list', _pr: _pr, _pg: _pg, sstr: _sstr, sopt: _sopt, state_id: _stateid, category_id: _categoryid,subcategory_id: _subcategoryid, status: _status, showall: _showall,survey_id: _svid }, _tabfunc );
		}
	};

	$("#"+_gridid+" [data-pagging]").live("click",function(e) {
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
	$("#"+_gridid+" input[data-paggingval]").live("keydown", function(e) {
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
	$("#"+_gridid+" img[data-del]").live("click", function(e) {
		e = e || window.event;
		e.preventDefault();
		var _id = $(this).attr('data-del');
		$.ajaxSetup({
			async: false,
			cache: true, global: false
		});
		_confirm("<?php _t("Adakah anda pasti anda mahu memadam?");?>", function() {
			$.post(_index, {  _post: 'delete', _what: _gridtpl, id: _id }, function(data) {
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
	$("#"+_gridid+" img[data-click=mdel]").live("click", function(e) {
		e = e || window.event;
		e.preventDefault();
		var _id = new Array();
		$('input[type=checkbox][name^=del]:checked').each(function() {
			var _name = $(this).attr("name");
			var _p = _name.match(/del\[(\d+)\]/);
			_id.push(_p[1]);
		});
		if ( _id.length > 0 ) {
			_confirm("<?php _t("Operasi ini akan membuang");?> "+_id.length+" <?php _t("Pelesen");?>", function() {
				$.ajaxSetup({
					async: false,
					cache: true, global: false
				});
				$.post(_index, { _post: 'delete', _what: _gridtpl, id: _id}, function(data) {
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
	$("#"+_gridid+" button[name=btadd]").live("click", function(e) {
		e = e || window.event;
		e.preventDefault();
		$.ajaxSetup({async: false, global: false});
		var _pid = '#'+_gridid+'-add', _tpl = _gridtpl+'-add', _formid = '<?php _E($this->fd->gid);?>-save';
		var _width = 600;
		var _height = $(document).height() - 120;
		$(_pid).load(_index, { _req: 'tpl', _f: _tpl, _formid: _formid }, function() {
			$(_pid+" input[name=nolesen]").bind("keyup", function(e) {
				e = e || window.event;
				var total = $(this).attr('value').length;
				if ( total == 6 ) {
					$(this).attr('value', $(this).attr('value')+"-");
				}
			});

			$(_pid+" select[name=category_id]").change(function() {
				var _sl = $(_pid+" select[name=category_id] option:selected").attr("data-type");
				if ( _sl == "peniaga" ) {
					$(_pid+" tr.hide").show();
				} else {
					$(_pid+" select[name=subcategory_id] option[data-def]").attr("selected","selected");
					$(_pid+" tr.hide").hide();
				}
			});

			$(_pid+" select[name=state_id]").change(function() {
				var _sl = $(this).attr("value");
				$.post(_index, {  _post: 'listdaerah_bystate_ajax', _what: 'display', state_id: _sl }, function(html) {
					$(_pid+" select[name=daerah_id]").html(html);
                        	},"text");

			});


		}).dialog({
				title: "<?php _t("Tambah Pelesen");?>",
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
							data: { _post: 'save', _what: _gridtpl },
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
	$("#"+_gridid+" img[data-cgroup]").live("click", function(e) {
		e = e || window.event;
		e.preventDefault();
		var _id = $(this).attr("data-cgroup") || null;
		if ( _id ) {
			$("#"+_gridid+" img[data-edit="+_id+"]").trigger("click");
		}
	});
	$("#"+_gridid+" img[data-edit]").live("click", function(e) {
		e = e || window.event;
		e.preventDefault();
		var _id = $(this).attr("data-edit");
		$.ajaxSetup({async: false, global: false});
		var _pid = '#'+_gridid+'-edit', _tpl = _gridtpl+'-edit', _formid = '<?php _E($this->fd->gid);?>-update';
		var _width = 600;
		var _height = $(document).height() - 120;
		$(_pid).load(_index, { _req: 'tpl', _f: _tpl, id: _id, _formid: _formid }, function() {
			$(_pid+" input[name=nolesen]").bind("keyup", function(e) {
				e = e || window.event;
				var total = $(this).attr('value').length;
				if ( total == 6 ) {
					$(this).attr('value', $(this).attr('value')+"-");
				}
			});

			$(_pid+" select[name=category_id]").change(function() {
				var _sl = $(_pid+" select[name=category_id] option:selected").attr("data-type");
				if ( _sl == "peniaga" ) {
					$(_pid+" tr.hide").show();
				} else {
					$(_pid+" select[name=subcategory_id] option[data-def]").attr("selected","selected");
					$(_pid+" tr.hide").hide();
				}
			});
			$(_pid+" select[name=category_id]").trigger("change");

			$(_pid+" select[name=state_id]").change(function() {
				var _sl = $(this).attr("value");
				$.post(_index, {  _post: 'listdaerah_bystate_ajax', _what: 'display', state_id: _sl }, function(html) {
					$(_pid+" select[name=daerah_id]").html(html);
                        	},"text");

			});

		}).dialog({
				title: "<?php _t("Ubah Pelesen");?>",
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
							data: { _post: 'update', _what: _gridtpl, id: _id },
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
	$("#"+_gridid+" td.info").live("click", function(e) {
		e = e || window.event;
		e.preventDefault();
		var _id = $(this).parent().attr('data-id') || null;
		$.ajaxSetup({async: false, global: false});
		var _pid = '#'+_gridid+'-info', _tpl = _gridtpl+'-info';
		var _width = 600;
		var _height = $(document).height() - 120;
		$(_pid).load(_index, { _req: 'tpl', _f: _tpl, id: _id }).dialog({
				title: "<?php _t("Informasi Pelesen");?>",
				modal: true,
				width: _width,
				resizable: true,
				position: ["center", "top"],
				buttons: {
					"<?php _t("Ubah");?>": function() {
						$("#"+_gridid+" img[data-edit="+_id+"]").trigger("click");
						window.setTimeout(function() {
							$(_pid).dialog("close");
						}, 1000);
					},
					"<?php _t("Tutup");?>": function() {
						$(this).dialog("close");
					}
				},
				open: function() {
					_dialog_maxheight($(this),_height);
					$(this).parent().find('.ui-dialog-buttonpane button:contains("<?php _t("Tutup");?>")').button({
						icons: { primary: 'ui-icon-circle-close' }
					});
					$(this).parent().find('.ui-dialog-buttonpane button:contains("<?php _t("Ubah");?>")').button({
						icons: { primary: 'ui-icon-pencil' }
					});
				},
				close: function() {
                                        $(_pid).empty().dialog("destroy");
                                }
		});
	});

	/* status */
	$("#"+_gridid+" img[data-stat]").live("click", function(e) {
		e = e || window.event;
		e.preventDefault();
		var _obj = $(this);
		var _var = $(this).attr("data-stat");
		var _opt = _var.split("|",2);
		if ( _opt ) {
			var _status = ( _opt[1] == 'on' ? 'off' : 'on' );
			$.ajaxSetup({
				async: false,
				cache: true, global: false
			});
			$.post(_index, { _post: 'statusenabledisable', _what: _gridtpl, id: _opt[0], status: _status }, function(data) {
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
		}
	});

	$("#"+_gridid+" img[data-click=menable], #"+_gridid+" img[data-click=mdisable]").live("click", function(e) {
		e = e || window.event;
		e.preventDefault();
		var _xx = $(this).attr("data-click");
		var _text = { "menable": "<?php _t("This operation will enable");?>", "mdisable": "<?php _t("This operation will disable");?>"  };
		var _stat = { "menable": "on", "mdisable": "off" };

		var _id = new Array();
		$('input[type=checkbox][name^=del]:checked').each(function() {
			var _name = $(this).attr("name");
			var _p = _name.match(/del\[(\d+)\]/);
			_id.push(_p[1]);
		});
		if ( _id.length > 0 ) {
			_confirm(_text[_xx]+" "+_id.length+" <?php _t("Pelesen");?>", function() {
				$.ajaxSetup({
					async: false,
					cache: true, global: false
				});
				$.post(_index, { _post: 'statusenabledisable', status: _stat[_xx], _what: _gridtpl, id: _id}, function(data) {
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


