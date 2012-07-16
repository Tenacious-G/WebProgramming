<?php
/*  Program name: buildSelect.php
 *  Description:  Program builds a selection list 
 *                from the database.
 */
  $user="admin";
  $host="localhost";
  $password="";
  $database = "PetCatalog";
  $cxn = mysqli_connect($host,$user,$password,$database)
         or die ("couldn't connect to server");
  $query = "SELECT DISTINCT petType FROM Pet ORDER BY petType";
  $result = mysqli_query($cxn,$query)
            or die ("Couldn't execute query.");
?>
<html>
<head><title>Pet Types</title></head>

<body>
<form action='processform.php' method='POST'>
  <select name='petType'>
<?php
   while($row = mysqli_fetch_assoc($result))
   {
     extract($row);
     echo "<option value='$petType'>$petType</option>\n";
   }
?>
  </select>
  <input type='submit' value='Select Type of Pet' />
</form></body></html>
