<?php
/*  Program name: dateSelect.php
 *  Description:  Program displays a selection list that
 *                customers can use to select a date.
 */
$monthName = array(1 => "January", "February", "March",
                      . "April", "May", "June", "July",
                      . "August", "September", "October",
                      . "November", "December");
$today = time();                   //stores today's date
$f_today = date("M-d-Y",$today);   //formats today's date

echo "<html>
     <head><title>Select a date</title></head>
     <body>
     <div style = 'text-align: center'>\n";

/* display today's date */
echo "<h3>Today is $f_today</h3><hr />\n";

/* create form containing date selection list */
echo "<form action='processform.php' method='POST'>\n";

/* build selection list for the month */
$todayMO = date("n",$today);  //get the month from $today
echo "<select name='dateMO'>\n";
for($n=1;$n<=12;$n++) 
{
  echo "<option value=$n\n";
  if($todayMO == $n)
  {
    echo " selected='selected'";
  }
  echo " > $monthName[$n]\n";
}
echo "</select>\n";

/* build selection list for the day */
$todayDay= date("d",$today);    //get the day from $today
echo "<select name='dateDay'>\n";
for($n=1;$n<=31;$n++)  
{
  echo " <option value=$n";
  if($todayDay == $n ) 
  {
    echo " selected='selected'";
  }
  echo " > $n\n";
}
echo "</select>\n";

/* build selection list for the year */
$startYr = date("Y", $today);  //get the year from $today
echo "<select name='dateYr'>\n";
for($n=$startYr;$n<=$startYr+3;$n++)
{
  echo " <option value=$n";
  if($startYr == $n )
  {
    echo " selected='selected'";
  }
  echo " > $n\n";
}
echo "</select>\n";
?>
</form></body></html>
