<?php
/**
 * Logging Class
 *
 * @author Mohd Nawawi Mohamad Jamili <nawawi@rutweb.com>
 * @category library
 */

class nwlog {
	public $enable = true;
	public function __construct() {
		if ( !_obj_caller() ) {
			_exit( _t("Error(%s): Invalid object",__CLASS__) );
		}

		$this->_pt = $GLOBALS['_pt'];
		$this->_pt->dbcache = false;

		if ( !defined('TABLE_LOGS') ) {
			$this->enable = false;
		}
		$this->table = TABLE_LOGS;

	}

	public function __destruct() {
		return true;
	}

	public function log($param, $events) {
		if ( $this->enable ) {
			return $this->_pt->insert($this->table,
						array(
							'date'=> date('Y-m-d H:i:s'),
							'param' => $param,
							'events' => $events
							)
						);
		}
		return false;
	}
}
