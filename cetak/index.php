<?php

include_once("emel_message.php");

echo "<form method=post name=form1 action='surat_result.php' enctype='multipart/form-data'>\n";

?>
E-Gunatenaga
<br>
Cetakan Surat (Belum Mula dan Mula Lapor)
<br>
Survey <select name=survey>
          <option value=''></option>
           <?php
             $survey_array = get_survey_list();
             foreach ($survey_array as $thissurvey)
               {
                 echo "<option value=\"";
                 echo $thissurvey["id"];
                 echo "\"";
           // existing value
                 if ($edit && $thissurvey["id"] == $survey)
                    echo "selected";
                 echo ">";
                 echo $thissurvey["year"],$thissurvey["month"];
                 echo "\n";
               }
           ?>
           </select>



Kategori <select name=brg>
          <option value=''></option>
           <?php
             $cat_array = get_category_list();
             foreach ($cat_array as $thiscat)
               {
                 echo "<option value=\"";
                 echo $thiscat["id"];
                 echo "\"";
           // existing value
                 if ($edit && $thiscat["id"] == $brg)
                    echo "selected";
                 echo ">";
                 echo $thiscat["name"];
                 echo "\n";
               }
           ?>
           </select>

<?php
echo "<input type=submit name=submit value='Surat Alamat'>\n";
echo "<input type=submit name=submit value='Download Senarai Alamat'>\n";

echo "</form>\n";

?>