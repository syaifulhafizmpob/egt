<?php
@_object($this) || exit("403 Forbidden");
$this->_notlogin();
$repclass="respondent";
if ( _null($this->tplname) ) $this->tplname = _tplname(__FILE__);
if ( _null($this->_pt->request['survey_id']) ) $this->_pt->request['survey_id'] = $this->survey->_getcurrent_id();

$this->{$repclass}->_getlist();
/* pagging */
$pagging = "";
if ( _num($this->{$repclass}->data->total) 
	&& $this->{$repclass}->data->total > 0
	&& _num($this->{$repclass}->data->pg) && $this->{$repclass}->data->pg > 1 ):

$pagging = "
<div class='x-pagging'>
<div class='x-pagging-inner'>
<input type='button' class='button x-pagging-first' data-pagging='".$this->{$repclass}->data->first."' value='&lt;&lt;'>
<input type='button' class='button x-pagging-prev' data-pagging='".$this->{$repclass}->data->prev."' value='&lt;'>
<input type='text' class='text x-pagging-page' data-paggingval='".$this->{$repclass}->data->next."' value='".($this->{$repclass}->data->pr + 1)."'><span class='x-pagging-page-text'>"._tr('from %d', $this->{$repclass}->data->pg)."</span>
<input type='button' class='button x-pagging-next' data-pagging='".$this->{$repclass}->data->next."' value='&gt;'>
<input type='button' class='button x-pagging-last' data-pagging='".$this->{$repclass}->data->last."' value='&gt;&gt;'>
</div> <!-- /inner -->
</div> <!-- /pagging -->";

endif;

$_colnum = 7;

$html = "";
$html_ops = "";
$html_ops .= "<tr><td colspan='".( $_colnum - 2 )."' class='left' style='padding-left: 2px;'>";
$html_ops .= "<input type='text' class='text stext' name='sstr' placeholder='"._tr("Carian..")."' value='".$this->request['sstr']."'>";
$html_ops .= "<select class='text' name='sopt' style='width: 150px !important; margin-left: 5px; margin-right: 5px;'>";
foreach( array("nolesen" => _tr("No. Lesen MPOB"), "company" => _tr("Syarikat") ) as $x => $y ) {
	$html_ops .= "<option value='".$x."'".($this->request['sopt'] == $x ? " selected" : "").">".$y."</option>";
}
$html_ops .= "</select><button class='button button_add' name='btsearch'>"._tr("Carian")."</button>";
$html_ops .= "<button class='button button_add' style='margin-left: 2px;' name='btreloadgrid'>"._tr("Reset")."</button>";
$html_ops .= "</td>";
$html_ops .= "<td colspan='2' class='right' style='padding-right: 2px; padding-bottom: 5px;'>";
$html_ops .= "<button class='button button_add' name='btadd'>"._tr("Tambah Pelesen")."</button></td>";
$html_ops .= "</tr>";

$html_ops .= "<tr><td colspan='".$_colnum."' class='left' style='padding-left: 2px;'>";
$html_ops .= "<select name='survey_id' class='text' style='width:160px!important;'>";
$list = $this->survey->_listsurvey();
if ( _array($list) ) {
	while( $dt = @array_shift($list) ) {
		if ( _null($survey_id) ) {
			$survey_id = ( !_null($this->request['survey_id']) ? $this->request['survey_id'] : $dt['id'] );
		}
		$html_ops .="<option value='".$dt['id']."'".( $this->request['survey_id'] == $dt["id"] ? " selected" : "").">".utf8_strtoupper($dt['month'])."-".utf8_ucwords($dt['year'])."</option>";
	}
}
$html_ops .= "</select>";
$html_ops .= "<select class='text' name='status' style='width: 150px !important; margin-left: 0px; margin-right: 2px;'>";
$html_ops .= "<option value=''>Filter Status</option>";
foreach( array("on" => "Aktif", "off" => "Nyah-Aktif" ) as $x => $y ) {
	$html_ops .= "<option value='".$x."'".( $this->request['status'] == $x ? " selected" : "" ).">".$y."</option>";
}
$html_ops .= "</select>";
$html_ops .= "<select class='text' name='state_id' style='width: 250px !important; margin-left: 5px; margin-right: 2px;'>";
$html_ops .= "<option value=''>Filter negeri</option>";
$list = $this->display->_liststate();
if ( _array($list) ) {
	while( $dt = @array_shift($list) ) {
		$html_ops .= "<option value='".$dt['id']."'".( $this->request['state_id'] == $dt['id'] ? " selected" : "" ).">".utf8_ucwords($dt['name'])."</option>";
	}
}
$html_ops .= "</select>";
$html_ops .= "<select class='text' name='category_id' style='width: 150px !important; margin-left: 2px; margin-right: 2px;'>";
//$html_ops .= "<option value=''>Filter kategori</option>";
if ( !$this->issuperadmin ) {
$grp = ( $this->user->_ingroup_type($this->session->data->id,"peniaga") ? "peniaga" : "kilang" );
$list = $this->display->_listcategory_respondent($grp);
} else {
$list = $this->display->_listcategory_respondent();
}
if ( _array($list) ) {
	while( $dt = @array_shift($list) ) {
		$html_ops .= "<option value='".$dt['id']."'".( $this->request['category_id'] == $dt['id'] ? " selected" : "" ).">".utf8_ucwords($dt['name'])."</option>";
	}
}
$html_ops .= "</select>";

