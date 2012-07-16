<?php
/* Program: petDescripFor.php
 * Desc:    Displays a numbered list of all pets in 
 *          selected category.
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
         or die ("Couldn't connect to server");
  $pettype = "horse";  //horse was typed in a form by user
  $query = "SELECT * FROM Pet WHERE petType='$pettype'";
  $result = mysqli_query($cxn,$query)
            or die ("Couldn't execute query.");
  $nrows = mysqli_num_rows($result);

  /* Display results in a table */
  echo "<h1>Horses</h1>";
  echo "<table cellspacing='15'>";
  echo "<tr><td colspan='4'><hr /></td></tr>";
  for ($i=0;$i<$nrows;$i++)
  {
     $n = $i + 1;  #add 1 so numbers don't start with 0
     $row = mysqli_fetch_assoc($result);
     extract($row);
     $f_price = number_format($price,2);
      echo "<tr>\n
           <td>$n.</td>\n
           <td>$petName</td>\n
           <td>$petDescription</td>\n
           <td style='text-align: right'>\$$f_price</td>\n
           </tr>\n";
     echo "<tr><td colspan='4'><hr></td></tr>\n";
  }
  echo "</table>\n";
?>
</body></html>
