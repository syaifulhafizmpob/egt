<?php
/**
 * Record Class
 *
 * @author Mohd Nawawi Mohamad Jamili <nawawi@rutweb.com>
 * @category library
 */

class record {
	public function __construct() {
		if ( !_obj_caller() ) {
			_exit( _t("Error(%s): Invalid object",__CLASS__) );
		}
		$this->_pt = $GLOBALS['_pt'];
		$this->_pt->dbcache = false;
		$this->table = $this->_pt->table->{__CLASS__};
		$this->table_meta = $this->table."_meta";
		$this->table_etnik = $this->table."_etnik";
		$this->table_alien = $this->table."_alien";
		$this->table_respondent = TABLE_PREFIX."respondent";
		$this->table_retnik = TABLE_PREFIX."etnik";
		$this->table_ralien = TABLE_PREFIX."alien";
	}

	public function __destruct() { 
		return true;
	}


	public function _getinfo($rid, $sid, $gid, $jid) {
		$data = $this->_pt->get_row("select * from `".$this->table."` where `respondent_id`='".$rid."' and `survey_id`='".$sid."' and `group_id`='".$gid."' and `jawatan_id`='".$jid."'", ARRAY_A);
		if ( $escape && _array($data) ) {
			$data = array_map_recursive('_htmlspecialchars',$data);
		}
		return $data;
	}

	public function _getinfo_respondent($state_id, $sid, $gid, $jid) {
		$resp = $this->_pt->respondent->_getidbystate_sql($state_id);
		$query = "select * from `".$this->table."` where 1";
		if ( !_null($resp) ) {
			$query .= " and `respondent_id` {$resp}";
		}
		$query .= " and `survey_id`='".$sid."' and `group_id`='".$gid."' and `jawatan_id`='".$jid."'";
		$data = $this->_pt->get_row($query, ARRAY_A);
		if ( $escape && _array($data) ) {
			$data = array_map_recursive('_htmlspecialchars',$data);
		}
		return $data;
	}



	public function _getetnik($id) {
		$ret = array();
		$data = $this->_pt->get_results("select * from `".$this->table_etnik."` where record_id='".$id."'", ARRAY_A);
		if ( _array($data) ) {
			while( $rt = @array_shift($data) ) {
				if ( _null($rt['value']) || $rt['value'] == 0 ) continue;
				$ret[$rt['name']] = $rt['value'];
			}
		}
		return $ret;
	}

	public function _getalien($id) {
		$ret = array();
		$data = $this->_pt->get_results("select * from `".$this->table_alien."` where record_id='".$id."'", ARRAY_A);
		if ( _array($data) ) {
			while( $rt = @array_shift($data) ) {
				if ( _null($rt['value']) || $rt['value'] == 0 ) continue;
				$ret[$rt['name']] = $rt['value'];
			}
		}
		return $ret;
	}

	public function _update_lainlain($id,$etnik = null,$alien = null) {
		if ( _array($etnik) ) {
			$this->_pt->query("delete from `".$this->table_etnik."` where record_id='".$id."'");
			foreach($etnik as $n => $v) {
				if ( _null($v) || $v == 0 ) continue;
				if ( _null($n) ) continue;
				$this->_pt->insert($this->table_etnik, array("record_id"=>$id,"name"=>$n,"value"=>$v));
			}
		}
		if ( _array($alien) ) {
			$this->_pt->query("delete from `".$this->table_alien."` where record_id='".$id."'");
			foreach($alien as $n => $v) {
				if ( _null($v) || $v == 0 ) continue;
				if ( _null($n) ) continue;
				$this->_pt->insert($this->table_alien, array("record_id"=>$id,"name"=>$n,"value"=>$v));
			}
		}
	}

