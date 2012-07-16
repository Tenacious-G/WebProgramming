<?php
/*  Program name: displayPhone
 *  Description:  Script displays a form that asks for 
 *                the customer phone number.
 */
$labels = array ( "first_name" => "First Name",
                  "last_name" => "Last Name",
                  "phone" => "Phone");
?>
<html>
<head>
<title>Customer Phone Number</title>
<style type='text/css'>
<!--
  form { margin: 1.5em 0 0 0; padding: 0; }
  .field { padding-top: .5em; }
  label { font-weight: bold; float: left; width: 20%;
          margin-right: 1em; text-align: right; }
  #submit { margin-left: 35%; padding-top: 1em; }
-->
</style> 
</head>

<body>
<h3>Please enter your phone number below.</h3>
<form action='savePhone.php' method='POST'>
<?php
  /* Loop that displays the form fields */
  foreach($labels as $field => $label)
  {
    echo "<div class='field'>
           <label for='$field'>$label</label>
            <input type='text' name='$field' id='$field'
              size='65' maxlength='65' /></div>\n";
  }
  echo "<div id='submit'><input type='submit' 
                   value='Submit Phone Number' />\n";
  echo "</div>\n</form>\n</body>\n</html>";
?>
