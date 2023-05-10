

<?php
@_object($this) || exit("403 Forbidden");
$this->_notlogin();

$xid = "x-"._rand_text(3).time();
$survey_id = null;

if(isset($_POST['btsearch'])){
  //code block for insertion,validation etc //
  echo "test";
}
?>

<?php
$html = "";
$html .= "<center><table id='x-tablep'>";
$html .= "<tr>";
$html .= "<th class='border-bottom border-top border-left' style='padding-left: 6px;width:60px;'>";
$html .= "Tahun";
$html .= "</th>";
$html .= "<td class='border-bottom border-top' style='padding-left: 0px;width:160px;'>";
$html .= "<select name='survey_id' class='text' style='width:150px!important;'>";
$list = $this->survey->_listsurvey();
if ( _array($list) ) {
	while( $dt = @array_shift($list) ) {
		if ( _null($survey_id) ) {
			$survey_id = ( !_null($this->request['survey_id']) ? $this->request['survey_id'] : $dt['id'] );
		}
		$html .="<option value='".$dt['id']."'".( $this->request['survey_id'] == $dt["id"] ? " selected" : "").">".utf8_strtoupper($dt['month'])."-".utf8_ucwords($dt['year'])."</option>";
	}
}
$html .= "</select>";
$html .= "</td>";


$html .= "<th class='subcat border-bottom border-top border-left' style='padding-left: 6px;width:100px;'>";
$html .= "Status";
$html .= "</th>";
$html .= "<td class='subcat border-bottom border-top border-right' style='padding-left: 0px;'>";
$html .= "<select name='status' class='text' style='width:150px!important;'>";
$list = array(
                        "belum_mula" => "Belum Mula",
                        "mula_lapor" => "Mula Lapor",
                        "telah_diterima" => "Telah diterima",
                        "telah_diproses" => "Telah diproses",
                        "terkecuali" => "Terkecuali"
                );
if ( _array($list) ) {
	foreach( $list as $x => $y ) {
		$html .= "<option value='".$x."'".( $this->request['status'] == $x ? " selected" : "" ).">".$y."</option>";
	}
}
$html .= "</select>";


$html .= "<select class='text' name='state_id' style='width: 250px !important; margin-left: 5px; margin-right: 2px;'>";
$html .= "<option value=''>Filter negeri</option>";
$list = $this->display->_liststate();
if ( _array($list) ) {
	while( $dt = @array_shift($list) ) {
		$html .= "<option value='".$dt['id']."'".( $this->request['state_id'] == $dt['id'] ? " selected" : "" ).">".utf8_ucwords($dt['name'])."</option>";
	}
}
$html .= "</select>";
$html .= "<select class='text' name='category_id' style='width: 150px !important; margin-left: 2px; margin-right: 2px;'>";
//$html .= "<option value=''>Filter kategori</option>";
if ( !$this->issuperadmin ) {
$grp = ( $this->user->_ingroup_type($this->session->data->id,"peniaga") ? "peniaga" : "kilang" );
$list = $this->display->_listcategory_respondent($grp);
} else {
$list = $this->display->_listcategory_respondent();
}
if ( _array($list) ) {
	while( $dt = @array_shift($list) ) {
		$html .= "<option value='".$dt['id']."'".( $this->request['category_id'] == $dt['id'] ? " selected" : "" ).">".utf8_ucwords($dt['name'])."</option>";
	}
}
$html .= "</select>";
//$category_id="04";
//if ( $this->request['category_id'] !== "02" 
//if ( category_id !== "02" 
 //   && $this->display->_getcategory_type($this->request['category_id']) == "peniaga" 
 //   && $this->user->_ingroup_type($this->session->data->id,"peniaga") ) {

	$html .= "<select class='text' name='subcategory_id' style='width: 150px !important; margin-left: 2px; margin-right: 2px;'>";
	$html .= "<option value=''>Filter Sub kategori</option>";
	$list = $this->display->_listsubcategory("peniaga");
	if ( _array($list) ) {
		while( $dt = @array_shift($list) ) {
			$html .= "<option value='".$dt['id']."'".( $this->request['subcategory_id'] == $dt['id'] ? " selected" : "" ).">".utf8_ucwords($dt['name'])."</option>";
		}
	}
	$html .= "</select>";

//}
$html .= "</td>";





$html .= "</tr>";
$html .= "<tr>";
$html .= "<td colspan='4' class='border-top border-bottom border-right border-left' style='padding: 5px;'>";
$html .= "<button class='button' name='btsearch'>"._tr("View")."</button>";
$html .= "<button class='button' name='btclear'>"._tr("Clear")."</button>";
$html .= "<button class='button' name='btprint' style='margin-left:5px;'>Cetak</button>";
$html .= "<button class='button' name='btpdf'>PDF</button>";
$html .= "<button class='button' name='btexcel'>Excel</button>";
$html .= "</td>";
$html .= "</tr>";


