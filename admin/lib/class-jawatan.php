<?php
/**
 * Data Jawatan Class
 *
 * @author Mohd Nawawi Mohamad Jamili <nawawi@rutweb.com>
 * @category library
 */

class jawatan {
	public $delhistory = false;
	public function __construct() {
		if ( !_obj_caller() ) {
			_exit( _t("Error(%s): Invalid object",__CLASS__) );
		}
		$this->_pt = $GLOBALS['_pt'];
		$this->_pt->dbcache = false;
		$this->table = $this->_pt->table->{__CLASS__};
		$this->table_jawatan = $this->table."_name";
		$this->table_group = $this->table."_group";
		$this->table_category = TABLE_PREFIX."category";
	}

	public function __destruct() { 
		return true;
	}

	public function _getinfo($id, $escape = false) {
		$data = $this->_pt->get_row("select * from `".$this->table."` where `id`='".$id."'", ARRAY_A);
		if ( $escape && _array($data) ) {
			$data = array_map_recursive('_htmlspecialchars',$data);
		}
		return $data;
	}

	public function _list_group() {
		return $this->_pt->get_results("select * from `".$this->table_group."`", ARRAY_A);
	}

	public function _list_group_kilang() {
		return $this->_pt->get_results("select * from `".$this->table_group."` where id!='5'", ARRAY_A);
	}

	public function _list_group_peniaga() {
		return $this->_pt->get_results("select * from `".$this->table_group."` where id!='4'", ARRAY_A);
	}

	public function _list_name() {
		return $this->_pt->get_results("select * from `".$this->table_jawatan."`", ARRAY_A);
	}

	public function _list_bygroup($group_id,$category_id) {
		return $this->_pt->get_results("select * from `".$this->table."` where group_id='".$group_id."' and category_id='".$category_id."'", ARRAY_A);
	}

	public function _list_bygroup_ajax($data) {
        $group_id = $data['group_id']; echo $group_id;
        $category_id = $data['category_id'];
        $lst = $this->_list_bygroup($group_id,$category_id);
        $html = "";
        if ( _array($lst) ) {
            $skip = array();
			$html .= "<option value='all'".( !_null($data['jid']) && $data['jid'] == 'all' ? " selected" : "" ). ">All</option>";
            while( $rt = @array_shift($lst) ) {
                $name = $this->_getname($rt['jawatan_id']);
                if ( !_null($skip[$name]) ) continue;
                $skip[$name] = 1;
                $html .= "<option value='".$rt['jawatan_id']."'".( !_null($data['jid']) && $data['jid'] == $rt['jawatan_id'] ? " selected" : "" ).">".$name."</option>";
            }
        }  // repair report 17p - modified 07/10/2016
		elseif ($group_id=='all'){
			$html .= "<option value='all'".($data['jid'] == 'all' ? " selected" : "" ). ">All</option>";
		}
        _exit($html);
    }
	
	public function _list_bypeniaga($group_id) {
		//return $this->_pt->get_results("select * from `".$this->table."` where group_id='".$group_id."' and ( category_id='2' or category_id='3' or category_id='4' ) group by jawatan_id", ARRAY_A);
        //$sql = "SELECT r_jawatan.* FROM `".$this->table."`,`".$this->table_category."` where r_jawatan.group_id='".$group_id."' ";
        //$sql .= "and r_jawatan.category_id = r_category.id and r_category.type = 'peniaga' group by r_jawatan.jawatan_id order by r_jawatan.id";
		
		$sql = "SELECT r_jawatan.* FROM `".$this->table."`,`".$this->table_category."` where r_jawatan.group_id='".$group_id."' ";
		$sql .= "and r_jawatan.category_id = r_category.id  and r_category.type = 'peniaga' group by r_jawatan.jawatan_id order by r_jawatan.id"; 
        return $this->_pt->get_results($sql, ARRAY_A); 
	}
	
	public function _list_bypeniaga_tanaman_bygroup($group_id) {
        $sql = "SELECT r_jawatan.* FROM `".$this->table."`,`".$this->table_category."` where r_jawatan.group_id='".$group_id."' ";
        $sql .= "and r_jawatan.category_id = '04' and r_category.type = 'peniaga' group by r_jawatan.jawatan_id order by r_jawatan.group_id";
        return $this->_pt->get_results($sql, ARRAY_A);
	}

	public function _list_bykilang($group_id) {
		//return $this->_pt->get_results("select * from `".$this->table."` where group_id='".$group_id."' and ( category_id='2' or category_id='3' or category_id='4' ) group by jawatan_id", ARRAY_A);
        $sql = "SELECT r_jawatan.* FROM `".$this->table."`,`".$this->table_category."` where r_jawatan.group_id='".$group_id."' ";
        $sql .= "and r_jawatan.category_id = r_category.id and r_category.type = 'kilang' group by r_jawatan.jawatan_id order by r_jawatan.id";
        return $this->_pt->get_results($sql, ARRAY_A);
	}

