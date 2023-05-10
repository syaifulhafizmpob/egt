<?php
/**
 * behavior script
 *
 * @author Mohd Nawawi Mohamad Jamili <nawawi@rutweb.com>
 * @category javascript
 * @update 19-Oct-2011
 */

/**
 * History:
 *
 * 19/10/2011: remove IE7 hack script
 * 12/03/2012: fix layerX layerY chrome issue
 * 17/04/2012: add _xtab_resize()
 */

define('ABSPATH', dirname(realpath(__FILE__)) . '/' );
if ( !@include_once(ABSPATH.'bootstrap.php') ) exit("Bootstrapping failed!\n");
ob_start();
?>
if ( typeof(_baseurl) == 'undefined' ) {
        var _baseurl = "";
}
if ( typeof(_pbaseurl) == 'undefined' ) {
        var _pbaseurl = _baseurl;
}

/* http://stackoverflow.com/questions/7825448/webkit-issues-with-event-layerx-and-event-layery */
$.event.props = $.event.props.join('|').replace('layerX|layerY|', '').split('|');

function _checkbox_fix() {
	if ( $.isFunction($().checkbox) ) {
		$('input[type=checkbox]').not("input[name^=multiselect]").checkbox({'empty': _pbaseurl+'/ui/blank.png'});
	}
};

function _xtooltip() {
	if ( $.isFunction($().xtooltip) ) {
		$(document.body).bind('mouseover.tooltip',function() {
			$(this).find('[data-tooltip]').each(function() {
				$(this).xtooltip();
			});
		}).bind("unload", function() {
			$(this).unbind("mouseover.tooltip");
		});
	}
};

function _button() {
	if ( $.isFunction($().button) ) {
		$(".button").button();
		$("input.button").live('click', function(e) {
			e = e || window.event;
			e.preventDefault();
			$(this).blur();
		});
	}
};

function _placeholder() {
	if ( $.isFunction($().placeholder) ) {
		$('input[placeholder], textarea[placeholder]').not(".placeholder").placeholder();
	}
};

function _fixjs() {
	_xtooltip();
	_checkbox_fix();
	_button();
	_placeholder();
};

$(document).ready(function() {
	_fixjs();
});

function _ajaxloader(show) {
	show = show || false;
	$("#ajax-loader").hide();
	if ( show ) {
		$("#ajax-loader").center().show();
	}
};

if ( $.isPlainObject($.gritter) ) {
	$.extend($.gritter.options, { 
		fade_in_speed: 100,
		fade_out_speed: 100,
		time: 4000
	});
};

function _gmsg(settings) {
	if ( $.isPlainObject($.gritter) ) {
		settings = $.extend({
			sticky: false
		}, settings);
		var _id = $.gritter.add(settings);
		$("#gritter-notice-wrapper").center({'top':'15%'}).zindex();
		return _id;
	}
	alert(settings.title+' '+settings.text);
	return null;
};

function _ghoverclose(id) {
	if ( $.isPlainObject($.gritter) ) {
		$("#gritter-notice-wrapper").mouseover(function() {
			$.gritter.remove(id,{speed: 'fast'});
		});
	}
};

function _gmsgremove() {
	$("#gritter-notice-wrapper").remove();
};

function _gwarning(msg, title) {
	title = title || "<?php _t("Warning");?>";
	msg = msg || ' ';
	var _id = _gmsg({
		title: title,
		text: msg,
		sticky: false,
		image: _pbaseurl+'/ui/gritter-warning.png'
	});
	return _id;
};

function _gnotice(msg, title) {
	title = title || "<?php _t("Notice");?>";
	msg = msg || ' ';
	var _id = _gmsg({
		title: title,
		text: msg,
		sticky: false,
		image: _pbaseurl+'/ui/gritter-notice.png'
	});
	return _id;
};

function _gerror(msg, title) {
	title = title || "<?php _t("Error");?>";
	msg = msg || ' ';
	var _id = _gmsg({
		title: title,
		text: msg,
		sticky: false,
		image: _pbaseurl+'/ui/gritter-error.png'
	});
	return _id;
};

function _gtrue(msg, title) {
	title = title || ' ';
	msg = msg || ' ';
	var _id = _gmsg({
		title: title,
		text: msg,
		sticky: false,
		image: _pbaseurl+'/ui/gritter-true.png'
	});
	return _id;
};

