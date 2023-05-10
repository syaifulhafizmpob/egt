<?php
@_object($this) || exit("403 Forbidden");
$this->_notlogin();
$repclass="survey";

if ( _null($this->tplname) ) $this->tplname = _tplname(__FILE__);

$this->{$repclass}->_getlist();

$_colnum = 6;

$html = "";

if ( _num($this->{$repclass}->data->total) && $this->{$repclass}->data->total > 0 ) {
	$html .= "<center><table id='x-table1' style='margin-left:0px;padding-left:0px;'>";
	if ( !_null($pagging) ) {
		$html .= "<tr><td colspan='".$_colnum."' class='right' style='padding-right: 2px;'>".$pagging."</td></tr>";
	}

	$html .= "<tr>";
	$html .= "<td colspan='".$_colnum."' class='left' style='padding: 5px 0px 5px 2px;'>";
	$html .= "<span class='anote'>"._tr("Total Records %d", $this->{$repclass}->data->total)."</span>";
	$html .= "</td></tr>";

	$html .= "<tr>";
	$html .= "<th class='left' style='width: 50px;'>"._tr("Status")."</th>";
	$html .= "<th>"._tr("Nama")."</th>";
	$html .= "<th>"._tr("Tarikh Mula")."</th>";
	$html .= "<th>"._tr("Tarikh Tamat")."</th>";
	$html .= "</tr>";
	$cnt = $this->{$repclass}->data->cnt;
	while( $data = @array_shift($this->{$repclass}->data->data) ) {
		$sdt = strtotime($data['edate']);
		$status = ( $sdt > 0 && $sdt < time() ? "<span style='color:red;font-weight:bold;'>Tutup</span>" : "<span style='color:green;font-weight:bold;'>open</span>" );
		$html .= "<tr class='row' data-id='".$data['id']."'>";
		$html .= "<td class='border-left border-bottom center' style='width: 50px;'>".$status."</td>";
		$html .= "<td class='border-left border-bottom info'>".$data['name']."</td>";
		$html .= "<td class='border-left border-bottom info'>".$this->_output_date($data['sdate'])."</td>";
		$html .= "<td class='border-left border-right border-bottom info'>".$this->_output_date($data['edate'])."</td>";
		$html .= "</tr>";
		$cnt++;
	}
	$html .= "<tr class='row_active'>";
	$html .= "<td colspan='".$_colnum."' class='left'></td>";
	$html .= "</tr>";
	$html .= "</table></center>";
} else {
	$html .= "<center><table id='x-table1'>";
	$html .= "<tr><td class='center'>"._tr("Tiada data yang tersedia")."</td></tr>";	
	$html .= "</table></center>";
}
_E($html);
?>


