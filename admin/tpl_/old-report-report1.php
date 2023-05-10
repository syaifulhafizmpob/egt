<?php
@_object($this) || exit("403 Forbidden");
$this->_notlogin();
?>

<style type="text/css">
#x-table1 th {
	border: 1px solid #bbbbbb;
}


#x-table1 td {
	vertical-align: top;
	padding-top: 5px;
}


#x-table1 td.ll_active {
	background: yellow;
	font-weight: bold;
}

#x-table1 .border-top {
        border-top: 1px solid #bbbbbb;
}

#x-table1 .border-bottom {
        border-bottom: 1px solid #bbbbbb;
}

#x-table1 .border-left {
        border-left: 1px solid #bbbbbb;
}

#x-table1 .border-right {
        border-right: 1px solid #bbbbbb;
}

</style>

<?php

$html = "";
$html .= "<center><table id='x-tablep'>";
$html .= "<tr>";
$html .= "<th class='border-bottom border-top border-left' style='padding-left: 6px;width:60px;'>";
$html .= "Tahun";
$html .= "</th>";
$html .= "<td class='border-bottom border-top' style='padding-left: 0px;width:100px;'>";
$html .= "<select name='survey_id' class='text' style='width:90px!important;'>";
$list = $this->survey->_listsurvey();
if ( _array($list) ) {
	while( $dt = @array_shift($list) ) {
		if ( _null($survey_id) ) {
			$survey_id = ( !_null($this->request['survey_id']) ? $this->request['survey_id'] : $dt['id'] );
		}
		$html .="<option value='".$dt['id']."'".( $this->request['survey_id'] == $dt["id"] ? " selected" : "").">".utf8_ucwords($dt['year'])."</option>";
	}
}
$html .= "</select>";
$html .= "</td>";

$html .= "<th class='border-bottom border-top' style='padding-left: 6px;width:60px;'>";
$html .= "Negeri";
$html .= "</th>";
$html .= "<td class='border-bottom border-top' style='padding-left: 0px;width:150px;'>";
$html .= "<select name='state_id' class='text'>";
$html .= "<option value=''>Filter Negeri</option>";
$list = $this->display->_liststate();
if ( _array($list) ) {
	while( $dt = @array_shift($list) ) {
		if ( _null($state_id) ) {
			$state_id = ( !_null($this->request['state_id']) ? $this->request['state_id'] : $dt['id'] );
		}
		$html .="<option value='".$dt['id']."'".( $this->request['state_id'] == $dt["id"] ? " selected" : "").">".utf8_ucwords($dt['name'])."</option>";
	}
}
$html .= "</select>";
$html .= "</td>";
$html .= "<th class='border-bottom border-top' style='padding-left: 10px; width:70px;'>";
$html .= "Kategori";
$html .= "</th>";
$html .= "<td class='border-bottom border-top' style='padding-left: 0px;width:150px;'>";
$html .= "<select name='category_id' class='text'>";
$html .= "<option value=''>Filter kategori</option>";
$list = $this->display->_listcategory_respondent();
if ( _array($list) ) {
	while( $dt = @array_shift($list) ) {
		if ( _null($category_id) ) {
			$category_id = ( !_null($this->request['category_id']) ? $this->request['category_id'] : $dt['id'] );
		}
		$html .="<option value='".$dt['id']."'".( $this->request['category_id'] == $dt["id"] ? " selected" : "").">".utf8_ucwords($dt['name'])."</option>";
	}
}
$html .= "</select>";
$html .= "</td>";


$html .= "<td class='border-top border-bottom border-right' style='padding-left: 5px;'>";
$html .= "<button class='button' name='btsearch'>"._tr("View")."</button>";
$html .= "<button class='button' name='btclear'>"._tr("Clear")."</button>";
//$html .= "<button class='button' name='btprint' style='margin-left:5px;'>Cetak</button>";
//$html .= "<button class='button' name='btpdf' style='margin-left:5px;'>PDF</button>";
$html .= "</td>";
$html .= "</tr>";

if ( $this->user->_ingroup_type($this->session->data->id,"peniaga") ) {
$html .= "<tr>";
$html .= "<th class='border-bottom border-left' style='padding-left: 10px; width:100px;'>";
$html .= "Sub Kategori";
$html .= "</th>";
$html .= "<td class='border-bottom border-right' style='padding-left: 0px;width:150px;' colspan='8'>";
$html .= "<select name='category_id' class='text'>";
$html .= "<option value=''>Filter Sub kategori</option>";
$list = $this->display->_listsubcategory("peniaga");
if ( _array($list) ) {
	while( $dt = @array_shift($list) ) {
		if ( _null($subcategory_id) ) {
			$subcategory_id = ( !_null($this->request['subcategory_id']) ? $this->request['subcategory_id'] : $dt['id'] );
		}
		$html .="<option value='".$dt['id']."'".( $this->request['subcategory_id'] == $dt["id"] ? " selected" : "").">".utf8_ucwords($dt['name'])."</option>";
	}
}
$html .= "</select>";
$html .= "</td>";
$html .= "</tr>";
}

$html .= "</table></center>";


_E($html);
?>

<script type="text/javascript">
$(document).ready(function() {
	$("input.datepicker" ).datepicker({
		changeYear: true,
		changeMonth: true,
		dateFormat: 'dd-mm-yy',
	});
	$("button[name=btsearch]").button({ icons: {primary:'ui-icon-search' }});
	$("button[name=btclear]").button({ icons: {primary:'ui-icon-cancel' }});
	$("button[name=btprint]").button({ icons: {primary:'ui-icon-print' }});
	$("button[name=btpdf]").button({ icons: {primary:'ui-icon-document' }});
	_xtab_resize();
});
</script>
