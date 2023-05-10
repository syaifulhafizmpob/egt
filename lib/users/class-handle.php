<?php
/**
 * Handle Class
 *
 * Handle all request for Laman Utama Page from client to server
 *
 * @author Mohd Nawawi Mohamad Jamili <nawawi@rutweb.com>
 * @category library
 */

class handle extends core {

	public function __construct() {
		parent::__construct();
		$GLOBALS['_pt'] = &$this;
		$this->_object();
	}

	public function __destruct() { 
		parent::__destruct();
		unset($GLOBALS['_pt']);
		return true;
	}

	public function _trace_caller($caller) {
		if ( !is_callable('debug_backtrace') ) return false;
		$bt = debug_backtrace();
		$bt = array_reverse( $bt );
		foreach ( (array) $bt as $call ) {
			if ( @$call['class'] == __CLASS__ ) continue;
			if ( $call['class'] == $caller ) return true;
		}
		return false;
	}

	public function _require($namel) {
		$buf = explode(",", $namel);
		while( $name = @array_shift($buf) ) {
			if ( $this->_trace_caller($name) ) {
				continue;
			}
			if ( !@_object($GLOBALS['_pt']->$name) ) {
				$GLOBALS['_pt']->$name = new $name();
			}
		}
	}


	public function _process() {
		if ( _array($this->request) ) {
			if ( !_null($this->request['_req']) ) {
				switch($this->request['_req']) {
					case 'login':
						_exit($this->user->_checklogin());
					break;
					case 'logout':
						$this->session->logout();
						$this->_index();
					break;
					case 'tpl':
						exit($this->_tpl($this->request['_f']));
					break;
					case 'pdf':
						if ( defined('CACHEPATH') && is_dir(CACHEPATH) ) {
							$f = CACHEPATH."/pdf/".$this->request['_f'];
							if ( file_exists($f) ) {
								$fname = preg_replace("/^\d+\-/","", $this->request['_f']);
								header("Content-Type: application/pdf; charset=UTF8");
		        					header("Content-Disposition: inline; filename=\"".$fname."\"");
								@readfile($f);
								//@_unlink($f);
								exit;
							}
						}
						exit;
					break;
					case 'register':
						exit($this->user->_register($this->request));
					break;
					case 'adminview':
						$this->user->_adminview();
						$this->_index();
					break;
					case 'download':
						exit($this->msg->_download($this->request['_f']));
					break;
				}
			} // _req

			if ( !_null($this->request['_post']) && !_null($this->request['_what']) ) {
				if ( method_exists($this->{$this->request['_what']}, "_".$this->request['_post']) 
					&& @_object($this->session->data) ) {
						if ( @call_user_func_array( array(
									$this->{$this->request['_what']},
									"_".$this->request['_post']
								), array($this->request)
						) == false ) {
							$this->json_return(false, _tr("Server Error!") );
						}
				}
				$this->json_return(false, _tr("Permintaan tidak sah!") );
			} // _post

		}
	}


	public function _staginc($file, $type = null) {
		$type = ( !_null($type) ? $type : strtolower( array_pop( explode( '.', $file) ) ) );
		$file = $this->baseurl."/".$file."?".$this->_cupdate;
		switch($type) {
			case 'js':
				_E("<script type=\"text/javascript\" src=\"".$file."\"></script>");
			break;
			case 'css':
				_E("<link rel=\"stylesheet\" type=\"text/css\" href=\"".$file."\" media=\"screen, projection\">");
			break;
		}
	}

	public function _stag($data, $type = null, $sep = "|") {
		$list = explode($sep, $data);
		while($file = @array_shift($list) ) {
			$this->_staginc($file, $type);
		}
	}

	public function _rsc($file, $once = true) {
		$type = strtolower( array_pop( explode( '.', $file) ) );
		$this->_include($this->rscpath."/".$file, $type, $once);
	}


	public function _index($page = 'layout') {
		$this->page = $page;
		if ( !@_object($this->session->data) ) {
			$this->page = "login";
			if ( @_object($this->session) ) $this->session->clean();
		}
		$this->_tpl($this->page);
		exit;
	}

}

?>
