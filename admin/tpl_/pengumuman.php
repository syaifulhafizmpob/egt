<?php
@_object($this) || exit("403 Forbidden");
$this->_notlogin();
$this->tplname = _tplname(__FILE__);
$this->fd = (object)null;
$this->fd->gid = "x-"._rand_text(3).time();
?>

<div id="sec">
<ul>
<li><a href="#sec0"><span class="ui-icon ui-icon-home"></span><?php _t("Laman Utama");?></a></li>
<li><a href="#sec1" data-code="text"><span class="ui-icon ui-icon-document"></span><?php _t("Pengumuman");?></a></li>
<?php if ( $this->session->data->level == "superadmin" ): ?>
<li><a href="#sec2" data-code="textadmin"><span class="ui-icon ui-icon-document"></span><?php _t("Pengumuman Admin");?></a></li>
<?php endif; ?>
</ul>

<div id="sec0"></div> <!-- /sec0 -->
<div id="sec1"><div class="x-list" id="<?php _E($this->fd->gid);?>"></div></div> <!-- /sec1 -->
<div id="sec2"><div class="x-list" id="<?php _E($this->fd->gid);?>2"></div></div> <!-- /sec2 -->
</div> <!-- /sec -->

<script type="text/javascript">
$(document).ready(function() {
    var _t = $.readCookie("cookietab");
    if ( _t === "" || _t === null || _t === 0 ) {
        $.setCookie("cookietab",1,{duration: 1});
    }
	<?php $this->_tpl("_tab.js"); ?>
    $("#sec" ).bind("tabsselect", function(e, ui) {
        if ( ui.index == "2" || ui.index == "1" ) {
            $.setCookie("cookietab",ui.index,{duration: 1});
            _winreload();
        }
    });
});
</script>
