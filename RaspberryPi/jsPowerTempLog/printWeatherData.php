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
  $columns = "ts, windDirection, windDirectionValue, averageWindDirectionValue, ROUND(AVG(windSpeed), 2) AS windSpeed, ROUND(AVG(averageWindSpeed), 2) AS averageWindSpeed, ROUND(SUM(rainSinceLast), 2) AS rainSinceLast, ROUND(AVG(temp), 2) AS temp, event";
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
Print "<th>Timestamp</th><th>Wind dir</th><th>Wind dir value</th><th>Avg wind dir value</th><th>Wind speed</th><th>Avg wind speed</th><th>Rain since last</th><th>Temp</th><th>Event</th></tr>\n";
while($row = mysql_fetch_array( $query )) 
  { 
    Print "<tr>"; 
    Print "<td>".$row['ts'] . "</td> "; 
    Print "<td>".$row['windDirection'] . "</td>";
    Print "<td>".$row['windDirectionValue'] . "</td>";
    Print "<td>".$row['averageWindDirectionValue'] . "</td>";
    Print "<td>".$row['windSpeed'] . "</td>";
    Print "<td>".$row['averageWindSpeed'] . "</td>";
    Print "<td>".$row['rainSinceLast'] . "</td>";
    Print "<td>".$row['temp'] . "</td>";
    Print "<td>".$row['event'] . "</td>";
    Print "</tr>\n";
  } 
Print "</table>"; 

// close connection to mysql
mysql_close($db_con);

?> 