	public function _list_bykilang_all() {
        $sql = "SELECT r_jawatan.* FROM `".$this->table."`,`".$this->table_category."` where 1 ";
        $sql .= "and r_jawatan.category_id = r_category.id and r_category.type = 'kilang' group by r_jawatan.jawatan_id order by r_jawatan.id";
        return $this->_pt->get_results($sql, ARRAY_A);
	}

	public function _list_bykilang_cat($cat_id) {
        $sql = "SELECT r_jawatan.* FROM `".$this->table."`,`".$this->table_category."` where 1 ";
        $sql .= "and r_jawatan.category_id = '".$cat_id."' ";
        $sql .= "and r_category.type = 'kilang' group by r_jawatan.jawatan_id order by r_jawatan.id";
        return $this->_pt->get_results($sql, ARRAY_A);
	}

	public function _list_bypeniaga_cat($cat_id) {
        $sql = "SELECT r_jawatan.* FROM `".$this->table."`,`".$this->table_category."` where 1 ";
        $sql .= "and r_jawatan.category_id = '{$cat_id}' and r_category.type = 'peniaga' group by r_jawatan.jawatan_id order by r_jawatan.group_id";
        return $this->_pt->get_results($sql, ARRAY_A);
	}

	public function _list_bypeniaga_all() {
        $sql = "SELECT r_jawatan.* FROM `".$this->table."`,`".$this->table_category."` where 1 ";
        $sql .= "and r_jawatan.category_id = r_category.id and r_category.type = 'peniaga' group by r_jawatan.jawatan_id order by r_jawatan.group_id";
        return $this->_pt->get_results($sql, ARRAY_A);
	}

	public function _list_bypeniaga_buah() {
        $sql = "SELECT r_jawatan.* FROM `".$this->table."`,`".$this->table_category."` where 1 ";
        $sql .= "and r_jawatan.category_id = '02' and r_category.type = 'peniaga' group by r_jawatan.jawatan_id order by r_jawatan.group_id";
        return $this->_pt->get_results($sql, ARRAY_A);
	}

	public function _list_bypeniaga_minyak() {
        $sql = "SELECT r_jawatan.* FROM `".$this->table."`,`".$this->table_category."` where 1 ";
        $sql .= "and r_jawatan.category_id = '03' and r_category.type = 'peniaga' group by r_jawatan.jawatan_id order by r_jawatan.id";
        return $this->_pt->get_results($sql, ARRAY_A);
	}

	public function _list_bypeniaga_tanaman() {
        $sql = "SELECT r_jawatan.* FROM `".$this->table."`,`".$this->table_category."` where 1 ";
        $sql .= "and r_jawatan.category_id = '04' and r_category.type = 'peniaga' group by r_jawatan.jawatan_id order by r_jawatan.group_id";
        return $this->_pt->get_results($sql, ARRAY_A);
	}

	public function _getgroup_name($group_id) {
		return $this->_pt->get_var("select name from `".$this->table_group."` where id='".$group_id."'");
	}

	public function _getname($id) {
		return $this->_pt->get_var("select name from `".$this->table_jawatan."` where id='".$id."'");
	}

	public function _save($data) {
		unset($data['_post'], $data['_what']);
		if ( _null($data['category_id']) ) {
			$this->_pt->json_return(false, _tr("Sila pilih kategori!" ));
		}

		if ( _null($data['group_id']) ) {
			$this->_pt->json_return(false, _tr("Sila pilih grup!" ));
		}

		if ( _null($data['jawatan_id']) ) {
			$this->_pt->json_return(false, _tr("Sila pilih jawatan!" ));
		}

		if ( $this->_pt->check_field($this->table, "jawatan_id", array("jawatan_id" => $data['jawatan_id'], "group_id" => $data['group_id'], "category_id" => $data['category_id']) ) ) {
			$this->_pt->json_return(false, _tr("Data telash sedia ada!") );
		}

		$data['cdate'] = date('Y-m-d H:i:s');
		$data['ldate'] = date('Y-m-d H:i:s');


		if ( $this->_pt->insert($this->table, $data ) != false && _null($this->_pt->last_error) ) {
			$this->_pt->json_return(true, _tr("Data dikemaskini!") );
		}
		$this->_pt->json_return(false, _tr("Kemaskini data tidak berjaya!"));
	}

