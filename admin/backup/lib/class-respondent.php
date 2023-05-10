<?php
/**
 * Respondent Class
 *
 * @author Mohd Nawawi Mohamad Jamili <nawawi@rutweb.com>
 * @category library
 */

class respondent {
	public function __construct() {
		if ( !_obj_caller() ) {
			_exit( _t("Error(%s): Invalid object",__CLASS__) );
		}
		$this->_pt = $GLOBALS['_pt'];
		$this->_pt->dbcache = false;
		$this->_pt->_require("survey");
		$this->table = $this->_pt->table->{__CLASS__};
		$this->table_record = TABLE_PREFIX."record_meta";
	}

	public function __destruct() { 
		return true;
	}

	public function _getinfo($id) {
		return $this->_pt->get_row("select * from `".$this->table."` where `id`='".$id."'", ARRAY_A);
	}

	public function _getlogin($id) {
		return $this->_pt->get_var("select login from `".$this->table."` where `id`='".$id."'");
	}

	public function _getname($id) {
		return $this->_pt->get_var("select name from `".$this->table."` where `id`='".$id."'");
	}

	public function _getstatus($id) {
		$status = $this->_pt->get_var("select status from `".$this->table."` where `state_id`='".$state_id."' and survey_id='".$survey_id."'");
		return ( !_null($status) ? $status : "belum mula" );
	}

	public function _getidbystate_sql($state_id) {
		$ret = "";
		$data = $this->_pt->get_results("select id from `".$this->table."` where state_id='".$state_id."'", ARRAY_A);
		if ( _array($data) ) {
			while( $rt = @array_shift($data) ) {
				$ret .= "'".$rt['id']."',";
			}
		}
		$ret = rtrim($ret,",");
		return ( !_null($ret) ? "IN (".$ret.")" : "" );
	}

	public function _getidbystatebycategorybysubcategory_sql($state_id, $catid, $subcatid = null) {
		$ret = "";
        $sql = "select id from `".$this->table."` where state_id='".$state_id."'";
        $sql .= " and category_id='".$catid."'";
        if ( !_null($subcatid) ) {
            $sql .= " and subcategory_id='".$subcatid."'";
        }
		$data = $this->_pt->get_results($sql, ARRAY_A);
		if ( _array($data) ) {
			while( $rt = @array_shift($data) ) {
				$ret .= "'".$rt['id']."',";
			}
		}
		$ret = rtrim($ret,",");
		return ( !_null($ret) ? "IN (".$ret.")" : "" );
	}

