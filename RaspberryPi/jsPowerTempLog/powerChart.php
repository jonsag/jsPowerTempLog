<html>
  <head>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    function drawChart() {
    var data = google.visualization.arrayToDataTable([
    ['Time', 'Power kW'],
<?php
include ('includes/functions.php');
include ('includes/config.php');
include ('includes/getSql.php');

// initiate array for sqls
$sqlsRun = [];

// connect to mysql
if (!$db_con) {
  die('Could not connect: ' . mysql_error());
}
// select database
mysql_select_db($db_name) or die(mysql_error());

if(isset($_GET['values']) && !isset($_GET['groupBy'])) {
  $values = $_GET['values'];
}

if(isset($_GET['groupBy'])) {
  $columns = "ts, AVG(currentAverageR1) AS currentAverageR1, AVG(currentAverageS2) AS currentAverageS2, AVG(currentAverageT3) AS currentAverageT3";
}
else {
  $columns = "ts, currentAverageR1, currentAverageS2, currentAverageT3";
}
$answer = getSql($_GET, $columns);
$sql = $answer[0];
$selection = $answer[1];

// do the query
$result = mysql_query($sql);
$sqlsRun[] = $sql;

$valuesDisplayed = 0;

if (isset($values)) {
  // get number of rows from query
  $noRows = mysql_num_rows($result);
  // calculate how many measures we will have per point in output
  if ( round($noRows / $values, 0, PHP_ROUND_HALF_UP) < 2 ) {
    $measuresPerPoint = 2;
  }
  else {
    $measuresPerPoint = round($noRows / $values, 0, PHP_ROUND_HALF_UP);
  }
  // get answer in groups
  for ($offset = 0; ( $offset + $measuresPerPoint ) <= $noRows; $offset += $measuresPerPoint ) {
    // construct new sql
    $newSql = $sql . " LIMIT " . $offset . ", " . $measuresPerPoint;
    $newSql = "SELECT ts, AVG(currentAverageR1) AS currentAverageR1, AVG(currentAverageS2) AS currentAverageS2, AVG(currentAverageT3) AS currentAverageT3 FROM ( " . $newSql . " ) AS result";
    // ask database
    $result = mysql_query($newSql);
    $sqlsRun[] = $newSql;
    // handle the result
    while($row = mysql_fetch_array($result)) {
      $power = ( $row['currentAverageR1'] + $row['currentAverageS2'] + $row['currentAverageT3'] ) * 230 * sqrt(3) / 1000;
      $valuesDisplayed++;
      // print values
      echo "['{$row['ts']}', {$power}]";
      echo ",\n";
    }
  }
}
// if we don't limit the number of points
else {
  // read result
  while($row = mysql_fetch_array($result)) {
    echo "['{$row['ts']}', ({$row['currentAverageR1']} + {$row['currentAverageS2']} + {$row['currentAverageT3']}) * 230 * 1.732 / 1000]";
    echo ",\n";
    $valuesDisplayed++;
  }
}

// close connection to mysql
mysql_close($db_con);
?>
]);
var options = {
<?php
echo "title: 'Power " . $selection;
if(isset($values)) {
  echo ", averaging to " . $valuesDisplayed . " values, " . $measuresPerPoint . " measurements per point";
}
else if (isset($_GET['groupBy'])) {
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
colors: ['red','blue']
};
var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
chart.draw(data, options);
}
    </script>
  </head>
<body>
  <div id="chart_div"></div>
<?php
      // print sqls run
      if ( $_GET['view_sql'] ) {
	foreach ($sqlsRun as &$sql) {
	  echo $sql;
	  lf();
	}
      }
?>
</body>
</html>
