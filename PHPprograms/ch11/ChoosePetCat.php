<?php
  /* Program: ChoosePetCat.php
   * Desc:    Allows users to select a pet type. All the 
   *          existing pet types from the PetType table 
   *          are displayed with radio buttons. A section 
   *          to enter a new pet type is provided.
   */
?>
<html>
<head>
 <title>Pet Categories</title>
 <style type='text/css'>
 <!--
  #new { border: thin solid; margin: 1em 0; padding: 1em; }
  #radio { padding-bottom: 1em; }
  .field { padding-top: .5em; }
  label { font-weight: bold ; }
  #new label { width: 20%; float: left; 
               margin-right: 1em; text-align: right; }
  input { margin-left: 1em; }
  #new input { margin-left: 0 }
 -->
 </style> 
</head>

<body style='margin: 1em'>
 <h3>Select a category for the pet you're adding.</h3>
 <p>If you are adding a pet in a category that is not 
    listed, choose <b>New Category</b> and type the 
    name and description of the category. Press  
    <b>Submit Category</b> when you have finished  
    selecting an existing category or typing a new 
    category.</p>
<?php
 include("misc.inc");
 $cxn = mysqli_connect($host,$user,$passwd,$dbname)
        or die ("couldn't connect to server");
 $query="SELECT petType FROM PetType 
                        ORDER BY petType";	#38
 $result = mysqli_query($cxn,$query)
           or die ("Couldn't execute query.");

 /* Display form for selecting pet type */
 echo "<form action='ChoosePetName.php' 
                method='post'>\n";
 $counter=0;
 while($row = mysqli_fetch_assoc($result))	#46
 {
    extract($row);
    echo "<label><input type='radio' name='category'  
                        value='$petType'";
           if($counter == 0)	#51
           { 
              echo " checked='checked'";
           }
    echo " />$petType </label>\n";	#55
    $counter++;	#56
 }
?>
<div id="new">
 <div id="radio">
  <label for="category">New Category</label>
   <input type="radio" name="category" id="category" value="new" />
 </div>
 <div class="field">
  <label for="newCat">Category name: </label>
   <input type="text" name="newCat" size="20" 
          id="newCat" maxlength="20" /></div>
 <div class="field">
  <label for="newDesc">Category description: </label>
   <input type="text" name="newDesc" id="newDesc" 
          size="70%" maxlength="255" /></div>
</div>
<input type='submit' value='Submit Category' />
</form></body></html>
