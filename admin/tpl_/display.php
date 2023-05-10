<?php
@_object($this) || exit("403 Forbidden");
$this->_notlogin();
$this->tplname = _tplname(__FILE__);
$this->fd = (object)null;
$this->fd->gid = "x-"._rand_text(3).time();
?>
<div class="x-grid-content">
<table class="x-grid-table">
<tr>
<td class="x-grid-table-box-left">

<div class="x-grid-table-box-left-inner border-corner6px-all">
<table id="x-table2">
<tr>
<td colspan='2' class='bold'><?php _t("Account Summary");?></td>
</tr>
<tr>
<td class='left'><?php _t("Username");?></td><td>: <?php _E($this->session->data->login);?></td>
</tr>
<tr>
<td class='left'><?php _t("Peranan");?></td><td>: <?php _E($this->session->data->level);?></td>
</tr>

</table> <!-- /x-table2 -->

</div> <!-- /x-grid-table-box-left-inner -->

</td> <!-- /x-grid-table-box-left -->

<td class="x-grid-table-box-right">
<div class="ui-widget left" style="margin: 9px;">
<div class="ui-state-highlight ui-corner-all" style="padding: 0.7em;"> 
<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span> 
<span style='font-weight: solid !important; color: #000000 !important; font-size: 12px !important;'>
Welcome <?php _E($this->session->data->login);?>!
</span></p>
</div>
</div>

<?php
$data = $this->{$this->tplname}->_iconlist();
if ( _array($data) ) {
        while($row = @array_shift($data) ) {
		if ( $row['level'] == "admin" && !$this->isadmin ) continue;
		echo "<div class='x-box-category border-corner6px-all' data-display='".$row['code']."' data-tooltip='"._htmlspecialchars($info['desc'])."'>";
		echo "<div class='x-box-category-icon'><img src='".$this->baseurl."/?_what=display&_post=icon&id=".$row['id']."'></div>";
		$title = ( !_null($row['title']) ? $row['title'] : $info['title'] );
		echo "<div class='x-box-category-title'>".$title."</div>";
		echo "</div>";
        }
}
?>

</td> <!-- /x-grid-table-box-right -->

</tr>
</table> <!-- /x-grid-table -->

</div> <!-- /x-grid-content -->

