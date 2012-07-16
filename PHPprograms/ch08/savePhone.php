<?php
/*  Program name: savePhone.php
 *  Description:  Program checks all the form fields for
 *                blank fields and incorrect format. Saves 
 *                the correct fields in a database.
 */
/* set up array of field labels */
$labels = array( "first_name" => "First Name",
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
<?php
/* Check information from form */
foreach($_POST as $field => $value)
{
  /* check each field for blank fields */
  if(empty($value))
  {
    $blank_array[] = $field;
  }
  /* check format of each field */
  elseif(preg_match("/name/i",$field))  
  {
    if(!preg_match("/^[A-Za-z' -]{1,50}$/",$value) )
    {
        $bad_format[] = $field;
    }
  }
  elseif($field == "phone")
  {
   if(!preg_match("/^[0-9)( -]{7,20}(([xX]|(ext)|(ex))?
                   [ -]?[0-9]{1,7})?$/",$value) )
    {
        $bad_format[] = $field;
    }
  }
} 
/* if any fields were not okay, display error and form */
if(@sizeof($blank_array) > 0 or @sizeof($bad_format) > 0)
{
  if(@sizeof($blank_array) > 0)
  {
     /* display message for missing information */
     echo "<p><b>You didn't fill in one or more required 
               Fields. You must enter:</b><br />";
     foreach($blank_array as $value)
     {
        echo "&nbsp;&nbsp;&nbsp;{$labels[$value]}<br />";
     }
     echo "</p>";
  }
  if(@sizeof($bad_format) > 0)
  {
     /* display message for bad information */
     echo "<p><b>One or more fields have information that
            appears to be incorrect. Correct the format 
            for:</b><br />";
     foreach($bad_format as $value)
     {
        echo "&nbsp;&nbsp;&nbsp;{$labels[$value]}<br />";
     }
     echo "</p>";
  }
  /* redisplay form */
  echo "<p><hr />";
  echo "<h3>Please enter your phone number below.</h3>";
  echo "<form action='$_SERVER[PHP_SELF]' method='post'>";
  foreach($labels as $field => $label)
  {
    $good_data[$field]=strip_tags(trim($_POST[$field]));
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
  $user="admin";
  $host="localhost";
  $passwd="";
  $dbnamee = "MemberDirectory";
  $cxn = mysqli_connect($host,$user,$passwd,$dbname)
         or die ("couldn't connect to server");
  foreach($labels as $field => $value)
  {
     $good_data[$field] = 
           strip_tags(trim($_POST[$field]));
     if($field == "phone")
     {
        $good_data[$field] = 
            preg_replace("/[)( .-]/","",$good_data[$field]);
     }
     $good_data[$field] = 
            mysqli_real_escape_string($cxn,
            $good_data[$field]);
  }
  $query = "INSERT INTO Phone (";	#118
  foreach($good_data as $field => $value)	#119
  {
    $query .= "$field,";
  }
  $query .= ") VALUES (";	#123
  $query = preg_replace("/,\)/",")",$query);	#124
  foreach($good_data as $field => $value)	#125
  {
     $query .= "'$value',";
  }
  $query .= ")";
  $query = preg_replace("/,\)/",")",$query);
  $result = mysqli_query($cxn,$query)
            or die ("Couldn't execute query. "
                     .mysqli_error($cxn));
  echo "<h4>New Member added to database</h4>";
}
?>
</body></html>
