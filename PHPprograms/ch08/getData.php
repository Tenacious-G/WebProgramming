<?php
/* Program: getdata.php
 * Desc:    Gets data from a database using a function
 */
?>
<html>
<head><title>Pet Catalog</title></head>
<body>
<?php

  $petInfo = getPetInfo("Unicorn");      //call function

  $f_price = number_format($petInfo['price'],2);
  echo "<p><b>{$petInfo['petName']}</b><br />\n
       Description: {$petInfo['petDescription']}<br />\n
       Price: \${$petInfo['price']}\n"
?>
</body></html>

<?php
function getPetInfo($petName)
{
  $user="catalog";
  $host="localhost";
  $password="";
  $dbname = "PetCatalog";
  $cxn = mysqli_connect($host,$user,$password,$dbname)
         or die ("Couldn't connect to server");
  $query = "SELECT * FROM Pet WHERE petName='$petName'";
  $result = mysqli_query($cxn,$query)
            or die ("Couldn't execute query.");
  return mysqli_fetch_assoc($result);
}
?>
