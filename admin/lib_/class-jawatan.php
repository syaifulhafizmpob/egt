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

	public function _list_name() {
		return $this->_pt->get_results("select * from `".$this->table_jawatan."`", ARRAY_A);
	}

	public function _list_bygroup($group_id,$category_id) {
		return $this->_pt->get_results("select * from `".$this->table."` where group_id='".$group_id."' and category_id='".$category_id."'", ARRAY_A);
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
