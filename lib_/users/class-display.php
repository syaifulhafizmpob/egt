<?php
/**
 * Display Class
 *
 * @author Mohd Nawawi Mohamad Jamili <nawawi@rutweb.com>
 * @category library
 */

class display {
	public function __construct() {
		if ( !_obj_caller() ) {
			_exit( _t("Error(%s): Invalid object",__CLASS__) );
		}
		$this->_pt = $GLOBALS['_pt'];
		$this->_pt->dbcache = false;
		$this->table = $this->_pt->table->{__CLASS__}."_user";
		$this->table_state = TABLE_PREFIX."state";
		$this->table_daerah = TABLE_PREFIX."daerah";
                $this->table_category = TABLE_PREFIX."category";
		$this->table_etnik = TABLE_PREFIX."etnik";
		$this->table_alien = TABLE_PREFIX."alien";
		$this->table_staff = TABLE_PREFIX."user";
	}

	public function __destruct() { 
		return true;
	}

	public function _getinfo_staff($id) {
		return $this->_pt->get_row("select * from `".$this->table_staff."` where `id`='".$id."'", ARRAY_A);
	}

	public function _getstaff_name($id) {
		return $this->_pt->get_var("select name from `".$this->table_staff."` where id='".$id."'");
	}

	public function _liststate($nl = false) {
		$nl = ( $nl == true ? " where nl='0' " : "" );
		return $this->_pt->get_results("select * from `".$this->table_state."` {$nl}order by porder", ARRAY_A);
	}

	public function _getstate_name($id) {
		return $this->_pt->get_var("select name from `".$this->table_state."` where id='".$id."'");
	}

	public function _listdaerah() {
		return $this->_pt->get_results("select * from `".$this->table_daerah."` order by porder", ARRAY_A);
	}

	public function _getdaerah_name($id) {
		return $this->_pt->get_var("select name from `".$this->table_daerah."` where id='".$id."'");
	}

	public function _getdaerah_id_bycode($code) {
		return $this->_pt->get_var("select id from `".$this->table_daerah."` where code='".$code."'");
	}

	public function _listdaerah_bycode($code) {
		return $this->_pt->get_results("select * from `".$this->table_daerah."` where `code`='".$code."' order by porder", ARRAY_A);
	}

	public function _listdaerah_bystate_ajax($data) {
		$txt = "";
		if ( !_null($data['state_id']) ) {
			$dt = $this->_pt->get_results("select * from `".$this->table_daerah."` where `state_id`='".$data['state_id']."' order by porder", ARRAY_A);
			if ( _array($dt) ) {
				while( $rt = @array_shift($dt) ) {
					$txt .= "<option value='".$rt['id']."'>".$rt['name']."</option>";
				}
			}
		}
		exit($txt);
	}

	public function _listcategory() {
		return $this->_pt->get_results("select * from `".$this->table_category."`", ARRAY_A);
	}

	public function _getcategory_name($id) {
		return $this->_pt->get_var("select `name` from `".$this->table_category."` where id='".$id."'");
	}

	public function _getcategory_ftitle($id) {
		return $this->_pt->get_var("select `formtitle` from `".$this->table_category."` where id='".$id."'");
	}

	public function _listetnik() {
		return $this->_pt->get_results("select id,name from `".$this->table_etnik."`", ARRAY_A);
	}

	public function _listalien() {
		return $this->_pt->get_results("select id,name from `".$this->table_alien."`", ARRAY_A);
	}

}

?>
