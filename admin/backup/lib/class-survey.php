<?php
/**
 * Survey Class
 *
 * @author Mohd Nawawi Mohamad Jamili <nawawi@rutweb.com>
 * @category library
 */

class survey {
	public $delhistory = false;
	public function __construct() {
		if ( !_obj_caller() ) {
			_exit( _t("Error(%s): Invalid object",__CLASS__) );
		}
		$this->_pt = $GLOBALS['_pt'];
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

	public function _getcurrent_id() {
		//$m = ( date('n') < 6 ? "jun" : "disember" );
        //return $this->_pt->get_var("select id from `".$this->table."` where `year`='".date('Y')."' and `month`='{$m}'");
        return $this->_pt->get_var("select id from `".$this->table."` where `status`='on'");
    }

	public function _getyear($id) {
		$data = $this->_pt->get_row("select `year`,`month` from `".$this->table."` where `id`='".$id."'", ARRAY_A);
		return strtoupper($data['month'])."-".$data['year'];
        }

	public function _listsurvey() {
                return $this->_pt->get_results("select * from `".$this->table."`", ARRAY_A);
        }

	public function _formtitle($id, $sid = null) {
		if ( !_null($sid) ) { // pdf
			$info = $this->_pt->respondent->_getinfo($sid);
		} else {
			$info = $this->_pt->respondent->_getinfo($this->_pt->session->data->id);
		}
		$catname = $this->_pt->display->_getcategory_name($info['category_id']);
		$ftitle = $this->_pt->display->_getcategory_ftitle($info['category_id']);
		$fyear = $this->_getyear($id);
		return $ftitle." ".$fyear;
	}

    public function _updatestatus($id, $status) {
        if ( $status == "on" ) {
            $this->_pt->query("update `".$this->table."` set `status`='off'");
            $this->_pt->query("update `".$this->table."` set `status`='on' where id='".$id."'");
        }
    }

	public function _save($data) {
		unset($data['_post'], $data['_what']);
		if ( _null($data['year']) ) {
			$this->_pt->json_return(false, _tr("Sila masukkan tahun kaji selidik!" ));
		}

		if ( !preg_match("/^\d\d\d\d$/", $data['year']) ) {
			$this->_pt->json_return(false, _tr("Tahun kaji selidik tidak sah '%s'!", $data['year'] ));
		}

		/*if ( $this->_pt->check_field($this->table, "year", array("year" => $data['year']) ) ) {
			$this->_pt->json_return(false, _tr("Tahun kaji selidik telah ada. Sila pilih tahun lain") );
		}*/

		if ( _null($data['sdate']) ) {
			$this->_pt->json_return(false, _tr("Sila masukkan tarikh mula!" ));
		}

		if ( _null($data['edate']) ) {
			$this->_pt->json_return(false, _tr("Sila masukkan tarikh tamat!" ));
		}

		if ( _null($data['status']) ) $data['status'] = 'off';
		$data['sdate'] = $this->_pt->_input_date($data['sdate']);
		$data['edate'] = $this->_pt->_input_date($data['edate']);
		//$data['desc'] = _strip_html_tags($data['desc']);
		$data['cdate'] = date('Y-m-d H:i:s');
		$data['ldate'] = date('Y-m-d H:i:s');


		if ( $this->_pt->insert($this->table, $data ) != false && _null($this->_pt->last_error) ) {
            $this->_updatestatus($this->_pt->insert_id,$data['status']);
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

		if ( _null($data['year']) ) {
			$this->_pt->json_return(false, _tr("Sila masukkan tahun kaji selidik!" ));
		}

		if ( !preg_match("/^\d\d\d\d$/", $data['year']) ) {
			$this->_pt->json_return(false, _tr("Tahun kaji selidik tidak sah '%s'!", $data['year'] ));
		}

		/*$this->_pt->query("select `year` from `".$this->table."` where id!='".$data['id']."' and `year`='".$this->_pt->escape($data['year'])."'", true);
		if ( $this->_pt->num_rows > 0 ) {
			$this->_pt->json_return(false, _tr("Tahun kaji selidik telah ada. Sila pilih tahun lain") );
		}*/

		if ( _null($data['sdate']) ) {
			$this->_pt->json_return(false, _tr("Sila masukkan tarikh mula!" ));
		}

		if ( _null($data['edate']) ) {
			$this->_pt->json_return(false, _tr("Sila masukkan tarikh tamat!" ));
		}

        $save['year'] = $data['year'];
        $save['month'] = $data['month'];
		$save['sdate'] = $this->_pt->_input_date($data['sdate']);
		$save['edate'] = $this->_pt->_input_date($data['edate']);
		if ( _null($data['status']) ) $data['status'] = 'off';
		//if ( !_null($data['desc']) ) $save['desc'] = _strip_html_tags($data['desc']);
		$save['ldate'] = date('Y-m-d H:i:s');

        $this->_updatestatus($data['id'],$data['status']);
		if ( _array($save) ) {
			if ( $this->_pt->update($this->table, $save, array('id' => $data['id'] ) ) != false ) {
				$this->_pt->json_return(true, _tr("Data dikemaskini!"));
			}
		}
		$this->_pt->json_return(false, _tr("Tiada data untuk dikemaskini!"));
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
