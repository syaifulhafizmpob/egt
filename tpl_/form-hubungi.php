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
Zainon Basran - No. Telefon: 03-78022912 / Emel : bzainon@mpob.gov.my<br />
Zanariah Binti Haron - No. Telefon: 03-78022906 / Emel : zanariah@mpob.gov.my<br />
Rosemaiza Izana Hassin - No. Telefon: 03-78022914 / Emel : izana@mpob.gov.my </p>
<br />
<p><b>Kilang Penapis</b></p>
Zainon Basran -No. Telefon: 03-78022912 / Emel : bzainon@mpob.gov.my<br />
Faizah Binti Kamruzaman - No. Telefon :03-78022917 / Emel : fkamruza@mpob.gov.my</p>
<br />

<p><b>Kilang Pelumat Isirung</b></p>
Abd. Radzak Abd Aziz -No. Telefon: 03-78022913 / Emel : radzak@mpob.gov.my<br />
Abd Rahman Bin Rahmat - No. Telefon : 03-78022990 / Emel : rahmanr@mpob.gov.my</p>
<br />

<p><b>Kilang Oleokimia</b></p>
Abd. Radzak Abd Aziz - No. Telefon: 03-78022913 / Emel : radzak@mpob.gov.my<br />
Faizah Binti Kamruzaman - No. Telefon :03-78022917 / Emel : fkamruza@mpob.gov.my</p>
<br />

<p><b>Kilang Biodiesel</b></p>
Noraida Omar - No. Telefon: 03-78022925 / Emel : noraida@mpob.gov.my<br />
Norasyiqqin shabuddin - No. Telefon: 03-78022816 / Emel : eqin@mpob.gov.my<br />
Aziana Mishan - No. Telefon :03-78022955 / Emel : aziana@mpob.gov.my</p>
<br />

<p><b>Pusat Simpanan</b></p>
Abd. Radzak Abd Aziz - No. Telefon: 03-78022913 / Emel : radzak@mpob.gov.my<br />
Abd Rahman Bin Rahmat - No. Telefon :03-78022990 / Emel : rahmanr@mpob.gov.my</p>
<br />
<?php else: ?>
<p><b>Peniaga</b></p>
Puan Harisah bt Hamid (Bahan Tanaman) - No. Telefon: 03-78022916 / Emel : harisah@mpob.gov.my<br />
Puan Fauziah bt Mohd Daud (Peniaga Minyak) - No. Telefon: 03-78022863 / Emel : fhajimoh@mpob.gov.my<br />
Encik Norazmi bin Numairi (Peniaga Buah) - No. Telefon: 03-78022991 / Emel : azmin@mpob.gov.my<br />
Puan Mazrian bt Musa - No. Telefon: 03-78022910 / Emel : mazrian@mpob.gov.my</p>
<br />

<?php endif; ?>




