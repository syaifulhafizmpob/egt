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
<span style="font-size:23px;color:#000000;text-shadow: 0 0 1px rgba(255,255,255,255);"><b>NOTIS PENUTUPAN</b></span>
</div>

<div class="ui-widget left" style="margin: 9px; width:700px;">
<table width="100%" border="0">
<tr>
<td><b><font color=blue>Sistem e-GUNATENAGA ditutup sementara waktu bagi proses penyelenggaraan. Harap maaf.</b></font></td>
</tr>
<tr><td>&nbsp</td></tr>
<tr>
<tr><td><b>Sila hubungi pegawai-pegawai berikut jika perlukan bantuan <a href="http://e-gunatenaga.mpob.gov.my/hubungi.html">Klik Disini</a></b></td></tr>
<tr>
<tr><td>&nbsp</td></tr>
<td>Paparan terbaik dalam <a href="http://www.google.com/chrome/"><b>Google Chrome</b></a> atau <a href="http://www.microsoft.com/windows/internet-explorer/default.aspx"><b>Internet Explorer 9.0</b></a> ke atas dengan 1280x800 resolusi piksel. Jika anda memghadapi masalah untuk akses sistem e-Gunatenaga, sila <a href="http://e-gunatenaga.mpob.gov.my/browser.html"><b>Klik Disini</b></a> untuk menaik taraf browser anda.</td>
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


