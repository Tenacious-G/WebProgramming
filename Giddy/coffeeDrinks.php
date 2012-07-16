<?php

error_reporting(E_ALL ^ E_NOTICE);

/*$coffee_array = array(
    array("name" => "Plain Latte",
          "descrip" => "Our Rock-a-Billy Goat Blend house roasted espresso blended with steamed milk."),
    array("name" => "Mocha",
          "descrip" => "Our house blend espresso blended with milk chocolate and steamed milk."),
    array("name" => "White Mocha",
          "descrip" => "Our house blend espresso blended with Ghiradelli white chocolate and steamed milk."),
    array("name" => "Breve",
          "descrip" => "Our house espresso blended with steamed half and half.")
);

$coffees_json = json_encode($coffee_array);
*/

$host = "localhost";
$user = "coffeeApp";
$pass = "godfirst1";
$database = "coffeeDescrips";
$table = "coffees";

//get connection to database
$linkID = mysql_connect($host, $user, $pass) or die("Could not connect to host.");
mysql_select_db($database, $linkID) or die("Could not find database.");

//dumps db to xml file, to be used on update
passthru("mysqldump --xml -u $user -p $pass [$table] > coffeeDescrips.xml");

?>

<pre>
    <?php var_dump($coffees_json); ?>
</pre>