	public function _update($data) {
		unset($data['_post'], $data['_what']);

		if ( _null($data['survey_id']) 
			|| _null($data['group_id'])
			|| _null($data['jawatan_id'])
		) {
			$this->_pt->json_return(false, "Permintaan tidak sah");
		}

		//if ( !_null($data["lainsela"]) ) $data[$data["lainsela"]] = $data["datalaina"];
		//if ( !_null($data["lainselb"]) ) $data[$data["lainselb"]] = $data["datalainb"];

		$etnik = $data['etnik'];
		$alien = $data['alien'];
		unset($data["juma"], $data["jumb"], $data["juma1"], $data["jumb1"],$data['etnik'], $data['alien']);

		$data['respondent_id'] = $this->_pt->session->data->id;
		$data['cdate'] = date('Y-m-d H:i:s');

		$save = $data;
		$id = $this->_pt->get_var("select id from `".$this->table."` where survey_id='".$data['survey_id']."' and group_id='".$data['group_id']."' and jawatan_id='".$data['jawatan_id']."'");

		if ( !_null($id) ) {
			$this->_pt->update($this->table, $save, array('id' => $id ) );
			$this->_update_lainlain($id,$etnik,$alien);
		} else {
			$this->_pt->insert($this->table, $save);
			$idx = $this->_pt->insert_id;
			$this->_update_lainlain($idx,$etnik,$alien);
		}

		$this->_lapor($data['survey_id'],$data['respondent_id']);
		$this->_pt->json_return(true, "OK");
	}

	public function _laporfirst($survey_id,$respondent_id,$category_id) {
            if ( !$this->_pt->query("select id from `$this->table_meta` where survey_id='{$survey_id}' and respondent_id='{$respondent_id}' and category_id='{$category_id}'") ) {
			    $save['status'] = "belum_mula";
			    $save['submit'] = "no";
			    $save['survey_id'] = $survey_id;
			    $save['respondent_id'] = $respondent_id;
			    $save['category_id'] = $category_id;
			    $save['sdate'] = date('Y-m-d H:i:s');
			    $save['cdate'] = date('Y-m-d H:i:s');
			    $this->_pt->insert($this->table_meta, $save);
            }
	}

	public function _lapor($survey_id,$respondent_id) {
		$id = $this->_pt->get_var("select id from `".$this->table_meta."` where survey_id='".$survey_id."' and respondent_id='".$respondent_id."'");

		$save = array();
		if ( !_null($id) ) {
			$save['status'] = "mula_lapor";
			$save['category_id'] = $this->_pt->session->data->category_id;
			$save['sdate'] = date('Y-m-d H:i:s');
			$this->_pt->update($this->table_meta, $save, array('id' => $id ) );
		} else {
			$save['status'] = "mula_lapor";
			$save['submit'] = "no";
			$save['survey_id'] = $survey_id;
			$save['respondent_id'] = $respondent_id;
			$save['category_id'] = $this->_pt->session->data->category_id;
			$save['sdate'] = date('Y-m-d H:i:s');
			$save['cdate'] = date('Y-m-d H:i:s');
			$this->_pt->insert($this->table_meta, $save);
		}
	}

	public function _submit($data) {
		unset($data['_post'], $data['_what']);
		if ( _null($data['survey_id']) 
			|| _null($data['respondent_id'])
		) {
			$this->_pt->json_return(false, "Permintaan tidak sah");
		}
		$id = $this->_pt->get_var("select id from `".$this->table_meta."` where survey_id='".$data['survey_id']."' and respondent_id='".$data['respondent_id']."'");

		$save = array();
		if ( !_null($id) ) {
			$save['submit'] = "yes";
			$save['status'] = "telah_diterima";
			$save['sdate'] = date('Y-m-d H:i:s');
			$this->_pt->update($this->table_meta, $save, array('id' => $id ) );
		} else {
			$save['submit'] = "yes";
			$save['status'] = "telah_diterima";
			$save['survey_id'] = $data['survey_id'];
			$save['respondent_id'] = $data['respondent_id'];
			$save['sdate'] = date('Y-m-d H:i:s');
			$save['cdate'] = date('Y-m-d H:i:s');
			$this->_pt->insert($this->table_meta, $save);
		}
		$this->_pt->json_return(true, "Ok");
	}

	public function _submit_done($respondent_id,$survey_id) {
		$var = $this->_pt->get_var("select `submit` from `".$this->table_meta."` where survey_id='".$survey_id."' and respondent_id='".$respondent_id."'");
		return ( !_null($var) && $var == "yes" ? true : false );
	}

