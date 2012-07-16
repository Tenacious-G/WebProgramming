<?php
/* Program: mysql_test.php
 * Desc:    Connects to MySQL Server and 
 *          outputs settings.
 */
 echo "<html>
       <head><title>Test MySQL</title></head>
       <body>";
 $host = "hostname";
 $user = "mysqlaccount";
 $password = "mysqlpassword";

 $cxn = mysqli_connect($host,$user,$password);
 $sql="SHOW DATABASES";
 $result = mysqli_query($cxn,$sql);
 if($result == false)
 {
    echo "<h4>Error: ".mysqli_error($cxn)."</h4>";
 }
 else
 {
   if(mysqli_num_rows($result) < 1)
   {
      echo "<p>No current databases</p>";
   }
   else
   {
      echo "<ol>";
      while($row = mysqli_fetch_row($result))
      {
        echo "<li>$row[0]</li>";
      }
      echo "</ol>";
   }
 }
?>
</body></html>
