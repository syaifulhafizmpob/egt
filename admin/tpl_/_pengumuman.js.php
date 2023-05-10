<?php
@_object($this) || exit("403 Forbidden");
$this->_notlogin();
?>
	function _dosubmit() {
		$('#pcharge').ajaxSubmit({
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
		return _dosubmit();
	});