	public function _submit_done_date($respondent_id,$survey_id) {
		return $this->_pt->get_var("select `sdate` from `".$this->table_meta."` where survey_id='".$survey_id."' and respondent_id='".$respondent_id."' and `submit`='yes'");
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

		$query = "select * from `".$this->table_meta."` where `respondent_id`='".$this->_pt->session->data->id."' and `submit`='yes'";
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

	/* report */
	public function _report1($cat_id,$survey_id) {
        $etnik = $this->_pt->get_results("select name from `".$this->table_retnik."`", ARRAY_A);
        $alien = $this->_pt->get_results("select name from `".$this->table_ralien."`", ARRAY_A);
		$sql = "SELECT sum({$this->table}.melayu+{$this->table}.cina+{$this->table}.india) as local,";
		$sql .= "sum({$this->table}.melayu) as melayu, sum({$this->table}.cina) as cina, sum({$this->table}.india) as india,";
		$sql .= "(select sum(value) from {$this->table_etnik} where record_id={$this->table}.id) as local2,";
		$sql .= "sum({$this->table}.indonesia+{$this->table}.bangladesh+{$this->table}.nepal) as alien,";
		$sql .= "sum({$this->table}.indonesia) as indonesia, sum({$this->table}.bangladesh) as bangladesh, sum({$this->table}.nepal) as nepal,";
		$sql .= "(select sum(value) from {$this->table_alien} where record_id={$this->table}.id) as alien2";
        if ( _array($etnik) ) {
            $seq = "";
            while( $eq = @array_shift($etnik) ) {
		        $seq .= "(select sum(value) from {$this->table_etnik} where record_id={$this->table}.id and `name`='{$eq['name']}') as `".strtolower($eq['name'])."`,";
            }
            $seq = rtrim($seq,",");
            if ( !_null($seq) ) $sql .= ",".$seq;
        }
        if ( _array($alien) ) {
            $seq = "";
            while( $eq = @array_shift($alien) ) {
		        $seq .= "(select sum(value) from {$this->table_alien} where record_id={$this->table}.id and `name`='{$eq['name']}') as `".strtolower($eq['name'])."`,";
            }
            $seq = rtrim($seq,",");
            if ( !_null($seq) ) $sql .= ",".$seq;
        }
        $sql .= " FROM `{$this->table}`,{$this->table_respondent} where survey_id='".$survey_id."' and {$this->table}.respondent_id = {$this->table_respondent}.id and {$this->table_respondent}.category_id='".$cat_id."'";
        //echo $sql."<br><br>";		
        return $this->_pt->get_row($sql, ARRAY_A);
	}

    public function _report2($cat_id,$survey_id) {
    }

	/*public function _report2($state_id,$survey_id) {
		$sql = "SELECT sum({$this->table}.melayu+{$this->table}.cina+{$this->table}.india) as local,";
		$sql .= "(select sum(value) from {$this->table_etnik} where record_id={$this->table}.id) as local2,";
		$sql .= "sum({$this->table}.indonesia+{$this->table}.bangladesh+{$this->table}.nepal) as alien,";
		$sql .= "(select sum(value) from {$this->table_alien} where record_id={$this->table}.id) as alien2 FROM `{$this->table}`,{$this->table_respondent} where {$this->table}.survey_id='".$survey_id."' and {$this->table}.respondent_id = {$this->table_respondent}.id";
		$sql .= " and {$this->table_respondent}.state_id = '".$state_id."'";
		return $this->_pt->get_row($sql, ARRAY_A);
	}*/

	public function _report3($cat_id,$field,$survey_id) {
		if ( $field != "lain" && $field != "local" && $field != "alien" ) {
			return $this->_pt->get_var("select sum({$field}) from {$this->table},{$this->table_respondent} where survey_id='".$survey_id."' and {$this->table}.respondent_id = {$this->table_respondent}.id and {$this->table_respondent}.category_id='".$cat_id."'");
		}
		if ( $field == "lain" ) {
			$sql = "select (select sum(value) from {$this->table_etnik} where record_id={$this->table}.id) as cnt FROM {$this->table},{$this->table_respondent} where {$this->table}.survey_id='".$survey_id."' and {$this->table}.respondent_id = {$this->table_respondent}.id and {$this->table_respondent}.category_id='".$cat_id."'";
			return $this->_pt->get_var($sql);
		}
		if ( $field == "local" ) {
			$dt = $this->_report1($group_id,$survey_id);
			return ( (int)$dt['local'] + (int)$dt['local2'] );
		}
		if ( $field == "alien" ) {
			$dt = $this->_report1($group_id,$survey_id);
			return ( (int)$dt['alien'] + (int)$dt['alien2'] );
		}
	}

	/*public function _report4($cat_id,$name,$survey_id) {
		if ( $name == "melayu" || $name == "cina" || $name == "india" ) {
			$sql = "SELECT sum({$name}) as cnt ";
		} else {
			$sql = "SELECT sum((select sum(value) from {$this->table_etnik} where record_id={$this->table}.id and name='".$name."')) as cnt ";
		}
		$sql .= "FROM {$this->table},{$this->table_respondent} where survey_id='".$survey_id."' and {$this->table}.respondent_id = {$this->table_respondent}.id and {$this->table_respondent}.category_id='".$cat_id."'";
		return $this->_pt->get_var($sql);
	}*/
	public function _report4($cat_id,$group_id,$jawatan_id,$survey_id) {
		$sql = "SELECT {$this->table}.melayu+{$this->table}.cina+{$this->table}.india as local,";
		$sql .= "(select sum(value) from {$this->table_etnik} where record_id={$this->table}.id) as local2";
		$sql .= " FROM `{$this->table}`,{$this->table_respondent} where survey_id='".$survey_id."' and {$this->table}.respondent_id = {$this->table_respondent}.id and {$this->table_respondent}.category_id='".$cat_id."' and {$this->table}.group_id='".$group_id."' and {$this->table}.jawatan_id='".$jawatan_id."'";
		return $this->_pt->get_row($sql, ARRAY_A);
	}

	/*public function _report5($cat_id,$name,$survey_id) {
		if ( $name == "indonesia" || $name == "bangladesh" || $name == "nepal" ) {
			$sql = "SELECT sum({$name}) as cnt ";
		} else {
			$sql = "SELECT sum((select sum(value) from {$this->table_alien} where record_id={$this->table}.id and name='".$name."')) as cnt ";
		}
		$sql .= "FROM {$this->table},{$this->table_respondent} where survey_id='".$survey_id."' and {$this->table}.respondent_id = {$this->table_respondent}.id and {$this->table_respondent}.category_id='".$cat_id."'";
		return $this->_pt->get_var($sql);
	}*/
	public function _report5($cat_id,$group_id,$jawatan_id,$survey_id) {
		$sql = "SELECT {$this->table}.melayu+{$this->table}.cina+{$this->table}.india as alien,";
		$sql .= "(select sum(value) from {$this->table_alien} where record_id={$this->table}.id) as alien2";
		$sql .= " FROM `{$this->table}`,{$this->table_respondent} where survey_id='".$survey_id."' and {$this->table}.respondent_id = {$this->table_respondent}.id and {$this->table_respondent}.category_id='".$cat_id."' and {$this->table}.group_id='".$group_id."' and {$this->table}.jawatan_id='".$jawatan_id."'";
		return $this->_pt->get_row($sql, ARRAY_A);
	}

	/*public function _report6($cat_id,$group_id,$jawatan_id,$survey_id) {
		$sql = "SELECT {$this->table}.melayu+{$this->table}.cina+{$this->table}.india as local,";
		$sql .= "(select sum(value) from {$this->table_etnik} where record_id={$this->table}.id) as local2";
		$sql .= " FROM `{$this->table}`,{$this->table_respondent} where survey_id='".$survey_id."' and {$this->table}.respondent_id = {$this->table_respondent}.id and {$this->table_respondent}.category_id='".$cat_id."' and {$this->table}.group_id='".$group_id."' and {$this->table}.jawatan_id='".$jawatan_id."'";
		return $this->_pt->get_row($sql, ARRAY_A);
	}*/

	public function _report6($cat_id,$group_id,$jawatan_id,$survey_id) {
		$sql = "SELECT SUM({$this->table}.gajia) as cnt";
		$sql .= " FROM `{$this->table}`,{$this->table_respondent} where survey_id='".$survey_id."' and {$this->table}.respondent_id = {$this->table_respondent}.id and {$this->table_respondent}.category_id='".$cat_id."' and {$this->table}.group_id='".$group_id."' and {$this->table}.jawatan_id='".$jawatan_id."'";
		return $this->_pt->get_var($sql);
	}

	/*public function _report7($cat_id,$group_id,$jawatan_id,$survey_id) {
		$sql = "SELECT {$this->table}.melayu+{$this->table}.cina+{$this->table}.india as alien,";
		$sql .= "(select sum(value) from {$this->table_alien} where record_id={$this->table}.id) as alien2";
		$sql .= " FROM `{$this->table}`,{$this->table_respondent} where survey_id='".$survey_id."' and {$this->table}.respondent_id = {$this->table_respondent}.id and {$this->table_respondent}.category_id='".$cat_id."' and {$this->table}.group_id='".$group_id."' and {$this->table}.jawatan_id='".$jawatan_id."'";
		return $this->_pt->get_row($sql, ARRAY_A);
	}*/

	public function _report7($cat_id,$group_id,$jawatan_id,$survey_id) {
		$sql = "SELECT SUM({$this->table}.gajib) as cnt";
		$sql .= " FROM `{$this->table}`,{$this->table_respondent} where survey_id='".$survey_id."' and {$this->table}.respondent_id = {$this->table_respondent}.id and {$this->table_respondent}.category_id='".$cat_id."' and {$this->table}.group_id='".$group_id."' and {$this->table}.jawatan_id='".$jawatan_id."'";
		return $this->_pt->get_var($sql);
	}

	public function _report8($cat_id,$group_id,$jawatan_id,$survey_id) {
		$sql = "SELECT SUM({$this->table}.gajia) as cnt";
		$sql .= " FROM `{$this->table}`,{$this->table_respondent} where survey_id='".$survey_id."' and {$this->table}.respondent_id = {$this->table_respondent}.id and {$this->table_respondent}.category_id='".$cat_id."' and {$this->table}.group_id='".$group_id."' and {$this->table}.jawatan_id='".$jawatan_id."'";
		return $this->_pt->get_var($sql);
	}

	public function _report9($cat_id,$group_id,$jawatan_id,$survey_id) {
		$sql = "SELECT SUM({$this->table}.gajib) as cnt";
		$sql .= " FROM `{$this->table}`,{$this->table_respondent} where survey_id='".$survey_id."' and {$this->table}.respondent_id = {$this->table_respondent}.id and {$this->table_respondent}.category_id='".$cat_id."' and {$this->table}.group_id='".$group_id."' and {$this->table}.jawatan_id='".$jawatan_id."'";
		return $this->_pt->get_var($sql);
	}
	public function _report10($cat_id,$survey_id) {
		$sql = "SELECT SUM({$this->table}.bp1) as bp1,SUM({$this->table}.bp2) as bp2,SUM({$this->table}.bp3) as bp3";
		$sql .= " FROM `{$this->table}`,{$this->table_respondent} where survey_id='".$survey_id."' and {$this->table}.respondent_id = {$this->table_respondent}.id and {$this->table_respondent}.category_id='".$cat_id."'";
		return $this->_pt->get_row($sql, ARRAY_A);
	}

	public function _report21($cat_id,$group_id,$survey_id) {
		$sql = "SELECT {$this->table}.melayu+{$this->table}.cina+{$this->table}.india as alien,";
		$sql .= "(select sum(value) from {$this->table_alien} where record_id={$this->table}.id) as alien2";
		$sql .= " FROM `{$this->table}`,{$this->table_respondent} where survey_id='".$survey_id."' and {$this->table}.respondent_id = {$this->table_respondent}.id and {$this->table_respondent}.category_id='".$cat_id."' and {$this->table}.group_id='".$group_id."'";
		return $this->_pt->get_row($sql, ARRAY_A);
	}

	public function _report22($cat_id,$group_id,$survey_id) {
		$sql = "SELECT {$this->table}.melayu+{$this->table}.cina+{$this->table}.india as alien,";
		$sql .= "(select sum(value) from {$this->table_alien} where record_id={$this->table}.id) as alien2";
		$sql .= " FROM `{$this->table}`,{$this->table_respondent} where survey_id='".$survey_id."' and {$this->table}.respondent_id = {$this->table_respondent}.id and {$this->table_respondent}.category_id='".$cat_id."' and {$this->table}.group_id='".$group_id."'";
		return $this->_pt->get_row($sql, ARRAY_A);
	}

}

?>
