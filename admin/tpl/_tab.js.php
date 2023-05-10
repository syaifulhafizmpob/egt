<?php
@_object($this) || exit("403 Forbidden");
$this->_notlogin();
?>

	var _cname = 'cookietab', _tabindex = $.readCookie(_cname) || 1;
	function _tabclean() {
		$("div.x-list").each(function() {
			$(this).empty();
		});
	};
	function _tabfunc(obj) {
		$(obj+" button[name=btadd]").button({ icons: {primary:'ui-icon-plus' }});
		$(obj+" button[name=btsearch]").button({ icons: {primary:'ui-icon-search' }});
		$(obj+" button[name=btreloadgrid]").button({ icons: {primary:'ui-icon-refresh' }});
		$(obj+" button[name=btexecute]").button({ icons: {primary:'ui-icon-extlink' }});
		$(obj+" input[type=checkbox][name=chkdel]").click(function() {
			var _checked = $(this).attr('checked') || false;
			if ( _checked == false ) {
				$(obj+' input[type=checkbox][name^=del]').each(function() {
					$(this).attr('checked', true );
				});
			} else {
				$(obj+' input[type=checkbox][name^=del]').each(function() {
					$(this).attr('checked', false );
				});
			}
		});
		var _hoverstat = function(obj) {
			var _file = $(obj).attr("src");
			_file = basename(_file);
			if ( _file.match(/on\.gif/) ) {
				$(obj).attr({'src': _pbaseurl+'/rsc/off.gif?'+time() } );
			} else {
				$(obj).attr({'src': _pbaseurl+'/rsc/on.gif?'+time() } );
			}
		};

		$(obj+" img[data-stat]").hover(
			function() { _hoverstat($(this)); },
			function() { _hoverstat($(this)); }
		);

		$(obj+" button[name=btexecute]").click(function(e) {
			e = e || window.event;
			var _sc = $(this).attr("data-script");
			e.preventDefault();
			$.ajaxSetup({
				async: false,
				cache: true, global: false
			});
			$.post(_index, {  _post: 'forcerun', _what: 'service', sc: _sc }, function(data) {
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
                        },"text");
		});
	};
	function _tabload(_id, index) {
		_tabclean();
		var $obj = $("div#sec > ul > li > a[href=#"+_id+"]");
		$obj.trigger("blur");
		var _code = $obj.attr("data-code") || null;
		if ( _code != null ) {
			if ( _code.match(/^!p/) ) {
				_redirect(_baseurl+'/#'+_code);
			} else {
				_page("div#sec > div#"+_id+" > div.x-list",{_req: 'tpl', _f: '<?php _E($this->tplname);?>-'+_code }, _tabfunc );
			}
		}
		if ( index > 0 ) $.setCookie(_cname,index,{duration: 1});
	};
	$( "#sec" ).tabs({
		cookie: {
			expires: 1
		},
		selected: ( typeof(_sindex) !== 'undefined' ? _sindex : ( typeof(_tabindex) !== 'undefined' ? _tabindex : 1 ) ),
		select: function(e, ui) {
                        e = e || window.event;
			if ( ui.index == 0 ) {
				$.delCookie(_cname);
				$.hash.go('!p');
			}
                },
		show: function(e, ui) {
			var _id = ui.panel.id || null;
			if ( _id !== null ) {
				_tabload(_id, ui.index);
			}
                }
	});

	function _tabselect(index) {
		$( "#sec" ).tabs("select", index);
	};

