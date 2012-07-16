<?php
 /* Function addNewType
  * Desc     Adds a new pet type and description to the
  *          PetType table. Checks for the new pet type
  *          first and does not add it to the table if 
  *          it is already there.
  */
function addNewType($petType,$typeDescription,$cxn)
{  
  /* Check whether new category is in PetType table.
     If it is not in table, add it to table. */
  $query = "SELECT petType FROM PetType 
                           WHERE petType='$petType'";
  $result = mysqli_query($cxn,$query) or 
            die("Couldn't execute select query");
  $ntype = mysqli_num_rows($result); // 
  if ($ntype < 1)    // if new type is not in table
  {
    $petType = ucfirst(strip_tags(trim($petType)));
    $typeDescription = 
        ucfirst(strip_tags(trim($typeDescription)));
    $petType = mysqli_real_escape_string($cxn,$petType);
    $typeDescription = 
        mysqli_real_escape_string($cxn,$typeDescription);

    $query="INSERT INTO PetType (petType,typeDescription) 
                 VALUES ('$petType','$typeDescription')";
    $result = mysqli_query($cxn,$query) 
               or die("Couldn't execute insert query");
  }
  return;
}
?>
