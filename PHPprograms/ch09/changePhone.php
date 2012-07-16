<?php
/*  Program name: changePhone.php
 *  Description:  Displays a phone number retrieved 
 *                from the database and allows the user 
 *                to change the phone number.
 */
?>
<html>
<head><title>Change phone number</title></head>
<body>
<?php
  $host="localhost";
  $user="admin";
  $password="";
  $database="MemberDirectory";
  $loginName = "gsmith";  // passed from previous page
  $cxn = mysqli_connect($host,$user,$password,$database)
         or die ("couldn't connect to server");

  if(@$_GET['first'] == "no")	#19
  {
    $phone = trim($_POST['phone']);
    if(!ereg("^[0-9)( -]{7,20}$",$phone) or $phone=="")
    {
       echo "<h3 style='text-align: center'> Phone
           number does not appear to be valid.</h3>";
       display_form($loginName,$phone);	#26
    }
    else // phone number is okay	#28
    {
       $query = "UPDATE Member SET phone='$phone' 
                        WHERE loginName='$loginName'";
       $result = mysqli_query($cxn,$query)
                 or die ("Couldn't execute query.");
       echo "<h3>Phone number has been updated.</h3>";
       exit();
    }
  }
  else // first time form is displayed	#38
  {
    $query = "SELECT phone FROM Member 
              WHERE loginName='$loginName'";
    $result = mysqli_query($cxn,$query)
              or die ("Couldn't execute query.");
    $row = mysqli_fetch_row($result);
    $phone = $row[0];
    display_form($loginName,$phone);	#45
  }

function display_form($loginName,$phone)	#48
{
  echo "<div style='text-align: center'>";
  echo "<form action='$_SERVER[PHP_SELF]?first=no'
              method='POST'>
          <h4>Please check the phone number below
              and correct it if necessary.</h4><hr />
          <p><b>$loginName</b> 
             <input type='text' name='phone'
               maxlength='20' value='$phone'></p>
          <p><input type='submit' 
               value='Submit phone number'></p>
        </form>";
  echo "</div>";
}
?>
</body></html>
