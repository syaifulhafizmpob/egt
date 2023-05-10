<?php
@_object($this) || exit("403 Forbidden");
$this->_notlogin();
$repclass="tasklogs";

if ( _null($this->tplname) ) $this->tplname = _tplname(__FILE__);

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

$_colnum = 9;

$html = "";
$html_ops = "";
$html_ops .= "<tr><td colspan='".$_colnum."' class='left' style='padding-left: 2px;'>";
$html_ops .= "<input type='text' class='text stext' name='sstr' placeholder='"._tr("Carian..")."' value='".$this->request['sstr']."'>";
$html_ops .= "<select class='text' name='sopt' style='width: 100px !important; margin-left: 5px; margin-right: 5px;'>";
foreach( array("user_id" => _tr("Staff") ) as $x => $y ) {
	$html_ops .= "<option value='".$x."'".($this->request['sopt'] == $x ? " selected" : "").">".$y."</option>";
}
$html_ops .= "</select><button class='button button_add' name='btsearch'>"._tr("Carian")."</button>";
$html_ops .= "<button class='button button_add' style='margin-left: 2px;' name='btreloadgrid'>"._tr("Reset")."</button>";
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
	$html .= "<th class='right'>#</th>";
	$html .= "<th style='width:150px;'>"._tr("Tarikh/Masa")."</th>";
	$html .= "<th>"._tr("Staff")."</th>";
	$html .= "<th>"._tr("Jenis Tindakan")."</th>";
	$html .= "<th>"._tr("Syarikat")."</th>";
	$html .= "<th>"._tr("No. Lesen")."</th>";
	$html .= "<th colspan='2'>"._tr("Logs")."</th>";
	$html .= "</tr>";
	$cnt = $this->{$repclass}->data->cnt;
	while( $data = @array_shift($this->{$repclass}->data->data) ) {
        $rinfo = $this->respondent->_getinfo($data['respondent_id']);
		$html .= "<tr data-id='".$data['id']."'>";
		$html .= "<td class='border-left border-bottom left' style='width: 50px;'><input type='checkbox' name='del[".$data['id']."]' value='1'><img data-del='".$data['id']."' class='del' src='".$this->pbaseurl."/rsc/delete.png?".$this->_cupdate."' data-tooltip='"._tr("Padam")."'></td>";	
		$html .= "<td class='border-left border-bottom right' style='width: 10px;'>".$cnt."</td>";
		$html .= "<td class='border-left border-bottom'>".$this->_output_datetime($data['date'])."</td>";
		$html .= "<td class='border-left border-bottom'>".$this->user->_getname($data['user_id'])."</td>";
		$html .= "<td class='border-left border-bottom'>".$data['type']."</td>";
		$html .= "<td class='border-left border-bottom'>".$rinfo['company']."</td>";
		$html .= "<td class='border-left border-bottom'>".$rinfo['nolesen']."</td>";
		$html .= "<td colspan='2' class='border-left border-right border-bottom'>".$data['msg']."</td>";
		$html .= "</tr>";
		$cnt++;
	}
	$html .= "<tr class='row_active'>";
	$html .= "<td colspan='".$_colnum."' class='left'><input type='checkbox' name='chkdel' data-click='msel'><img data-click='mdel' class='del' src='".$this->pbaseurl."/rsc/delete.png?".$this->_cupdate."'></td>";
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
_E($html);
?>


