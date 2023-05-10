<?php
@_object($this) && !_null($this->request['id']) || exit("403 Forbidden");
$this->_notlogin();
if ( _null($this->tplname) ) $this->tplname = _tplname(__FILE__);
$fdb = (object)$this->{$this->tplname}->_getinfo($this->request['id'], true);
$rinfo = $this->respondent->_getinfo($fdb->sender_id);
?>
<html>
<head>
<meta http-equiv=Content-Type content="text/html; charset=UTF-8">
<title><?php _E($this->options->apptitle);?></title>
<link rel="shortcut icon" type="image/x-icon" href="<?php _E($this->baseurl);?>/favicon.ico">
<style type="text/css" media="print"> 
body, td{font-family:arial,sans-serif;font-size:80%} a:link, a:active, a:visited{color:#0000CC} img{border:0} pre { white-space: pre; white-space: -moz-pre-w
rap; white-space: -o-pre-wrap; white-space: pre-wrap; word-wrap: break-word; width: 800px; overflow: auto;}
</style>
<style>
table {
	margin: 0px;
        padding: 6px;
        width: 100%;
}
th, td {
        padding: 2px 10px 2px 10px;
}

th {
        vertical-align: middle;
        text-align: left;
        font-weight: bold;
        font-size: 12px;
        font-family: arial;
        background: #ffffff;
        color: #000000;
}

td {
        vertical-align: top;
        text-align: left;
        font-size: 12px;
        font-family: arial;
}

.center {
        text-align: center;
}
.right {
        text-align: right;
}
.left {
        text-align: left;
}
.border {
	border: 1px solid #000000;
}

.border-top {
	border-top: 1px solid #000000;
}

.border-bottom {
	border-bottom: 1px solid #000000;
}

.border-left {
	border-left: 1px solid #000000;
}

.border-right {
	border-right: 1px solid #000000;
}

.border-none {
	border: 0px solid transparent !important;
}

.border-top-none {
	border-top: 0px solid transparent !important;
}

.border-bottom-none {
	border-bottom: 0px solid transparent !important;
}

.border-left-none {
	border-left: 0px solid transparent !important;
}

.border-right-none {
	border-right: 0px solid transparent !important;
}

span.anote {
        padding: 0px;
        font-weight: bold;
}

table { 
  border-spacing:0;
  border-collapse:collapse;
}

.bold {
        font-weight: bold;
}

#x-table1 {
	border-spacing:0;
  	border-collapse:collapse;
}
#x-table1 th {
	border-spacing:0;
  	border-collapse:collapse;
	border: 1px solid #bbbbbb;
}


#x-table1 td {
	vertical-align: top;
	padding-top: 5px;
	border: 1px solid #bbbbbb;
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
#x-table1 td.title {
	font-weight:bold;
	border: none;
	padding-left: 0px;
	font-size: 14px;
	text-align:left;
}
</style>


<script type="text/javascript"> 
function doprint(){
        document.body.offsetHeight;
        setTimeout(function() {
                window.print();
        }, 500);
};
</script>

</head> 
<body onload="doprint();">

<table id="x-table">

<tr>
<th><?php _t("Tarikh/Masa");?></th>
<td class="dinfo"><?php _E($this->_output_datetime($fdb->cdate));?></td>
</tr>

<tr>
<th><?php _t("Nama Pegawai");?></th>
<td class="dinfo"><?php _E($rinfo['pegawai']);?></td>
</tr>

<tr>
<th><?php _t("Nama Syarikat");?></th>
<td class="dinfo"><?php _E($rinfo['company']);?></td>
</tr>

<tr>
<th><?php _t("No. Lesen");?></th>
<td class="dinfo"><?php _E($rinfo['nolesen']);?></td>
</tr>

<tr>
<th><?php _t("Subjek");?></th>
<td class="dinfo"><?php _E($fdb->subject);?></td>
</tr>


<tr>
<th><?php _t("Mesej");?></th>
<td class="dinfo">
<?php _E($fdb->msg);?>
</td>
</tr>

<tr>
<th style="vertical-align:top;padding-top:5px;"><?php _t("Status");?></th>
<td class="dinfo" data-status='<?php _E($fdb->status);?>'>
<?php 
echo ( $fdb->status == "1" ? "Telah Diproses" : "Belum Diproses" );
if ( $fdb->status == "1" && !_null($fdb->staff_id) ) {
	echo "<br><b>Oleh:</b> ".$this->user->_getname($fdb->staff_id);
}
?></td>
</tr>

<tr>
<th><?php _t("Fail Lampiran");?></th>
<td class="dinfo"><?php echo ( !_null($fdb->file) ? "<a href='".$this->baseurl."/?_req=download&_f=".$fdb->id."' target='new'>".basename($fdb->file)."</a>" : "tiada" );?></td>
</tr>

</table>

</body>
</html>
