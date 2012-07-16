<?php
/*  Script name:  processform.php
 *  Description:  Script displays all the information
 *                passed from a form.
 */
  echo "<html>
        <head><title>Customer Address</title></head>
        <body>";
  foreach ($_POST as $field => $value)
  {
     echo "$field = $value<br />\n";
  }
?>
</body></html>
