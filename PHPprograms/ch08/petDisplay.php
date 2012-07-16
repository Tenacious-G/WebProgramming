<?php
/* Program: petDisplay.php
 * Desc:    Displays all pets in selected category.
 */
?>
<html>
<head><title>Pet Catalog</title></head>
<body>
<?php
  $user="catalog";
  $host="localhost";
  $password="";
  $database = "PetCatalog";
  $cxn = mysqli_connect($host,$user,$password,$database)
         or die ("couldn't connect to server");
  $pettype = "horse";  //horse was typed in a form by user
  $query = "SELECT * FROM Pet WHERE petType='$pettype'";
  $result = mysqli_query($cxn,$query)
            or die ("Couldn't execute query.");

  /* Display results in a table */
  $pettype = ucfirst($pettype)."s";
  echo "<h1>$pettype</h1>\n";
  echo "<table cellspacing='15'>\n";
  echo "<tr><td colspan='3'><hr /></td></tr>\n";
  while($row = mysqli_fetch_assoc($result))
  {
     extract($row);
     $f_price = number_format($price,2);
     echo "<tr>\n
           <td>$petName</td>\n
           <td>$petDescription</td>\n
           <td style='text-align: right'>\$$f_price</td>\n
           </tr>\n";
     echo "<tr><td colspan='3'><hr /></td></tr>\n";
  }
  echo "</table>\n";
?>
</body></html>
