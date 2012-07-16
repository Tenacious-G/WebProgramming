<?php
 /* Program: New_member.php
  * Desc:    Displays the new member welcome page. Greets
             member by name and gives a choice to enter
  *          restricted section or go back to main page.
  */
  session_start();	#7
  
  if (@$_SESSION['auth'] != "yes")	#9
  {
     header("Location: login.php");
     exit();
  }
  include("dogs.inc");	#14
  $cxn = mysqli_connect($host,$user,$passwd,$dbname)	#15
         or die ("Couldn't connect to server.");	#16
  $sql = "SELECT firstName,lastName FROM Member 
               WHERE loginName='{$_SESSION['logname']}'";
  $result = mysqli_query($cxn,$sql)
            or die("Couldn't execute query");
  $row = mysqli_fetch_assoc($result);
  extract($row);
  echo "<html>
        <head><title>New Member Welcome</title></head>
        <body>
        <h2 style='margin-top: .7in; text-align: center'>
        Welcome $firstName $lastName</h2>\n";	#27
?>		
<p>Your new Member Account lets you enter the Members 
Only section of our web site. You'll find special 
discounts and bargains, a huge database of animal facts 
and stories, advice from experts, advance notification 
of new pets for sale, a message board where you can talk 
to other Members, and much more.</p>
<p>Your new Member ID and password were emailed to you. 
   Store them carefully for future use.</p>
<div style="text-align: center">
<p style="margin-top: .5in; font-weight: bold">
         Glad you could join us!</p>
<form action="SecretPage.php" method="post">	
   <input type="submit" 
          value="Enter the Members Only Section">
</form>
<form action="PetShopFrontMembers.php" method="post">	
   <input type="submit" value="Go to Pet Store Main Page">
</form>
</div>
</body></html>
