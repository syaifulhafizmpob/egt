<?php
/**
 * Class Session
 *
 * This Class will handle session operations.
 *
 * @author Mohd Nawawi Mohamad Jamili <nawawi@rutweb.com>
 * @category library
 */

class session {
	public $cookie_secure = 0;
	public $session_timeout = 0; // in minute; 0 = disable
	public $session_name = '_sess';
	public $mysession = false;

	public function __construct() {
		if ( !_obj_caller() ) {
			_exit( _t("Error(%s): Invalid object",__CLASS__) );
		}
		$this->_pt = $GLOBALS['_pt'];

		if ( !defined('TABLE_SESSION') ) {
			_exit( _t("Error(%s): Session Table not defined!",__CLASS__) );
		}
		$this->table = TABLE_SESSION;

		if ( defined('SESSION_TIMEOUT') && _num(SESSION_TIMEOUT) ) {
			$this->session_timeout = SESSION_TIMEOUT;
			if ( $this->session_timeout == 0 ) {
				ini_set("session.cache_expire", 600);
				ini_set("session.gc_maxlifetime", 36000);
				ini_set("session.cookie_lifetime", 36000);
			}
		}
		if ( defined('SESSION_NAME') ) {
			@ini_set('session.name', SESSION_NAME);
			$this->session_name = SESSION_NAME;
		}
		if ( defined('SESSION_SAVEPATH') ) {
			if ( !is_dir(SESSION_SAVEPATH) ) {
				if ( file_exists(SESSION_SAVEPATH) ) @unlink(SESSION_SAVEPATH);
				_mkdir(SESSION_SAVEPATH,0777,true);
			}
			if ( is_dir(SESSION_SAVEPATH) ) {
				session_save_path(SESSION_SAVEPATH);
				ini_set('session.gc_probability', 1);
				$this->mysession = true;
				@chmod(SESSION_SAVEPATH, 0777);
			}
		}
	}

	public function __destruct() {
		return true;
	}

	public function _log($msg, $data = array()) {
		$text = "msg=".$msg." ";
		if ( _array($data) ) {
			foreach($data as $k => $v) {
				$text .= $k."=".$v." ";
			}
		}
		return $this->_pt->nwlog->log(__CLASS__, trim($text));
	}

	public function status($status = false, $msg = null, $data = array() ) {
		$this->_log($msg, $data);
		return array("success"=>$status, "msg"=>$msg);
	}

	public function start() {
		if ( $this->cookie_secure == 1 ) {
			@ini_set('session.cookie_secure','1');
		}
		return @session_start();
	}

	public function set_session($data,$_timeout = 300) {
		if ( !_array($data) ) return false;
		$_session_timeout = $_timeout;
		if ( $_timeout != 0 ) {
			$_session_timeout = time()+60 * $_timeout;
		}
		if ( $this->start() ) {
			foreach($data as $key => $value) {
				if ( $key == 'ip' ) {
					$_SESSION[$this->session_name]['oldip'] = $value;
				} else {
					$_SESSION[$this->session_name][$key] = $value;
				}
			}
			$_SESSION[$this->session_name]['ip'] = $this->_pt->uip;
			$_SESSION[$this->session_name]['time'] = time();
			$_SESSION[$this->session_name]['timeout_data'] = $_timeout;
			$_SESSION[$this->session_name]['timeout'] = $_session_timeout;
			if ( defined('SESSION_SAVEPATH') ) {
				@chmod(session_save_path()."/sess_".session_id(),0666);
			}
			return true;
		}
		return false;
	}

	public function update_session($data = array()) {
		if ( $this->start() && _array($data) ) {
			foreach($data as $key => $value) {
				$_SESSION[$this->session_name][$key] = $value;
			}
			return true;
		}
		return false;
	}

	public function checklogin() {
		if ( !_null($this->_pt->post['uname']) && !_null($this->_pt->post['upass']) ) {
			$pd = $this->_pt->get_row($this->_pt->prepare("select * from `".$this->table."` where login=%s and pass=MD5(%s)", $this->_pt->post['uname'], $this->_pt->post['upass'] ),ARRAY_A);
			if ( _array($pd) ) {
				if ( $pd['status'] != 'on' ) {
					return $this->status(false, _tr("Account Disabled!"),
								array(
									'login' => $pd['login'],
									'ip' => $this->_pt->uip,
									'uagent' => $this->_pt->uagent,
									'access' => $pd['level']
								) 
							);
				}
				if ( $this->set_session($pd, $this->session_timeout) ) {			
					$this->_pt->query(
						$this->_pt->prepare("update `".$this->table."` set lastlogin=NOW(),lastip=%s,uagent=%s where id=%d",$this->_pt->uip, $this->_pt->uagent, $pd['id'])
					);
					return $this->status(true, _tr("Access granted!"),
								array(
									'login' => $pd['login'],
									'ip' => $this->_pt->uip,
									'uagent' => $this->_pt->uagent,
									'access' => $pd['level']
								) 
							);
				}
			}
		}
		return $this->status(false, _tr("Authentication failure!"),
						array(
							'login' => $this->_pt->post['uname'],
							'password' => $this->_pt->post['upass'],
							'uagent' => $this->_pt->uagent,
							'ip' => $this->_pt->uip
						) 
					);
	}

	public function check() {	
		if ( $this->start() ) {
			if ( _array($_SESSION[$this->session_name]) ) {
				$_LOGIN = $_SESSION[$this->session_name];
				if ( ( !_null($_LOGIN['timeout']) && $_LOGIN['timeout'] !=0 ) && ( time() >= $_LOGIN['timeout'] ) ) {
					$this->_log( _tr("Session timeout!"),
							array(
								'login' => $_LOGIN['login'],
								'ip' => $_LOGIN['ip'],
								'uagent' => $this->_pt->uagent,
								'access' => $_LOGIN['level']
							)
						);
					$this->clean();
					return false;
		                }
				if ( _num($_LOGIN['timeout_data']) && $_LOGIN['timeout_data'] != 0 ) {
					$_LOGIN['timeout'] = time()+60 * $_LOGIN['timeout_data'];
					$_LOGIN['time'] = time();
					$this->update_session($_LOGIN);
				}
				return true;
			}
		}
		$this->clean();
		return false;
	}

	public function clean_garbage() {
		if ( $this->mysession ) {
                	$files = _glob(session_save_path()."/sess_*");
			if ( _array($_files) ) {
				while ( $ln = array_shift($_files) ) {
					if ( _null(_file_get($_ln)) ) @unlink($_ln);
				}
			}
		}
	}

	public function clean() {
		if ( $this->start() ) {
			@unlink(session_save_path()."/sess_".session_id());
			@session_unset();
			@session_destroy();
			@session_write_close();
		}
		$this->clean_garbage();
        }

	public function logout() {
		if ( $this->start() ) {
			if ( _array($_SESSION[$this->session_name]) ) {
				$_LOGIN = $_SESSION[$this->session_name];
				if( !_null($_LOGIN['id']) ) {
					$this->_log( _tr("Session logout"),
							array(
								'name' => $_LOGIN['name'],
								'ip' => $_LOGIN['ip'],
								'uagent' => $this->_pt->uagent,
								'access' => $_LOGIN['level']
							)
						);
				}
			}
		}
		$this->clean();
	}
}

?>
