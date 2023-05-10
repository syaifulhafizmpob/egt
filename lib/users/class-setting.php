<?php
/**
 * Setting Class
 *
 * @author Mohd Nawawi Mohamad Jamili <nawawi@rutweb.com>
 * @category library
 */

class setting {
	public function __construct() {
		if ( !_obj_caller() ) {
			_exit( _t("Error(%s): Invalid object",__CLASS__) );
		}
		$this->_pt = $GLOBALS['_pt'];
		$this->_pt->dbcache = false;
		$this->table = $this->_pt->table->{__CLASS__};
		$this->table_meta = $this->table."_meta";
	}

	public function __destruct() { 
		return true;
	}

	public function _incategory($category_id) {
		$ret = $this->_pt->get_var("select category_id from `".$this->table_meta."` where category_id='".$category_id."'");
		return ( !_null($ret) ? true : false );
	}

	public function _getconfig($pat = null) {
		$ret = array();
		$query = "select * from `".$this->table."` where 1 ";
		if ( !_null($pat) ) $query .= "and `param` rlike '^".$pat."'";
		$data = $this->_pt->get_results($query, ARRAY_A);
		if ( _array($data) ) {
			while( $row = @array_shift($data) ) {
				if ( $row['param'] == "querydb_pass" ) {
					if ( !_null($row['value']) ) $row['value'] = _base64_decrypt($row['value'],'123');
				}
				$ret[$row['param']] = $row['value'];
			}
		}
		return $ret;
	}

	public function _updateconfig($data) {
		if ( !$this->_pt->isadmin ) {
			$this->_pt->json_return(false, _tr("Permision denied!"));
		}
		unset($data['_post'], $data['_what']);

		$match = null;
		if ( !_null($data['_match']) ) $match = $data['_match'];
		unset($data['_match']);

		if ( _array($data) ) {
			foreach($data as $param => $value ) {
				if ( !_null($match) && @preg_match("/^{$match}/", $value )) continue;

				$this->_pt->query("select `param` from `".$this->table."` where `param`='".$param."'", true);
				if ( $this->_pt->num_rows > 0 ) {
					$this->_pt->update($this->table, array( "value" => $value), array("param" => $param ) );
				} else {
					$this->_pt->insert($this->table, array("param" => $param, "value" => $value) );
				}
			}
		}
		if ( _null($this->_pt->last_error) ) {
			$this->_pt->json_return(true, _tr("Data dikemaskini!"));
		}
		$this->_pt->json_return(false, _tr("Tiada data untuk dikemaskini!"));
	}
}

?>
