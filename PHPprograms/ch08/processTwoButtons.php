<?php
/*  Program name: processTwoButtons.php
 *  Description:  Program displays different information
 *                depending on which submit button was
 *                pushed.
 */
?>
<html>
<head><title>Member Address or Phone Number</title></head>
<body>
<?php
  $user="";
  $host="localhost";
  $password="";
  $database = "MemberDirectory";
  $cxn = mysqli_connect($host,$user,$password,$database)
         or die ("Couldn't connect to server");
  if($_POST['display_button'] == "Show Address")
  {
     $query = "SELECT street,city,state,zip FROM Member 
                 WHERE lastName='$_POST[last_name]'";
     $result = mysqli_query($cxn,$query)
               or die ("Couldn't execute query.");
     $row = mysqli_fetch_assoc($result);
     extract($row);
     echo "$street<br />$city, $state  $zip<br />";
  }
  else
  {
     $query = "SELECT phone FROM Member 
                   WHERE lastName='$_POST[last_name]'";
     $result = mysqli_query($cxn,$query)
               or die ("Couldn't execute query.");
     $row = mysqli_fetch_assoc($result);
     echo "Phone: {$row['phone']}";
  }
?>
</body></html>
