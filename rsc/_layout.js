var _cname = 'cookietab';
var _sname = '_display-tab';
var _mainpage = 'display';

function _winload() {
	var p = null, _hash = location.hash || null;
	if ( _hash ) {
		p = _hash.match(/!p$/);
		if ( p ) {
			_page("div.ui-layout-center",{_req: 'tpl', _f: _mainpage }, function() {
				$("select[name=navdisplay]").selectmenu("index", 1);
			});
			return;
		}
		p = _hash.match(/!p\/(\S+)$/);
		if ( p && p[1]) {
			_page("div.ui-layout-center",{_req: 'tpl', _f: p[1] }, function() {
				$("select[name=navdisplay] option").each(function(i) {
					var _val = $(this).attr("value") || null;
					if ( _val == p[1] ) {
						$("select[name=navdisplay]").selectmenu("index", i);
					}
				});
			});
			return;
		}
	}
	_redirect(_baseurl+'/#!p', true);
};

$(window).load(_winload);

$(document).ready(function () {
        var _layout = $('body').layout(
		{ 
			defaults: {
				applyDemoStyles: false,
				spacing_open: 4,
				spacing_closed: 4
			},
			north: {
				spacing_open: 0,
				spacing_closed: 1,
				closable: false,
				slidable: false,
				resizable: false
			},
			/*west: {
				spacing_open: 4,
				spacing_closed: 4,
				initClosed: true,
				size: 100
			},*/
			south: {
				spacing_open: 0,
				spacing_closed: 1,
				closable: false,
				slidable: false,
				resizable: false
			}
		}
	);

	$(".button").button();
	$("button[name=btprofile]").button({ icons: {primary:'ui-icon-wrench' }});
	$("button[name=btlogout]").button({ icons: {primary:'ui-icon-power' }});
	$("button[name=bthome]").button({ icons: {primary:'ui-icon-home' }});
	$("button[name=btreload]").button({ icons: {primary:'ui-icon-refresh' }});
<?php if ( $this->isadmin ): ?>
	$("button[name=btadmin]").button({ icons: {primary:'ui-icon-key' }});
	$("button[name=btadmin]").live("click", function(e) {
		e = e || window.event;
		e.preventDefault();
		_selectmenuindex(null);
		$.hash.go('!p/admin');
	});
<?php endif; ?>

	/* ajax history */
        $.hash.init();

	$("button[name=btlogout]").live("click", function(e) {
		e = e || window.event;
		e.preventDefault();
		$.ajaxSetup({
			async: false,
			cache: false, global: false,
			
		});
		var _ref = _baseurl+"/";
		$.get(_index, { _req: 'logout'}, function(data) {
	                _ajaxmsg("<?php _t("Logout");?>");
			window.setTimeout(function() { _redirect(_ref); }, 500);
	        },"text");
	});

	$("div.x-logo").live("click", function(e) {
		e = e || window.event;
		e.preventDefault();
		_redirect(_baseurl+'/#!p');
	});

	$("button[name=btreload]").live("click", function(e) {
		e = e || window.event;
		e.preventDefault();
		window.location.reload();
	});

	$("button[name=bthome]").live("click", function(e) {
		e = e || window.event;
		e.preventDefault();
		$("select[name=navdisplay]").selectmenu("index", 0);
		$.hash.go('!p');
	});

	$("div[data-display]").live("click", function(e) {
		e = e || window.event;
		e.preventDefault();
		var _code = $(this).attr("data-display") || null;
		if ( _code != null ) {
			$.hash.go('!p/'+_code);
		}
	});

	function _selectmenuindex(_code) {
		_code = _code || null;
		$.delCookie(_cname);
		if ( _code != null ) {
			$("select[name=navdisplay] option").each(function(i) {
				var _val = $(this).attr("value") || null;
				if ( _val == _code ) {
					$("select[name=navdisplay]").selectmenu("index", i);
				}
			});
		} else {
			$("select[name=navdisplay]").selectmenu("index", 0);
		}
	};

	$("select[name=navdisplay]").selectmenu({
		style: 'dropdown',
		width: 200,
		change: function(e, obj) {
			$.delCookie(_cname);
			var _code = obj.value || null;
			if ( _code != null ) {
				$.hash.go('!p/'+_code);
			} else {
				$.hash.go('!p');
			}
		},
		icons: [
			{find: ".lymenu", icon: 'ui-icon-link'},
			{find: ".lyhome", icon: 'ui-icon-home'}
	<?php
		if ( method_exists($this->display, "_iconlist" ) ) {
			$data = @$this->display->_iconlist();
			if ( _array($data) ) {
				echo ",";
				while($row = @array_shift($data) ) {
					echo "{find: \".ly".$row['uicon']."\", icon: '".$row['uicon']."'},";
				}
			}
		}
	?>
		]
	});

	$(document.body).hashchange(function (e, _hash) {
		var p = null;
		p = _hash.match(/!p$/);
		if ( p ) {
			$("select[name=navdisplay]").selectmenu("index", 1);
			_page("div.ui-layout-center",{_req: 'tpl', _f: _mainpage });
			return;
		}
		p = _hash.match(/!p\/(\S+)$/);
		if ( p && p[1]) {
			_page("div.ui-layout-center",{_req: 'tpl', _f: p[1] }, function() {
				_selectmenuindex(p[1]);
			});
			return;
		}
	});

<?php if ( $this->session->data->adminview ): ?>
	$("button[name=btadmlogout]").button({ icons: {primary:'ui-icon-power' }});
	$("button[name=btadmlogout]").live("click", function(e) {
		e = e || window.event;
		e.preventDefault();
		$.ajaxSetup({
			async: false,
			cache: false, global: false,
			
		});
		var _ref = _baseurl+"/";
		$.get(_index, { _req: 'logout'}, function(data) {
			window.setTimeout(function() { window.close(); }, 500);
	        },"text");
	});
<?php endif; ?>
});

