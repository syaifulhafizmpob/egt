<?php
@is_object($this) || exit("403 Forbidden");
$this->_tpl("header");
?>
<div class="container">
<center>
<div id="login">
<?php if ( _ieneedfix(8) ): ?>
<!--[if lt IE 8]>
<?php _redirect($this->baseurl."/browser.html");exit;?>
<![endif]-->
<?php else: ?>
<div class="ui-widget center" style="margin: 9px; width:700px;">
<img id="logo" src="rsc/logob.png">
</div>
<div id="bb" class="ui-widget center" style="margin: 9px; width:700px;margin-top:110px;">
<span style="font-size:23px;color:#000000;text-shadow: 0 0 1px rgba(255,255,255,255);"><b>Selamat Datang Ke e-GUNATENAGA</b></span>
</div>
<form id="login-form" onsubmit="return false;">
<table>
<tr>
<th><?php _E("No Lesen");?></th>
<td><input type="text" class="text" name="uname" value=""></td>
</tr>
<tr>
<th><?php _E("Kata laluan");?></th>
<td><input type="password" class="text" name="upass" value=""></td>
</tr>
<tr>
<td colspan="2" style="text-align: right;"><button class="button button_red" name="btlogin">Login</button></td>
</tr>
</table>
</form>
<?php
$fdb = (object)$this->setting->_getconfig("pengumuman_admin");
if ( !_null($fdb->pengumuman_admin) && $fdb->pengumuman_admin != '<br>' ):
?>
<div class="ui-widget left" style="margin: 9px; width:650px;">
<div class="ui-state-highlight ui-corner-all" style="padding: 0.7em;"> 
<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span> 
<span style='font-weight: solid !important; color: #000000 !important; font-size: 12px !important;'>
<?php _E($fdb->pengumuman_admin);?>
</span></p>
</div>
</div>
<?php endif; ?>
<div class="ui-widget left" style="margin: 9px; width:700px;">
<table width="100%" border="0">
<tr>
<td><b>e-GUNATENAGA adalah sistem pengumpulan maklumat gunatenaga atas talian bagi sektor pemprosesan, peniaga, pusat simpanan minyak sawit dan bahan tanaman sawit Malaysia.</b></td>
</tr>
<tr><td><br></td></tr>
<tr>
<td>Paparan terbaik dalam <a href="http://www.google.com/chrome/">Google Chrome</a> atau <a href="http://www.microsoft.com/windows/internet-explorer/default.aspx">Internet Explorer 9.0</a> ke atas dengan 1280x800 resolusi piksel. Jika anda memghadapi masalah untuk akses sistem e-Gunatenaga, sila klik <a href="http://e-gunatenaga.mpob.gov.my/browser.html">di sini</a> untuk menaik taraf browser anda.</td>
</tr>
</table>
</div>
<?php endif; ?>
</div> <!-- /login -->
<div class="ui-widget center" style="margin: 9px; width:930px;">
<img src="rsc/imag5.jpg" width="150" style="float:left;margin-right:5px;">
<img src="rsc/imag1.jpg" width="150" style="float:left;margin-right:5px;">
<img src="rsc/imag2.jpg" width="150" style="float:left;margin-right:5px;">
<img src="rsc/imag3.jpg" width="150" style="float:left;margin-right:5px;">
<img src="rsc/imag4.jpg" width="150" style="float:left;margin-right:5px;">
<img src="rsc/imag6.jpg" width="150" style="float:right;">
</div> <!-- /image -->
</center>

<div class="push"></div>
</div> <!-- /container -->
<div class="footer">
<center>
<div class="footer-inner">
<center>
<?php _E(_safe_eval($this->options->copyright)); ?>
</center>
</div> <!-- /inner -->
</center>
</div> <!-- /footer -->
</body>
</html>


