<?php
 /* Program: ShowPets.php
  * Desc:    Displays all the pets in a category. 
  *          Category is passed in a variable from a 
  *          form. The information for each pet is  
  *          displayed on a single line, unless the pet  
  *          comes in more than one color. If the pet 
  *          comes in colors, a single line is displayed  
  *          without a picture, and a line for each color,
  *          with pictures, is displayed following the 
  *          single line. Small pictures are displayed, 
  *          which are links to larger pictures.
  */
?>
<html>
<head><title>Pet Catalog</title></head>
<body>
<?php
 include("misc.inc");
 
 $cxn = mysqli_connect($host,$user,$passwd,$dbname)
        or die ("couldn't connect to server");

 /* Select pets of the given type */
 $query = "SELECT * FROM Pet 
             WHERE petType=\"{$_POST['interest']}\""; 	#26
 $result = mysqli_query($cxn,$query)
           or die ("Couldn't execute query.");

 /* Display results in a table */
 echo "<table cellspacing='10' border='0' cellpadding='0' 
              width='100%'>";
 echo "<tr><td colspan='5' style='text-align: right'>
             Click on any picture to see a larger
                 version. <hr /></td></tr>\n";
 while($row = mysqli_fetch_assoc($result))            #36
 {
   $f_price = number_format($row['price'],2);

   /* check whether pet comes in colors */
   $query = "SELECT * FROM Color 
                  WHERE petName='{$row['petName']}'"; #42
   $result2 = mysqli_query($cxn,$query) 
              or die(mysqli_error($cxn));             #44
   $ncolors = mysqli_num_rows($result2);              #45
 
   /* display row for each pet */
   echo "<tr>\n";
   echo " <td>{$row['petID']}</td>\n";
   echo " <td style='font-weight: bold; 
            font-size: 1.1em'>{$row['petName']}</td>\n";
   echo " <td>{$row['petDescription']}</td>\n";
   /* display picture if pet does not come in colors */
   if( $ncolors <= 1 )                                #54
   {
      echo "<td><a href='../images/{$row['pix']}'
                          border='0'>
                  <img src='../images/{$row['pix']}' 
                   border='0' width='100' height='80' />
                </a></td>\n";
   }
   echo "<td align='center'>\$$f_price</td>\n
         </tr>\n";
   /* display row for each color  */
   if($ncolors > 1 )                                  #65
   {
      while($row2 = mysqli_fetch_assoc($result2))     #67
      {
        echo "<tr><td colspan=2>&nbsp;</td>
                  <td>{$row2['petColor']}</td>
                  <td><a href='../images/{$row2['pix']}'
                            border='0'>
                      <img src='../images/{$row2['pix']}' 
                         border='0' width='100' 
                         height='80' /></a></td>\n";
      }
   }
   echo "<tr><td colspan='5'><hr /></td></tr>\n";
 }
 echo "</table>\n";
 echo "<div style='text-align: center'>
       <a href='PetCatalog.php'>
             <h3>See more pets</h3></a></div>";
?>
</body></html>
