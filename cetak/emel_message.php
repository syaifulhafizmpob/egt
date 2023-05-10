<?php

function db_connect()
{

$result = mysql_connect("localhost","egt","egt2018!");
if (!$result)
   return false;
if (!@mysql_select_db("egunatenaga"))
   return false;

return $result;

}

function db_result_to_array($result)
{

$res_array = array();

for ($count=0; $row = @mysql_fetch_array($result); $count++)
   $res_array[$count] = $row;

return $res_array;

}


function get_survey_list()
{

   // query database for maklumat pelesen dan penyata bulanan

   $conn = db_connect();
   $query = "select id,year,month
             from r_survey";

   $result = @mysql_query($query);
   if (!$result)
     return false;
   $num_prod = @mysql_num_rows($result);
   if ($num_prod == 0)
     return false;
   $result = db_result_to_array($result);
   $arr1 = array();
   $result = array_merge($arr1,$result);
   return $result;

}

function get_category_list()
{

   // query database for maklumat pelesen dan penyata bulanan

   $conn = db_connect();
   $query = "select id,name
             from r_category";

   $result = @mysql_query($query);
   if (!$result)
     return false;
   $num_prod = @mysql_num_rows($result);
   if ($num_prod == 0)
     return false;
   $result = db_result_to_array($result);
   $arr1 = array();
   $result = array_merge($arr1,$result);
   return $result;

}

function get_profile($brg,$survey)
{

   // query database for maklumat pelesen dan penyata bulanan

   $conn = db_connect();
   $query = "select r_respondent.company,r_respondent.nolesen,r_respondent.address_surat,r_respondent.address_surat2,r_respondent.address_surat3 from r_respondent, r_record_meta
            where r_respondent.id = r_record_meta.respondent_id and r_respondent.category_id = r_record_meta.category_id and 
            r_respondent.category_id='$brg' and r_record_meta.survey_id='$survey'
            and r_record_meta.`status` in ('mula_lapor','belum_mula') order by r_respondent.company";

   $result = @mysql_query($query);
   if (!$result)
      return false;


   return $result;

}

function papar_surat_alamat($company,$nolesen,$address_surat,$address_surat2,$address_surat3)

{
/*
$tarikh = date("d-m-Y");


$tarikh = date("d-m-Y");

$tarikh1 = date("Y");

$day = date("d");

$month = date("m");



if ($month == '01')

  $valmonth = 'Januari';

elseif ($month == '02')

  $valmonth = 'Februari';

elseif ($month == '03')

  $valmonth = 'Mac';

elseif ($month == '04')

  $valmonth = 'April';

elseif ($month == '05')

  $valmonth = 'Mei';

elseif ($month == '06')  

  $valmonth = 'Jun';

elseif ($month == '07')

  $valmonth = 'Julai';

elseif ($month == '08')

  $valmonth = 'Ogos';

elseif ($month == '09')

  $valmonth = 'September';

elseif ($month == '10')

  $valmonth = 'Oktober';

elseif ($month == '11')

  $valmonth = 'November';

elseif ($month == '12')

  $valmonth = 'Disember';

  

$tkh = $day . ' ' . $valmonth . ' ' . $tarikh1;
*/
?>






 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

 &nbsp;&nbsp;

 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<!--<p align=left>
(17)04/B/PI/526/3 Jil.10</p>

<p><?php echo $tkh?></p> -->

<!--<table border=0 width=50% height=8%>
<tr><td> </td></tr>
</table> -->


   <br><font face=Arial size=3>
   <?php echo $company?><br>

   <?php echo $address_surat?><br>

   <?php echo $address_surat2?><br>

   <?php echo $address_surat3?></p>
   (No Lesen : <?php echo $nolesen?>)</font></p>


<table border=0 width=50% height=62%>
<tr><td> </td></tr>
</table>
<H1 STYLE="page-break-before:always"></H1>

<?php

}


function download_surat_alamat($brg,$survey){
 // output headers so that the file is downloaded rather than displayed
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=data.csv');

// create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// output the column headings
fputcsv($output, array('No Lesen', 'Nama', 'Alamat Surat', 'Alamat Surat2','Alamat Surat3','Status Survey'));

// fetch the data

$conn = db_connect();


$rows = mysql_query("select r_respondent.company,r_respondent.nolesen,r_respondent.address_surat,r_respondent.address_surat2,r_respondent.address_surat3,r_record_meta.`status` from r_respondent, r_record_meta
            where r_respondent.id = r_record_meta.respondent_id and r_respondent.category_id = r_record_meta.category_id and
            r_respondent.category_id='$brg' and r_record_meta.survey_id='$survey'
            and r_record_meta.`status` in ('mula_lapor','belum_mula') order by r_respondent.company");


// loop over the rows, outputting them
while ($row = mysql_fetch_assoc($rows)) fputcsv($output, $row);

}

?>
