<?php
@_object($this) || exit("403 Forbidden");
$this->_notlogin();

$xid = "x-"._rand_text(3).time();
$survey_id = null;
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

div.xreport {
	clear: both;
	width: 100%;
}

</style>

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

$html .= "<th class='border-bottom border-top border-left' style='padding-left: 6px;width:60px;'>";
$html .= "Negeri";
$html .= "</th>";
$html .= "<td class='border-bottom border-top' style='padding-left: 0px;width:160px;'>";
$html .= "<select name='state_id' class='text' style='width:150px!important;'>";
$list = $this->display->_liststate();
if ( _array($list) ) {
	while( $dt = @array_shift($list) ) {
		$html .="<option value='".$dt['id']."'".( $this->request['state_id'] == $dt["id"] ? " selected" : "").">".utf8_ucwords($dt['name'])."</option>";
	}
}
$html .= "</select>";
$html .= "</td>";

$html .= "<th class='border-bottom border-top border-left' style='padding-left: 6px;width:60px;'>";
$html .= "Kategori";
$html .= "</th>";
$html .= "<td class='border-bottom border-top' style='padding-left: 0px;width:160px;'>";
$html .= "<select name='category_id' class='text' style='width:150px!important;'>";
$list = $this->display->_listcategory("peniaga");
if ( _array($list) ) {
	while( $dt = @array_shift($list) ) {
		$html .="<option value='".$dt['id']."'".( $this->request['category_id'] == $dt["id"] ? " selected" : "").">".utf8_ucwords($dt['name'])."</option>";
	}
}
$html .= "</select>";
$html .= "</td>";

$html .= "<th class='subcat border-bottom border-top border-left' style='padding-left: 6px;width:100px;'>";
$html .= "Sub Kategori";
$html .= "</th>";
$html .= "<td class='subcat border-left border-bottom border-top' style='padding-left: 0px;width:160px;'>";
$html .= "<select name='subcategory_id' class='text' style='width:150px!important;'>";
$html .= "<option value=''>All</option>";
$list = $this->display->_listsubcategory("peniaga");
if ( _array($list) ) {
	while( $dt = @array_shift($list) ) {
		$html .= "<option value='".$dt['id']."'".( $this->request['subcategory_id'] == $dt['id'] ? " selected" : "" ).">".utf8_ucwords($dt['name'])."</option>";
	}
}
$html .= "</select>";
$html .= "</td>";

$html .= "<th class='border-bottom border-top border-left' style='padding-left: 6px;width:100px;'>";
$html .= "Kumpulan Jawatan";
$html .= "</th>";
$html .= "<td class='border-bottom border-top' style='padding-left: 0px;width:160px;'>";
$html .= "<select name='group_id' class='text' style='width:150px!important;'>";
$list = $this->jawatan->_list_group();
if ( _array($list) ) {
	while( $dt = @array_shift($list) ) {
        if ( $dt['id'] == '4' ) continue;
		$html .= "<option value='".$dt['id']."'".( $this->request['group_id'] == $dt['id'] ? " selected" : "" ).">".utf8_ucwords($dt['name'])."</option>";
	}
}
$html .= "</select>";
$html .= "</td>";

$html .= "<th class='border-bottom border-top border-left' style='padding-left: 6px;width:100px;'>";
$html .= "Jawatan";
$html .= "</th>";
$html .= "<td class='border-bottom border-top border-left border-right' style='padding-left: 0px;width:160px;'>";
$html .= "<select name='jawatan_id' class='text' style='width:150px!important;'>";
$list = $this->jawatan->_list_bypeniaga_all();
if ( _array($list) ) {
	while( $dt = @array_shift($list) ) {
		$html .= "<option value='".$dt['jawatan_id']."'".( $this->request['jawatan_id'] == $dt['jawatan_id'] ? " selected" : "" )." data-g='".$dt['group_id']."'>".utf8_ucwords($this->jawatan->_getname($dt['jawatan_id']))."</option>";
	}
}
$html .= "</select>";
$html .= "</td>";