function _gfalse(msg, title) {
	title = title || ' ';
	msg = msg || ' ';
	var _id = _gmsg({
		title: title,
		text: msg,
		sticky: false,
		image: _pbaseurl+'/ui/gritter-false.png'
	});
	return _id;
};

function _ismsg_json(data) {
	return ( /^{/.test(data) );
};

/* ajax loader */
$(document).ready(function() {
	$("div#ajax-loader").center({top: "1%"});
});
var _ajaxloader_timer = null;
function _ajaxloader(show, title) {
	title = title || "<?php _t("Loading..");?>";
	show = show || false;
	if ( _ajaxloader_timer !== null ) {
		window.clearTimeout(_ajaxloader_timer);
		_ajaxloader_timer = null;
	}
	$("#ajax-loader").center({top: "1%", absolute: true}).zindex().html(title);
	if ( show ) {
		$("#ajax-loader").show();
	} else {
		_ajaxloader_timer = window.setTimeout(function() {
			$("#ajax-loader").css("z-index","1").hide().empty();
		}, 900);
	}
};

var _ajaxmsg_timer = null;
function _ajaxmsg(msg, timeout) {
	timeout = timeout || 1000;
	if ( _ajaxmsg_timer !== null ) {
		window.clearTimeout(_ajaxmsg_timer);
		_ajaxmsg_timer = null;
	}
	$("#ajax-msg").center({top: "1%"}).zindex().html(msg).show();
	_ajaxmsg_timer = window.setTimeout(function() {
			$("#ajax-msg").css("z-index","1").hide().empty();
		}, 1000);
};

$.ajaxSetup({
	global: true,
	async: true,
	cache: false,
	timeout: <?php echo ( @ini_get('max_execution_time') > 0 ? @ini_get('max_execution_time') * 1000 : 3600000 );?>,
	headers: { "X-AJAX-REQUEST": "OK" },
	beforeSend: function() {
		if ( !$.support.placeholder ) {
			$("input[placeholder], textarea[placeholder]").each(function() {
				var _text = $.trim($(this).attr("value")) || null, _ptext = $(this).attr("placeholder") || null;
				if ( _text != null && _ptext != null ) {
					if ( _text == _ptext ) {
						$(this).attr("value", "");
					}
				}
				
			});
		}
	},
	success: function() {
		_fixjs();
	},
	complete: function() {
		_fixjs();
	},
	error: function(e, s, t) {
		_fixjs();
		var msg="";
        if ( s === "timeout" ) {
            _gerror(s);
        }
        /*
		if ( s !== null ) {
			msg += "Status: "+s+"<br>";
		}

		if ( !$.isEmptyObject(t) ) {
			msg += "Error: "+print_r(t,true)+"<br>";
		}
		_gerror(msg);*/
	}
});

function _redirect(_url, _replace) {
	_replace = _replace || false;
	if ( _replace ) {
		self.location.replace(_url);
		return;
	}
	self.location.href = _url;
};

function preload_image(data) {
	if ( data instanceof Array ) {
		$(window).load(function() {
			$.each(data, function(n,f) {
				var _p = new Image();
				_p.src = _pbaseurl+'/'+f;
			});
		});
	}
};
<?php echo _preload_images();?>

function _pause(ms) {
	var date = new Date(), curDate = null;
	do{curDate = new Date();}
	while(curDate - date < ms);
};

function _page(obj,arg, func) {
	func = func || null;
	$.ajaxSetup({
			global: false,
			async: true,
			cache: true,
			beforeSend: function() {
				_ajaxloader(true);
			},
			success: function() {
				_ajaxloader(false);
			},
			complete: function() {
				_ajaxloader(false);
			}
		});

	$(obj).load(_index, arg, function() {
		if ( func !== null ) {
			func(obj);
		} else {
			$(this).show();
		}
		_fixjs();
	});
};

function check_valid_email(email) {
	var m=/^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,3}|[0-9]{1,3})(\]?)$/;
	if ( email.match(m) == null ) {
		return false;
	}
	return true;
};


