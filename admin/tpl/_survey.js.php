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

	function _loadgrid(_pr, _pg, _showall) {
		_pr = _pr || 0;
		_pg = _pg || 0;
		_showall = _showall || 0;

		var _sstr = $.trim($("#"+_gridid+" input[name=sstr]").attr("value"));
		var _sopt = $.trim($("#"+_gridid+" select[name=sopt]").attr("value"));

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
			_page("#"+_gridid, { _req: 'tpl', _f: _gridtpl+'-list', sstr: _sstr, sopt: _sopt, showall: _showall }, _tabfunc );
		} else {
			_page("#"+_gridid, { _req: 'tpl', _f: _gridtpl+'-list', _pr: _pr, _pg: _pg, sstr: _sstr, sopt: _sopt, showall: _showall }, _tabfunc );
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
			_confirm("<?php _t("Operasi ini akan membuang");?> "+_id.length+" <?php _t("data");?>", function() {
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
			$(_pid+" .datepicker" ).datepicker({
                                changeYear: true,
                                changeMonth: true,
                                dateFormat: 'dd-mm-yy',
                                yearRange: '2010:2025',
                                onChangeMonthYear: function(year, month, inst) {
                                        var dd = ( inst.currentDay < 10 ? "0"+inst.currentDay : inst.currentDay );
                                        var mm = ( month < 10 ? "0"+month : month );
					if ( dd == 00 || dd == 0 ) {
						dd = "01";
					}
                                        var ct = dd+'-'+mm+'-'+year;
                                        $(this).attr("value", ct);
                                }
                        });

			/*function _setdate() {
				var _y = $(_pid+" select[name=year]").attr("value");
				var _m = $(_pid+" select[name=month]").attr("value");
				var _n = ( _m == "jun" ? "06" : "12" );
				var _s = ( _m == "jun" ? "01-01-"+_y : "01-06-"+_y );
				var _e = ( _m == "jun" ? "30-06-"+_y : "31-12-"+_y );
				$(_pid+" input[name=sdate]").attr("value", _s);
				$(_pid+" input[name=edate]").attr("value", _e);
			};
			_setdate();

			$(_pid+" select[name=year]").change(function() {
				_setdate();
			});

			$(_pid+" select[name=month]").change(function() {
				_setdate();
			});*/

		}).dialog({
				title: "<?php _t("Tambah");?>",
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
			$(_pid+" .datepicker" ).datepicker({
                                changeYear: true,
                                changeMonth: true,
                                dateFormat: 'dd-mm-yy',
                                yearRange: '2010:2025',
                                onChangeMonthYear: function(year, month, inst) {
                                        var dd = ( inst.currentDay < 10 ? "0"+inst.currentDay : inst.currentDay );
                                        var mm = ( month < 10 ? "0"+month : month );
					if ( dd == 00 || dd == 0 ) {
						dd = "01";
					}
                                        var ct = dd+'-'+mm+'-'+year;
                                        $(this).attr("value", ct);
                                }
                        });
			/*function _setdate() {
				var _y = $(_pid+" select[name=year]").attr("value");
				var _m = $(_pid+" select[name=month]").attr("value");
				var _n = ( _m == "jun" ? "06" : "12" );
				var _s = ( _m == "jun" ? "01-01-"+_y : "01-06-"+_y );
				var _e = ( _m == "jun" ? "30-06-"+_y : "31-12-"+_y );
				$(_pid+" input[name=sdate]").attr("value", _s);
				$(_pid+" input[name=edate]").attr("value", _e);
			};
			_setdate();
			$(_pid+" select[name=year]").change(function() {
				_setdate();
			});

			$(_pid+" select[name=month]").change(function() {
				_setdate();
			});*/

		}).dialog({
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
				title: "<?php _t("Informasi eGunatenaga");?>",
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



