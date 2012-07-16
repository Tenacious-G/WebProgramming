<?php
/*  Program name: NewName_form.inc
 *  Description:  Script displays a form that asks for
 *                the new pet information.
 */
$labels = array( "petName" => "Pet Name: ",
                 "petDescription" => "Pet Description: ",
                 "price" => "Price",
                 "pix" => "Picture file name: ",
                 "petColor" => "Pet color (optional)");      #10
if(isset($_POST['category']))    #11
{
    $category = $_POST['category'];
}
else
{
    $category = $_POST['newCat'];
}
?>
<html>
<head>
 <title>New Pet Information Form</title>
 <style type='text/css'>
  <!--
   form { margin: 1em; padding: 0; }
   .field { padding-top: .5em; }
   label { font-weight: bold; float: left; width: 18%;
           margin-right: 1em; text-align: right; }
   #submit { margin-top: 1em; )
  -->
 </style> 
</head>

<body>
<form action="AddPet.php" method="post">
<?php
 echo "<h4>Pet Information</h4>";          #37
 echo "<div class='field'>
        <label>Pet Category:</label>
         <b>$category</b></div>\n";
 foreach($labels as $field => $label)
 {
   echo "<div class='field'>
          <label for='$field'>$label</label>
           <input type='text' name='$field' id='$field'
             size='65' maxlength='65' value='".@$$field."' /></div>\n";
 }
?>
<div id="submit">
 <input type='hidden' name='newCat' 
              value='<?php echo $category ?>' /> 
 <input type='submit' value='Submit Pet Name' />
 <input type='submit' name='newbutton' value='Cancel' />
</div> </form></body></html>


