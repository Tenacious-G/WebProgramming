<?php
/*  Program name: buildRadio.php
 *  Description:  Program displays a list of radio
 *                buttons from database info.
 */
 $user="catalog";
 $host="localhost";
 $password="";
 $database = "PetCatalog";
 $cxn = mysqli_connect($host,$user,$password,$database)
        or die ("Couldn't connect to server");
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
  <p>Please choose one type of pet from the 
     following list:</p>
<form action='processform.php' method='POST'>
<?php
 while($row = mysqli_fetch_assoc($result))
 {
    extract($row);
    echo "<input type='radio' name='interest' 
                 value='$petType' />$petType<br />\n";
 }
?>
<p><input type='submit' value='Select Type of Pet' /></p>
</form></div></body></html>
