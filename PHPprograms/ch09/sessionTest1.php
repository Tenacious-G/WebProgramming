<?php
  session_start();
?>
<html>
<head><title>Testing Sessions page 1</title></head>
<body>
<?php
  $_SESSION['session_var'] = "testing";
  echo "This is a test of the sessions feature.
       <form action='sessionTest2.php' method='POST'>
       <input type='hidden' name='form_var'
              value='testing'>
       <input type='submit' value='go to next page'>
       </form>";
?>
</body></html>
