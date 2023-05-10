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
<li><a href="#sec1" data-code="user"><span class="ui-icon ui-icon-person"></span><?php _t("Pengguna");?></a></li>
<?php if ( $this->issuperadmin ): ?>
<li><a href="#sec2" data-code="jawatan"><span class="ui-icon ui-icon-gear"></span><?php _t("Kategori Jawatan");?></a></li>
<li><a href="#sec5" data-code="jawatan2"><span class="ui-icon ui-icon-document"></span><?php _t("Nama Jawatan");?></a></li>
<li><a href="#sec4" data-code="bangsa"><span class="ui-icon ui-icon-document"></span><?php _t("Bangsa");?></a></li>
<li><a href="#sec3" data-code="tasklogs"><span class="ui-icon ui-icon-document"></span><?php _t("Task Logs");?></a></li>
<?php endif; ?>
</ul>

<div id="sec0"></div> <!-- /sec0 -->
<div id="sec1"><div class="x-list" id="<?php _E($this->fd->gid);?>"></div> </div> <!-- /sec1 -->
<div id="sec2"><div class="x-list" id="<?php _E($this->fd->gid);?>2"></div> </div> <!-- /sec2 -->
<div id="sec3"><div class="x-list" id="<?php _E($this->fd->gid);?>3"></div> </div> <!-- /sec3 -->
<div id="sec4"><div class="x-list" id="<?php _E($this->fd->gid);?>4"></div> </div> <!-- /sec4 -->
<div id="sec5"><div class="x-list" id="<?php _E($this->fd->gid);?>5"></div> </div> <!-- /sec5 -->
</div> <!-- /sec -->

<!-- user -->
<div class="hide" id="<?php _E($this->fd->gid);?>-add"></div>
<div class="hide" id="<?php _E($this->fd->gid);?>-edit"></div>
<div class="hide" id="<?php _E($this->fd->gid);?>-info"></div>

<!-- data jawatan -->
<div class="hide" id="<?php _E($this->fd->gid);?>2-add"></div>
<div class="hide" id="<?php _E($this->fd->gid);?>2-edit"></div>
<div class="hide" id="<?php _E($this->fd->gid);?>2-info"></div>

<!-- task logs -->
<div class="hide" id="<?php _E($this->fd->gid);?>3-add"></div>
<div class="hide" id="<?php _E($this->fd->gid);?>3-edit"></div>
<div class="hide" id="<?php _E($this->fd->gid);?>3-info"></div>

<!-- bangsa -->
<div class="hide" id="<?php _E($this->fd->gid);?>4-add"></div>
<div class="hide" id="<?php _E($this->fd->gid);?>4-edit"></div>
<div class="hide" id="<?php _E($this->fd->gid);?>4-info"></div>

<!-- jawatan -->
<div class="hide" id="<?php _E($this->fd->gid);?>5-add"></div>
<div class="hide" id="<?php _E($this->fd->gid);?>5-edit"></div>
<div class="hide" id="<?php _E($this->fd->gid);?>5-info"></div>

<script type="text/javascript">
$(document).ready(function() {
	_sindex = 1;
	<?php $this->_tpl("_tab.js"); ?>
	<?php $this->_tpl("_admin-user.js"); ?>
	<?php $this->_tpl("_admin-jawatan.js"); ?>
	<?php $this->_tpl("_admin-tasklogs.js"); ?>
	<?php $this->_tpl("_admin-bangsa.js"); ?>
	<?php $this->_tpl("_admin-jawatan2.js"); ?>
});
</script>
