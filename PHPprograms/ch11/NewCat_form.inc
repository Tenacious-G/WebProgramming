<?php
  /* Program: NewCat_form.inc
   * Desc:    Displays a form to collect a category name
   *          and description.
   */
?>
<html>
<head>
<title>New Category Form</title>
<style type='text/css'>
 <!--
  .field { padding-top: .5em; }
  label { font-weight: bold; float: left; width: 18%;
          margin-right: 1em; text-align: right; }
 -->
</style> 
</head>
<body style="padding: 1em">
<h3>Either the category name or the category description 
    was left blank. You must enter both.</h3>
<form action=<?php echo "$_SERVER[PHP_SELF]" ?> method="post">
  <div class="field">
    <label for="newCat">Category name: </label>
     <input type="text" name="newCat" id="newCat"
        size="20" maxlength="20"
        value="<?php echo $_POST['newCat'] ?>" /></div>
  <div class="field">
    <label for="newDesc">Category description: </label>
     <input type="text" name="newDesc" id="newDesc"
        value="<?php echo $_POST['newDesc'] ?>" 
        size="70%" maxlength="255" /></div>
  <input type="hidden" name="category" value="new">
   <p><input type="submit" name="newbutton" 
         value="Enter new category">
      <input type="submit" name="newbutton" 
         value="Return to category page">
</form></body></html>
