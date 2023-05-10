<?php
@_object($this) && !_null($this->request['id']) || exit("403 Forbidden");
$this->_notlogin();
$fdb = (object)$this->respondent->_getinfo($this->request['id']);
?>
<div class="dialog_max_height">
belum!
</div> <!-- /dialog_max_height -->