	public function _save($data) {
		unset($data['_post'], $data['_what']);

		if ( _null($data['nolesen']) ) {
			$this->_pt->json_return(false, _tr("Sila masukkan No. Lesen MPOB!"));
		}
		
		if ( !preg_match("/^\d\d\d\d\d\d\-\d/",$data['nolesen']) ) {
			$this->_pt->json_return(false, _tr("Format No. Lesen tidak sah!. Sila guna (nnnnnn-nnnnnn)"));
		}

		if ( $this->_pt->check_field($this->table, "nolesen", array("nolesen" => $data['nolesen']) ) ) {
			$this->_pt->json_return(false, _tr("No. Lesen telah ada. Sila masukkan nombor lain") );
		}

		if ( _null($data['company']) ) {
			$this->_pt->json_return(false, _tr("Sila masukkan nama syarikat!"));
		}

		if ( _null($data['pegawai']) ) {
			$this->_pt->json_return(false, _tr("Sila masukkan nama pegawai yang melapor!"));
		}

		if ( !_valid_common_text($data['pegawai']) ) {
                        $this->_pt->json_return(false, _tr("Nama pegawai tidah sah, aksara yang dibenarkan \"a-z0-9. `'\" !"));
                }


		if ( _null($data['npass']) || _null($data['rpass']) ) {
			$this->_pt->json_return(false, _tr("Sila masukkan Kata Laluan!"));
		}

		if ( !_null($data['npass']) && !_null($data['rpass']) ) {
			if ( $data['npass'] != $data['rpass'] ) {
	 			$this->_pt->json_return(false, _tr("Kata Laluan tidak padan!"));
			}
			$data['pass'] = _base64_encrypt($data['rpass'],'abahko');
		}
		unset($data['npass'], $data['rpass']);

		$data['address'] = _strip_html_tags($data['address']);

		//foreach(array("postcode","phone","fax") as $n) {
		foreach(array("phone","fax") as $n) {
			if ( !_null($data[$n]) ) {
				$data[$n] = _only_number_etc((string)$data[$n]);
			}
		}

		if ( !_null($data['email']) ) {
			if ( !_check_email($data['email']) ) {
				$this->_pt->json_return(false, _tr("Alamat emel tidak sah '%s'!", $data['email']));
			}
		}

		$data['cdate'] = date('Y-m-d H:i:s');
		$data['ldate'] = date('Y-m-d H:i:s');

		if ( _null($data['status']) ) $data['status'] = 'on';

		if ( $this->_pt->insert($this->table, $data ) != false && _null($this->_pt->last_error) ) {
			$this->_pt->record->_laporfirst($this->_pt->survey->_getcurrent_id(),$this->_pt->insert_id,$data['category_id']);
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
			$query = preg_replace("/\s+and\s+$/","", $query);
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

		if ( _null($data['company']) ) {
			$this->_pt->json_return(false, _tr("Sila masukkan nama syarikat!"));
		}

		if ( _null($data['pegawai']) ) {
			$this->_pt->json_return(false, _tr("Sila masukkan nama pegawai yang melapor!"));
		}

		if ( !_valid_common_text($data['pegawai']) ) {
                        $this->_pt->json_return(false, _tr("Nama pegawai tidah sah, aksara yang dibenarkan \"a-z0-9. `'\" !"));
                }

		if ( $this->_pt->isadmin && !_null($data['nolesen']) ) {
			$this->_pt->query("select nolesen from `".$this->table."` where id!='".$data['id']."' and nolesen='".$this->_pt->escape($data['nolesen'])."'", true);
			if ( $this->_pt->num_rows > 0 ) {
				$this->_pt->json_return(false, _tr("No. Lesen telah ada. Sila masukkan nombor lain") );
			}
			$save['nolesen'] = $data['nolesen'];
		}

		if ( !preg_match("/^\d\d\d\d\d\d\-\d/",$data['nolesen']) ) {
			$this->_pt->json_return(false, _tr("Format No. Lesen tidak sah!. Sila guna (nnnnnn-nnnnnn)"));
		}

		if ( !_null($data['address']) ) $data['address'] = _strip_html_tags($data['address']);
		//foreach(array("postcode","phone","fax") as $n) {
		foreach(array("phone","fax") as $n) {
			if ( !_null($data[$n]) ) {
				$data[$n] = _only_number_etc((string)$data[$n]);
			}
		}

		if ( !_null($data['email']) ) {
			if ( !_check_email($data['email']) ) {
				$this->_pt->json_return(false, _tr("Alamat emel tidak sah '%s'!", $data['email']));
			}
		}

		if ( !_null($data['npass']) && !_null($data['rpass']) ) {
			if ( $data['npass'] != $data['rpass'] ) {
	 			$this->_pt->json_return(false, _tr("Kata Laluan tidak padan!"));
			}
			$data['pass'] = _base64_encrypt($data['rpass'],'abahko');
		}

		unset($data['npass'], $data['rpass']);

		$save = $data;

		$save['ldate'] = date('Y-m-d H:i:s');
		if ( $this->_pt->update($this->table, $save, array('id' => $data['id'] ) ) != false ) {
			$this->_pt->json_return(true, _tr("Data dikemaskini!"));
		}

		$this->_pt->json_return(false, _tr("Tiada data untuk dikemaskini!"));
	}

	public function _statusenabledisable($data) {
		if ( !$this->_pt->isadmin ) {
			$this->_pt->json_return(false, _tr("Akses tidak mencukupi!"));
		}

		unset($data['_post'], $data['_what']);
		$fd = $data;
		if ( !_array($fd['id']) && _null($fd['id']) ) $this->_pt->json_return(false, _tr("Permintaan tidak sah!"));

		if ( !_array($fd['id']) && !_null($fd['id']) ) {
			if ( $this->_pt->query("update `".$this->table."` set `status`='".$data['status']."' where `id`='".$data['id']."'") ) {
				$this->_pt->json_return(true, _tr("Data dikemaskini!") );
			}
		} else {
			$query = "update `".$this->table."` set `status`='".$data['status']."' where 1 and ";
			while( $id = array_shift($fd['id']) ) {
				$query .= sprintf("(id='%d') or ", $id);
			}
			$query = preg_replace("/\s+or\s+$/","", $query);
			$query = preg_replace("/\s+and\s+$/","", $query);
			if ( $this->_pt->query($query) ) {
				$this->_pt->json_return(true, _tr("Data dikemaskini!") );
			}
		}
		$this->_pt->json_return(false, _tr("Kemaskini data tidak berjaya!"));
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

		$query = "select r_respondent.* from `".$this->table."`,`".$this->table_record."` where r_respondent.id = r_record_meta.respondent_id ";
		if ( !_null($this->_pt->request['survey_id']) ) {
			$this->data->search = true;
			$query .= " and r_record_meta.survey_id='".$this->_pt->request['survey_id']."'";
		}
		if ( !$this->_pt->issuperadmin ) {
			$ing = $this->_pt->user->_getgroup_sql($this->_pt->session->data->id);
			if ( !_null($ing) ) {
				$query .= " and r_respondent.category_id {$ing}";
			}
		}
		if ( !_null($this->_pt->request['sstr']) && !_null($this->_pt->request['sopt']) ) {
			$this->data->search = true;
			$query .=' and r_respondent.`'.$this->_pt->request['sopt'].'` like "%'.$this->_pt->request['sstr'].'%"'; 
		}

		if ( !_null($this->_pt->request['state_id']) ) {
			$this->data->search = true;
			$query .=" and r_respondent.`state_id` = '".$this->_pt->request['state_id']."'";
		}

		if ( !_null($this->_pt->request['category_id']) ) {
			$this->data->search = true;
			$query .=" and r_respondent.`category_id` = '".$this->_pt->request['category_id']."'";
		}

		if ( !_null($this->_pt->request['subcategory_id']) ) {
			$this->data->search = true;
			$query .=" and r_respondent.`subcategory_id` = '".$this->_pt->request['subcategory_id']."'";
		}

		if ( !_null($this->_pt->request['status']) ) {
			$this->data->search = true;
			$query .=" and r_respondent.`status` = '".$this->_pt->request['status']."'";
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
