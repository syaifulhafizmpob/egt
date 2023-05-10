<?php
/**
 * Survey Class
 *
 * @author Mohd Nawawi Mohamad Jamili <nawawi@rutweb.com>
 * @category library
 */

class survey {
	public function __construct() {
		if ( !_obj_caller() ) {
			_exit( _t("Error(%s): Invalid object",__CLASS__) );
		}
		$this->_pt = $GLOBALS['_pt'];
		$this->_pt->_require("user");
		$this->_pt->dbcache = false;
		$this->table = $this->_pt->table->{__CLASS__};
		$this->table_jawatan = TABLE_PREFIX."jawatan";
		$this->table_jawatan_name = $this->table_jawatan."_name";
		$this->table_jawatan_group = $this->table_jawatan."_group";
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

	public function _list_jawatan($catid) {
		return $this->_pt->get_results("select * from `".$this->table_jawatan."` where category_id='".$catid."'", ARRAY_A);
	}

	public function _getgroup_name($group_id) {
		return $this->_pt->get_var("select name from `".$this->table_jawatan_group."` where id='".$group_id."'");
	}

	public function _getjawatan_name($id) {
		return $this->_pt->get_var("select name from `".$this->table_jawatan_name."` where id='".$id."'");
	}

	public function _count_jawatan($gid,$catid) {
		return $this->_pt->get_var("select count(jawatan_id) as cnt from `".$this->table_jawatan."` where group_id='".$gid."' and category_id='".$catid."'");
	}

	public function _getyear($id) {
		$data = $this->_pt->get_row("select `year`,`month` from `".$this->table."` where `id`='".$id."'", ARRAY_A);
        $_SESSION['fyear'] = strtoupper($data['month'])." ".$data['year'];
		return strtoupper($data['month'])."-".$data['year'];
    }

	public function _getmonth($id) {
		return $this->_pt->get_var("select `month` from `".$this->table."` where `id`='".$id."'");
    }

    public function _expired($id) {
        //_adebug("select edate from `".$this->table."` where `id`='".$id."' and status='on' and edate < '".date('Y-m-d')."'");
        //echo "select edate from `".$this->table."` where `id`='".$id."' and status='on' and edate < '".date('Y-m-d')."'";
        $ex = $this->_pt->get_var("select edate from `".$this->table."` where `id`='".$id."' and status='on' and edate < '".date('Y-m-d')."'");
        return ( !_null($ex) ? true : false );
    }

	public function _getstate($id) {
		$sid = $this->_pt->get_var("select `state_id` from `".$this->table."` where id='".$id."'");
		return $this->_pt->display->_getstate_name($sid);
	}

	public function _getcurrent_id() {
		//$m = ( date('n') < 6 ? "jun" : "disember" );
        //return $this->_pt->get_var("select id from `".$this->table."` where `year`='".date('Y')."' and `month`='{$m}'");
        return $this->_pt->get_var("select id from `".$this->table."` where `status`='on'");
    }

	public function _formtitle($id, $sid = null) {
		if ( !_null($sid) ) { // pdf
			$info = $this->_pt->user->_getinfo($sid);
		} else {
			$info = $this->_pt->user->_getinfo($this->_pt->session->data->id);
		}
		$catname = $this->_pt->display->_getcategory_name($info['category_id']);
		$ftitle = $this->_pt->display->_getcategory_ftitle($info['category_id']);
		$fyear = $this->_getyear($id);
		return $ftitle." ".$fyear;
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

		if ( _null($this->_pt->request['_mr']) ) $this->_pt->request['_mr'] = 20;
		if ( _null($this->_pt->request['_pr']) ) $this->_pt->request['_pr'] = 0;

		$_pr = $this->_pt->request['_pr'];

		$max_row = $this->_pt->request['_mr'];
		$start_row=@($_pr * $max_row);

		$query = "select * from `".$this->table."` where 1";
		if ( !_null($this->_pt->request['sstr']) && !_null($this->_pt->request['sopt']) ) {
			$this->data->search = true;
			$query .=' and `'.$this->_pt->request['sopt'].'` like "%'._query_escape($this->_pt->request['sstr']).'%"'; 
		}
		$query .=" order by `ldate` DESC";

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
