<?php
@_object($this) || exit("403 Forbidden");
$this->_notlogin();
$this->fd = (object)null;
$this->fd->gid = "x-"._rand_text(3).time();
?>

<div id="x-menu-h">
<?php
$data = $this->display->_iconlist_report();
$data2 = $data;
if ( _array($data) ) {
	echo "<select name='reportdisplay' class='select'>";
	$nx = 0;
        while($row = @array_shift($data) ) {
		if ( !$this->issuperadmin ) {
			if ( !$this->user->_ingroup_type($this->session->data->id,$row['type']) ) continue;
		} else {
			$row['title'] = $row['title']." (".$row['type'].")";
		}
		$nx++;
		echo "<option value='".$row['code']."' class='ly".$row['uicon']."'>".$nx.": ".$row['title']."</option>";
        }
	echo "</select>";
}
?>
</div>

<center>
<?php
if ( _array($data2) ) {
        while($row = @array_shift($data2) ) {
                echo "<div class='x-list-m' id='".$this->fd->gid.$row['code']."'></div>";
        }
}
?>
</center>

<script type="text/javascript">
$(document).ready(function() {
        var _gridid  = '<?php _E($this->fd->gid);?>';
        <?php $this->_tpl("_report-list.js"); ?>
});
</script>



