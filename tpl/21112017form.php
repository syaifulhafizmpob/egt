<?php
@_object($this) || exit("403 Forbidden");
$this->_notlogin();
$this->tplname = _tplname(__FILE__);
$this->survey_id = $this->survey->_getcurrent_id();
if ( _null($this->survey_id) ) exit("Invalid survey!");
$this->fd = (object)null;
$this->fd->gid = "x-"._rand_text(3).time();
$ftitle = $this->survey->_formtitle($this->survey_id); 
$submit_done = $this->record->_submit_done($this->session->data->id,$this->survey_id);
$submit_done_date = $this->record->_submit_done_date($this->session->data->id,$this->survey_id);
if ( $this->session->data->adminview ) {
	$submit_done = false;
}
?>

<table id="x-tabler" style='margin: 0px;padding:0px;width:100%;'>
<tr>
<th class="border-top border-bottom border-left border-right" colspan="2" style="font-size:14px;"><?php _E(strtoupper($ftitle));?></th>
</tr>
<tr>
<th class="border-bottom border-left" style="width:200px;">NAMA KILANG/SYARIKAT</th>
<td class="border-bottom border-left border-right"><?php _E($this->fdb->company);?></td>
</tr>
<tr>
<th class="border-bottom border-left" style="width:200px;">NO. LESEN MPOB</th>
<td class="border-bottom border-left border-right"><?php _E($this->fdb->nolesen);?></td>
</tr>
<tr>
<th class="border-bottom border-left">NEGERI PREMIS</th>
<td class="border-bottom border-left border-right"><?php _E($this->display->_getstate_name($this->fdb->state_id));?></td>
</tr>
<?php if ( $submit_done ): ?>
<tr>
<th class="border-bottom border-left">TARIKH HANTAR</th>
<td class="border-bottom border-left border-right"><?php _E($this->_output_date($submit_done_date,"%d-%m-%Y"));?></td>
</tr>
<?php endif;?>
</table>
<br>
<div id="sec">
<ul>
<li class="hide"><a href="#sec0"><span class="ui-icon ui-icon-home"></span><?php _t("Laman Utama");?></a></li>
<li><a href="#sec1" data-code="help"><span class="ui-icon ui-icon-help"></span><?php _t("Panduan");?></a></li>
<?php if ( !$submit_done ): ?>
<li><a href="#sec2" data-code="list"><span class="ui-icon ui-icon-document"></span><?php _t("Isi Borang");?></a></li>
<?php endif; ?>
<li><a href="#sec3" data-code="view"><span class="ui-icon ui-icon-zoomin"></span><?php _t("Paparan");?></a></li>
<?php if ( !$submit_done ): ?>
<li><a href="#sec4" data-code="submit"><span class="ui-icon ui-icon-check"></span><?php _t("Hantar Borang");?></a></li>
<?php endif; ?>
<!--<li><a href="#sec5" data-code="print"><span class="ui-icon ui-icon-print"></span><?php _t("Cetak");?></a></li>-->
<li><a href="#sec6" data-code="history"><span class="ui-icon ui-icon-clock"></span><?php _t("Borang terdahulu");?></a></li>
<li><a href="#sec7" data-code="msg"><span class="ui-icon ui-icon-comment"></span><?php _t("Pindaan / Pertanyaan");?></a></li>
<li><a href="#sec8" data-code="hubungi"><span class="ui-icon ui-icon-comment"></span><?php _t("Hubungi Kami");?></a></li>
</ul>

<div id="sec0"></div> <!-- /sec0 -->
<div id="sec1"><div class="x-list" id="<?php _E($this->fd->gid);?>"></div></div> <!-- /sec1 -->
<div id="sec2"><div class="x-list" id="<?php _E($this->fd->gid);?>2"></div></div> <!-- /sec2 -->
<div id="sec3"><div class="x-list" id="<?php _E($this->fd->gid);?>3"></div></div> <!-- /sec3 -->
<div id="sec4"><div class="x-list" id="<?php _E($this->fd->gid);?>4"></div></div> <!-- /sec4 -->
<div id="sec5"><div class="x-list" id="<?php _E($this->fd->gid);?>5"></div></div> <!-- /sec5 -->
<div id="sec6"><div class="x-list" id="<?php _E($this->fd->gid);?>6"></div></div> <!-- /sec6 -->
<div id="sec7"><div class="x-list" id="<?php _E($this->fd->gid);?>7"></div></div> <!-- /sec7 -->
<div id="sec8"><div class="x-list" id="<?php _E($this->fd->gid);?>8"></div></div> <!-- /sec8 -->
</div> <!-- /sec -->


<div class="hide" id="<?php _E($this->fd->gid);?>7-add"></div>
<div class="hide" id="<?php _E($this->fd->gid);?>7-edit"></div>
<div class="hide" id="<?php _E($this->fd->gid);?>7-info"></div>

<script type="text/javascript">
$(document).ready(function() {
	<?php if ( $submit_done ): ?>
	_sindex = 1;
	<?php else: ?>
	delete _sindex;
	<?php endif; ?>

	<?php $this->_tpl("_tab.js"); ?>
	<?php $this->_tpl("_form-msg.js"); ?>
	_tabselect(1);

});
</script>
