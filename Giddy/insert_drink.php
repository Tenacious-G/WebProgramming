<html>
<head>
    <title>The Giddy Goat Coffee House - Drink Add Results</title>
</head>
<body>
<h1>The Giddy Goat Coffee House - Drink Add Results</h1>

<?php
$host = "localhost";
$user = "coffeeApp";
$pass = "godfirst1";
$database = "coffeeDescrips";
$table = "coffees";

$name=$_POST['name'];
$descrip=$_POST['descrip'];

if(!$name || !$descrip) {
    echo "You have not entered information in all fields, Go back and try again.";
    exit;
}

if(!get_magic_quotes_gpc()){
    $name = addslashes($name);
    $descrip = addslashes($descrip);
}

//get connection to database
@ $db = new mysqli($host, $user, $pass, $database);

$query = "insert into coffees values('".$name."', '".$descrip."')";

$result = $db->query($query);

if($result){
    echo $db->affected_rows." drinks inserted into database";
} else {
    echo "Fail, an error has occurred, some one messed up the database, no items added";
}

$db->close();

?>
</body>
</html>