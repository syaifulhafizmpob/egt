<?php 
@_object($this) || exit;
?>
<!DOCTYPE html>
<html>
<head>


<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-MJ5JGGPDYY"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-MJ5JGGPDYY');
</script>


<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=1024"> 
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title><?php _E($this->options->apptitle);?></title>
<meta name="keywords" content="">
<meta name="description" content=""> 
<meta name="robots" content="index,follow,noodp,noydir">
<link rel="shortcut icon" type="image/x-icon" href="<?php _E($this->baseurl);?>/favicon.ico">
<script type="text/javascript">
var _baseurl = "<?php _E($this->baseurl);?>", _locationurl = "<?php _E($this->locationurl);?>", _index = _baseurl+"/index.php";
</script>
<?php
	$this->_stag("ui/ui.css|rsc/style.css|rsc/tables.css");
	if ( $this->islogin ) {
		$this->_stag("rsc/_layout.css");
	} else {
		$this->_stag("rsc/login.css");
	}
?>

<?php
	$this->_stag("ui/ui.js|behavior.php", "js");
	if ( $this->islogin ) {
		$this->_rsc("_layout.js");
	} else {
		$this->_stag("rsc/login.js");
	}
?>
</head>
<body scroll="auto">
<div id="ajax-loader"></div>
<div id="ajax-msg"></div>
<div id="dialog"></div>

