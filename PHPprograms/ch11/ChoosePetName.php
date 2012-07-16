<?php
 /* Program: ChoosePetName.php
  * Desc:    Allows the user to enter the information for
  *          the new pet. IF the category is new, it's entered
  *          into the database.  
  */
if (@$_POST['newbutton'] == "Return to category page"
    or @$_POST['newbutton'] == "Cancel")	#8
{
   header("Location: ChoosePetCat.php");
}
include("misc.inc");
include("functions.inc");                    #13
$cxn = mysqli_connect($host,$user,$passwd,$dbname)
       or die ("Couldn't connect to server");
/* If new was selected for pet category, check if 
   category name and description were filled in. */
if(trim($_POST['category']) == "new")	#18
{
  $_POST['category']=trim($_POST['newCat']);
  if(empty($_POST['newCat']) 
          or empty($_POST['newDesc']) )	#22
  {
     include("NewCat_form.inc");	#24
     exit();	#25
  }
  else	#27
  {
     addNewType($_POST['newCat'],$_POST['newDesc'],$cxn);
  }
}	#31
include("NewName_form.inc");     #32
?>
