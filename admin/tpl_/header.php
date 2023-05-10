<?php 
@_object($this) || exit;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0"> 
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title><?php _E($this->options->apptitle);?></title>
<meta name="keywords" content="">
<meta name="description" content=""> 
<meta name="robots" content="index,follow,noodp,noydir">
<link rel="shortcut icon" type="image/x-icon" href="<?php _E($this->pbaseurl);?>/favicon.ico">
<script type="text/javascript">
var _baseurl = "<?php _E($this->baseurl);?>", _pbaseurl= "<?php _E($this->pbaseurl);?>", _locationurl = "<?php _E($this->locationurl);?>", _index = _baseurl+"/index.php";
</script>
<?php
	$this->_stag("ui/ui.css|rsc/style.css,tables.css,multiselect.css,menu.css");
	if ( $this->islogin ) {
		$this->_stag("rsc/_layout.css");
		if ( !_null($_SERVER["HTTP_USER_AGENT"]) ) {
		        if ( preg_match("/Opera\/(\d+)/i", $_SERVER["HTTP_USER_AGENT"], $mm ) ) {
		                if ( $mm[1] >= 9 ) {
					$this->_stag("rsc/operafix.css");
				}
		        }
		}
	} else {
		$this->_stag("rsc/login.css");
	}
?>

<?php
	$this->_stag("ui/ui.js|behavior.php|htmlbox/htmlbox.full.js", "js");
	//$this->_stag("ui/RGraph/RGraph.common.core.js|ui/RGraph/RGraph.pie.js|ui/RGraph/RGraph.common.tooltips.js","js");
	if ( $this->islogin ) {
		$this->_rsc("_layout.js");
	} else {
		$this->_stag("rsc/login_admin.js");
	}
?>
</head>
<body scroll="auto">
<div id="ajax-loader"></div>
<div id="ajax-msg"></div>
<div id="dialog"></div>

