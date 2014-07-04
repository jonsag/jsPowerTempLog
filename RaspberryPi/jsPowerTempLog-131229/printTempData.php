 <?php 
include ("config.php");
include ('functions.php');

include ('getTempSql.php');

$selected = false;

///// catch attributes
if (isset($_GET['time'])) {
  $timeSelection = $_GET['time'];
}

///// connect to database
if (!$db_con) {
  die('Could not connect: ' . mysql_error());
}

///// choose database
mysql_select_db($db_name) or die(mysql_error());

$query = mysql_query($sql);

Print "<table border cellpadding=3>";
Print "<tr><td colspan=18>tempLog " . $selection;
lf();
Print "sql=" . $sql . "<td></tr>\n";
Print "<tr>";
Print "<th>Timestamp</th><th>Temp 00</th><th>Temp 01</th><th>Temp 02</th><th>Temp 03</th><th>Temp 04</th><th>Temp 05</th><th>Temp 06</th><th>Temp 07</th><th>Temp 08</th><th>Temp 09</th><th>Temp 10</th><th>Event</th></tr>\n";
while($row = mysql_fetch_array( $query )) 
  { 
    Print "<tr>"; 
    Print "<td>".$row['ts'] . "</td> "; 
    Print "<td>".$row['temp00'] . "</td>";
    Print "<td>".$row['temp01'] . "</td>";
    Print "<td>".$row['temp02'] . "</td>";
    Print "<td>".$row['temp03'] . "</td>";
    Print "<td>".$row['temp04'] . "</td>";
    Print "<td>".$row['temp05'] . "</td>";
    Print "<td>".$row['temp06'] . "</td>";
    Print "<td>".$row['temp07'] . "</td>";
    Print "<td>".$row['temp08'] . "</td>";
    Print "<td>".$row['temp09'] . "</td>";
    Print "<td>".$row['temp10'] . "</td>";
    Print "<td>".$row['event'] . "</td>";
    Print "</tr>\n";
  } 
Print "</table>"; 
 ?> 
