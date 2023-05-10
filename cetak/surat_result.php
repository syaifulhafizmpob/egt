<?php

include_once("emel_message.php");
$brg = $_POST["brg"];
$survey = $_POST["survey"];
$submit = $_POST["submit"];


if ($submit == 'Surat Alamat')
 {

     $result1 = get_profile($brg,$survey);
     while ($row = mysql_fetch_row($result1))
     {
     papar_surat_alamat($row[0],$row[1],$row[2],$row[3],$row[4]);

     }

 }
 elseif  ($submit == 'Download Senarai Alamat')
 {
     download_surat_alamat($brg,$survey);

 }

 ?>