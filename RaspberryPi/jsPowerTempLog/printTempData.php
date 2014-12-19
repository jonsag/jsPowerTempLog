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
  $columns = "ts, ROUND(AVG(temp0), 2) AS temp0, ROUND(AVG(temp1), 2) AS temp1, ROUND(AVG(temp2), 2) AS temp2, ROUND(AVG(temp3), 2) AS temp3, ROUND(AVG(temp4), 2) AS temp4, ROUND(AVG(temp5), 2) AS temp5, ROUND(AVG(temp6), 2) AS temp6, ROUND(AVG(temp7), 2) AS temp7, ROUND(AVG(temp8), 2) AS temp8, ROUND(AVG(temp9), 2) AS temp9, ROUND(AVG(temp10), 2) AS temp10, event";
}
else {
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
Print "<th>Timestamp</th><th>Temp 0</th><th>Temp 1</th><th>Temp 2</th><th>Temp 3</th><th>Temp 4</th><th>Temp 5</th><th>Temp 6</th><th>Temp 7</th><th>Temp 8</th><th>Temp 9</th><th>Temp 10</th><th>Event</th></tr>\n";
while($row = mysql_fetch_array( $query )) 
  { 
    Print "<tr>"; 
    Print "<td>".$row['ts'] . "</td> "; 
    Print "<td>".$row['temp0'] . "</td>";
    Print "<td>".$row['temp1'] . "</td>";
    Print "<td>".$row['temp2'] . "</td>";
    Print "<td>".$row['temp3'] . "</td>";
    Print "<td>".$row['temp4'] . "</td>";
    Print "<td>".$row['temp5'] . "</td>";
    Print "<td>".$row['temp6'] . "</td>";
    Print "<td>".$row['temp7'] . "</td>";
    Print "<td>".$row['temp8'] . "</td>";
    Print "<td>".$row['temp9'] . "</td>";
    Print "<td>".$row['temp10'] . "</td>";
    Print "<td>".$row['event'] . "</td>";
    Print "</tr>\n";
  } 
Print "</table>"; 

// close connection to mysql
mysql_close($db_con);

 ?> 
