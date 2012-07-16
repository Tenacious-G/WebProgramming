<?php
/* Program: getPets.php
 * Desc:    Displays list of items from a database.
 */
?>
<html>
<head><title>Pet Catalog</title></head>
<body>
<?php
  $type = "Horse";
  $petInfo = getPetsOfType($type);      //call function

  /* Display results in a table */
  echo "<h1>{$type}s</h1>\n";
  echo "<table cellspacing='15'>\n";
  echo "<tr><td colspan='4'><hr /></td></tr>\n";
  for($i=1;$i<=sizeof($petInfo);$i++)
  {
      $f_price = number_format($petInfo[$i]['price'],2);
      echo "<tr>\n
           <td>$i.</td>\n
           <td>{$petInfo[$i]['petName']}</td>\n
           <td>{$petInfo[$i]['petDescription']}</td>\n
           <td style='text-align: right'>\$$f_price</td>\n
           </tr>\n";
     echo "<tr><td colspan='4'><hr /></td></tr>\n";
  }
  echo "</table>\n";
?>
</body></html>

<?php
function getPetsOfType($petType)
{
  $user="catalog";
  $host="localhost";
  $passwd="";
  $cxn = mysqli_connect($host,$user,$passwd,"PetCatalog")
         or die("Couldn't connect to server");
  $query = "SELECT * FROM Pet WHERE petType='$petType'";
  $result = mysqli_query($cxn,$query)
            or die("Couldn't execute query.");
  
  $j = 1;
  while($row=mysqli_fetch_assoc($result))
  {
    foreach($row as $colname => $value)
    {
       $array_multi[$j][$colname] = $value;
    }
    $j++;
  }
  return $array_multi;
}
?>
