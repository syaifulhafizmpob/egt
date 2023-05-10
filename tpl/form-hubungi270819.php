<?php
@_object($this) || exit("403 Forbidden");
$this->_notlogin();
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
<?php if ( $this->user->_ingroup_type($this->session->data->id,"kilang") ): ?>
<p><b>Kilang Buah</b></p>
Puan Nurul Syuhada Azrin Nasarudin- No. Telefon: 03-78022912 / Emel : nurul.syuhada@mpob.gov.my <br />
Puan Nor Baayah Mohammed Yusop - No. Telefon: 03-78022865 / Emel : abby@mpob.gov.my<br />
Puan Nooralfaziana Ahmat - No. Telefon: 03-78022917 / Emel : noralfaziana@mpob.gov.my<br />


<br />
<p><b>Kilang Penapis</b></p>
Puan Nurul Syuhada Azrin Nasarudin- No. Telefon: 03-78022912 / Emel : nurul.syuhada@mpob.gov.my  <br />
Puan Puan Aziana Misnan - No. Telefon: 03-78022955 / Emel : aziana@mpob.gov.my<br />

<br />

<p><b>Kilang Pelumat Isirung</b></p>
Cik Siti Maisarah Mohd Ali - No. Telefon: 03-78022913 / Emel : siti.maisarah@mpob.gov.my<br />
En. Abd Rahman Bin Rahmat - No. Telefon : 03-78022990 / Emel : rahmanr@mpob.gov.my<br />
<br />

<p><b>Kilang Oleokimia</b></p>
Cik Siti Maisarah Mohd Ali - No. Telefon: 03-78022913 / Emel : siti.maisarah@mpob.gov.my<br />
Puan Aziana Misnan - No. Telefon :03-78022955 / Emel : aziana@mpob.gov.my<br />


<br />

<p><b>Kilang Biodiesel</b></p>
Puan Siti Suziyana Mohd Omar - No. Telefon: 03-78022820 / Emel: suziyana@mpob.gov.my<br />
Puan Nurazura Amrullah - No. Telefon: 03-78022991/ Emel: suziyana@mpob.gov.my<br />

<br />

<p><b>Pusat Simpanan</b></p>
Cik Siti Maisarah Mohd Ali - No. Telefon: 03-78022913 / Emel : siti.maisarah@mpob.gov.my<br />
En. Abd Rahman Bin Rahmat - No. Telefon :03-78022990 / Emel : rahmanr@mpob.gov.my <br />


<br />
<?php else: ?>
<p><b>Peniaga Buah</b></p>
Puan Nurul Ain Ahmad Tarmizi - No. Telefon: 03-78022816 / Emel : ainas@mpob.gov.my <br />
Puan Nurazura Amrullah - No. Telefon: 03-78022991/ Emel: suziyana@mpob.gov.my<br />
<br />

<p><b>Bahan Tanaman</b></p>
Puan Harisah bt Hamid - No. Telefon: 03-78022916 / Emel : harisah@mpob.gov.my<br />
Puan Siti Suziyana Mohd Omar - No. Telefon: 03-78022820 / Emel: suziyana@mpob.gov.my<br />
<br />

<p><b>Peniaga Minyak</b></p>
Puan Nurul Hufaidah Sharif- No. Telefon: 03-78022824 / Emel : hufaidah@mpob.gov.my<br />
Puan Nurul Ain Ahmad Tarmizi - No. Telefon: 03-78022816 / Emel : ainas@mpob.gov.my<br />
Puan Harisah Hamid - No. Telefon: 03-78022916 / Emel : harisah@mpob.gov.my<br />
Puan Siti Suziyana Mohd Omar - No. Telefon: 03-78022820 / Emel: suziyana@mpob.gov.my<br />
<br />

<?php endif; ?>




