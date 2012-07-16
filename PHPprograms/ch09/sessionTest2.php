<?php
  session_start();
?>
<html>
<head><title>Testing Sessions page 2</title></head>
<body>
<?php
  echo "session_var = {$_SESSION['session_var']}<br>\n";
  echo "form_var = {$_POST['form_var']}<br>\n";
?>
</body></html>
