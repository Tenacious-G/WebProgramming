<?php
/**
 * Created by JetBrains PhpStorm.
 * User: i7pk13
 * Date: 6/9/12
 * Time: 6:02 PM
 * To change this template use File | Settings | File Templates.
 */

//function to parse out version date as substring of pureXML string
function get_string_between($string, $start, $end){
    $string = " ".$string;
    $ini = strpos($string,$start);
    if ($ini == 0) return "";
    $ini += strlen($start);
    $len = strpos($string,$end,$ini) -$ini;
    return substr($string,$ini,$len);
}

//get variable from GET method
$passedVersionDate = trim($_GET["versionDate"]);
if (file_exists('coffeeDescrips.xml')) {
    //$xml = simplexml_load_file('coffeeDescrips.xml');
    $pureXML = file_get_contents('coffeeDescrips.xml');
    //check file for update version date
    //if update version date is same return "nil"
    $versionDate = trim(get_string_between($pureXML, "- Generation Time: ", "- Server version:"));

    if ($passedVersionDate === $versionDate) {
        echo "nil";
    } else {
        header("Content-Type: text/xml");
        echo $pureXML;
    }
    //var_dump($xml);
} else {
    echo "nil";
}

?>