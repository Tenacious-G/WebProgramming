<?php
 /* Program: AddPet.php
  * Desc:    Adds new pet to the database. A confirmation
  *          page is sent to the user.
  */
if (@$_POST['newbutton'] == "Cancel")                  #6
{
   header("Location: ChoosePetCat.php");
}
include("misc.inc");                                  #10
$cxn = mysqli_connect($host,$user,$passwd,$dbname)
       or die ("Couldn't connect to server");
foreach($_POST as $field => $value)                   #13
{
  if(empty($value))                                   #15
  {
    if($field == "petName" or $field == "petDescription")
    {
        $blank_array[] = $field;
    }
  }
  else                                                #22
  {
     if($field != "category")
     {
        if(!preg_match("/^[A-Za-z0-9., _-]+$/",$value))
        {
          $error_array[] = $field;
        }
        if($field == "newCat")
        {
          $clean_data['petType']=trim(strip_tags($value));
        }
        else
        {
          $clean_data[$field] = trim(strip_tags($value));
        }
     }
  }
}
if(@sizeof($blank_array)>0 or @sizeof($error_array)>0) #41
{
   if(@sizeof($blank_array) > 0)
   {
      echo "<p><b>You must enter both pet name and
                  pet description</b></p>\n";
   }
   if(@sizeof($error_array) > 0)                       #48
   {
      echo "<p><b>The following fields have incorrect 
            information. Only letters, numbers, spaces,
            underscores, and hyphens are allowed:</b><br />\n";
      foreach($error_array as $value)
      {
         echo "&nbsp;&nbsp;$value<br />\n";
      }
   }
   extract($clean_data);
   include("NewName_form.inc");
   exit();
}
foreach($clean_data as $field => $value)               #62
{ 
   if(!empty($value) and $field != "petColor")         #64
   {
      $fields_form[$field] = 
         ucfirst(strtolower(strip_tags(trim($value))));
      $fields_form[$field] = 
           mysqli_real_escape_string($cxn,
                $fields_form[$field]);
      if($field == "price")
      {
         $fields_form[$field] = 
               (float) $fields_form[$field];
      }
   }
   if(!empty($_POST['petColor']))                      #77
   {
     $petColor = strip_tags(trim($_POST['petColor']));
     $petColor = ucfirst(strtolower($petColor));
     $petColor = 
            mysqli_real_escape_string($cxn,$petColor);
   }
}
?>
<html>
<head><title>Add Pet</title></head>
<body>
<?php
 $field_array = array_keys($fields_form);              #90
 $fields=implode(",",$field_array);
 $query = "INSERT INTO Pet ($fields) VALUES (";
 foreach($fields_form as $field => $value)             #93
 {
   if($field == "price")
   {
      $query .= "$value ,";
   }
   else
   {
      $query .= "'$value' ,";
   }
 }
 $query .= ") ";
 $query = preg_replace("/,\)/",")",$query);
 $result = mysqli_query($cxn,$query)
      or die ("Couldn't execute query");
 $petID = mysqli_insert_id($cxn);                    #108
 $query = "SELECT * from Pet WHERE petID='$petID'";  #109
 $result = mysqli_query($cxn,$query)
       or die ("Couldn't execute query.");
 $row = mysqli_fetch_assoc($result);
 extract($row);                                      #117
 echo "The following pet has been added to the 
       Pet Catalog:<br /> 
       <ul>
        <li>Category: $petType</li>
        <li>Pet Name: $petName</li>
        <li>Pet Description: $petDescription</li>
        <li>Price: \$$price</li>
        <li>Picture file: $pix</li>\n";
 if (@$petColor != "")	                            #122
 {
   $query = "SELECT petName FROM Color 
                    WHERE petName='$petName' 
                    AND petColor='$petColor'";
   $result = mysqli_query($cxn,$query)
             or die("Couldn't execute query.");
   $num = mysqli_num_rows($result);
   if ($num < 1)
   {
     $query = "INSERT INTO Color (petName,petColor,pix)
               VALUES ('$petName','$petColor','$pix')";
     $result = mysqli_query($cxn,$query)
               or die("Couldn't execute query.".mysqli_error($cxn));
     echo "<li>Color: $petColor</li>\n";
   }
 }                                                   #138
 echo "</ul>\n";
 echo "<a href='ChoosePetCat.php'>Add Another Pet</a>\n";
?>
</body></html>
