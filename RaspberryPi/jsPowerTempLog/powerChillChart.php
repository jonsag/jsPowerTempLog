<html>
  <head>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    function drawChart() {
    var data = google.visualization.arrayToDataTable([
    ['Time', 'Power kW', 'Temp', 'Chill factor'],
<?php
include ('includes/config.php');
include ('includes/functions.php');
include ('includes/getSql.php');

$sqlsRun = [];

// connect to mysql
if (!$db_con) {
  die('Could not connect: ' . mysql_error());
}
// select database
mysql_select_db($db_name) or die(mysql_error());

// find outdoor temp in database
$sql = "SELECT id FROM 1wireDevices WHERE place='ute'";
$result = mysql_query($sql);
$sqlsRun[] = $sql;
if ($result) {
  $id = (mysql_result($result,0));
}
else {
  die('Invalid query: ' . mysql_error());
}

// construct sql
if( isset($_GET['groupBy']) ) {
  $columns = "ts, ROUND(AVG(temp0), 2) AS temp0 , ROUND(AVG(temp1), 2) AS temp1, ROUND(AVG(temp2), 2) AS temp2, ROUND(AVG(temp3), 2) AS temp3, ROUND(AVG(temp4), 2) AS temp4, ROUND(AVG(temp5), 2) AS temp5, ROUND(AVG(temp6), 2) AS temp6, ROUND(AVG(temp7), 2) AS temp7, ROUND(AVG(temp8), 2) AS temp8, ROUND(AVG(temp9), 2) AS temp9, ROUND(AVG(temp10), 2) AS temp10";
}
else {
  $columns = "ts, temp0, temp1, temp2, temp3, temp4, temp5, temp6, temp7, temp8, temp9, temp10";
}
$answer = getSql($_GET, $columns);
$sql = $answer[0];
$selection = $answer[1];

// do the query
$result1 = mysql_query($sql);
$sqlsRun[] = $sql;

$valuesDisplayed = 0;
    
// read result
while($row = mysql_fetch_array($result1)) {
  $timeStamp = $row['ts'];
  $outdoorTemp = $row[$id];
  // get windspeed
  if ( isset($_GET['groupBy']) ) {
    if ( $_GET['groupBy'] == "hour" ) {
      $time = substr($row['ts'], 0, -6);
      $sql = "SELECT AVG(averageWindSpeed) FROM weatherLog WHERE `ts` LIKE '{$time}%' GROUP BY YEAR(ts), MONTH(ts), DAY(ts), HOUR(ts)";
    }
    else if ( $_GET['groupBy'] == "day" ){
      $time = substr($row['ts'], 0, -9);
      $sql = "SELECT AVG(averageWindSpeed) FROM weatherLog WHERE `ts` LIKE '{$time}%' GROUP BY YEAR(ts), MONTH(ts), DAY(ts)";
    }
    else if ( $_GET['groupBy'] == "week" ){
      $time = substr($row['ts'], 0, -9);
      $sql = "SELECT AVG(averageWindSpeed) FROM weatherLog WHERE `ts` LIKE '{$time}%' GROUP BY YEAR(ts), WEEK(ts)";
    }
    else if ( $_GET['groupBy'] == "month" ){
      $time = substr($row['ts'], 0, -12);
      $sql = "SELECT AVG(averageWindSpeed) FROM weatherLog WHERE `ts` LIKE '{$time}%' GROUP BY YEAR(ts), MONTH(ts)";
    }
    else if ( $_GET['groupBy'] == "year" ){
      $time = substr($row['ts'], 0, -15);
      $sql = "SELECT AVG(averageWindSpeed) FROM weatherLog WHERE `ts` LIKE '{$time}%' GROUP BY YEAR(ts)";
    }
  }
  else {
    $time = substr($row['ts'], 0, -3);
    $sql = "SELECT averageWindSpeed FROM weatherLog WHERE `ts` LIKE '{$time}%'";
  }
  $result2 = mysql_query($sql);
  $sqlsRun[] = $sql;
  // get currents
  if ( isset($_GET['groupBy']) ) {
    if ( $_GET['groupBy'] == "hour" ) {
      $time = substr($row['ts'], 0, -6);
      $sql = "SELECT AVG(currentAverageR1), AVG(currentAverageS2), AVG(currentAverageT3) FROM powerLog WHERE `ts` LIKE '{$time}%' GROUP BY YEAR(ts), MONTH(ts), DAY(ts), HOUR(ts)";
    }
    else if ( $_GET['groupBy'] == "day" ){
      $time = substr($row['ts'], 0, -9);
      $sql = "SELECT AVG(currentAverageR1), AVG(currentAverageS2), AVG(currentAverageT3) FROM powerLog WHERE `ts` LIKE '{$time}%' GROUP BY YEAR(ts), MONTH(ts), DAY(ts)";
    }
    else if ( $_GET['groupBy'] == "week" ){
      $time = substr($row['ts'], 0, -9);
      $sql = "SELECT AVG(currentAverageR1), AVG(currentAverageS2), AVG(currentAverageT3) FROM powerLog WHERE `ts` LIKE '{$time}%' GROUP BY YEAR(ts), WEEK(ts)";
    }
    else if ( $_GET['groupBy'] == "month" ){
      $time = substr($row['ts'], 0, -12);
      $sql = "SELECT AVG(currentAverageR1), AVG(currentAverageS2), AVG(currentAverageT3) FROM powerLog WHERE `ts` LIKE '{$time}%' GROUP BY YEAR(ts), MONTH(ts)";
    }
    else if ( $_GET['groupBy'] == "year" ){
      $time = substr($row['ts'], 0, -15);
      $sql = "SELECT AVG(currentAverageR1), AVG(currentAverageS2), AVG(currentAverageT3) FROM powerLog WHERE `ts` LIKE '{$time}%' GROUP BY YEAR(ts)";
    }
  }
  else {
    $time = substr($row['ts'], 0, -3);
    $sql = "SELECT currentAverageR1, currentAverageS2, currentAverageT3 FROM powerLog WHERE `ts` LIKE '{$time}%'";
  }
  $result3 = mysql_query($sql);
  $sqlsRun[] = $sql;
  if ($result2 && $result3) {
    $averageWindSpeed = (mysql_result($result2, 0));
    $currentR1 = (mysql_result($result3, 0));
    $currentS2 = (mysql_result($result3, 1));
    $currentT3 = (mysql_result($result3, 2));
    $power = ( $currentR1 + $currentS2 + $currentT3 ) * 230 * sqrt(3) / 1000;
    if (!empty($averageWindSpeed)) {
      $chillFactor = round((13.12 + 0.6215 * $outdoorTemp - 13.956 * pow($averageWindSpeed, 0.16) + 0.48669 * $outdoorTemp * pow($averageWindSpeed, 0.16)), 2, PHP_ROUND_HALF_UP);
      echo "['{$timeStamp}', {$power},  {$outdoorTemp}, {$chillFactor},]";
      echo ",\n";
      $valuesDisplayed++;
    }
  }
  else {
    die('Invalid query: ' . mysql_error());
  }
}

// close connection to mysql
mysql_close($db_con);
?>
]);
var options = {
<?php
echo "title: 'Power/Temp/Chill - " . $selection;
if(isset($values)) {
  echo ", averaging to " . $valuesDisplayed . " values, " . $measuresPerPoint . " measurements per point";
}
else if ($_GET['groupBy']) {
  echo ", grouped by " . $_GET['groupBy'];
}
else {
  echo ", showing all " . $valuesDisplayed . " values";
}
echo "',";
echo "\nwidth: " . $chartWidth . ",";
echo "\nheight: " . $chartHeight . ",";
echo "\nlineWidth: " . $chartLineWidth . ",";
?>
curveType: 'function',
colors: ['red', 'green', 'blue']
};
var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
chart.draw(data, options);
}
</script>
  </head>
<body>
<div id="chart_div"></div>
<?php
      // print the sqls run
      if ( $_GET['view_sql'] ) {
	foreach ($sqlsRun as &$sql) {
	  echo $sql;
	  lf();
	}
      }
?>
  </body>
</html>
