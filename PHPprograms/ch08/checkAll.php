<?php
/*  Program name: checkAll.php*  Description:  Program 
 *                checks all the form fields for
 *                blank fields and incorrect format.
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
  /* set up array containing all the fields */
  $labels = array ( "first_name" => "First Name",
                    "middle_name" => "Middle Name",
                    "last_name" => "Last Name",
                    "phone" => "Phone");
  foreach ($_POST as $field => $value)
  {
    /* check each field except middle name for blank
       fields */
    if(empty($value))
    {
       if($field != "middle_name")
       {
          $blank_array[] = $field;
       }
    }
    /* check names for invalid formats. */
    elseif($field == "first_name" or $field == 
            "middle_name" or $field == "last_name" )
    {
        if(!preg_match("/^[A-Za-z' -]{1,50}$/",$_POST[$field]) )
        {
            $bad_format[] = $field;
        }
    }
    /* check phone for invalid format. */
    elseif($field == "phone")
    {
      if(!preg_match("/^[0-9)( -]{7,20}(([xX]|(ext)|(ex))?[ -]?[0-9]{1,7})?$/",$value))
      {
           $bad_format[] = $field;
      }
    }
  }
  /* if any fields are not okay, display error message 
     and form */
  if(@sizeof($blank_array) >0 or @sizeof($bad_format) > 0)
  {
    if(@sizeof($blank_array) > 0)
    {
        /* display message for missing information */
        echo "<p><b>You didn't fill in one or more 
                 required fields.
                 You must enter:</b><br />\n";
        /* display list of missing information */
        foreach($blank_array as $value)
        {
          echo "&nbsp;&nbsp;&nbsp;{$labels[$value]}
                <br />\n";
        }
        echo "</p>\n";
    }
    if(@sizeof($bad_format) > 0)
    {
        /* display message for bad information */
        echo "<p><b>One or more fields have information 
                    that appears to be incorrect.
                    Correct the format for:</b><br />\n";
        /* display list of bad information */
        foreach($bad_format as $value)
        {
           echo "&nbsp;&nbsp;&nbsp;{$labels[$value]}
                 <br />\n";
        }
        echo "</p>\n";
    }
    /* redisplay form */
    echo "<form action='$_SERVER[PHP_SELF]' 
                method='POST'>";
    foreach($labels as $field => $label)
    {
      $clean_data[$field] = 
          strip_tags(trim($_POST[$field]));
       echo "<div class='field'>
              <label for='$field'>$label</label>
                <input type='text' name='$field' 
                  id='$field'
                  size='65' maxlength='65' 
                  value='$clean_data[$field]' /></div>\n";
    }
    echo "<div id='submit'><input type='submit' 
                   value='Submit Phone Number' />\n";
    echo "</div>\n</form>\n</body>\n</html>";
    exit();
  }
  /* if data is good */
  echo "<p>All data is good</p></body></html>";
?>
