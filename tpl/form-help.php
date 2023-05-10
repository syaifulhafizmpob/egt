<?php
@_object($this) || exit("403 Forbidden");
$this->_notlogin();
$this->survey_id = $this->survey->_getcurrent_id();
if ( _null($this->survey_id) ) exit("Invalid survey!");
$expired = $this->survey->_expired($this->survey_id);
?>
<style type="text/css">
li {
	font-size: 13px;
	margin-bottom: 5px;
}
li.roman {
	list-style: lower-roman;
	margin-bottom: 2px;
}
h1 {
	font-size: 13px;
	font-weight: bold;
	margin-bottom: 10px;
}
</style>
<p><b>PANDUAN MENJAWAB SOALAN BILANGAN PEKERJA DAN PURATA GAJI</b></p>

<br />
<b>A. BILANGAN PEKERJA</b><br />
<ol> <li>Bilangan pekerja dan purata gaji merujuk kepada bulan <b><?php _E($_SESSION['fyear']);?></b> sahaja.</li>
<li>Maklumat gunatenaga yang diberikan hendaklah untuk nombor lesen yang tertera sahaja.</li>
<li>Bilangan Pekerja:</li>
</ol> <ol> <li>Jika tuan mempunyai pekerja-pekerja yang menjalankan lebih daripada satu jenis kerja di syarikat tuan, sila laporkan pekerja itu mengikut masa yang lebih banyak digunakan untuk menjalankan sesuatu kerja itu.</li>
<li>Jika seorang pekerja diambil untuk menggantikan seorang pekerja lain yang telah meletak jawatan, ianya dikira sebagai seorang sahaja bukan dua orang.</li>
</ol> <br />
<b>B. PURATA GAJI DAN ELAUN</b> <ol> <li>Purata Gaji :</li>
</ol><p>Purata gaji yang diberi adalah <b>purata gaji seorang sebulan</b> bagi setiap kategori</p>

<p><br />
Contoh : Kerani,</p>

<p>a. Melayu - 3 orang, jumlah gaji untuk 3 orang sebulan ialah RM 850 + RM 880 + RM 875 = RM 2605,</p>
<p>b. Cina - 3 orang, jumlah gaji untuk 3 orang sebulan ialah RM 845 +RM 900 + RM 815 = RM 2560</p>
<p>c. India - 2 orang, jumlah gaji untuk 2 orang sebulan ialah RM 890 + RM 885 = RM 1775</p>
<p><br />
<p>Purata gaji seorang ialah : (a)RM 2605 + (b) RM2560 + (c) RM 1775 = RM 6940 / 8 orang pekerja = RM 867.50 = RM 868 (digenapkan)</p>

<p><br />
</p>

<ol start="2"> <li>Purata gaji adalah merujuk kepada gaji asas yang dibayar pada akhir bulan termasuk caruman pekerja kepada KWSP dan lain-lain seumpamanya tetapi <b>TIDAK</b> termasuk kerja lebih masa dan bonus tahunan.</li>
<li> Jika pekerja diberi gaji harian, beri anggaran gaji untuk sebulan.</li>
<li>Bayaran kepada buruh kontrak mesti dimasukkan di bawah gaji kepada pekerja-pekerja kontrak. Ianya tidak termasuk kos pengangkutan.</li>
<li>Elaun termasuk elaun minyak, elaun telefon, elaun rumah, elaun pengankutan dan lain-lain elaun yang diberikan oleh syarikat.</li>
</ol> <b><br />
C. KATEGORI PEKERJA</b><br />
<ol> <li>Pengurus termasuk pengurus kilang, kewangan, pemasaran, perkapalan, lojistik dan seumpamanya.</li>
<li>Eksekutif/pegawai termasuk pegawai kewangan, pemasaran, perkapalan, lojistik, dan seumpamanya </li>
<li>Kerani termasuk kerani kewangan, pemasaran, perkapalan, lojistik, dan seumpamanya. </li>
</ol><p><br />
<b>D. KEKURANGAN / KEPERLUAN PEKERJA</b></p>

<ol> <li>Bilangan kekurangan/keperluan pekerja merujuk kepada bilangan pekerja yang masih diperlukan oleh syarikat bagi setiap kategori pekerja dan tahun.</li>
</ol><p><br />
</p>

<b>MANUAL e-Gunetenaga</b></p>
<p><br />
<a href="rsc/manual-egunatenaga.pdf" target="_blank">
<img src="rsc/manual-egunatenaga.png" border="0" style="border:none;max-width:100%;" alt="Manual e-Gunatenaga" />
</p>

<br />
<br />

<?php
if ( !$this->session->data->adminview ):
$fdb = (object)$this->setting->_getconfig("pengumuman");
if ( !_null($fdb->pengumuman) && $fdb->pengumuman != '<br>' && $this->setting->_incategory($this->session->data->category_id) ) {
	echo "<textarea name='pengumuman' class='hide'>".$fdb->pengumuman."</textarea>";
}
endif;
?>
<div id="dialog-mm" class="hide"></div>
<script type="text/javascript">
$(document).ready(function() {
	$("div.ui-tabs").css("width","auto");

	function _pengumuman(msg) {
		var _pid = "#dialog";
		var title= "Pengumuman";
		var _height = $(document).height() - 20;
		var $pid = $(_pid);
		$pid.html(msg).dialog({
			width: 400,
			modal: true,
			position: ["center", 150],
			title: title,
			buttons: {
				"Tutup": function() {
					$pid.dialog("close");
				}
			},
			open: function() {
				var $this = $(this);
				_dialog_maxheight($this,_height);
				$this.parent().find('.ui-dialog-buttonpane button:contains("Tutup")').button({
					icons: { primary: 'ui-icon-close' }
				});
			},
			close: function() {
				$pid.empty().dialog("destroy");
			}
		});
	};
	var mm = $.trim($("textarea[name=pengumuman]").attr("value"));
	if ( mm !== "" ) {
		_pengumuman(mm);
	}

    <?php if ( $expired ): ?>
	function _expired(msg) {
		var _pid = "#dialog-mm";
		var title= "Pengumuman";
		var _height = $(document).height() - 20;
		var $pid = $(_pid);
		$pid.html(msg).dialog({
			width: 400,
			modal: true,
			position: ["center", 150],
			title: title,
			buttons: {
				"Ok": function() {
					$pid.dialog("close");
				}
			},
			open: function() {
				var $this = $(this);
				_dialog_maxheight($this,_height);
				$this.parent().find('.ui-dialog-buttonpane button:contains("Ok")').button({
					icons: { primary: 'ui-icon-close' }
				});
			},
			close: function() {
					_redirect(_index+"?_req=logout");
				$pid.empty().dialog("destroy");
			}
		});
	};
    _expired("Tarikh Kemasukan Data Telah Tamat!");
    <?php endif; ?>
});
</script>