$html .= "</tr>";
$html .= "<tr>";
$html .= "<td colspan='12' class='border-top border-bottom border-right border-left' style='padding: 5px;'>";
$html .= "<button class='button' name='btsearch'>"._tr("View")."</button>";
$html .= "<button class='button' name='btclear'>"._tr("Clear")."</button>";
$html .= "<button class='button' name='btprint' style='margin-left:5px;'>Cetak</button>";
$html .= "<button class='button' name='btpdf'>PDF</button>";
$html .= "<button class='button' name='btexcel'>Excel</button>";
$html .= "</td>";
$html .= "</tr>";


$html .= "</table></center>";

_E($html);
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

    $("select[name=group_id]").change(function() {
		var _catid = $("select[name=category_id]").attr("value");
        var _groupid = $("select[name=group_id]").attr("value");
        $.get(_index, { _post: 'list_bygroup_ajax', _what: 'jawatan', group_id: _groupid, category_id: _catid }, function(html) {
            $("select[name=jawatan_id]").html(html);
        }, 'text');
    });

    $("select[name=category_id]").change(function() {
        var id = $(this).attr("value");
        if ( id === '02' ) {
            $(".subcat").show();
        } else {
            $(".subcat").hide();
            $("select[name=subcategory_id] option[value='']").attr("selected","selected");
        }
    });

	function _load_rpage() {
        $("select[name=category_id]").trigger("change");
        $("select[name=group_id]").trigger("change");
		var _p = "<?php _E($this->request['_f']);?>-data";
		var _svid = $("select[name=survey_id]").attr("value");
		var _catid = $("select[name=category_id]").attr("value");
		var _subcatid = $("select[name=subcategory_id]").attr("value");
		var _groupid = $("select[name=group_id]").attr("value");
		var _jawatanid = $("select[name=jawatan_id]").attr("value");
        var _stateid = $("select[name=state_id]").attr("value");
		_page("#"+_xid, { _req: 'tpl', _f: _p, survey_id: _svid, catid: _catid, subcatid: _subcatid, group_id: _groupid, jawatan_id: _jawatanid, state_id: _stateid });
	};
	_load_rpage();
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
		var _catid = $("select[name=category_id]").attr("value");
		var _subcatid = $("select[name=subcategory_id]").attr("value");
		var _groupid = $("select[name=group_id]").attr("value");
		var _jawatanid = $("select[name=jawatan_id]").attr("value");
        var _stateid = $("select[name=state_id]").attr("value");
		var _url = _baseurl+"/?_req=tpl&_f=report-report17p-print&survey_id="+_svid+"&catid="+_catid+"&subcatid="+_subcatid+"&group_id="+_groupid+"&jawatan_id="+_jawatanid+"&state_id="+_stateid;
		_popupnewin(_url,"print");
	});
	$("button[name=btpdf]").button({ icons: {primary:'ui-icon-document' }}).click(function(e) {
		e = e || window.event;
		e.preventDefault();
		var _svid = $("select[name=survey_id]").attr("value");
		var _catid = $("select[name=category_id]").attr("value");
		var _subcatid = $("select[name=subcategory_id]").attr("value");
		var _groupid = $("select[name=group_id]").attr("value");
		var _jawatanid = $("select[name=jawatan_id]").attr("value");
        var _stateid = $("select[name=state_id]").attr("value");
		var _url = _baseurl+"/?_req=tpl&_f=report-report17p-print&dopdf=1&survey_id="+_svid+"&catid="+_catid+"&subcatid="+_subcatid+"&group_id="+_groupid+"&jawatan_id="+_jawatanid+"&state_id="+_stateid;
		_popupnewin(_url,"print");
	});
	$("button[name=btexcel]").button({ icons: {primary:'ui-icon-document' }}).click(function(e) {
		e = e || window.event;
		e.preventDefault();
		var _svid = $("select[name=survey_id]").attr("value");
		var _catid = $("select[name=category_id]").attr("value");
		var _subcatid = $("select[name=subcategory_id]").attr("value");
		var _groupid = $("select[name=group_id]").attr("value");
		var _jawatanid = $("select[name=jawatan_id]").attr("value");
        var _stateid = $("select[name=state_id]").attr("value");
		var _url = _baseurl+"/?_req=tpl&_f=report-report17p-print&doexcel=1&survey_id="+_svid+"&catid="+_catid+"&subcatid="+_subcatid+"&group_id="+_groupid+"&jawatan_id="+_jawatanid+"&state_id="+_stateid;
		_popupnewin(_url,"print");
	});
});
</script>
