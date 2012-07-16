<?php
/*  Program name: checkBlank.php
 *  Description:  Program checks all the form fields for
 *                blank fields.
 */
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
<?php
  /* set up array with all the fields */
  $labels = array( "first_name" => "First Name",
                   "middle_name" => "Middle Name",
                   "last_name" => "Last Name",
                   "phone" => "Phone");
  /* check each field except middle name for 
     blank fields */
  foreach($_POST as $field => $value)
  {
    if($field != "middle_name")
    {
      if(empty($value))
      {
         $blank_array[] = $field;
      }  
    }
  }  
  /* if any fields were blank, display error message and 
     redisplay form */
  if(@sizeof($blank_array) > 0) //blank fields are found
  {
    echo "<p><b>You didn't fill in one or more required
             fields. You must enter:</b><br />\n";
    /* display list of missing information */
    foreach($blank_array as $value)
    {
      echo "&nbsp;&nbsp;&nbsp;{$labels[$value]}<br />\n";
    } 
    echo "</p>";
    /* redisplay form */
    echo "<form action='$_SERVER[PHP_SELF]' 
                method='POST'>\n";
    foreach($labels as $field => $label)
    {
      $good_data[$field]=strip_tags(trim($_POST[$field]));
       echo "<div class='field'>
              <label for='$field'>$label</label>
                <input type='text' name='$field' 
                       id='$field' size='65' 
                       maxlength='65' 
                       value='$good_data[$field]' />\n
             </div>\n";
    }
    echo "<div id='submit'><input type='submit' 
                   value='Submit Phone Number' />\n";
    echo "</div>\n</form>\n</body>\n</html>";
    exit();
  }
  echo "All required fields contain information";
?>
