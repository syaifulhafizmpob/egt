<?php
@_object($this) || exit("403 Forbidden");
$this->_notlogin();
$repclass="form";

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

$_colnum = 11;

$html = "";
$html_ops = "";
$html_ops .= "<tr><td colspan='".$_colnum."' class='left' style='padding-left: 2px;'>";
$html_ops .= "<input type='text' class='text stext' name='sstr' placeholder='"._tr("Carian..")."' value='".$this->request['sstr']."'>";
$html_ops .= "<select class='text' name='sopt' style='width: 150px !important; margin-left: 5px; margin-right: 5px;'>";
foreach( array("nolesen" => _tr("No. Lesen MPOB"), "company" => _tr("Syarikat"), "year" => _tr("Tahun") ) as $x => $y ) {
	$html_ops .= "<option value='".$x."'".($this->request['sopt'] == $x ? " selected" : "").">".$y."</option>";
}
$html_ops .= "</select><button class='button button_add' name='btsearch'>"._tr("Carian")."</button>";


$html_ops .= "<button class='button button_add' style='margin-left: 2px;' name='btreloadgrid'>"._tr("Reset")."</button>";
$html_ops .= "</td>";
$html_ops .= "</tr>";

$html_ops .= "<tr><td colspan='".$_colnum."' class='left' style='padding-left: 2px;'>";
$html_ops .= "<select name='survey_id' class='text' style='width:160px!important;'>";
$list = $this->survey->_listsurvey();
if ( _array($list) ) {
	while( $dt = @array_shift($list) ) {
        if ( _null($this->request['survey_id']) && $dt['status'] == "on" ) {
            $this->request['survey_id'] = $dt['id'];
        }
		$html_ops .="<option value='".$dt['id']."'".( $this->request['survey_id'] == $dt["id"] ? " selected" : "").">".utf8_strtoupper($dt['month'])."-".utf8_ucwords($dt['year'])."</option>";
	}
}
$html_ops .= "</select>";
$html_ops .= "<select class='text' name='status' style='width: 150px !important; margin-left: 5px; margin-right: 2px;'>";
//$html_ops .= "<option value=''>Filter Status</option>";
foreach( $this->form->pstatus as $x => $y ) {
    if ( _null($this->request['status']) && $x == "telah_diterima" ) {
        $this->request['status'] = $x;
    }
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
	$html .= "<th class='right'>#</th>";
	$html .= "<th style='width: 100px;'>Status</th>";
	$html .= "<th style='width: 100px;'>"._tr("No. Lesen")."</th>";
	$html .= "<th>"._tr("Syarikat")."</th>";
	$html .= "<th>"._tr("Kategori")."</th>";
	$html .= "<th>"._tr("Negeri")."</th>";
	$html .= "<th style='width:100px;'>"._tr("Tahun")."</th>";
	$html .= "<th style='width:100px;'>"._tr("Tarikh hantar")."</th>";
	$html .= "<th style='width:100px;'>"._tr("Tarikh Kemaskini")."</th>";
	$html .= "<th>"._tr("Staff")."</th>";
	$html .= "<th>"._tr("Operasi")."</th>";
	$html .= "</tr>";
	$cnt = $this->{$repclass}->data->cnt;
	while( $data = @array_shift($this->{$repclass}->data->data) ) {
		$info = $this->form->_getinfo($data['respondent_id']);
		$subcat = "";
		if ( $this->user->_ingroup_type($this->session->data->id,"peniaga") ) {
			$subcat = $this->display->_getsubcategory_name($data['subcategory_id']);
			if ( !_null($subcat) ) $subcat = "<br>- ".$subcat;
		}

		$html .= "<tr class='row' data-id='".$data['id']."'>";
		$html .= "<td class='border-left border-bottom right' style='width: 10px;'>".$cnt."</td>";
		$html .= "<td class='border-left border-bottom'>".$this->form->pstatus[$data['status']]."</td>";		

		$html .= "<td class='border-left border-bottom'>".$info['nolesen']."</td>";
		$html .= "<td class='border-left border-bottom'>".$info['company']."</td>";
		$html .= "<td class='border-left border-bottom'>".$this->display->_getcategory_name($info['category_id']).$subcat."</td>";
		$html .= "<td class='border-left border-bottom'>".$this->display->_getstate_name($info['state_id'])."</td>";
		$html .= "<td class='border-left border-bottom'>".$this->survey->_getyear($data['survey_id'])."</td>";
		$html .= "<td class='border-left border-bottom'>".$this->_output_date($data['sdate'],"%d-%m-%Y")."</td>";
		$html .= "<td class='border-left border-bottom'>".$this->_output_date($data['udate'],"%d-%m-%Y")."</td>";
		$html .= "<td class='border-left border-bottom'>".$this->user->_getname($data['user_id'])."</td>";
		$html .= "<td class='border-left border-right border-bottom'>";
        $html .= "<table class='tbbodo'><tr>";
        $html .= "<tr><td><button name='hpedit' class='button btbodo' data-edit='".$data['id']."' data-sid='".$data['survey_id']."' data-rid='".$data['respondent_id']."'>Edit Status</button></td>";
		$html .= "<td><button name='hpadmin' class='button btbodo' data-ord='"._base64_encrypt($info['nolesen'],'waklu')."'>Edit Form</button></td>";
        $html .= "</tr>";
		$html .= "<td><button name='hview' class='button btbodo' data-sid='".$data['survey_id']."' data-rid='".$data['respondent_id']."'>Paparan</button></td>";
		$html .= "<td><button name='hprint' class='button btbodo' data-sid='".$data['survey_id']."' data-rid='".$data['respondent_id']."'>Cetak</button></td>";
		$html .= "</tr><tr>";
        $html .= "<td colspan='2'><button name='hpdf' class='button btbodo' data-sid='".$data['survey_id']."' data-rid='".$data['respondent_id']."'>PDF</button></td>";
        $html .= "</tr></table></td>";
		$html .= "</tr>";
		$cnt++;
	}
	$html .= "<tr class='row_active'>";
	$html .= "<td colspan='".$_colnum."' class='left'></td>";
	$html .= "</tr>";
	$html .= "</table></center>";
} else {
	$html .= "<center><table id='x-table1'>";
        if ( $this->{$repclass}->data->search ) {
		$html .= $html_ops;
		$html .= "<tr><td colspan='".$_colnum."' class='border-left border-right border-bottom border-top'>"._tr("Tiada data untuk padanan carian")."</td></tr>";
        } else {
		$html .= "<tr><td class='center'>"._tr("Tiada data yang tersedia")."</td></tr>";
	}	
	$html .= "</table></center>";
}
?>
<style>
table.tbbodo {
    width: 100% !important;
    margin: 0px;
    padding: 0px;
}
button.btbodo {
    font-size: 10px !important;
    width: 100px;
    margin: 1px !important;
    padding: 0px;
}
</style>
<?php
_E($html);
if ( _null($this->request['category_id']) ):
?>
<script>
$("select[name=category_id]").trigger("change")
</script>
<?php endif; ?>
<script type="text/javascript">
$(document).ready(function() {
	$("div.ui-tabs").css("width","1366px");
	$("button[name=hpedit]").button({ icons: {primary:'ui-icon-pencil' }});
	/*$("button[name=hpedit]").button({ icons: {primary:'ui-icon-pencil' }}).click(function(e) {
		e = e || window.event;
		e.preventDefault();
		var sid = $(this).attr("data-sid");
		var rid = $(this).attr("data-rid");
		var id = $(this).attr("data-id");
		var _url = _baseurl+"/?_req=tpl&_f=form-edit&sid="+sid+"&rid="+rid;
		_popupnewin(_url,"print");
	});*/
	$("button[name=hview]").button({ icons: {primary:'ui-icon-zoomin' }}).click(function(e) {
		e = e || window.event;
		e.preventDefault();
		var sid = $(this).attr("data-sid");
		var rid = $(this).attr("data-rid");
		var _url = _baseurl+"/?_req=tpl&_f=form-print&sid="+sid+"&rid="+rid+"&noprint=1";
		_popupnewin(_url,"print");
	});
	$("button[name=hpdf]").button({ icons: {primary:'ui-icon-document' }}).click(function(e) {
		e = e || window.event;
		e.preventDefault();
		var sid = $(this).attr("data-sid");
		var rid = $(this).attr("data-rid");
		var _url = _baseurl+"/?_req=tpl&_f=form-print&sid="+sid+"&rid="+rid+"&noprint=1&dopdf=1";
		_popupnewin(_url,"print");
	});
	$("button[name=hprint]").button({ icons: {primary:'ui-icon-print' }}).click(function(e) {
		e = e || window.event;
		e.preventDefault();
		var sid = $(this).attr("data-sid");
		var rid = $(this).attr("data-rid");
		var _url = _baseurl+"/?_req=tpl&_f=form-print&sid="+sid+"&rid="+rid;
		_popupnewin(_url,"print");
	});

	$("button[name=hpadmin]").button({ icons: {primary:'ui-icon-document' }}).click(function(e) {
                e = e || window.event;
                e.preventDefault();
                var _id = $(this).attr("data-ord");
                _popupfull(_pbaseurl+"/?_req=adminview&ord="+_id+"&u=<?php _E(_base64_encrypt($this->session->data->id,'waklu'));?>&l=<?php _E(_base64_encrypt($this->session->data->level,'waklu'));?>","form"+time());
        });

});
</script>

