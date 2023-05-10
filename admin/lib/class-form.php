<?php
/**
 * form Class
 *
 * @author Mohd Nawawi Mohamad Jamili <nawawi@rutweb.com>
 * @category library
 */

class form {
	public function __construct() {
		if ( !_obj_caller() ) {
			_exit( _t("Error(%s): Invalid object",__CLASS__) );
		}
		$this->_pt = $GLOBALS['_pt'];
		$this->_pt->_require("user");
		$this->_pt->dbcache = false;
		$this->table_respondent = TABLE_PREFIX."respondent";
		$this->table_survey = TABLE_PREFIX."survey";
		$this->table = TABLE_PREFIX."record_meta";

		$this->pstatus = array(
			"belum_mula" => "Belum Mula",
			"mula_lapor" => "Mula Lapor",
			"telah_diterima" => "Telah diterima",
			"telah_diproses" => "Telah diproses",
			"terkecuali" => "Terkecuali"
		);
	}

	public function __destruct() { 
		return true;
	}

	public function _getinfo2($id) {
		return $this->_pt->get_row("select * from `".$this->table."` where `id`='".$id."'", ARRAY_A);
	}

	public function _getinfo($id) {
		return $this->_pt->get_row("select * from `".$this->table_respondent."` where `id`='".$id."'", ARRAY_A);
	}

	public function _getlogin($id) {
		return $this->_pt->get_var("select login from `".$this->table_respondent."` where `id`='".$id."'");
	}

	public function _getname($id) {
		return $this->_pt->get_var("select name from `".$this->table_respondent."` where `id`='".$id."'");
	}

	public function _getstatus($id) {
		$survey_id = $this->_pt->survey->_getcurrent_id();
		$status = $this->_pt->get_var("select status from `".$this->table."` where `respondent_id`='".$id."' and survey_id='".$survey_id."'");
		return ( !_null($status) ? $status : "belum mula" );
	}

	public function _getrespondent_id_byopt($field,$value) {
		return $this->_pt->get_var("select id from `".$this->table_respondent."` where `{$field}` like \"%{$value}%\" limit 1");
	}

	public function _getrespondent_id_byopt_single($field,$value) {
		return $this->_pt->get_var("select id from `".$this->table_respondent."` where `{$field}`='{$value}'");
	}

	public function _getrespondent_id_byopt_single_all($field,$value) {
		$ret = "";
		$data = $this->_pt->get_results("select id from `".$this->table_respondent."` where `{$field}`='{$value}'", ARRAY_A);
		if ( _array($data) ) {
			while( $rt = @array_shift($data) ) {
				$ret .= "'".$rt['id']."',";	
			}
		}
		$ret = rtrim($ret,",");
		return ( !_null($ret) ? "({$ret})" : null );
	}

	public function _getsurvey_id_byopt($field,$value) {
		return $this->_pt->get_var("select id from `".$this->table_survey."` where `{$field}` like \"%{$value}%\" limit 1");
	}

	public function _update($data) {
		unset($data['_post'], $data['_what']);

		$save = array(); 

		if ( _null($data['id']) ) $this->_pt->json_return(false, _tr("Permintaan tidak sah!"));

		if ( $data["status"] == "telah_diterima" || $data["status"] == "telah_diproses" || $data["status"] == "terkecuali" ) {
			$data["submit"] = "yes";
		} else {
			$data["submit"] = "no";
		}

		$save = $data;

		//$save['ldate'] = date('Y-m-d H:i:s');
		if ( $this->_pt->update($this->table, $save, array('id' => $data['id'] ) ) != false ) {
            $rid = $this->_pt->get_var("select respondent_id from `".$this->table."` where `id`='".$data['id']."'"); 
			$this->_pt->tasklogs->_add("status laporan","Kemaskini status kepada ".$this->pstatus[$data["status"]], $rid);
			$this->_pt->json_return(true, _tr("Data dikemaskini!"));
			
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

		if ( _null($this->_pt->request['_mr']) ) $this->_pt->request['_mr'] = 50;
		if ( _null($this->_pt->request['_pr']) ) $this->_pt->request['_pr'] = 0;

		$_pr = $this->_pt->request['_pr'];

		$max_row = $this->_pt->request['_mr'];
		$start_row=@($_pr * $max_row);

		$query = "select * from `".$this->table."` where 1";
		if ( !_null($this->_pt->request['survey_id']) ) {
			$this->data->search = true;
			$query .= " and survey_id='".$this->_pt->request['survey_id']."'";
		}
		if ( !$this->_pt->isadmin || preg_match("/^admin_/", $this->_pt->session->data->level) ) {
			$ing = $this->_pt->user->_getgroup_sql($this->_pt->session->data->id);
			if ( !_null($ing) ) {
				$query .= " and category_id {$ing}";
			}
		}
		if ( !_null($this->_pt->request['sstr']) && !_null($this->_pt->request['sopt']) ) {
			$this->data->search = true;
			if ( $this->_pt->request['sopt'] == "company"
				|| $this->_pt->request['sopt'] == "nolesen"
				) {
				//$repid = $this->_getrespondent_id_byopt($this->_pt->request['sopt'],$this->_pt->request['sstr']);
				$repid = $this->_getrespondent_id_byopt_single_all("nolesen",$this->_pt->request['sstr']);
				if ( _null($repid) ) $repid = "-1";				
				//$query .= " and `respondent_id`='".$repid."'";
				$query .= " and `respondent_id`in {$repid}";
			} elseif ( $this->_pt->request['sopt'] == "year" ) {
				$repid = $this->_getsurvey_id_byopt($this->_pt->request['sopt'],$this->_pt->request['sstr']);
				if ( _null($repid) ) $repid = "-1";
				$query .= " and `survey_id`='".$repid."'";
			} else {
				$query .=' and `'.$this->_pt->request['sopt'].'` like "%'.$this->_pt->request['sstr'].'%"'; 
			}
		}
	
		if ( !_null($this->_pt->request['state_id']) ) {
			$this->data->search = true;
			$repid = $this->_getrespondent_id_byopt_single_all("state_id",$this->_pt->request['state_id']);
			if ( !_null($repid) ) {
				$query .= " and `respondent_id` in {$repid}";
			} else {
				$query .= " and `respondent_id`='-1'";
			}
		}

		if ( !_null($this->_pt->request['category_id']) ) {
			$this->data->search = true;
			$repid = $this->_getrespondent_id_byopt_single_all("category_id",$this->_pt->request['category_id']);
			if ( !_null($repid) ) {
				$query .= " and `respondent_id` in {$repid}";
			} else {
				$query .= " and `respondent_id`='-1'";
			}
		}

		if ( !_null($this->_pt->request['subcategory_id']) ) {
			$this->data->search = true;
			$repid = $this->_getrespondent_id_byopt_single_all("subcategory_id",$this->_pt->request['subcategory_id']);
			if ( !_null($repid) ) {
				$query .= " and `respondent_id` in {$repid}";
			} else {
				$query .= " and `respondent_id`='-1'";
			}
		}

		if ( !_null($this->_pt->request['status']) ) {
			$this->data->search = true;
			$query .=" and `status` = '".$this->_pt->request['status']."'";
		}
		$query .=" order by `cdate`,`sdate` DESC";
	    //echo "$query";

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
