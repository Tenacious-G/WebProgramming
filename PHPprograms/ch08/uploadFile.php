<?php
 /* Script name: uploadFile.php
  * Description: Uploads a file via HTTP with a POST form. 
  */
  if(!isset($_POST['Upload']))	#5
  {
    include("form_upload.inc");
  }
  else	#9
  {
    if($_FILES['pix']['tmp_name'] == "none")	#11
    {
      echo "<p style='font-weight: bold'>
        File did not successfully upload. Check the 
            file size. File must be less than 500K.</p>";
      include("form_upload.inc");
      exit();
    }
    if(!ereg("image",$_FILES['pix']['type']))	#19
    {
      echo "<p style='font-weight: bold'>
        File is not a picture. Please try another 
            file.</p>";
      include("form_upload.inc");
      exit();
    }
    else		#27
    {
      $destination='c:\data'."\\".$_FILES['pix']['name'];
      $temp_file = $_FILES['pix']['tmp_name'];
      move_uploaded_file($temp_file,$destination);
      echo "<p style='font-weight: bold'>
        The file has successfully uploaded:
            {$_FILES['pix']['name']} 
            ({$_FILES['pix']['size']})</p>"; 
    }
  }
?>
