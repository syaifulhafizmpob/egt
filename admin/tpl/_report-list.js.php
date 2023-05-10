<?php
@_object($this) || exit("403 Forbidden");
$this->_notlogin();
?>
	function _menuclean() {
		$("div.x-list-m").each(function() {
			$(this).empty();
		});
	};

	function _load_mpage(_code) {
		var _p = "report-"+_code;
		_menuclean();
		_page("#"+_gridid+_code, { _req: 'tpl', _f: _p});
		$.setCookie('ktab',_code,{duration: 1});
	};


	$("select[name=reportdisplay]").selectmenu({
		style: 'dropdown',
		width: 1024,
		change: function(e, obj) {
			$.delCookie('ktab');
			var _code = obj.value || null;
			if ( _code != null ) {
				_load_mpage(_code);
			}
		},
		icons: [
	<?php
		$data = $this->display->_iconlist_report();
		if ( _array($data) ) {
			while($row = @array_shift($data) ) {
				echo "{find: \".ly".$row['uicon']."\", icon: '".$row['uicon']."'},";
			}
		}
	?>
		]
	});

	function _selectmenuindex_report(_code) {
		_code = _code || null;
		if ( _code != null ) {
			$("select[name=reportdisplay] option").each(function(i) {
				var _val = $(this).attr("value") || null;
				if ( _val == _code ) {
					$("select[name=reportdisplay]").selectmenu("index", i);
				}
			});
		} else {
			$("select[name=reportdisplay]").selectmenu("index", 0);
		}
	};
	var _ktab = $.readCookie('ktab') || "report1";
	_selectmenuindex_report(_ktab);
	_load_mpage(_ktab);