if ( $this->request['category_id'] == "02" && $this->user->_ingroup_type($this->session->data->id,"peniaga") ) {

	$html_ops .= "<select class='text' name='subcategory_id' style='width: 150px !important; margin-left: 2px; margin-right: 2px;'>";
	$html_ops .= "<option value=''>Filter Sub kategori</option>";
	$list = $this->display->_listsubcategory("peniaga");
	if ( _array($list) ) {
		while( $dt = @array_shift($list) ) {
			$html_ops .= "<option value='".$dt['id']."'".( $this->request['subcategory_id'] == $dt['id'] ? " selected" : "" ).">".utf8_ucwords($dt['name'])."</option>";
		}
	}
	$html_ops .= "</select>";

}
$html_ops .= "</td>";
$html_ops .= "</tr>";

if ( _num($this->{$repclass}->data->total) && $this->{$repclass}->data->total > 0 ) {
	$html .= "<center><table id='x-table1'>";
	if ( !_null($pagging) ) {
		$html .= "<tr><td colspan='".$_colnum."' class='right' style='padding-right: 2px;'>".$pagging."</td></tr>";
	}

	$html .= $html_ops;

	$html .= "<tr>";
	$html .= "<td colspan='".$_colnum."' class='left' style='padding: 5px 0px 5px 2px;'>";
	$html .= "<span class='anote'>"._tr("Total Records %d", $this->{$repclass}->data->total)."</span>";
	$html .= "</td></tr>";

	$html .= "<tr>";
	$html .= "<th class='left' style='width: 50px;'>"._tr("Kemaskini")."</th>";
	$html .= "<th class='center' style='width: 50px;'>"._tr("Status")."</th>";
	$html .= "<th class='right'>#</th>";
	$html .= "<th>"._tr("No. Lesen")."</th>";
	$html .= "<th>"._tr("Kategori")."</th>";
	$html .= "<th>"._tr("Syarikat")."</th>";
	$html .= "<th>"._tr("Negeri")."</th>";
	$html .= "</tr>";
	$cnt = $this->{$repclass}->data->cnt;
	while( $data = @array_shift($this->{$repclass}->data->data) ) {
		$subcat = $this->display->_getsubcategory_name($data['subcategory_id']);
        $type = $this->display->_getcategory_type($data['category_id']);
        $cat = $this->display->_getcategory_name($data['category_id']);
		if ( $type != "kilang" && !_null($subcat) ) {
            $subcat = "<br>- ".$subcat;
        } else {
            $subcat = "";
        }
		$html .= "<tr class='row' data-id='".$data['id']."'>";
		$html .= "<td class='border-left border-bottom left' style='width: 50px;'><input type='checkbox' name='del[".$data['id']."]' value='1'><img data-del='".$data['id']."' class='del' src='".$this->pbaseurl."/rsc/delete.png?".$this->_cupdate."' data-tooltip='"._tr("Padam")."'><img data-edit='".$data['id']."' class='edit' src='".$this->pbaseurl."/rsc/edit.png?".$this->_cupdate."' data-tooltip='"._tr("Ubah")."'></td>";
		$html .= "<td class='border-left border-bottom center' style='width: 50px;'><img class='status' data-stat='".$data['id']."|".$data['status']."' src='".$this->pbaseurl."/rsc/".$data['status'].".gif?".$this->_cupdate."' data-tooltip='".( $data['status'] == "on" ? _tr("Nyah-Aktif") : _tr("Aktif") )."'></td>";		
		$html .= "<td class='border-left border-bottom right info' style='width: 10px;'>".$cnt."</td>";
		$html .= "<td class='border-left border-bottom info'>".$data['nolesen']."</td>";
		$html .= "<td class='border-left border-bottom info'>".$cat.$subcat."</td>";
		$html .= "<td class='border-left border-bottom info'>".$data['company']."</td>";
		$html .= "<td class='border-left border-right border-bottom'>".utf8_ucwords($this->display->_getstate_name($data['state_id']))."</td>";
		$html .= "</tr>";
		$cnt++;
	}
	$html .= "<tr class='row_active'>";
	$html .= "<td colspan='".$_colnum."' class='left'><input type='checkbox' name='chkdel' data-click='msel'><img data-click='mdel' class='del' src='".$this->pbaseurl."/rsc/delete.png?".$this->_cupdate."'><img data-click='menable' class='del' src='".$this->pbaseurl."/rsc/on.gif?".$this->_cupdate."'><img data-click='mdisable' class='del' style='margin-left:0px;' src='".$this->pbaseurl."/rsc/off.gif?".$this->_cupdate."'></td>";
	$html .= "</tr>";
	$html .= "</table></center>";
} else {
	$html .= "<center><table id='x-table1'>";
        if ( $this->{$repclass}->data->search ) {
		$html .= $html_ops;
		$html .= "<tr><td colspan='".$_colnum."' class='border-left border-right border-bottom border-top'>"._tr("Tiada data untuk padanan carian")."</td></tr>";
        } else {
		$html .= "<tr><td class='right'>";
		$html .= "<button class='button button_add' name='btadd'>"._tr("Tambah Pelesen")."</button></td></tr>";
		$html .= "<tr><td class='center'>"._tr("Tiada data yang tersedia")."</td></tr>";
	}	
	$html .= "</table></center>";
}
_E($html);
if ( _null($this->request['category_id']) ):
?>
<script>
$("select[name=category_id]").trigger("change")
</script>
<?php endif; ?>