$html .= "</table></center>";


_E($html);

echo ($this->request['category_id']);
?>

<div class="xreport" id="<?php _E($xid);?>"></div>

<script type="text/javascript">
$(document).ready(function() {
	_xtab_resize();
	var _xid = "<?php _E($xid);?>";
	$("input.datepicker" ).datepicker({
		changeYear: true,
		changeMonth: true,
		dateFormat: 'dd-mm-yy',
	});


	function _load_rpage() {
		var _p = "<?php _E($this->request['_f']); ?>-data";
		var _svid = $("select[name=survey_id]").attr("value");
		var _status = $("select[name=status]").attr("value");
		var _state_id = $("select[name=state_id]").attr("value");
		var _category_id = $("select[name=category_id]").attr("value"); 
		
		 if (_category_id=='02')
		    $("select[name=subcategory_id]").hide();
		var _subcategory_id = $("select[name=subcategory_id]").attr("value"); 
		_page("#"+_xid, { _req: 'tpl', _f: _p, survey_id: _svid, status: _status , state_id: _state_id, category_id: _category_id, subcategory_id: _subcategory_id});
	};
	_load_rpage();

    $("select[name=status]").change(function() {
        _load_rpage();
    });
	
	$("select[name=state_id]").change(function() {
        _load_rpage();
    });
	
	
	
	
	$("select[name=category_id]").change(function() {
		  var _categoryid = $("select[name=category_id]").attr("value"); 
		  if (_categoryid=='02')
		    $("select[name=subcategory_id]").hide();
		  else	
			$("select[name=subcategory_id]").show();
		  _load_rpage();	
	});
	
	$("select[name=subcategory_id]").change(function() {
		
		  _load_rpage();	
	});
	
	


	$("button[name=btsearch]").button({ icons: {primary:'ui-icon-search' }}).click(function(e) {
		e = e || window.event;
		e.preventDefault();
		_load_rpage();
	});

	$("button[name=btclear]").button({ icons: {primary:'ui-icon-cancel' }}).click(function(e) {
		e = e || window.event;
		e.preventDefault();
		_winload();
	});

	$("button[name=btprint]").button({ icons: {primary:'ui-icon-print' }}).click(function(e) {
		e = e || window.event;
		e.preventDefault();
		var _svid = $("select[name=survey_id]").attr("value");
		var _status = $("select[name=status]").attr("value");
		var _state_id = $("select[name=state_id]").attr("value");
		var _category_id = $("select[name=category_id]").attr("value");
		var _subcategory_id = $("select[name=subcategory_id]").attr("value");
		var _url = _baseurl+"/?_req=tpl&_f=report-report28p-print&survey_id="+_svid+"&status="+_status+"&state_id="+_state_id+"&category_id="+_category_id+"&subcategory_id="+_subcategory_id;
		_popupnewin(_url,"print");
	});
	$("button[name=btpdf]").button({ icons: {primary:'ui-icon-document' }}).click(function(e) {
		e = e || window.event;
		e.preventDefault();
		var _svid = $("select[name=survey_id]").attr("value");
		var _status = $("select[name=status]").attr("value");
		var _state_id = $("select[name=state_id]").attr("value");
		var _category_id = $("select[name=category_id]").attr("value");
		var _subcategory_id = $("select[name=subcategory_id]").attr("value");
		var _url = _baseurl+"/?_req=tpl&_f=report-report28p-print&dopdf=1&survey_id="+_svid+"&status="+_status+"&state_id="+_state_id+"&category_id="+_category_id+"&subcategory_id="+_subcategory_id;
		_popupnewin(_url,"print");
	});
	$("button[name=btexcel]").button({ icons: {primary:'ui-icon-document' }}).click(function(e) {
		e = e || window.event;
		e.preventDefault();
		var _svid = $("select[name=survey_id]").attr("value");
		var _status = $("select[name=status]").attr("value");
		var _state_id = $("select[name=state_id]").attr("value");
		var _category_id = $("select[name=category_id]").attr("value");
		var _subcategory_id = $("select[name=subcategory_id]").attr("value");
		var _url = _baseurl+"/?_req=tpl&_f=report-report28p-print&doexcel=1&survey_id="+_svid+"&status="+_status+"&state_id="+_state_id+"&category_id="+_category_id+"&subcategory_id="+_subcategory_id;
		_popupnewin(_url,"print");
	});
});
</script>
