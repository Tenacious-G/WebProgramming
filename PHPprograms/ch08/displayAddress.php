<?php
/*  Program name: displayAddress
 *  Description:  Script displays a form with address 
 *                information obtained from the database.
 */

  $labels = array( "firstName"=>"First Name:",
                   "lastName"=>"Last Name:",
                   "street"=>"Street Address:",
                   "city"=>"City:",
                   "state"=>"State:",
                   "zip"=>"Zipcode:");
  $user="admin";
  $host="localhost";
  $password="";
  $database = "MemberDirectory";
  $loginName = "gsmith";     // user login name
  
  $cxn = mysqli_connect($host,$user,$password,$database)
         or die ("couldn't connect to server");
  $query = "SELECT * FROM Member 
                     WHERE loginName='$loginName'";
  $result = mysqli_query($cxn,$query)
            or die ("Couldn't execute query.");
  $row = mysqli_fetch_assoc($result);
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
  echo "<div style='text-align: center'>
        <h1>Address for $loginName</h1>\n";
  echo "<p style='font-size: large; font-weight: bold'>
           Please check the information below and change 
           any information that is incorrect.</p>
           <hr /></div>\n";
  echo "<form action='processAddress.php' method='POST'>";
  foreach($labels as $field => $label)
  {
    echo "<div class='field'>
           <label for='$field'>$label</label>
            <input type='text' name='$field' id='$field'
             value='$row[$field]' size='65' 
             maxlength='65' /></div>\n";
  }
  echo "<div id='submit'><input type='submit' 
             value='Submit Address' />\n";
  echo "</div>\n</form>\n</body>\n</html>";
?>