/* win popup */
function _popup(w,h,u) {
	var pop = window.open(u,'popup','width='+w+',height='+h+',scrollbars=no,resizable=no,toolbar=no,directories=no,location=no,menubar=no,status=no,left=0,top=0');
	if ( !pop ) {
		_gerror("<?php _t("Please allow window popup!");?>");
	}
	if ( !pop ) {
		_gerror("<?php _t("Please allow window popup!");?>");
		return false;
	}
	pop.focus();
	return true;
};
function _popupfull(u,t) {
	t = t || 'site';
	var w = $(window).width();
	var h = $(window).height();
	var pop = window.open(u,t,'width='+w+',height='+h+',fullscreen=yes,scrollbars=yes,resizable=yes,toolbar=no,directories=no,location=no,menubar=no,status=no,left=0,top=0');
	if ( !pop ) {
		_gerror("<?php _t("Please allow window popup!");?>");
		return false;
	}
	pop.focus();
	return true;
};

function _popupnewin(u,t) {
	t = t || 'new';
	var pop = window.open(u,t);
	if ( !pop ) {
		_gerror("<?php _t("Please allow window popup!");?>");
		return false;
	}
	pop.focus();
	return true;
};

function _winreload() {
	window.location.reload();
};

function _removeckeditor() {
	if ( typeof(CKEDITOR) != 'undefined' && CKEDITOR.instances) {
		for (var name in CKEDITOR.instances) {
			CKEDITOR.instances[name].destroy(true);
			delete CKEDITOR.instances[name];
		}
	}
};

/* workaround for maxHeight issue: http://bugs.jqueryui.com/ticket/4820 */
function _dialog_maxheight(obj,height) {
	obj.find('.dialog_max_height').css('max-height',height+'px');
};

function _confirm(msg,func_yes,func_no, title) {
	var _pid = "#dialog";
	title = title || "<?php _t("Confirmation");?>";
	func_yes = func_yes || null;
	func_no = func_no || null;
	var _height = $(document).height() - 20;
	var $pid = $(_pid);
	$pid.html(msg).dialog({
		modal: true,
		position: ["center", 150],
		title: title,
		buttons: {
			"<?php _t("Ya");?>": function() {
				if ( func_yes !== null ) {
					func_yes();
				}
				$pid.dialog("close");
			},
			"<?php _t("Tidak");?>": function() {
				if ( func_no !== null ) {
					func_no();
				}
				$pid.dialog("close");
			}
		},
		open: function() {
			var $this = $(this);
			_dialog_maxheight($this,_height);
			$this.parent().find('.ui-dialog-buttonpane button:contains("<?php _t("Ya");?>")').button({
				icons: { primary: 'ui-icon-circle-check' }
			});
			$this.parent().find('.ui-dialog-buttonpane button:contains("<?php _t("Tidak");?>")').button({
				icons: { primary: 'ui-icon-circle-close' }
			});
		},
		close: function() {
			$pid.empty().dialog("destroy");
		}
	});
};


/* input next focus */
$.extend($.expr[':'], {
	focusable: function(element) {
			var nodeName = element.nodeName.toLowerCase(), tabIndex = $.attr(element, 'tabindex');
			return (/input|select|textarea|button|object/.test(nodeName) ? !element.disabled : 'a' == nodeName || 'area' == nodeName ? element.href || !isNaN(tabIndex) : !isNaN(tabIndex)) && !$(element)['area' == nodeName ? 'parents' : 'closest'](':hidden').length;
		}
});

$("input[type=text], input[type=password]").live("keydown",function(e) {
	e = e || window.event;
	var knum = document.all ? e.keyCode : e.which;
	var $this = $(this), $focusables = $(':focusable'), current = $focusables.index(this), $next;
	if (knum == '13') {
		$next = $focusables.eq(current+1).length ?$focusables.eq(current+1) : $focusables.eq(0);
		$next.focus();
	}
});

/** resize tab width **/
function _xtab_resize() {
	$("div.ui-tabs").css("width","auto");
	var _xt = parseInt($("div.ui-tabs").width()) || 0;
	if ( _xt > 0 ) {
		var _xw = parseInt($("#x-table1").width()) || 0;
		if ( _xw > _xt ) {
			_xw = _xw + 100;
			$("div.ui-tabs").css("width",_xw+"px");
		}
	}
};

function isNumberKey(evt) {
evt = (evt) ? evt : event;
var charCode = (evt.which) ? evt.which : 
                 ((evt.charCode) ? evt.charCode : 
                   ((evt.keyCode) ? evt.keyCode : 0));
	if (charCode > 31 && (charCode < 48 || charCode > 57)) {
		return false;
	}
	return true;
};

<?php
$content = ob_get_contents();
ob_end_clean();
_nocache( array('Content-type' => 'application/x-javascript', 'future_expire' => true) );
exit(_minify_js($content));
?>
