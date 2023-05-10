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
		$this->_pt->_require("user");
		$this->table = $this->_pt->table->{__CLASS__};
		$this->table_report = $this->table."_report";
		$this->iconpath = $this->_pt->mydatapath."/icons";
		$this->table_state = TABLE_PREFIX."state";
		$this->table_daerah = TABLE_PREFIX."daerah";
                $this->table_category = TABLE_PREFIX."category";
                $this->table_subcategory = TABLE_PREFIX."subcategory";
		$this->table_etnik = TABLE_PREFIX."etnik";
		$this->table_alien = TABLE_PREFIX."alien";
        $this->table_respondent = TABLE_PREFIX."respondent";
	}

	public function __destruct() { 
		return true;
	}

	public function _liststate($nl = false) {
		$nl = ( $nl == true ? " where nl='0' " : "" );
		return $this->_pt->get_results("select * from `".$this->table_state."` {$nl}order by porder", ARRAY_A);
	}

	public function _getstate_name($id) {
		return $this->_pt->get_var("select name from `".$this->table_state."` where id='".$id."'");
	}

	public function _getstate_id($name) {
		return $this->_pt->get_var("select id from `".$this->table_state."` where name='".$name."'");
	}

	public function _listdaerah() {
		return $this->_pt->get_results("select * from `".$this->table_daerah."` order by porder", ARRAY_A);
	}

	public function _getdaerah_name($id) {
		return $this->_pt->get_var("select name from `".$this->table_daerah."` where id='".$id."'");
	}

	public function _getdaerah_id($name) {
		return $this->_pt->get_var("select id from `".$this->table_daerah."` where name='".$name."'");
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

	public function _listdaerah_bystate_select($state_id,$daerah_id) {
		$txt = "";
		$dt = $this->_pt->get_results("select * from `".$this->table_daerah."` where `state_id`='".$state_id."' order by porder", ARRAY_A);
		if ( _array($dt) ) {
			while( $rt = @array_shift($dt) ) {
				$txt .= "<option value='".$rt['id']."'".( $daerah_id == $rt['id'] ? " selected" : "").">".$rt['name']."</option>";
			}
		}
		return $txt;
	}

	public function _listcategory($type = null) {
		$tt = ( !_null($type) ? " where `type` = '{$type}'" : "" );
		return $this->_pt->get_results("select * from `".$this->table_category."`{$tt}", ARRAY_A);
	}

	public function _listcategory_respondent($type = null) {
		$tt = ( !_null($type) ? " where `type` = '{$type}'" : "" );
		$query = "select * from `".$this->table_category."` where 1";
		if ( !_null($type) ) {
			$query .= " and `type` = '{$type}'";
		}
		if ( !$this->_pt->isadmin ) {
			$ing = $this->_pt->user->_getgroup_sql($this->_pt->session->data->id);
			if ( !_null($ing) ) {
				$query .= " and id {$ing}";
			}
		}
		//_adebug($query);
		$query .= " order by porder";
		return $this->_pt->get_results($query, ARRAY_A);
	}

	public function _listemail_respondent($type = null) {
        $html = "";
		$data = $this->_listcategory_respondent($type);
        if ( _array($data) ) {
            while( $rt = @array_shift($data) ) {
                //echo "select id,company,nolesen,email from `{$this->table_respondent}` where email !='' and category_id='".$rt['id']."'<br>";
                $dt = $this->_pt->get_results("select id,company,nolesen,email from `{$this->table_respondent}` where email !='' and category_id='".$rt['id']."'", ARRAY_A);
                if ( _array($dt) ) {
                    while( $do = @array_shift($dt) ) {
                        $html .= "<option value='".$do['id']."'>".$do['company']." - ".$do['nolesen']."</option>";
                    }
                }
            }
        }
        return $html;
	}

	public function _getcategory_name($id) {
		return $this->_pt->get_var("select `name` from `".$this->table_category."` where id='".$id."'");
	}

	public function _getcategory_type($id) {
		return $this->_pt->get_var("select `type` from `".$this->table_category."` where id='".$id."'");
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

	public function _listsubcategory($type = null) {
		$tt = ( !_null($type) ? " where `type` = '{$type}'" : "" );
		return $this->_pt->get_results("select * from `".$this->table_subcategory."`{$tt}", ARRAY_A);
	}

	public function _getsubcategory_name($id) {
		return $this->_pt->get_var("select `name` from `".$this->table_subcategory."` where id='".$id."'");
	}

	public function _geticon($id) {
                return $this->_pt->get_var("select icon from `".$this->table."` where `id`='".$id."'");
        }

        public function _icon($data) {
                $fd = new nwimage();
                if ( !_null($data['id']) ) {
                        $img = $this->_geticon($data['id']);
                        $img = $this->iconpath."/".$img;
                        if ( !is_dir($img) && file_exists($img) ) {
                                $fd->load($img);
                                $fd->show();
                        }
                }
                $fd->string("64","64"," ");
        }

        public function _iconlist() {
		$query = "select * FROM `".$this->table."` order by `order` ASC";
                return $this->_pt->get_results($query, ARRAY_A);
        }

        public function _iconlist_report() {
                $query = "select * FROM `".$this->table_report."` order by `order` ASC";
                return $this->_pt->get_results($query, ARRAY_A);
        }

}

?>
