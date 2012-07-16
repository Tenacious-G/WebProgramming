<?php
/*  Program name: buildCheckbox.php
 *  Description:  Program displays a list of 
 *                check boxes from database info.
 */
$user="catalog";
$host="localhost";
$password="";
$database = "PetCatalog";
$cxn = mysqli_connect($host,$user,$password,$database)
       or die ("couldn't connect to server");
$query = "SELECT DISTINCT petType FROM Pet
                 ORDER BY petType";
$result = mysqli_query($cxn,$query)
          or die ("Couldn't execute query.");
?>

<html>
<head><title>Pet Types</title></head>
<body>
<div style='margin-left: .5in; margin-top: .5in'>
  <p style='font-weight: bold'>
     Which type of pet are you interested in?</p>
  <p>Choose as many types of pets as you want:</p>
  <form action='processform.php' method='POST'>
<?php
   while($row = mysqli_fetch_assoc($result))
   {
     extract($row);
     echo "<input type='checkbox'
                 name='interest[$petType]'
                 value='$petType' />$petType<br />\n";
   }
   echo "<p><input type='submit' 
                   value='Select Type of Pet' />\n";
?>
</form></div></body></html>
