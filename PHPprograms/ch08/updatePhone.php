<?php
/*  Program name: updatePhone.php
 *  Description:  Program checks the phone number for 
 *                incorrect format. Updates the phone 
 *                number in the database 
 *                for the specified name.
 */
$labels = array ( "first_name" => "First Name",
                  "last_name" => "Last Name", 
                  "phone" => "Phone");
?>
<html>
<head>
<title>Customer Phone Number Update</title>
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
/* check each field for blank fields */
foreach($_POST as $field => $value)
{
  if(empty($value))
  {
      $blank_array[] = $field;
  }
}
/* check format of phone number */
if(!preg_match
     ("/^[0-9)( -]{7,20}(([xX]|(ext)|(ex))?
        [ -]?[0-9]{1,7})?$/",
     $_POST['phone']))
{
     $bad_format[] = "phone";
}
/* if any fields were not okay, display error and form */
if(@sizeof($blank_array) > 0 or @sizeof($bad_format) > 0)
{
  if(@sizeof($blank_array) > 0)
  {
    /* display message for missing information */
    echo "<p><b>You didn't fill in one or more required
             fields. You must enter:</b><br />";
    /* display list of missing information */
    foreach($blank_array as $value)
    {
       echo "&nbsp;&nbsp;&nbsp;{$labels[$value]}<br />";
    }
    echo "</p>";
  }
  if(@sizeof($bad_format) > 0)
  {
    /* display message for bad phone number */
    echo "<p><b>Your phone number appears to be incorrect. 
             </b></p>";
  }
  /* redisplay form */
  echo "<p><hr />";
  echo "<h3>Please enter your phone number below.</h3>";
  echo "<form action='$_SERVER[PHP_SELF]' method='post'>";
  foreach($labels as $field => $label)
  {
    $good_data[$field] = strip_tags(trim($_POST[$field]));
    echo "<div class='field'>
           <label for='$field'>$label</label>
            <input type='text' name='$field' id='$field' 
             size='65' maxlength='65' 
             value='$good_data[$field]' /></div>\n";
  }
  echo "<div id='submit'><input type='submit' 
             value='Submit Phone Number' />\n";
  echo "</div>\n</form>\n</body>\n</html>";
  exit();
}
else   //if data is okay
{
  $good_data['phone'] = strip_tags(trim($_POST['phone']));
  $good_data['phone'] = 
          ereg_replace("[)( .-]","",$good_data['phone']);
  $user="admin";
  $host="localhost";
  $passwd="";
  $dbname = "MemberDirectory";
  $cxn = mysqli_connect($host,$user,$passwd,$dbname)
         or die ("Couldn't connect to server");
  $query = "UPDATE Phone SET phone='$good_data[phone]' 
                   WHERE last_name='$_POST[last_name]' 
                     AND first_name='$_POST[first_name]'";
  $result = mysqli_query($cxn,$query)
            or die ("Couldn't execute query: "
                     .mysqli_error($cxn));
  if(mysqli_affected_rows($cxn) > 0)
  {
     echo "<h3>The phone number for {$_POST['first_name']} 
            {$_POST['last_name']} has been updated</h3>";
  }
  else
    echo "No record updated";
}
?>
</body></html>
