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
		$this->table_state = TABLE_PREFIX."state";
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
		$query = "select {$this->table}.* from `".$this->table."`,{$this->table_meta} where 1";
		if ( !_null($resp) ) {
			$query .= " and {$this->table}.`respondent_id` {$resp}";
            $query .= " and {$this->table_meta}.respondent_id={$this->table}.`respondent_id`";
            $query .= " and {$this->table_meta}.status = 'telah_diproses'";
		    $query .= " and {$this->table}.`survey_id`='".$sid."' and {$this->table}.`group_id`='".$gid."' and {$this->table}.`jawatan_id`='".$jid."'";
            $query .= " group by {$this->table}.`respondent_id`";
		} else {
            $query .= " and {$this->table}.`respondent_id`='-1'";
        }
        //echo "$query;<br><br>";
		$data = $this->_pt->get_results($query, ARRAY_A);
		return $data;
	}

	public function _getinfo_respondent_fix($state_id, $sid, $gid, $jid, $cid) {
		$resp = $this->_pt->respondent->_getidbystate_sql($state_id);
        $ret = array();
		if ( !_null($resp) ) {
		    $sql = "select {$this->table}.* from `".$this->table."` where";
            $sql .= " {$this->table}.`survey_id`='".$sid."' and ";
            $sql .= " {$this->table}.`group_id`='".$gid."' and ";
            $sql .= " {$this->table}.`jawatan_id`='".$jid."'";
			$sql .= " and {$this->table}.`respondent_id` {$resp}";
            $data = $this->_pt->get_results($sql, ARRAY_A);
            if ( _array($data) ) {
                while( $rt = @array_shift($data) ) {
                    $sq = "select id from {$this->table_meta} where ";
                    $sq .= " survey_id='{$rt['survey_id']}' ";
                    $sq .= " and respondent_id='{$rt['respondent_id']}' ";
                    if ( $cid == 'kilang' ) {
                        $sq .= " and ( category_id = '1' or category_id = '5' or category_id = '6' or category_id = '7' or category_id = '8' or category_id = '9' )";
                    } else {
                        $sq .= " and category_id='{$cid}' ";
                    }
                    $sq .= " and status='telah_diproses'"; 
                    $val = $this->_pt->get_var($sq);
                    if ( !_null($val) ) {
                        $ret[] = $rt;
                    }
                }
            }
        }
        return $ret;
	}

	public function _getinfo_respondent_tot($state_id, $sid, $gid, $jid, $catid, $subcatid = null) { 
        if ( $subcatid == "undefined" ) $subcatid = null; //echo $gid;
		$resp = $this->_pt->respondent->_getidbystatebycategorybysubcategory_sql($state_id, $catid, $subcatid); 
        $data = array();
		//jika pilih kumpulan jawatan dan jawatan - modified 07/10/2016
		if ( !_null($resp) && $gid !='all' && $jid !='all' ) {
            $sql = "select {$this->table}.* from `".$this->table."`,{$this->table_meta} where 1";
			$sql .= " and {$this->table}.`respondent_id` {$resp}";
            $sql .= " and {$this->table_meta}.respondent_id={$this->table}.`respondent_id`";
            $sql .= " and {$this->table_meta}.status = 'telah_diproses'";
			$sql .= " and {$this->table_meta}.survey_id = '".$sid."'";
		    $sql .= " and {$this->table}.`survey_id`='".$sid."' and {$this->table}.`group_id`='".$gid."'";
            $sql .= " and {$this->table}.`jawatan_id`='".$jid."'";
            $sql .= " group by {$this->table}.`respondent_id`";
            //echo "$sql;<br><br>";
		    $data = $this->_pt->get_results($sql, ARRAY_A); 
		}//jika pilih jawatan='all' - modified 07/10/2016
		elseif ( !_null($resp) && $gid !='all' && $jid=='all' ) {
            $sql = "select {$this->table}.respondent_id, sum(melayu) as melayu, sum(cina) as cina, sum(india) as india,";
			$sql .= "sum(gajia) as gajia, sum(elauna) as elauna, sum(indonesia) as indonesia, ";
			$sql .= "sum(bangladesh) as bangladesh, sum(nepal) as nepal, sum(gajib) as gajib,";
			$sql .= "sum(elaunb) as elaunb, sum(bp1) as bp1, sum(bp2) as bp2, sum(bp3) as bp3,";
			$sql .= "sum(keluasan_tapak_semaian) as keluasan_tapak_semaian,";
			$sql .= "sum(juma) as juma, sum(juma1) as juma1, sum(jumb) as jumb, sum(jumb1) as jumb1 ";
			$sql .= "from `".$this->table."`,{$this->table_meta} where 1";
			$sql .= " and {$this->table}.`respondent_id` {$resp}";
            $sql .= " and {$this->table_meta}.respondent_id={$this->table}.`respondent_id`";
            $sql .= " and {$this->table_meta}.status = 'telah_diproses'";
			$sql .= " and {$this->table_meta}.survey_id = '".$sid."'";
		    $sql .= " and {$this->table}.`survey_id`='".$sid."' and {$this->table}.`group_id`='".$gid."'";
            $sql .= " group by {$this->table}.`respondent_id`";
            //echo "$sql;<br><br>";
		    $data = $this->_pt->get_results($sql, ARRAY_A); 
		}//jika pilih kumpulan jawatan='all' dan jawatan='all' - modified 07/10/2016
		elseif ( !_null($resp) && $gid =='all' && $jid=='all' ) {
            $sql = "select {$this->table}.respondent_id, sum(melayu) as melayu, sum(cina) as cina, sum(india) as india,";
			$sql .= "sum(gajia) as gajia, sum(elauna) as elauna, sum(indonesia) as indonesia, ";
			$sql .= "sum(bangladesh) as bangladesh, sum(nepal) as nepal, sum(gajib) as gajib,";
			$sql .= "sum(elaunb) as elaunb, sum(bp1) as bp1, sum(bp2) as bp2, sum(bp3) as bp3,";
			$sql .= "sum(keluasan_tapak_semaian) as keluasan_tapak_semaian,";
			$sql .= "sum(juma) as juma, sum(juma1) as juma1, sum(jumb) as jumb, sum(jumb1) as jumb1 ";
			$sql .= "from `".$this->table."`,{$this->table_meta} where 1";
			$sql .= " and {$this->table}.`respondent_id` {$resp}";
            $sql .= " and {$this->table_meta}.respondent_id={$this->table}.`respondent_id`";
            $sql .= " and {$this->table_meta}.status = 'telah_diproses'";
			$sql .= " and {$this->table_meta}.survey_id = '".$sid."'";
		    $sql .= " and {$this->table}.`survey_id`='".$sid."' ";
            $sql .= " group by {$this->table}.`respondent_id`";
            //echo "$sql;<br><br>";
		    $data = $this->_pt->get_results($sql, ARRAY_A); 
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
		//tambah _getetnik_selected utk report 17p - modified 07/10/2016
		public function _getetnik_selected($group_id, $jawatan_id,$survey_id, $respondent_id) {
		$ret = array();
		if ($group_id !='all' && $jawatan_id =='all' ) {
            $sql = "select sum({$this->table_etnik}.value) as value from `".$this->table."`,{$this->table_meta}, {$this->table_etnik} where 1";
			$sql .= " and {$this->table}.`id` = {$this->table_etnik}.record_id";
            $sql .= " and {$this->table_meta}.respondent_id={$this->table}.`respondent_id`";
            $sql .= " and {$this->table_meta}.status = 'telah_diproses'";
			$sql .= " and {$this->table_meta}.survey_id = '".$survey_id."'";
		    $sql .= " and {$this->table}.`survey_id`='".$survey_id."' and {$this->table}.`group_id`='".$gid."'";
            $sql .= " and {$this->table}.`respondent_id`='".$respondent_id."'";
			//echo "$sql;<br><br>";
			$data = $this->_pt->get_results($sql, ARRAY_A); 
		}
		if ($group_id =='all' && $jawatan_id =='all' ) {
            $sql = "select sum({$this->table_etnik}.value) as value from `".$this->table."`,{$this->table_meta}, {$this->table_etnik} where 1";
			$sql .= " and {$this->table}.`id` = {$this->table_etnik}.record_id";
            $sql .= " and {$this->table_meta}.respondent_id={$this->table}.`respondent_id`";
            $sql .= " and {$this->table_meta}.status = 'telah_diproses'";
			$sql .= " and {$this->table_meta}.survey_id = '".$survey_id."'";
		    $sql .= " and {$this->table}.`survey_id`='".$survey_id."'";
            $sql .= " and {$this->table}.`respondent_id`='".$respondent_id."'";
			//echo "$sql;<br><br>";
			$data = $this->_pt->get_results($sql, ARRAY_A); 
		}
		
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
	//tambah _getalien_selected utk report 17p - modified 07/10/2016
	public function _getalien_selected($group_id, $jawatan_id,$survey_id, $respondent_id) {
		$ret = array();
		if ($group_id !='all' && $jawatan_id =='all' ) {
            $sql = "select sum({$this->table_alien}.value) as value from `".$this->table."`,{$this->table_meta}, {$this->table_alien} where 1";
			$sql .= " and {$this->table}.`id` = {$this->table_alien}.record_id";
            $sql .= " and {$this->table_meta}.respondent_id={$this->table}.`respondent_id`";
            $sql .= " and {$this->table_meta}.status = 'telah_diproses'";
			$sql .= " and {$this->table_meta}.survey_id = '".$survey_id."'";
		    $sql .= " and {$this->table}.`survey_id`='".$survey_id."' and {$this->table}.`group_id`='".$gid."'";
            $sql .= " and {$this->table}.`respondent_id`='".$respondent_id."'";
			//echo $sql;
			$data = $this->_pt->get_results($sql, ARRAY_A); 
		}
		if ($group_id =='all' && $jawatan_id =='all' ) {
            $sql = "select sum({$this->table_alien}.value) as value from `".$this->table."`,{$this->table_meta}, {$this->table_alien} where 1";
			$sql .= " and {$this->table}.`id` = {$this->table_alien}.record_id";
            $sql .= " and {$this->table_meta}.respondent_id={$this->table}.`respondent_id`";
            $sql .= " and {$this->table_meta}.status = 'telah_diproses'";
			$sql .= " and {$this->table_meta}.survey_id = '".$survey_id."'";
		    $sql .= " and {$this->table}.`survey_id`='".$survey_id."'";
            $sql .= " and {$this->table}.`respondent_id`='".$respondent_id."'";
			//echo "$sql;<br><br>";
			$data = $this->_pt->get_results($sql, ARRAY_A); 
		}
		
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
			    //$save['sdate'] = date('Y-m-d H:i:s');
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
			//$save['sdate'] = date('Y-m-d H:i:s');
			$this->_pt->update($this->table_meta, $save, array('id' => $id ) );
		} else {
			$save['status'] = "mula_lapor";
			$save['submit'] = "no";
			$save['survey_id'] = $survey_id;
			$save['respondent_id'] = $respondent_id;
			$save['category_id'] = $this->_pt->session->data->category_id;
			//$save['sdate'] = date('Y-m-d H:i:s');
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
        $etnika = $this->_pt->get_results("select name from `".$this->table_retnik."`", ARRAY_A);
        $aliena = $this->_pt->get_results("select name from `".$this->table_ralien."`", ARRAY_A);
        $local = 0; $alien = 0; $local2 = 0; $alien2 = 0;
        foreach( array("melayu","india","cina") as $bn) {
            $local += $this->_report2($cat_id,$bn,$survey_id);
        }
        foreach( array("indonesia","bangladesh","nepal") as $bn) {
            $alien += $this->_report3($cat_id,$bn,$survey_id);
        }
        while( $rt = @array_shift($etnika) ) {
            $bn = $rt['name'];
            $local2 += $this->_report2($cat_id,$bn,$survey_id);
        }
        while( $rt = @array_shift($aliena) ) {
            $bn = $rt['name'];
            $alien2 += $this->_report3($cat_id,$bn,$survey_id);
        }
        return array("local" => $local, "local2" => $local2, "alien" => $alien, "alien2" => $alien2);
	}

	public function _report2($cat_id,$name,$survey_id) {
		if ( $name == "melayu" || $name == "cina" || $name == "india" ) {
			$sql = "SELECT sum({$name}) as cnt ";
		} else {
			$sql = "SELECT sum((select sum(value) from {$this->table_etnik} where record_id={$this->table}.id and name='".ucfirst($name)."')) as cnt ";
		}
		$sql .= "FROM {$this->table},{$this->table_respondent},{$this->table_meta}";
        $sql .= " where {$this->table}.survey_id='".$survey_id."' ";
        $sql .= " and {$this->table}.respondent_id = {$this->table_respondent}.id";
        $sql .= " and {$this->table_respondent}.category_id='".$cat_id."'";
        $sql .= " and {$this->table_meta}.survey_id = '".$survey_id."'";
        $sql .= " and {$this->table_meta}.respondent_id = {$this->table_respondent}.id";
        $sql .= " and {$this->table_meta}.category_id='".$cat_id."'";
        $sql .= " and {$this->table_meta}.status = 'telah_diproses'";
        //_adebug($sql."\n", true);
        return $this->_pt->get_var($sql);
	}

	public function _report3($cat_id,$name,$survey_id) {
		if ( $name == "indonesia" || $name == "bangladesh" || $name == "nepal" ) {
			$sql = "SELECT sum({$name}) as cnt ";
		} else {
			$sql = "SELECT sum((select sum(value) from {$this->table_alien} where record_id={$this->table}.id and name='".$name."')) as cnt ";
		}
		$sql .= "FROM {$this->table},{$this->table_respondent},{$this->table_meta}";
        $sql .= " where {$this->table}.survey_id='".$survey_id."'";
        $sql .= " and {$this->table}.respondent_id = {$this->table_respondent}.id";
        $sql .= " and {$this->table_respondent}.category_id='".$cat_id."'";
        $sql .= " and {$this->table_meta}.survey_id = '".$survey_id."'";
        $sql .= " and {$this->table_meta}.respondent_id = {$this->table_respondent}.id";
        $sql .= " and {$this->table_meta}.category_id='".$cat_id."'";
        $sql .= " and {$this->table_meta}.status = 'telah_diproses'";
		return $this->_pt->get_var($sql);
	}

	public function _report4($cat_id,$group_id,$jawatan_id,$survey_id) {
		$sql = "SELECT {$this->table}.melayu+{$this->table}.cina+{$this->table}.india as local,";
		$sql .= "(select sum(value) from {$this->table_etnik} where record_id={$this->table}.id) as local2";
		$sql .= " FROM `{$this->table}`,{$this->table_respondent},{$this->table_meta}";
        $sql .= " where {$this->table}.survey_id='".$survey_id."'";
        $sql .= " and {$this->table}.respondent_id = {$this->table_respondent}.id";
        $sql .= " and {$this->table_respondent}.category_id='".$cat_id."'";
        $sql .= " and {$this->table}.group_id='".$group_id."'";
        $sql .= " and {$this->table}.jawatan_id='".$jawatan_id."'";
        $sql .= " and {$this->table_meta}.survey_id = '".$survey_id."'";
        $sql .= " and {$this->table_meta}.respondent_id = {$this->table_respondent}.id";
        $sql .= " and {$this->table_meta}.category_id='".$cat_id."'";
        $sql .= " and {$this->table_meta}.status = 'telah_diproses'"; //if ($group_id=='1' and $jawatan_id=='2')echo($sql);
		return $this->_pt->get_results($sql, ARRAY_A); 
	}
	
	public function _report24p_w($cat_id,$group_id,$jawatan_id,$survey_id,$subcatid) {
		$sql = "SELECT {$this->table}.melayu+{$this->table}.cina+{$this->table}.india as local,";
		$sql .= "(select sum(value) from {$this->table_etnik} where record_id={$this->table}.id) as local2";
		$sql .= " FROM `{$this->table}`,{$this->table_respondent},{$this->table_meta}";
        $sql .= " where {$this->table}.survey_id='".$survey_id."'";
        $sql .= " and {$this->table}.respondent_id = {$this->table_respondent}.id";
        $sql .= " and {$this->table_respondent}.category_id='".$cat_id."'";
		$sql .= " and {$this->table_respondent}.subcategory_id='".$subcatid."'";
        $sql .= " and {$this->table}.group_id='".$group_id."'";
        $sql .= " and {$this->table}.jawatan_id='".$jawatan_id."'";
        $sql .= " and {$this->table_meta}.survey_id = '".$survey_id."'";
        $sql .= " and {$this->table_meta}.respondent_id = {$this->table_respondent}.id";
        $sql .= " and {$this->table_meta}.category_id='".$cat_id."'";
        $sql .= " and {$this->table_meta}.status = 'telah_diproses'"; //echo($sql);
		return $this->_pt->get_results($sql, ARRAY_A); 
	}
	
	public function _report24p_wtotal($cat_id,$group_id,$survey_id,$subcatid) {
		$sql = "SELECT {$this->table}.melayu+{$this->table}.cina+{$this->table}.india as local,";
		$sql .= "(select sum(value) from {$this->table_etnik} where record_id={$this->table}.id) as local2";
		$sql .= " FROM `{$this->table}`,{$this->table_respondent},{$this->table_meta}";
        $sql .= " where {$this->table}.survey_id='".$survey_id."'";
        $sql .= " and {$this->table}.respondent_id = {$this->table_respondent}.id";
        $sql .= " and {$this->table_respondent}.category_id='".$cat_id."'";
		$sql .= " and {$this->table_respondent}.subcategory_id='".$subcatid."'";
        $sql .= " and {$this->table}.group_id='".$group_id."'";
        $sql .= " and {$this->table_meta}.survey_id = '".$survey_id."'";
        $sql .= " and {$this->table_meta}.respondent_id = {$this->table_respondent}.id";
        $sql .= " and {$this->table_meta}.category_id='".$cat_id."'";
        $sql .= " and {$this->table_meta}.status = 'telah_diproses'"; //echo($sql);
		return $this->_pt->get_results($sql, ARRAY_A); 
	}
	
	public function _report24p_bw($cat_id,$group_id,$jawatan_id,$survey_id,$subcatid) {
		$sql = "SELECT {$this->table}.indonesia+{$this->table}.bangladesh+{$this->table}.nepal as alien,";
		$sql .= "(select sum(value) from {$this->table_alien} where record_id={$this->table}.id) as alien2";
		$sql .= " FROM `{$this->table}`,{$this->table_respondent},{$this->table_meta}";
        $sql .= " where {$this->table}.survey_id='".$survey_id."'";
        $sql .= " and {$this->table}.respondent_id = {$this->table_respondent}.id";
        $sql .= " and {$this->table_respondent}.category_id='".$cat_id."'";
		$sql .= " and {$this->table_respondent}.subcategory_id='".$subcatid."'";
        $sql .= " and {$this->table}.group_id='".$group_id."'";
        $sql .= " and {$this->table}.jawatan_id='".$jawatan_id."'";
        $sql .= " and {$this->table_meta}.survey_id = '".$survey_id."'";
        $sql .= " and {$this->table_meta}.respondent_id = {$this->table_respondent}.id";
        $sql .= " and {$this->table_meta}.category_id='".$cat_id."'";
        $sql .= " and {$this->table_meta}.status = 'telah_diproses'"; //echo($sql);
		return $this->_pt->get_results($sql, ARRAY_A); 
	}
	
	public function _report24p_bwtotal($cat_id,$group_id,$survey_id,$subcatid) {
		$sql = "SELECT {$this->table}.indonesia+{$this->table}.bangladesh+{$this->table}.nepal as alien,";
		$sql .= "(select sum(value) from {$this->table_alien} where record_id={$this->table}.id) as alien2";
		$sql .= " FROM `{$this->table}`,{$this->table_respondent},{$this->table_meta}";
        $sql .= " where {$this->table}.survey_id='".$survey_id."'";
        $sql .= " and {$this->table}.respondent_id = {$this->table_respondent}.id";
        $sql .= " and {$this->table_respondent}.category_id='".$cat_id."'";
		$sql .= " and {$this->table_respondent}.subcategory_id='".$subcatid."'";
        $sql .= " and {$this->table}.group_id='".$group_id."'";
        $sql .= " and {$this->table_meta}.survey_id = '".$survey_id."'";
        $sql .= " and {$this->table_meta}.respondent_id = {$this->table_respondent}.id";
        $sql .= " and {$this->table_meta}.category_id='".$cat_id."'";
        $sql .= " and {$this->table_meta}.status = 'telah_diproses'"; //echo($sql);
		return $this->_pt->get_results($sql, ARRAY_A); 
	}

	public function _report4total($cat_id,$group_id,$survey_id) {
		$sql = "SELECT {$this->table}.melayu+{$this->table}.cina+{$this->table}.india as local,";
		$sql .= "(select sum(value) from {$this->table_etnik} where record_id={$this->table}.id) as local2";
		$sql .= " FROM `{$this->table}`,{$this->table_respondent},{$this->table_meta}";
        $sql .= " where {$this->table}.survey_id='".$survey_id."'";
        $sql .= " and {$this->table}.respondent_id = {$this->table_respondent}.id";
        $sql .= " and {$this->table_respondent}.category_id='".$cat_id."'";
        $sql .= " and {$this->table}.group_id='".$group_id."'";
        $sql .= " and {$this->table_meta}.survey_id = '".$survey_id."'";
        $sql .= " and {$this->table_meta}.respondent_id = {$this->table_respondent}.id";
        $sql .= " and {$this->table_meta}.category_id='".$cat_id."'";
        $sql .= " and {$this->table_meta}.status = 'telah_diproses'"; //echo($sql);
		return $this->_pt->get_results($sql, ARRAY_A); 
	}
	
	public function _report5($cat_id,$group_id,$jawatan_id,$survey_id) {
		$sql = "SELECT {$this->table}.indonesia+{$this->table}.bangladesh+{$this->table}.nepal as alien,";
		$sql .= "(select sum(value) from {$this->table_alien} where record_id={$this->table}.id) as alien2";
		$sql .= " FROM `{$this->table}`,{$this->table_respondent},{$this->table_meta}";
        $sql .= " where {$this->table}.survey_id='".$survey_id."'";
        $sql .= " and {$this->table}.respondent_id = {$this->table_respondent}.id";
        $sql .= " and {$this->table_respondent}.category_id='".$cat_id."'";
        $sql .= " and {$this->table}.group_id='".$group_id."'";
        $sql .= " and {$this->table}.jawatan_id='".$jawatan_id."'";
        // r_record_meta
        $sql .= " and {$this->table_meta}.survey_id = '".$survey_id."'";
        $sql .= " and {$this->table_meta}.respondent_id = {$this->table_respondent}.id";
        $sql .= " and {$this->table_meta}.category_id='".$cat_id."'";
        $sql .= " and {$this->table_meta}.status = 'telah_diproses'";
		return $this->_pt->get_results($sql, ARRAY_A);
	}
	
	public function _report5total($cat_id,$group_id,$jawatan_id,$survey_id) {
		$sql = "SELECT {$this->table}.indonesia+{$this->table}.bangladesh+{$this->table}.nepal as alien,";
		$sql .= "(select sum(value) from {$this->table_alien} where record_id={$this->table}.id) as alien2";
		$sql .= " FROM `{$this->table}`,{$this->table_respondent},{$this->table_meta}";
        $sql .= " where {$this->table}.survey_id='".$survey_id."'";
        $sql .= " and {$this->table}.respondent_id = {$this->table_respondent}.id";
        $sql .= " and {$this->table_respondent}.category_id='".$cat_id."'";
        $sql .= " and {$this->table}.group_id='".$group_id."'";
        // r_record_meta
        $sql .= " and {$this->table_meta}.survey_id = '".$survey_id."'";
        $sql .= " and {$this->table_meta}.respondent_id = {$this->table_respondent}.id";
        $sql .= " and {$this->table_meta}.category_id='".$cat_id."'";
        $sql .= " and {$this->table_meta}.status = 'telah_diproses'";
		return $this->_pt->get_results($sql, ARRAY_A);
	}

	public function _report6($cat_id,$group_id,$jawatan_id = null,$survey_id) {
        if ( $jawatan_id == "undefined" ) $jawatan_id = null;
		$sql = "select r_record.* FROM `{$this->table}`,{$this->table_respondent},{$this->table_meta}";
        $sql .= " where {$this->table}.survey_id='".$survey_id."'";
        $sql .= " and {$this->table}.respondent_id = {$this->table_respondent}.id";
        $sql .= " and {$this->table_respondent}.category_id='".$cat_id."'";
        $sql .= " and {$this->table}.group_id='".$group_id."'";
        if ( !_null($jawatan_id) ) {
            $sql .= " and {$this->table}.jawatan_id='".$jawatan_id."'";
        }
        // r_record_meta
        $sql .= " and {$this->table_meta}.survey_id = '".$survey_id."'";
        $sql .= " and {$this->table_meta}.respondent_id = {$this->table_respondent}.id";
        $sql .= " and {$this->table_meta}.category_id='".$cat_id."'";
        $sql .= " and {$this->table_meta}.status = 'telah_diproses'"; 

		$data = $this->_pt->get_results($sql, ARRAY_A);
        $total = 0;
        if ( _array($data) ) {
            $purata_gaji = 0;
            $jumlah_pekerja_s = 0;
            $purata_gaji_s = 0;
            while( $rt = @array_shift($data) ) {
                $pekerja1 = ( $rt['melayu'] + $rt['cina'] + $rt['india'] );
                $pekerja2 = (int)$this->_pt->get_var("select sum(value) from {$this->table_etnik} where record_id={$rt['id']}");
                $jumlah_pekerja = ( $pekerja1 + $pekerja2 ); 
                $purata_gaji = ( $rt['gajia'] * $jumlah_pekerja );
                $purata_gaji_s += $purata_gaji;
                $jumlah_pekerja_s += $jumlah_pekerja;
            }
            $total = @( $purata_gaji_s / $jumlah_pekerja_s );
        }
        return @round($total);
	}

	public function _report7($cat_id,$group_id,$jawatan_id = null,$survey_id) {
        if ( $jawatan_id == "undefined" ) $jawatan_id = null;
		$sql = "select r_record.* FROM `{$this->table}`,{$this->table_respondent},{$this->table_meta}";
        $sql .= " where {$this->table}.survey_id='".$survey_id."'";
        $sql .= " and {$this->table}.respondent_id = {$this->table_respondent}.id";
        $sql .= " and {$this->table_respondent}.category_id='".$cat_id."' ";
        $sql .= " and {$this->table}.group_id='".$group_id."' ";
        if ( !_null($jawatan_id) ) {
            $sql .= " and {$this->table}.jawatan_id='".$jawatan_id."'";
        }
        // r_record_meta
        $sql .= " and {$this->table_meta}.survey_id = '".$survey_id."'";
        $sql .= " and {$this->table_meta}.respondent_id = {$this->table_respondent}.id";
        $sql .= " and {$this->table_meta}.category_id='".$cat_id."'";
        $sql .= " and {$this->table_meta}.status = 'telah_diproses'";
		$data = $this->_pt->get_results($sql, ARRAY_A);
        $total = 0;
        if ( _array($data) ) {
            $purata_gaji = 0;
            $jumlah_pekerja_s = 0;
            $purata_gaji_s = 0;
            while( $rt = @array_shift($data) ) {
                $pekerja1 = ( $rt['indonesia'] + $rt['bangladesh'] + $rt['nepal'] );
                $pekerja2 = (int)$this->_pt->get_var("select sum(value) from {$this->table_alien} where record_id={$rt['id']}");
                $jumlah_pekerja = ( $pekerja1 + $pekerja2 );
                $purata_gaji = ( $rt['gajib'] * $jumlah_pekerja );
                $purata_gaji_s += $purata_gaji;
                $jumlah_pekerja_s += $jumlah_pekerja;
            }
            $total = @( $purata_gaji_s / $jumlah_pekerja_s );
        }
        return @round($total);
	}

	public function _report8($cat_id,$group_id,$jawatan_id,$survey_id) {
		$sql = "select r_record.* FROM `{$this->table}`,{$this->table_respondent},{$this->table_meta}";
        $sql .= " where {$this->table}.survey_id='".$survey_id."'";
        $sql .= " and {$this->table}.respondent_id = {$this->table_respondent}.id ";
        $sql .= " and {$this->table_respondent}.category_id='".$cat_id."'";
        $sql .= " and {$this->table}.group_id='".$group_id."'";
        $sql .= " and {$this->table}.jawatan_id='".$jawatan_id."'";
        // r_record_meta
        $sql .= " and {$this->table_meta}.survey_id = '".$survey_id."'";
        $sql .= " and {$this->table_meta}.respondent_id = {$this->table_respondent}.id";
        $sql .= " and {$this->table_meta}.category_id='".$cat_id."'";
        $sql .= " and {$this->table_meta}.status = 'telah_diproses'";
		$data = $this->_pt->get_results($sql, ARRAY_A);
        $total = 0;
        if ( _array($data) ) {
            $purata_elaun = 0;
            $jumlah_pekerja_s = 0;
            $purata_elaun_s = 0;
            while( $rt = @array_shift($data) ) {
                $pekerja1 = ( $rt['melayu'] + $rt['cina'] + $rt['india'] );
                $pekerja2 = (int)$this->_pt->get_var("select sum(value) from {$this->table_etnik} where record_id={$rt['id']}");
                $jumlah_pekerja = ( $pekerja1 + $pekerja2 );
                $purata_elaun = ( $rt['elauna'] * $jumlah_pekerja );
                $purata_elaun_s += $purata_elaun;
                $jumlah_pekerja_s += $jumlah_pekerja;
            }
            $total = @( $purata_elaun_s / $jumlah_pekerja_s );
        }
        return @round($total);
	}

	public function _report9($cat_id,$group_id,$jawatan_id,$survey_id) {
		$sql = "select r_record.* FROM `{$this->table}`,{$this->table_respondent},{$this->table_meta}";
        $sql .= " where {$this->table}.survey_id='".$survey_id."'";
        $sql .= " and {$this->table}.respondent_id = {$this->table_respondent}.id";
        $sql .= " and {$this->table_respondent}.category_id='".$cat_id."'";
        $sql .= " and {$this->table}.group_id='".$group_id."'";
        $sql .= " and {$this->table}.jawatan_id='".$jawatan_id."'";
        // r_record_meta
        $sql .= " and {$this->table_meta}.survey_id = '".$survey_id."'";
        $sql .= " and {$this->table_meta}.respondent_id = {$this->table_respondent}.id";
        $sql .= " and {$this->table_meta}.category_id='".$cat_id."'";
        $sql .= " and {$this->table_meta}.status = 'telah_diproses'";
		$data = $this->_pt->get_results($sql, ARRAY_A);
        $total = 0;
        if ( _array($data) ) {
            $purata_elaun = 0;
            $jumlah_pekerja_s = 0;
            $purata_elaun_s = 0;
            while( $rt = @array_shift($data) ) {
                $pekerja1 = ( $rt['indonesia'] + $rt['bangladesh'] + $rt['nepal'] );
                $pekerja2 = (int)$this->_pt->get_var("select sum(value) from {$this->table_alien} where record_id={$rt['id']}");
                $jumlah_pekerja = ( $pekerja1 + $pekerja2 );
                $purata_elaun = ( $rt['elaunb'] * $jumlah_pekerja );
                $purata_elaun_s += $purata_elaun;
                $jumlah_pekerja_s += $jumlah_pekerja;
            }
            $total = @( $purata_elaun_s / $jumlah_pekerja_s );
        }
        return @round($total);
	}

	public function _report10($cat_id,$group_id,$jawatan_id,$survey_id) {
		$sql = "select r_record.* FROM `{$this->table}`,{$this->table_respondent},{$this->table_meta}";
        $sql .= " where {$this->table}.survey_id='".$survey_id."'";
        $sql .= " and {$this->table}.respondent_id = {$this->table_respondent}.id";
        $sql .= " and {$this->table_respondent}.category_id='".$cat_id."'";
        $sql .= " and {$this->table}.group_id='".$group_id."'";
        $sql .= " and {$this->table}.jawatan_id='".$jawatan_id."'";
        // r_record_meta
        $sql .= " and {$this->table_meta}.survey_id = '".$survey_id."'";
        $sql .= " and {$this->table_meta}.respondent_id = {$this->table_respondent}.id";
        $sql .= " and {$this->table_meta}.category_id='".$cat_id."'";
        $sql .= " and {$this->table_meta}.status = 'telah_diproses'";
		$data = $this->_pt->get_results($sql, ARRAY_A);
        $total = 0;
        if ( _array($data) ) {
            $collect = array();
            while( $rt = @array_shift($data) ) {
                $pekerja1 = ( $rt['melayu'] + $rt['cina'] + $rt['india'] );
                $pekerja2 = (int)$this->_pt->get_var("select sum(value) from {$this->table_etnik} where record_id={$rt['id']}");
                $jumlah_pekerja = ( $pekerja1 + $pekerja2 );
                $purata_gaji = ( $rt['gajia'] + $rt['elauna'] );
                $collect[$rt['id']]['purata_gaji'] = $purata_gaji;
                $collect[$rt['id']]['jumlah_pekerja'] = $jumlah_pekerja;
            }
            $purata_gaji = 0;
            while( $rt = @array_shift($collect) ) {
                $purata_gaji += $rt['jumlah_pekerja'] * $rt['purata_gaji'];
                $pekerja +=  $rt['jumlah_pekerja'];
            }
            if ( $purata_gaji > 0 && $pekerja > 0 ) {
                $total = $purata_gaji / $pekerja;
            }
        }
        return @round($total);
	}

	public function _report11($cat_id,$group_id,$jawatan_id,$survey_id) {
		$sql = "select r_record.* FROM `{$this->table}`,{$this->table_respondent},{$this->table_meta}";
        $sql .= " where {$this->table}.survey_id='".$survey_id."'";
        $sql .= " and {$this->table}.respondent_id = {$this->table_respondent}.id";
        $sql .= " and {$this->table_respondent}.category_id='".$cat_id."'";
        $sql .= " and {$this->table}.group_id='".$group_id."'";
        $sql .= " and {$this->table}.jawatan_id='".$jawatan_id."'";
        // r_record_meta
        $sql .= " and {$this->table_meta}.survey_id = '".$survey_id."'";
        $sql .= " and {$this->table_meta}.respondent_id = {$this->table_respondent}.id";
        $sql .= " and {$this->table_meta}.category_id='".$cat_id."'";
        $sql .= " and {$this->table_meta}.status = 'telah_diproses'";
		$data = $this->_pt->get_results($sql, ARRAY_A);
        $total = 0;
        if ( _array($data) ) {
            $collect = array();
            while( $rt = @array_shift($data) ) {
                $pekerja1 = ( $rt['indonesia'] + $rt['bangladesh'] + $rt['nepal'] );
                $pekerja2 = (int)$this->_pt->get_var("select sum(value) from {$this->table_alien} where record_id={$rt['id']}");
                $jumlah_pekerja = ( $pekerja1 + $pekerja2 );
                $purata_gaji = ( $rt['gajib'] + $rt['elaunb'] );
                $collect[$rt['id']]['purata_gaji'] = $purata_gaji;
                $collect[$rt['id']]['jumlah_pekerja'] = $jumlah_pekerja;
            }
            $purata_gaji = 0;
            while( $rt = @array_shift($collect) ) {
                $purata_gaji += $rt['jumlah_pekerja'] * $rt['purata_gaji'];
                $pekerja +=  $rt['jumlah_pekerja'];
            }
            if ( $purata_gaji > 0 && $pekerja > 0 ) {
                $total = $purata_gaji / $pekerja;
            }
        }
        return @round($total);
	}

	public function _report12($cat_id,$group_id,$survey_id) {
		$sql = "SELECT SUM({$this->table}.bp1) as bp1,SUM({$this->table}.bp2) as bp2,SUM({$this->table}.bp3) as bp3";
		$sql .= " FROM `{$this->table}`,{$this->table_respondent},{$this->table_meta}";
        $sql .= " where {$this->table}.survey_id='".$survey_id."'";
        $sql .= " and {$this->table}.respondent_id = {$this->table_respondent}.id";
        $sql .= " and {$this->table_respondent}.category_id='".$cat_id."'";
        // r_record_meta
        $sql .= " and {$this->table_meta}.survey_id = '".$survey_id."'";
        $sql .= " and {$this->table_meta}.respondent_id = {$this->table_respondent}.id";
        $sql .= " and {$this->table_meta}.category_id='".$cat_id."'";
        $sql .= " and {$this->table_meta}.status = 'telah_diproses'";
        if ( !_null($group_id) && $group_id !== "all" ) {
            $sql .= " and {$this->table}.group_id='".$group_id."'";
        }
		return $this->_pt->get_row($sql, ARRAY_A);
	}

	public function _report13($cat_id,$group_id,$survey_id, $opt) {
        return ( $opt == "local" ? $this->_report6($cat_id,$group_id,null,$survey_id) : $this->_report7($cat_id,$group_id,null,$survey_id) );
	}

	public function _report14($cat_id,$group_id,$survey_id, $state_id, $opt) {

        if ( $opt == "local" ) {
		    $sql = "SELECT {$this->table}.melayu+{$this->table}.cina+{$this->table}.india as local,";
		    $sql .= "(select sum(value) from {$this->table_etnik} where record_id={$this->table}.id) as local2";

            $sql .= ",{$this->table_respondent}.nolesen as nolesen";
            $sql .= ",{$this->table_respondent}.company as company";

		    $sql .= " FROM `{$this->table}`,{$this->table_respondent},{$this->table_meta}";
            $sql .= " where {$this->table}.survey_id='".$survey_id."'";
            $sql .= " and {$this->table}.respondent_id = {$this->table_respondent}.id";
            if ( $cat_id != 'all' ) $sql .= " and {$this->table_respondent}.category_id='".$cat_id."'";
            // semenanjung
            if ( $state_id == 19 ) {
                $sql .= " and {$this->table_respondent}.state_id IN ('01','02','03','04','05','06','07','08','09','10','11','12','16')";
            } elseif ( $state_id == 18 ) { // sabah / sarawak
                $sql .= " and {$this->table_respondent}.state_id IN ('13','14','15')";
            } elseif ( $state_id == 17 ) { // malaysia
                $sql .= " and {$this->table_respondent}.state_id IN ('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16')";
            } else {
                $sql .= " and {$this->table_respondent}.state_id='".$state_id."'";
            }

            // r_record_meta
            $sql .= " and {$this->table_meta}.survey_id = '".$survey_id."'";
            $sql .= " and {$this->table_meta}.respondent_id = {$this->table_respondent}.id";

            if ( $cat_id != 'all' ) $sql .= " and {$this->table_meta}.category_id='".$cat_id."'";
            $sql .= " and {$this->table_meta}.status = 'telah_diproses'";

            $sql .= " and {$this->table}.group_id='".$group_id."'";

            $sql .= " group by {$this->table_respondent}.nolesen";

            $data = $this->_pt->get_results($sql, ARRAY_A);

            $local = 0;
            $skip = array();
            if ( _array($data) ) {
                
                while( $rt = @array_shift($data) ) {

                    $rt['local1'] = ( !_num($rt['local1']) ? 0 : $rt['local1'] );
                    $rt['local2'] = ( !_num($rt['local2']) ? 0 : $rt['local2'] );

                    /*if ( isset($skip[$rt['nolesen']][$group_id][$state_id]) ) continue;
                    $skip[$rt['nolesen']][$group_id][$state_id] = "{$rt['local1']} {$rt['local2']}";*/

                    $local1 = ( $rt['local'] + $rt['local2'] );
                    $local = $local + $local1;
                }
            }

            return $local;

        } else {
		    $sql = "SELECT {$this->table}.indonesia+{$this->table}.bangladesh+{$this->table}.nepal as alien,";
		    $sql .= "(select sum(value) from {$this->table_alien} where record_id={$this->table}.id) as alien2";
		    $sql .= " FROM `{$this->table}`,{$this->table_respondent},{$this->table_meta}";
            $sql .= " where {$this->table}.survey_id='".$survey_id."'";
            $sql .= " and {$this->table}.respondent_id = {$this->table_respondent}.id";
            if ( $cat_id != 'all' ) $sql .= " and {$this->table_respondent}.category_id='".$cat_id."'";
            // semenanjung
            if ( $state_id == 19 ) {
                $sql .= " and {$this->table_respondent}.state_id IN ('01','02','03','04','05','06','07','08','09','10','11','12','16')";
            } elseif ( $state_id == 18 ) { // sabah / sarawak
                $sql .= " and {$this->table_respondent}.state_id IN ('13','14','15')";
            } elseif ( $state_id == 17 ) { // malaysia
                $sql .= " and {$this->table_respondent}.state_id IN ('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16')";
            } else {
                $sql .= " and {$this->table_respondent}.state_id='".$state_id."'";
            }
            $sql .= " and {$this->table}.group_id='".$group_id."'";
            // r_record_meta
            $sql .= " and {$this->table_meta}.survey_id = '".$survey_id."'";
            $sql .= " and {$this->table_meta}.respondent_id = {$this->table_respondent}.id";
            if ( $cat_id != 'all' ) $sql .= " and {$this->table_meta}.category_id='".$cat_id."'";
            $sql .= " and {$this->table_meta}.status = 'telah_diproses'";

            $sql .= " group by {$this->table_respondent}.nolesen";

            $data = $this->_pt->get_results($sql, ARRAY_A);
            $alien = 0;
            if ( _array($data) ) {
                while( $rt = @array_shift($data) ) {
                    $alien += ( $rt['alien'] + $rt['alien2'] );
                }
            }
            return $alien;
        }
        return 0;
	}

	public function _report15($cat_id,$group_id,$survey_id, $state_id, $opt) {
        if ( $opt == "local" ) {
		    $sql = "select r_record.* FROM `{$this->table}`,{$this->table_respondent},{$this->table_meta}";
            $sql .= " where {$this->table}.survey_id='".$survey_id."'";
            $sql .= " and {$this->table}.respondent_id = {$this->table_respondent}.id";
            $sql .= " and {$this->table_respondent}.category_id='".$cat_id."'";
            // semenanjung
            if ( $state_id == 19 ) {
                $sql .= " and {$this->table_respondent}.state_id IN ('01','02','03','04','05','06','07','08','09','10','11','12','16')";
            } elseif ( $state_id == 18 ) { // sabah / sarawak
                $sql .= " and {$this->table_respondent}.state_id IN ('13','14','15')";
            } elseif ( $state_id == 17 ) { // malaysia
                $sql .= " and {$this->table_respondent}.state_id IN ('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16')";
            } else {
                $sql .= " and {$this->table_respondent}.state_id='".$state_id."'";
            }
            $sql .= " and {$this->table}.group_id='".$group_id."'";
            // r_record_meta
            $sql .= " and {$this->table_meta}.survey_id = '".$survey_id."'";
            $sql .= " and {$this->table_meta}.respondent_id = {$this->table_respondent}.id";
            $sql .= " and {$this->table_meta}.category_id='".$cat_id."'";
            $sql .= " and {$this->table_meta}.status = 'telah_diproses'";
		    $data = $this->_pt->get_results($sql, ARRAY_A);
            $total = 0;
            if ( _array($data) ) {
                $collect = array();
                while( $rt = @array_shift($data) ) {
                    $pekerja1 = ( $rt['melayu'] + $rt['cina'] + $rt['india'] );
                    $pekerja2 = (int)$this->_pt->get_var("select sum(value) from {$this->table_etnik} where record_id={$rt['id']}");
                    $jumlah_pekerja = ( $pekerja1 + $pekerja2 );
                    $purata_gaji = $rt['gajia'];
                    $collect[$rt['id']]['purata_gaji'] = $purata_gaji;
                    $collect[$rt['id']]['jumlah_pekerja'] = $jumlah_pekerja;
                }
                $purata_gaji = 0;
                while( $rt = @array_shift($collect) ) {
                    $purata_gaji += $rt['jumlah_pekerja'] * $rt['purata_gaji'];
                    $pekerja +=  $rt['jumlah_pekerja'];
                }
                if ( $purata_gaji > 0 && $pekerja > 0 ) {
                    $total = $purata_gaji / $pekerja;
                }
            }
            return @round($total);
        } else {
		    $sql = "select r_record.* FROM `{$this->table}`,{$this->table_respondent},{$this->table_meta}";
            $sql .= " where {$this->table}.survey_id='".$survey_id."'";
            $sql .= " and {$this->table}.respondent_id = {$this->table_respondent}.id";
            $sql .= " and {$this->table_respondent}.category_id='".$cat_id."' ";
            // semenanjung
            if ( $state_id == 19 ) {
                $sql .= " and {$this->table_respondent}.state_id IN ('01','02','03','04','05','06','07','08','09','10','11','12','16')";
            } elseif ( $state_id == 18 ) { // sabah / sarawak
                $sql .= " and {$this->table_respondent}.state_id IN ('13','14','15')";
            } elseif ( $state_id == 17 ) { // malaysia
                $sql .= " and {$this->table_respondent}.state_id IN ('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16')";
            } else {
                $sql .= " and {$this->table_respondent}.state_id='".$state_id."'";
            }
            $sql .= " and {$this->table}.group_id='".$group_id."' ";
            // r_record_meta
            $sql .= " and {$this->table_meta}.survey_id = '".$survey_id."'";
            $sql .= " and {$this->table_meta}.respondent_id = {$this->table_respondent}.id";
            $sql .= " and {$this->table_meta}.category_id='".$cat_id."'";
            $sql .= " and {$this->table_meta}.status = 'telah_diproses'";
		    $data = $this->_pt->get_results($sql, ARRAY_A);
            $total = 0;
            if ( _array($data) ) {
                $collect = array();
                while( $rt = @array_shift($data) ) {
                    $pekerja1 = ( $rt['indonesia'] + $rt['bangladesh'] + $rt['nepal'] );
                    $pekerja2 = (int)$this->_pt->get_var("select sum(value) from {$this->table_alien} where record_id={$rt['id']}");
                    $jumlah_pekerja = ( $pekerja1 + $pekerja2 );
                    $purata_gaji = $rt['gajib'];
                    $collect[$rt['id']]['purata_gaji'] = $purata_gaji;
                    $collect[$rt['id']]['jumlah_pekerja'] = $jumlah_pekerja;
                }
                $purata_gaji = 0;
                while( $rt = @array_shift($collect) ) {
                    $purata_gaji += $rt['jumlah_pekerja'] * $rt['purata_gaji'];
                    $pekerja +=  $rt['jumlah_pekerja'];
                }
                if ( $purata_gaji > 0 && $pekerja > 0 ) {
                    $total = $purata_gaji / $pekerja;
                }
            }
            return @round($total);
        }
        return 0;
	}

	public function _report16($cat_id,$group_id,$survey_id) {
		$sql = "SELECT SUM({$this->table}.bp1) as bp1";
		$sql .= " FROM `{$this->table}`,{$this->table_respondent},{$this->table_meta}";
        $sql .= " where {$this->table}.survey_id='".$survey_id."'";
        $sql .= " and {$this->table}.respondent_id = {$this->table_respondent}.id";
        $sql .= " and {$this->table_respondent}.category_id='".$cat_id."'";
        $sql .= " and {$this->table}.group_id='".$group_id."'";
        // r_record_meta
        $sql .= " and {$this->table_meta}.survey_id = '".$survey_id."'";
        $sql .= " and {$this->table_meta}.respondent_id = {$this->table_respondent}.id";
        $sql .= " and {$this->table_meta}.category_id='".$cat_id."'";
        $sql .= " and {$this->table_meta}.status = 'telah_diproses'";
		return $this->_pt->get_var($sql);
	}

    /* peniaga */
    public function _report1p($cat_id,$survey_id) {
        return $this->_report1($cat_id,$survey_id);
    }

	public function _report2p($cat_id,$name,$survey_id) {
        return $this->_report2($cat_id,$name,$survey_id);
    }

	public function _report3p($cat_id,$name,$survey_id) {
        return $this->_report3($cat_id,$name,$survey_id);
    }

	public function _report4p($cat_id,$group_id,$jawatan_id,$survey_id) {
        return $this->_report4($cat_id,$group_id,$jawatan_id,$survey_id);
    }

	public function _report5p($cat_id,$group_id,$jawatan_id,$survey_id) {
        return $this->_report5($cat_id,$group_id,$jawatan_id,$survey_id);
    }

    public function _report6p($cat_id,$group_id,$jawatan_id = null,$survey_id) {
        return $this->_report6($cat_id,$group_id,$jawatan_id,$survey_id);
    }

    public function _report7p($cat_id,$group_id,$jawatan_id = null,$survey_id) {
        return $this->_report7($cat_id,$group_id,$jawatan_id,$survey_id);
    }

	public function _report8p($cat_id,$group_id,$jawatan_id,$survey_id) {
        return $this->_report8($cat_id,$group_id,$jawatan_id,$survey_id);
    }

    public function _report9p($cat_id,$group_id,$jawatan_id,$survey_id) {
        return $this->_report9($cat_id,$group_id,$jawatan_id,$survey_id);
    }

    public function _report10p($cat_id,$group_id,$jawatan_id,$survey_id) {
        return $this->_report10($cat_id,$group_id,$jawatan_id,$survey_id);
    }

    public function _report11p($cat_id,$group_id,$jawatan_id,$survey_id) {
        return $this->_report11($cat_id,$group_id,$jawatan_id,$survey_id);
    }

    public function _report12p($cat_id,$group_id,$survey_id) {
        return $this->_report12($cat_id,$group_id,$survey_id);
    }

	public function _report20p($cat_id,$group_id,$survey_id, $state_id, $opt) {
        return $this->_report15($cat_id,$group_id,$survey_id, $state_id, $opt);
    }

    // 16092015
    public function _report28p($survey_id,$status,$state_id,$category_id,$subcategory_id) {
        $sql = "select a.id,a.survey_id,b.company as company, a.sdate,";
        $sql .= "b.nolesen as nolesen,b.state_id as state_id,b.category_id as category_id,b.subcategory_id as subcategory_id ";
        $sql .= "from `{$this->table_meta}` a inner join `{$this->table_respondent}` b ";
        $sql .= "on a.respondent_id = b.id where a.survey_id = '{$survey_id}' and a.status = '{$status}' ";
            if ( $state_id == 19 ) {
                $sql .= " and b.state_id IN ('01','02','03','04','05','06','07','08','09','10','11','12','16')";
            } elseif ( $state_id == 18 ) { // sabah / sarawak
                $sql .= " and b.state_id IN ('13','14','15')";
            } elseif ( $state_id == 17 or $state_id =='') { // malaysia
                $sql .= " and b.state_id IN ('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16')";
            } else {
                $sql .= "and b.state_id = '{$state_id}' ";
            }		
		$sql .= "and b.category_id = '{$category_id}' ";
			if (($category_id=='03' or $category_id=='04') && ($subcategory_id!=''))
				$sql .= "and b.subcategory_id = '{$subcategory_id}' ";
			
			
		$sql .= "group by a.id order by b.nolesen"; //echo $sql;
        return $this->_pt->get_results($sql, ARRAY_A);
    }
	
	public function _report29p($cat_id,$subcat_id,$group_id,$survey_id, $state_id, $opt) {
	   
        if ( $opt == "local" ) {
		    $sql = "SELECT {$this->table}.melayu+{$this->table}.cina+{$this->table}.india as local,";
		    $sql .= "(select sum(value) from {$this->table_etnik} where record_id={$this->table}.id) as local2";

            $sql .= ",{$this->table_respondent}.nolesen as nolesen";
            $sql .= ",{$this->table_respondent}.company as company";

		    $sql .= " FROM `{$this->table}`,{$this->table_respondent},{$this->table_meta}";
            $sql .= " where {$this->table}.survey_id='".$survey_id."'";
            $sql .= " and {$this->table}.respondent_id = {$this->table_respondent}.id";
			if ($cat_id == 'all' ) $sql .= " and {$this->table_respondent}.category_id in ('02','03','04')";
            elseif ( $cat_id != 'all' ) $sql .= " and {$this->table_respondent}.category_id='".$cat_id."'";
            // semenanjung
            if ( $state_id == 19 ) {
                $sql .= " and {$this->table_respondent}.state_id IN ('01','02','03','04','05','06','07','08','09','10','11','12','16')";
            } elseif ( $state_id == 18 ) { // sabah / sarawak
                $sql .= " and {$this->table_respondent}.state_id IN ('13','14','15')";
            } elseif ( $state_id == 17 ) { // malaysia
                $sql .= " and {$this->table_respondent}.state_id IN ('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16')";
            } else {
                $sql .= " and {$this->table_respondent}.state_id='".$state_id."'";
            }

            // r_record_meta
            $sql .= " and {$this->table_meta}.survey_id = '".$survey_id."'";
            $sql .= " and {$this->table_meta}.respondent_id = {$this->table_respondent}.id";


			if ($cat_id == 'all' ) $sql .= " and {$this->table_meta}.category_id in ('02','03','04')";
            elseif ( $cat_id != 'all' ) $sql .= " and {$this->table_meta}.category_id ='".$cat_id."'";
			
			if (($cat_id=='03' or $cat_id=='04') && ($subcat_id!=''))
				$sql .= " and {$this->table_respondent}.subcategory_id = '".$subcat_id."'";
			
            $sql .= " and {$this->table_meta}.status = 'telah_diproses'";

            $sql .= " and {$this->table}.group_id='".$group_id."'";

            
            
            $data = $this->_pt->get_results($sql, ARRAY_A);

            $local = 0;
            $skip = array();
            if ( _array($data) ) {
                
                while( $rt = @array_shift($data) ) {

                    $rt['local1'] = ( !_num($rt['local1']) ? 0 : $rt['local1'] );
                    $rt['local2'] = ( !_num($rt['local2']) ? 0 : $rt['local2'] );

                    /*if ( isset($skip[$rt['nolesen']][$group_id][$state_id]) ) continue;
                    $skip[$rt['nolesen']][$group_id][$state_id] = "{$rt['local1']} {$rt['local2']}";*/

                    $local1 = ( $rt['local'] + $rt['local2'] );
                    $local = $local + $local1;
                }
            }

            return $local;

        } else {
		    $sql = "SELECT {$this->table}.indonesia+{$this->table}.bangladesh+{$this->table}.nepal as alien,";
		    $sql .= "(select sum(value) from {$this->table_alien} where record_id={$this->table}.id) as alien2";
		    $sql .= " FROM `{$this->table}`,{$this->table_respondent},{$this->table_meta}";
            $sql .= " where {$this->table}.survey_id='".$survey_id."'";
            $sql .= " and {$this->table}.respondent_id = {$this->table_respondent}.id";
            if ($cat_id == 'all' ) $sql .= " and {$this->table_respondent}.category_id in ('02','03','04')";
            elseif ( $cat_id != 'all' ) $sql .= " and {$this->table_respondent}.category_id='".$cat_id."'";
            // semenanjung
            if ( $state_id == 19 ) {
                $sql .= " and {$this->table_respondent}.state_id IN ('01','02','03','04','05','06','07','08','09','10','11','12','16')";
            } elseif ( $state_id == 18 ) { // sabah / sarawak
                $sql .= " and {$this->table_respondent}.state_id IN ('13','14','15')";
            } elseif ( $state_id == 17 ) { // malaysia
                $sql .= " and {$this->table_respondent}.state_id IN ('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16')";
            } else {
                $sql .= " and {$this->table_respondent}.state_id='".$state_id."'";
            }
            $sql .= " and {$this->table}.group_id='".$group_id."'";
            // r_record_meta
            $sql .= " and {$this->table_meta}.survey_id = '".$survey_id."'";
            $sql .= " and {$this->table_meta}.respondent_id = {$this->table_respondent}.id";
            
			if ($cat_id == 'all' ) $sql .= " and {$this->table_meta}.category_id in ('02','03','04')";
            elseif ( $cat_id != 'all' ) $sql .= " and {$this->table_meta}.category_id ='".$cat_id."'";
			
			if (($cat_id=='03' or $cat_id=='04') && ($subcat_id!=''))
				$sql .= " and {$this->table_respondent}.subcategory_id = '".$subcat_id."'";
			
            $sql .= " and {$this->table_meta}.status = 'telah_diproses'";

            $sql .= " and {$this->table}.group_id='".$group_id."'";

            $data = $this->_pt->get_results($sql, ARRAY_A);
            $alien = 0;
            if ( _array($data) ) {
                while( $rt = @array_shift($data) ) {
                    $alien += ( $rt['alien'] + $rt['alien2'] );
                }
            }
            return $alien;
        }
        return 0;
	}
	
	
	//25/10/2017 - added by ikin
	
	  public function _report30($survey_id,$status,$state_id,$category_id,$subcategory_id) {
        $sql = "select a.id,a.survey_id,b.company as company, a.sdate,";
        $sql .= "b.nolesen as nolesen,b.state_id as state_id,b.category_id as category_id,b.subcategory_id as subcategory_id ";
        $sql .= "from `{$this->table_meta}` a inner join `{$this->table_respondent}` b ";
        $sql .= "on a.respondent_id = b.id where a.survey_id = '{$survey_id}' and a.status = '{$status}' ";
            if ( $state_id == 19 ) {
                $sql .= " and b.state_id IN ('01','02','03','04','05','06','07','08','09','10','11','12','16')";
            } elseif ( $state_id == 18 ) { // sabah / sarawak
                $sql .= " and b.state_id IN ('13','14','15')";
            } elseif ( $state_id == 17 or $state_id =='') { // malaysia
                $sql .= " and b.state_id IN ('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16')";
            } else {
                $sql .= "and b.state_id = '{$state_id}' ";
            }		
		$sql .= "and b.category_id = '{$category_id}' ";
			if (($category_id=='03' or $category_id=='04') && ($subcategory_id!=''))
				$sql .= "and b.subcategory_id = '{$subcategory_id}' ";
			
			
		$sql .= "group by a.id order by b.nolesen"; //echo $sql;
        return $this->_pt->get_results($sql, ARRAY_A);
    }
	
	 public function _report31($survey_id,$status,$state_id,$category_id,$subcategory_id) {
        $sql = "select b.nolesen,b.email ";
        //$sql .= "b.nolesen as nolesen,b.state_id as state_id,b.category_id as category_id,b.subcategory_id as subcategory_id ";
        $sql .= "from `{$this->table_meta}` a inner join `{$this->table_respondent}` b ";
        $sql .= "on a.respondent_id = b.id where a.survey_id = '{$survey_id}' and a.status = '{$status}' ";
		$sql .= " and (b.email is not NULL AND b.email <> '')";
            if ( $state_id == 19 ) {
                $sql .= " and b.state_id IN ('01','02','03','04','05','06','07','08','09','10','11','12','16')";
            } elseif ( $state_id == 18 ) { // sabah / sarawak
                $sql .= " and b.state_id IN ('13','14','15')";
            } elseif ( $state_id == 17 or $state_id =='') { // malaysia
                $sql .= " and b.state_id IN ('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16')";
            } else {
                $sql .= "and b.state_id = '{$state_id}' ";
            }		
		$sql .= "and b.category_id = '{$category_id}' ";
			if (($category_id=='03' or $category_id=='04') && ($subcategory_id!=''))
				$sql .= "and b.subcategory_id = '{$subcategory_id}' ";
			
			
		$sql .= "group by a.id order by b.nolesen"; //echo $sql;
        return $this->_pt->get_results($sql, ARRAY_A);
    }
	
	
	 public function _report31p($survey_id,$status,$state_id,$category_id,$subcategory_id) {
         $sql = "select b.nolesen,b.email ";
      
        $sql .= "from `{$this->table_meta}` a inner join `{$this->table_respondent}` b ";
        $sql .= "on a.respondent_id = b.id where a.survey_id = '{$survey_id}' and a.status = '{$status}' ";
		$sql .= " and (b.email is not NULL AND b.email <> '')";
            if ( $state_id == 19 ) {
                $sql .= " and b.state_id IN ('01','02','03','04','05','06','07','08','09','10','11','12','16')";
            } elseif ( $state_id == 18 ) { // sabah / sarawak
                $sql .= " and b.state_id IN ('13','14','15')";
            } elseif ( $state_id == 17 or $state_id =='') { // malaysia
                $sql .= " and b.state_id IN ('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16')";
            } else {
                $sql .= "and b.state_id = '{$state_id}' ";
            }		
		$sql .= "and b.category_id = '{$category_id}' ";
			if (($category_id=='03' or $category_id=='04') && ($subcategory_id!=''))
				$sql .= "and b.subcategory_id = '{$subcategory_id}' ";
			
			
		$sql .= "group by a.id order by b.nolesen"; //echo $sql;
        return $this->_pt->get_results($sql, ARRAY_A);
    }
		
	
	
	
	
}

?>
