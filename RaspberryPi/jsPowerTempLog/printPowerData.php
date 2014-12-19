<?php 
include ('includes/functions.php');
include ('includes/config.php');
include ('includes/getSql.php');

// connect to mysql
if (!$db_con) {
  die('Could not connect: ' . mysql_error());
}
// select database
mysql_select_db($db_name) or die(mysql_error());

// construct sql
if(isset($_GET['groupBy'])) {
  $columns = "ts, ROUND(AVG(currentR1), 2) AS currentR1, ROUND(AVG(currentS2), 2) AS currentS2, ROUND(AVG(currentT3), 2) AS currentT3, ROUND(AVG(currentAverageR1), 2) AS currentAverageR1, ROUND(AVG(currentAverageS2), 2) AS currentAverageS2, ROUND(AVG(currentAverageT3), 2) AS currentAverageT3, ROUND(AVG(temp), 2) AS temp, ROUND(AVG(pulses), 2) AS pulses, event";
}
else {
  //$columns = "ts, currentR1, currentS2, currentT3, currentAverageR1, currentAverageS2, currentAverageT3, temp0. pulses, event";
  $columns = "*";
}
$answer = getSql($_GET, $columns);
$sql = $answer[0];
$selection = $answer[1];

// run sql
$query = mysql_query($sql);

Print "<table border cellpadding=3>";
Print "<tr><td colspan=18>" . $table . " " . $selection;
lf();
Print "sql=" . $sql . "<td></tr>\n";
Print "<tr>";
Print "<th>Timestamp</th><th>Current R1</th><th>Current S2</th><th>Current T3</th><th>Avg Current R1</th><th>Avg Current S2</th><th>Avg Current T3</th><th>Temp 0</th><th>Pulses</th><th>Event</th></tr>\n";
while($row = mysql_fetch_array( $query )) 
  { 
    Print "<tr>"; 
    Print "<td>".$row['ts'] . "</td> "; 
    Print "<td>".$row['currentR1'] . "</td>";
    Print "<td>".$row['currentS2'] . "</td>";
    Print "<td>".$row['currentT3'] . "</td>";
    Print "<td>".$row['currentAverageR1'] . "</td>";
    Print "<td>".$row['currentAverageS2'] . "</td>";
    Print "<td>".$row['currentAverageT3'] . "</td>";
    Print "<td>".$row['temp'] . "</td>";
    Print "<td>".$row['pulses'] . "</td>";
    Print "<td>".$row['event'] . "</td>";
    Print "</tr>\n";
  } 
Print "</table>"; 

// close connection to mysql
mysql_close($db_con);

?> 
