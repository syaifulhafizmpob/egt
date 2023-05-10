<?php
@_object($this) || exit("403 Forbidden");
$this->_notlogin();
$this->tplname = _tplname(__FILE__);
$this->fd = (object)null;
$this->fd->gid = "x-"._rand_text(3).time();
$this->fdb = (object)$this->user->_getinfo($this->session->data->id);
$this->_tpl("form");
?>


