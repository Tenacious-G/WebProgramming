<?php
  /* Program: PetCatalog.php
   * Desc:    Displays a list of pet categories from the
   *           PetType table. Includes descriptions.
   *          Displays radio buttons for user to check.
   */
?>
<html>
<head><title>Pet Types</title></head>
<body>
<?php
  include("misc.inc");	                                #12

  $cxn = mysqli_connect($host,$user,$passwd,$dbname)	  #14
         or die ("couldn't connect to server");

  /* Select all categories from PetType table */
  $query = "SELECT * FROM PetType ORDER BY petType";	  #18
  $result = mysqli_query($cxn,$query)
            or die ("Couldn't execute query.");	       #20

  /* Display text before form */
  echo "<div style='margin-left: .1in'>\n
  <h1 style='text-align: center'>Pet Catalog</h1>\n
  <h2 style='text-align: center'>The following animal 
      friends are waiting for you.</h2>\n
  <p style='text-align: center'>Find just what you want
      and hurry in to the store to pick up your 
      new friend.</p>
  <h3>Which pet are you interested in?</h3>\n";

  /* Create form containing selection list */
  echo "<form action='ShowPets.php' method='POST'>\n";	#33
  echo "<table cellpadding='5' border='1'>";
  $counter=1;	                                         #35
  while($row = mysqli_fetch_assoc($result))	           #36
  {
     extract($row);                                   	#38
     echo "<tr><td valign='top' width='15%' 
                   style='font-weight: bold; 
                   font-size: 1.2em'\n";
     echo "<input type='radio' name='interest' 
                  value='$petType'\n";                	#43
     if( $counter == 1 )                              	#44
     {
         echo "checked='checked'";
     }
     echo ">$petType</td>";                           	#48
     echo "<td>$typeDescription</td></tr>";           	#49
     $counter++;                                      	#50
  }
  echo "</table>";
  echo "<p><input type='submit' value='Select Pet Type'>  
        </form></p>\n";                               	#54
?>
</div>
</body></html>
