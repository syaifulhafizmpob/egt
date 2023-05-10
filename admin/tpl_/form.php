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
<li><a href="#sec1" data-code="list"><span class="ui-icon ui-icon-contact"></span><?php _t("Status Laporan");?></a></li>
<li><a href="#sec2" data-code="email"><span class="ui-icon ui-icon-contact"></span><?php _t("Hantar Emel");?></a></li>
</ul>

<div id="sec0"></div> <!-- /sec0 -->
<div id="sec1"><div class="x-list" id="<?php _E($this->fd->gid);?>"></div></div> <!-- /sec1 -->
<div id="sec2"><div class="x-list" id="<?php _E($this->fd->gid);?>"2></div></div> <!-- /sec2 -->
</div> <!-- /sec -->

<div class="hide" id="<?php _E($this->fd->gid);?>-add"></div>
<div class="hide" id="<?php _E($this->fd->gid);?>-edit"></div>
<div class="hide" id="<?php _E($this->fd->gid);?>-info"></div>

<script type="text/javascript">
$(document).ready(function() {
	_sindex = 1;
	<?php $this->_tpl("_tab.js"); ?>
	<?php $this->_tpl("_form.js"); ?>
});
</script>
