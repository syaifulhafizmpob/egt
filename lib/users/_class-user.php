<?php
/**
 * User Class
 *
 * @author Mohd Nawawi Mohamad Jamili <nawawi@rutweb.com>
 * @category library
 */

class user {
	public function __construct() {
		if ( !_obj_caller() ) {
			_exit( _t("Error(%s): Invalid object",__CLASS__) );
		}
		$this->_pt = $GLOBALS['_pt'];
		$this->_pt->dbcache = false;
		$this->table = $this->_pt->table->session;
        $this->table_record_meta = TABLE_PREFIX."record_meta";
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

	public function _ingroup_type($user_id,$type) {
		$sql = "SELECT r_respondent.*,r_category.type as type FROM `r_respondent`,r_category WHERE r_category.id = r_respondent.category_id and r_respondent.id='".$user_id."' and r_category.type = '".$type."'";
        //echo $sql;		
        $ret = $this->_pt->get_var($sql);
		return ( !_null($ret) ? true : false );
	}

	public function _adminview() {
		if ( !_null($_GET['ord'])  ) {
			$lesen = _base64_decrypt($_GET['ord'],'waklu');
			$uid = _base64_decrypt($_GET['u'],'waklu');
			$level = _base64_decrypt($_GET['l'],'waklu');
            $utime = date('Y-m-d H:i:s');
			$pd = $this->_pt->get_row($this->_pt->prepare("select * from `".$this->table."` where nolesen=%s", $lesen ),ARRAY_A); 
			$po = array_merge($pd, array("adminview" => "1","adminid"=>$uid, "adminlevel" => $level ));
			$this->_pt->session->set_session($po, $this->session_timeout);
            @$this->_pt->update($this->table_record_meta, array("user_id" => $uid, "udate" => $utime), array("respondent_id" => $pd['id']) );
			_redirect($this->_pt->baseurl);
			exit;
		}
	}

    public function _admin_getinfo($respondent_id) {
        return $this->_pt->get_row("select * from `".$this->table_record_meta."` where `respondent_id`='".$respondent_id."'", ARRAY_A);
    }

	public function _checklogin() {
		if ( !_null($this->_pt->post['uname']) && !_null($this->_pt->post['upass']) ) {
			$pd = $this->_pt->get_row($this->_pt->prepare("select * from `".$this->table."` where nolesen=%s and pass=%s", $this->_pt->post['uname'], _base64_encrypt($this->_pt->post['upass'],'abahko') ),ARRAY_A);
			if ( _array($pd) ) {
				if ( $pd['status'] != 'on' ) {
					return $this->_pt->session->status(false, _tr("Akaun dibekukan!"),
								array(
									'login' => $pd['nolesen'],
									'ip' => $this->_pt->uip,
									'uagent' => $this->_pt->uagent,
									'access' => $pd['level']
								) 
							);
				}
				if ( $this->_pt->session->set_session($pd, $this->session_timeout) ) {			
					$this->_pt->query(
						$this->_pt->prepare("update `".$this->table."` set lastlogin=NOW(),lastip=%s,uagent=%s where id=%d",$this->_pt->uip, $this->_pt->uagent, $pd['id'])
					);
					return $this->_pt->session->status(true, _tr("Akses berjaya!"),
								array(
									'login' => $pd['nolesen'],
									'ip' => $this->_pt->uip,
									'uagent' => $this->_pt->uagent,
									'access' => $pd['level']
								) 
							);
				}
			}
		}
		return $this->_pt->session->status(false, _tr("Akses gagal!"),
						array(
							'login' => $this->_pt->post['uname'],
							'password' => $this->_pt->post['upass'],
							'uagent' => $this->_pt->uagent,
							'ip' => $this->_pt->uip
						) 
					);
	}

	public function _profile($data) {
		unset($data['_post'], $data['_what']);

		$save = array();

		if ( _null($data['id']) ) $this->_pt->json_return(false, _tr("Permintaan tidak sah!"));


		if ( !_null($data['npass']) && !_null($data['rpass']) ) {
			if ( $data['npass'] != $data['rpass'] ) {
	 			$this->_pt->json_return(false, _tr("Kata Laluan tidak padan!"));
			}
			$save['pass'] = _base64_encrypt($data['rpass'],'abahko');
		}


		if ( _array($save) ) {
			$save['ldate'] = date('Y-m-d H:i:s');
			if ( $this->_pt->update($this->table, $save, array('id' => $data['id'] ) ) != false ) {
				$this->_pt->json_return(true, _tr("Data dikemaskini!"));
			}
		}
		$this->_pt->json_return(false, _tr("Tiada data untuk dikemaskini!"));
	}

	public function _update_info($data) {
		unset($data['_post'], $data['_what']);

		$save = array();

		if ( _null($data['id']) ) $this->_pt->json_return(false, _tr("Permintaan tidak sah!"));

		if ( _null($data['pegawai']) ) {
			$this->_pt->json_return(false, _tr("Sila masukkan nama pegawai yang melapor!"));
		}

		if ( !_valid_common_text($data['pegawai']) ) {
                        $this->_pt->json_return(false, _tr("Nama pegawai tidah sah, aksara yang dibenarkan \"a-z0-9. `'\" !"));
                }

		if ( _null($data['pegawai']) ) {
			$this->_pt->json_return(false, _tr("Sila masukkan nama pegawai yang melapor!"));
		}

		if ( _null($data['jawatan']) ) {
			$this->_pt->json_return(false, _tr("Sila masukkan jawatan pegawai yang melapor!"));
		}

		if ( _null($data['phone']) ) {
			$this->_pt->json_return(false, _tr("Sila masukkan nombor telefon!"));
		}

		if ( !_null($data['address']) ) $data['address'] = _strip_html_tags($data['address']);
		/*foreach(array("postcode","phone","fax") as $n) {
			if ( !_null($data[$n]) ) {
				$data[$n] = _only_number_etc((string)$data[$n]);
			}
		}*/
		if ( !_null($data['email']) ) {
			if ( !_check_email($data['email']) ) {
				$this->_pt->json_return(false, _tr("Alamat emel tidak sah '%s'!", $data['email']));
			}
		}

        if ( !_null($data['sdate']) ) {
            @$this->_pt->update($this->table_record_meta, array("sdate" => $this->_pt->_input_date($data['sdate']) ), array("respondent_id"=>$this->_pt->session->data->id) );
            unset($data['sdate']);
        }

		$save = $data;

		$save['ldate'] = date('Y-m-d H:i:s');
		if ( $this->_pt->update($this->table, $save, array('id' => $data['id'] ) ) != false ) {
			$this->_pt->json_return(true, _tr("Data dikemaskini!"));
		}

		$this->_pt->json_return(false, _tr("Tiada data untuk dikemaskini!"));
	}

}

?>