	public function _delete($data) {
		unset($data['_post'], $data['_what']);
		$fd = $data;
		if ( !_array($fd['id']) && _null($fd['id']) ) $this->_pt->json_return(false, _tr("Permintaan tidak sah!"));

		if ( !_array($fd['id']) && !_null($fd['id']) ) {
			if ( $this->_pt->query("delete from `".$this->table."` where `id`='".$data['id']."'") ) {
				$this->_pt->json_return(true, _tr("Data dikemaskini!") );
			}
		} else {
			$query = "delete from `".$this->table."` where 1 and ";
			while( $id = array_shift($fd['id']) ) {
				$query .= sprintf("(id='%d') or ", $id);
			}
			$query = preg_replace("/\s+or\s+$/","", $query);
			if ( $this->_pt->query($query) ) {
				$this->_pt->json_return(true, _tr("Data dikemaskini!") );
			}
		}
		$this->_pt->json_return(false, _tr("Kemaskini data tidak berjaya!"));
	}

	public function _update($data) {

		unset($data['_post'], $data['_what']);
		$save = array();

		if ( _null($data['id']) ) $this->_pt->json_return(false, _tr("Permintaan tidak sah!"));

		if ( _null($data['category_id']) ) {
			$this->_pt->json_return(false, _tr("Silih pilih kategori!" ));
		}

		if ( _null($data['group_id']) ) {
			$this->_pt->json_return(false, _tr("Sila pilih grup!" ));
		}

		if ( _null($data['jawatan_id']) ) {
			$this->_pt->json_return(false, _tr("Sila pilih jaawatan!" ));
		}

		$this->_pt->query("select login from `".$this->table."` where id!='".$data['id']."' and category_id='".$data['category_id']."' and group_id='".$data['group_id']."' and jawatan_id='".$data['jawatan_id']."'", true);
		if ( $this->_pt->num_rows > 0 ) {
			$this->_pt->json_return(false, _tr("Data telash sedia ada!") );
		}

		$save = $data;
		$save['ldate'] = date('Y-m-d H:i:s');

		if ( _array($save) ) {
			if ( $this->_pt->update($this->table, $save, array('id' => $data['id'] ) ) != false ) {
				$this->_pt->json_return(true, _tr("Data dikemaskini!"));
			}
		}
		$this->_pt->json_return(false, _tr("No data to be updated!"));
	}


	public function _getlist() {
		$this->data = (object)null;
		$this->data->data = array();
		$this->data->num = null;
		$this->data->first = null;
		$this->data->last = null;
		$this->data->next = null;
		$this->data->prev = null;
		$this->data->search = false;

		if ( _null($this->_pt->request['_mr']) ) $this->_pt->request['_mr'] = 100;
		if ( _null($this->_pt->request['_pr']) ) $this->_pt->request['_pr'] = 0;

		$_pr = $this->_pt->request['_pr'];

		$max_row = $this->_pt->request['_mr'];
		$start_row=@($_pr * $max_row);

		$query = "select * from `".$this->table."` where 1";
		if ( !_null($this->_pt->request['sstr']) && !_null($this->_pt->request['sopt']) ) {
			$this->data->search = true;
			$query .=' and `'.$this->_pt->request['sopt'].'` like "%'._query_escape($this->_pt->request['sstr']).'%"'; 
		}
		$query .=" order by `id`,`cdate` DESC";

		$query_limit = sprintf("%s limit %d, %d", $query, $start_row, $max_row);

		$this->_pt->dbcache = false;

		$this->_pt->query($query, true);
		$this->data->total = $this->_pt->num_rows;

		$data = $this->_pt->get_results($query_limit,ARRAY_A);
		
		if ( _null($this->_pt->request['_pg']) ) {
			$page_total = @ceil( $this->data->total / $max_row );
		} else {
			$page_total = $this->_pt->request['_pg'];
		}

		$this->data->data = $data;
		$this->data->pr = $_pr;
		$this->data->pg = $page_total;
		$this->data->cnt = $this->data->pr;
		$this->data->maxrow = $max_row;
		$this->data->sstr = ( !_null($this->_pt->request['sstr']) ? $this->_pt->request['sstr'] : "" );

		if ( $this->data->pr == 0 ) {
			$this->data->cnt = 1;
		} else {
			$this->data->cnt = $this->data->pr * $max_row + 1;
		}

		if ( $_pr > 0 ) {
			$this->data->first = json_encode(array('_pr' => 0, '_pg' => $page_total, '_sstr' => $this->data->sstr, '_sopt' => $this->data->sopt));
			$this->data->prev = json_encode(array('_pr' => max(0, $_pr - 1), '_pg' => $page_total, '_sstr' => $this->data->sstr, '_sopt' => $this->data->sopt));
		}

		if ( $_pr < $page_total ) {
			$this->data->next = json_encode(array('_pr' => min($page_total - 1, $_pr + 1), '_pg' => $page_total, '_sstr' => $this->data->sstr, '_sopt' => $this->data->sopt));
        		$this->data->last = json_encode(array('_pr' => $page_total - 1, '_pg' => $page_total, '_sstr' => $this->data->sstr, '_sopt' => $this->data->sopt));
		}
	}


}

?>
