<?php
/**
 * Msg Class
 *
 * @author Mohd Nawawi Mohamad Jamili <nawawi@rutweb.com>
 * @category library
 */

class msg {
	public $uploadpath = null;
	public function __construct() {
		if ( !_obj_caller() ) {
			_exit( _t("Error(%s): Invalid object",__CLASS__) );
		}
		$this->_pt = $GLOBALS['_pt'];
		$this->_pt->dbcache = false;
		$this->table = $this->_pt->table->{__CLASS__};
		if ( defined('UPLOADPATH') ) {
                        $this->uploadpath  = UPLOADPATH.'/msg';
                        if ( !is_dir($this->uploadpath) ) {
                                if ( file_exists($this->uploadpath) ) @unlink($this->uploadpath);
                                _mkdir($this->uploadpath,0777,true);
                        } else {
                                _chmod($this->uploadpath, 0777);
                        }
                }

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

	public function _download($data) {
		$id = $data['id'];
		if ( !_null($id) ) {
			$file = $this->_pt->get_var("select file from `".$this->table."` where id='".$id."'");
			$fpath = $this->uploadpath."/".$file;
			if ( file_exists($fpath) ) {
				$size =  sprintf("%u", filesize($fpath) );
                                _nocache(array('Content-type'=> 'application/force-download', 'Content-length'=> $size, 'Content-Transfer-Encoding'=>'binary'));
                                header("Content-Disposition: attachment; filename=\"".basename($fpath)."\";");
				@readfile($fpath);
			}
		}
		exit;
	}

	private function _upload($pid,$data) {
                if ( !is_dir($this->uploadpath) ) return false;
                $fpath = $this->uploadpath."/".$pid;
                if ( !is_dir($fpath) ) {
                        _mkdir($fpath,0777,true);
                }

                $data['name'] = strtolower($data['name']);

		if ( !_get_upload_max_size() ) {
			$this->_pt->json_return(false, _tr("Saiz fail melebihi had!") );
		}

		if ( !_check_upload_filename($data['name']) ) {
			$this->_pt->json_return(false, _tr("Nama fail lampiran tidak sah!") );
		}

		$allow = array("doc", "docx", "pdf");
		$ext = strtolower( array_pop( explode( '.', $data['name']) ) );
		if ( !in_array($ext, $allow) ) {
			$this->_pt->json_return(false, _tr("Jenis fail lampiran tidak dibenarkan, hanya .doc,.docx dan .pdf yang dibenarkan!") );
		}

                if ( @move_uploaded_file($data['tmp_name'], $fpath."/". $data['name']) ) {
                        @chmod($fpath."/".$data['name'],0666);
                        return $pid."/".$data['name'];
                }
                return false;
        }

	public function _save($data) {
		unset($data['_post'], $data['_what']);
		if ( _null($data['subject']) ) {
			$this->_pt->json_return(false, _tr("Sila masukkan subjek!" ));
		}

		if ( _null($data['msg']) ) {
			$this->_pt->json_return(false, _tr("Sila masukkan mesej!" ));
		}

		if ( _array($_FILES['file']) && !_null($_FILES['file']['name']) ) {
			if ( ( $data['file'] = $this->_upload($this->_pt->session->data->id, $_FILES['file']) ) == false ) {
		                $this->_pt->json_return(false, _tr("Muat-naik fail lampiran tidak berjaya!") );
		        }
		}
		$data['status'] = "2";
		$data['cdate'] = date('Y-m-d H:i:s');
		$data['ldate'] = date('Y-m-d H:i:s');


		if ( $this->_pt->insert($this->table, $data ) != false && _null($this->_pt->last_error) ) {
			$this->_pt->json_return(true, _tr("Mesej berjaya dihantar!") );
		}
		$this->_pt->json_return(false, _tr("Mesej tidak berjaya dihantar!"));
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

	public function _getlist() {
		$this->data = (object)null;
		$this->data->data = array();
		$this->data->num = null;
		$this->data->first = null;
		$this->data->last = null;
		$this->data->next = null;
		$this->data->prev = null;
		$this->data->search = false;

		if ( _null($this->_pt->request['_mr']) ) $this->_pt->request['_mr'] = 10;
		if ( _null($this->_pt->request['_pr']) ) $this->_pt->request['_pr'] = 0;

		$_pr = $this->_pt->request['_pr'];

		$max_row = $this->_pt->request['_mr'];
		$start_row=@($_pr * $max_row);

		$query = "select * from `".$this->table."` where sender_id='".$this->_pt->session->data->id."'";
		if ( !_null($this->_pt->request['sstr']) && !_null($this->_pt->request['sopt']) ) {
			$this->data->search = true;
			$query .=' and `'.$this->_pt->request['sopt'].'` like "%'.$this->_pt->request['sstr'].'%"'; 
		}
		if ( !_null($this->_pt->request['statusopt']) ) {
			$this->data->search = true;
			$query .= " and status='".$this->_pt->request['statusopt']."'";
		}

		$query .=" order by `cdate` DESC";
		//echo $query;
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
