function _fixbg(){if($(window).width()<=1024){$("html").css("background-image",_baseurl+"/rsc/bg2.jpg");$("div#bb").css("margin-top","80px");$("img#logo").attr("src",_baseurl+"/rsc/logoa.png")}else{$("html").css("background-image",_baseurl+"/rsc/bg1.jpg");$("div#bb").css("margin-top","110px");$("img#logo").attr("src",_baseurl+"/rsc/logob.png")}};$(window).bind("load resize",function(){_fixbg()});$(document).ready(function(){_fixbg();$(".button").button();$("button[name=btlogin]").button({icons:{primary:'ui-icon-key'}});function _clear(){$('input[name=uname], input[name=upass]').val('');$('input[name=uname]').focus()};_clear();$('input[name=uname], input[name=upass]').click(function(e){e=e||window.event;e.preventDefault();$(this).val('')});$("input[name=uname]").bind("keyup",function(e){e=e||window.event;var total=$(this).attr('value').length;if(total==6){$(this).attr('value',$(this).attr('value')+"-")}});$('form#login-form').submit(function(){$(this).ajaxSubmit({url:_index,type:'POST',method:'POST',dataType:'text',async:false,clearForm:false,resetForm:false,cache:false,data:{_req:'login'},beforeSubmit:function(){var _u,_p;_u=$('input[name=uname]').attr('value');_p=$.trim($('input[name=upass]').attr('value'));if(_u==''){$('input[name=uname]').focus();return false}if(_p==''){$('input[name=upass]').focus();return false}},success:function(data){if(!_ismsg_json(data)){_gerror(data);_clear();return false}data=$.evalJSON(data);if(!data.success){_gid=_gfalse(data.msg);_ghoverclose(_gid);_clear();return false}_ajaxmsg(data.msg);window.location.hash='!p';window.setTimeout(_winreload,500)}});return false});$("button[name=btlogin]").click(function(e){e=e||window.event;e.preventDefault();$('form#login-form').submit();return false})});
