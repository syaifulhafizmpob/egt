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
		$this->_pt->_require("display");
		$this->_pt->dbcache = false;
		$this->table = $this->_pt->table->{__CLASS__};
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

	public function _getlogin($id) {
		return $this->_pt->get_var("select login from `".$this->table."` where `id`='".$id."'");
	}

	public function _getname($id) {
		return $this->_pt->get_var("select name from `".$this->table."` where `id`='".$id."'");
	}


	public function _checklogin() {
		if ( !_null($this->_pt->post['uname']) && !_null($this->_pt->post['upass']) ) {
			$pd = $this->_pt->get_row($this->_pt->prepare("select * from `".$this->table."` where login=%s and pass=MD5(%s)", $this->_pt->post['uname'], $this->_pt->post['upass'] ),ARRAY_A);
			if ( _array($pd) ) {
				if ( $pd['status'] != 'on' ) {
					return $this->_pt->session->status(false, _tr("Akaun dibekukan!"),
								array(
									'login' => $pd['login'],
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
									'login' => $pd['login'],
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

	public function _ingroup($user_id,$category_id) {
		//if ( $this->_pt->session->data->level == "superadmin" ) return true;
		$ret = $this->_pt->get_var("select category_id from `".$this->table_group."` where user_id='".$user_id."' and category_id='".$category_id."'");
		return ( !_null($ret) ? true : false );
	}

	public function _ingroup_type($user_id,$type) {
		if ( $this->_pt->session->data->level == "superadmin" ) return true;
		$sql = "SELECT r_user_group.*,r_category.type as type FROM `r_user_group`,r_category WHERE r_category.id = r_user_group.category_id and r_user_group.user_id='".$user_id."' and r_category.type = '".$type."'";
		$ret = $this->_pt->get_var($sql);
		return ( !_null($ret) ? true : false );
	}

	public function _getgroup_sql($user_id) {
		$ret = "";
		$data = $this->_pt->get_results("select category_id from `".$this->table_group."` where user_id='".$user_id."'", ARRAY_A);
		if ( _array($data) ) {
			while( $rt = @array_shift($data) ) {
				$ret .= "'".$rt['category_id']."',";
			}
		}
		$ret = rtrim($ret,",");
		return ( !_null($ret) ? "IN (".$ret.")" : "" );
	}

	public function _getgroup($user_id) {
		$ret = "";
		$data = $this->_pt->get_results("select category_id from `".$this->table_group."` where user_id='".$user_id."'", ARRAY_A);
		if ( _array($data) ) {
			while( $rt = @array_shift($data) ) {
				$ret .= $this->_pt->display->_getcategory_name($rt['category_id'])." ";
			}
		}
		return ( _null($ret) ? "-none-" : $ret );
	}

	public function _save_groups($user_id, $groups) {
		@$this->_pt->query("delete from `".$this->table_group."` where user_id='".$user_id."'");
		foreach($groups as $id => $n ) {
			$data['user_id'] = $user_id;
			$data['category_id'] = $id;
			$this->_pt->insert($this->table_group, $data );
		}
	}

	public function _save($data) {
		unset($data['_post'], $data['_what']);

		if ( _null($data['login']) ) {
			$this->_pt->json_return(false, _tr("Sila masukkan Id Pengguna!"));
		}

		if ( !_valid_login_name($data['login']) ) {
                        $this->_pt->json_return(false, _tr("Id Pengguna tidak sah, aksara yang dibenarkan 'a-z0-9.' !"));
                }

		if ( $this->_pt->check_field($this->table, "login", array("login" => $data['login']) ) ) {
			$this->_pt->json_return(false, _tr("Id Pengguna telah ada. Sila pilih yang lain!") );
		}

		if ( _null($data['name']) ) {
			$this->_pt->json_return(false, _tr("Sila masukkan nama penuh!"));
		}

		if ( !_valid_common_text($data['name']) ) {
                        $this->_pt->json_return(false, _tr("Nama Penuh tidak sah, aksara yang dibenarkan \"a-z0-9. `'\" !"));
                }

		if ( $this->_pt->check_field($this->table, "name", array("name" => $data['name']) ) ) {
			$this->_pt->json_return(false, _tr("Nama Penuh telah ada. Sila pilih yang lain!") );
		}

		if ( _null($data['npass']) || _null($data['rpass']) ) {
			$this->_pt->json_return(false, _tr("Sila masukkan katalaluan!"));
		}

		if ( !_null($data['npass']) && !_null($data['rpass']) ) {
			if ( $data['npass'] != $data['rpass'] ) {
	 			$this->_pt->json_return(false, _tr("Katalaluan tidak padan!"));
			}
			$data['pass'] = md5($data['rpass']);
		}
		unset($data['npass'], $data['rpass']);

		if ( !_null($data['email']) && !_check_email($data['email']) ) {
			$this->_pt->json_return(false, _tr("Alamat Emel tidak sah '%s'!", $data['email']) );
		}

		if ( _null($data['level']) ) $data['level'] = 'staff';
		
		if ( preg_match("/^admin_(.*?)/", $data['level'], $mm) ) {
			if ( $mm[1] == "kilang" || $mm[1] == "peniaga" ) {
				$catl= $this->_pt->display->_listcategory($mm[1]);
				if ( _array($catl) ) {
					while( $rt = @array_shift($catl) ) {
						$data['groups'][$rt['id']] = "on";
					}
				}
			}
		}

		if ( !_null($data['groups']) ) {
			$groups = $data['groups'];
			unset($data['groups']);
		}

		$data['desc'] = _strip_html_tags($data['desc']);
		$data['cdate'] = date('Y-m-d H:i:s');
		$data['ldate'] = date('Y-m-d H:i:s');

		if ( _null($data['status']) ) $data['status'] = 'on';

		if ( $this->_pt->insert($this->table, $data ) != false && _null($this->_pt->last_error) ) {
			if ( !_null($groups) ) $this->_save_groups($this->_pt->insert_id, $groups);

			$this->_pt->json_return(true, _tr("Data dikemaskini!") );
		}
		$this->_pt->json_return(false, _tr("Kemaskini data tidak berjaya!"));
	}

	public function _delete($data) {
		unset($data['_post'], $data['_what']);
		$fd = $data;
		if ( !_array($fd['id']) && _null($fd['id']) ) $this->_pt->json_return(false, _tr("Permintaan tidak sah!"));

		if ( !_array($fd['id']) && !_null($fd['id']) ) {
			if ( $fd['id'] == $this->_pt->session->data->id ) {
				$this->_pt->json_return(false, _tr("Tidak boleh buang diri anda sendiri!"));
			}
			if ( $this->_pt->query("delete from `".$this->table."` where `id`='".$data['id']."'") ) {
				$this->_pt->json_return(true, _tr("Data dikemaskini!") );
			}
		} else {
			$query = "delete from `".$this->table."` where id!='".$this->_pt->session->data->id."' and ";
			while( $id = array_shift($fd['id']) ) {
				if ( $id != $this->_pt->session->data->id ) {
					$query .= sprintf("(id='%d') or ", $id);
				}
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

		if ( _null($data['name']) ) {
			$this->_pt->json_return(false, _tr("Sila masukkan nama penuh!"));
		}
		if ( !_valid_common_text($data['name']) ) {
                        $this->_pt->json_return(false, _tr("Nama Penuh tidak sah, aksara yang dibenarkan \"a-z0-9. `'\" !"));
                }
		$this->_pt->query("select name from `".$this->table."` where id!='".$data['id']."' and name='".$this->_pt->escape($data['name'])."'", true);
		if ( $this->_pt->num_rows > 0 ) {
			$this->_pt->json_return(false, _tr("Nama Penuh telah ada. Sila pilih yang lain") );
		}
		$save['name'] = $data['name']; 

		if ( $this->_pt->isadmin && !_null($data['login']) ) {
			$this->_pt->query("select login from `".$this->table."` where id!='".$data['id']."' and login='".$this->_pt->escape($data['login'])."'", true);
			if ( $this->_pt->num_rows > 0 ) {
				$this->_pt->json_return(false, _tr("Id Pengguna telah ada. Sila pilih yang lain") );
			}
			$save['login'] = $data['login'];
		}

		if ( $this->_pt->isadmin && !_null($data['level']) ) $save['level'] = $data['level'];
		if ( !_null($data['desc']) ) $save['desc'] = _strip_html_tags($data['desc']);

		if ( ( !$this->_pt->isadmin || !_null($data['doprofile']) ) && !_null($data['opass']) ) {
			$this->_pt->query("select `pass` from `".$this->table."` where `pass`='".md5($data['opass'])."' and `id`='".$data['id']."'", true);
			if ( $this->_pt->num_rows == 0 )  $this->_pt->json_return(false, _tr("Katalaluan lama tidak padan!"));
			if ( _null($data['npass']) || _null($data['rpass']) ) {
 				$this->_pt->json_return(false, _tr("Katalaluan tidak padan!"));
			}
			unset($data['doprofile']);
		}

		if ( !_null($data['npass']) && !_null($data['rpass']) ) {
			if ( $data['npass'] != $data['rpass'] ) {
	 			$this->_pt->json_return(false, _tr("Katalaluan tidak padan!"));
			}
			$save['pass'] = md5($data['rpass']);
		}

		if ( $this->_pt->isadmin && !_null($data['status']) ) {
			if ( $data['status'] != 'on' && $data['id'] == $this->_pt->session->data->id ) {
				$this->_pt->json_return(false, _tr("Tidak boleh nyah-aktif diri anda sendiri!"));
			}
			$save['status'] = $data['status'];
		}

		if ( !_null($data['email']) ) {
			if ( !_check_email($data['email']) ) {
				$this->_pt->json_return(false, _tr("Alamat Emel tidak sah '%s'!", $data['email']) );
			}
			$save['email'] = $data['email'];
		}

		if ( preg_match("/^admin\_(peniaga|kilang)/", $data['level'], $mm) ) {
			if ( !_null($mm[1]) ) {
				$catl= $this->_pt->display->_listcategory($mm[1]);
				if ( _array($catl) ) {
					while( $rt = @array_shift($catl) ) {
						$data['groups'][$rt['id']] = "on";
					}
				}
			}
		}

		$groups = ( !_null($data['groups']) ? $data['groups'] : array() );
		unset($data['groups']);

		if ( _array($save) || _array($groups) ) {
			$save['ldate'] = date('Y-m-d H:i:s');
			if ( $this->_pt->update($this->table, $save, array('id' => $data['id'] ) ) != false ) {
				$this->_save_groups($data['id'], $groups);
				$this->_pt->json_return(true, _tr("Data dikemaskini!"));
			}
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
			if ( $data['status'] != 'on' && $data['id'] == $this->_pt->session->data->id ) {
				$this->_pt->json_return(false, _tr("Tidak boleh nyah-aktif diri anda sendiri!"));
			}
			if ( $this->_pt->query("update `".$this->table."` set `status`='".$data['status']."' where `id`='".$data['id']."'") ) {
				$this->_pt->json_return(true, _tr("Data dikemaskini!") );
			}
		} else {
			$query = "update `".$this->table."` set `status`='".$data['status']."' where id!='".$this->_pt->session->data->id."' and ";
			while( $id = array_shift($fd['id']) ) {
				if ( $id != $this->_pt->session->data->id ) {
					$query .= sprintf("(id='%d') or ", $id);
				}
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

		if ( _null($this->_pt->request['_mr']) ) $this->_pt->request['_mr'] = 50;
		if ( _null($this->_pt->request['_pr']) ) $this->_pt->request['_pr'] = 0;

		$_pr = $this->_pt->request['_pr'];

		$max_row = $this->_pt->request['_mr'];
		$start_row=@($_pr * $max_row);

		$admin_s = false;
		
		if ( preg_match("/^admin_(peniaga|kilang)/", $this->_pt->session->data->level, $mm) ) {
			$admin_level = $mm[1];
			$admin_s = true;
		}

		if ( $admin_s ) {
			$query = "SELECT a.* from `r_user` a, r_user_group b, r_category c WHERE c.type = '{$admin_level}' and b.user_id = a.id and c.id = b.category_id";
		} else {
			$query = "select * from `".$this->table."` where 1";
		}

		if ( !_null($this->_pt->request['sstr']) && !_null($this->_pt->request['sopt']) ) {
			$this->data->search = true;
			$query .=' and `'.$this->_pt->request['sopt'].'` like "%'.$this->_pt->request['sstr'].'%"'; 
		}
		if ( $admin_s ) {
			$query .=" group by `id` DESC";
		} else {
			$query .=" order by `id`,`cdate` DESC";
		}

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
