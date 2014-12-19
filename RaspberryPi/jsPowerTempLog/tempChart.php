<html>
  <head>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    function drawChart() {
    var data = google.visualization.arrayToDataTable([
<?php
include ('includes/config.php');
include ('includes/functions.php');
include ('includes/getSql.php');

// initiate array for sqls
$sqlsRun = [];

// connect to mysql
if (!$db_con) {
  die('Could not connect: ' . mysql_error());
}
// select database
mysql_select_db($db_name) or die(mysql_error());

// find temps in database
$placeSql = "SELECT place FROM 1wireDevices WHERE deviceType='temp'";
$temps = mysql_query($placeSql);

// print headers
echo "['Time', ";
// print header for each temp
while($row = mysql_fetch_array($temps)) {
  echo "'" . $row['place'] . "',";
}
echo "],\n";

// should we limit number of values displayed
if( isset($_GET['values']) && !isset($_GET['groupBy']) ) {
  $values = $_GET['values'];
}

// construct sql
if( isset($_GET['groupBy']) ) {
  $columns = "ts, ROUND(AVG(temp0), 2) AS temp0 , ROUND(AVG(temp1), 2) AS temp1, ROUND(AVG(temp2), 2) AS temp2, ROUND(AVG(temp3), 2) AS temp3, ROUND(AVG(temp4), 2) AS temp4, ROUND(AVG(temp5), 2) AS temp5, ROUND(AVG(temp6), 2) AS temp6, ROUND(AVG(temp7), 2) AS temp7, ROUND(AVG(temp8), 2) AS temp8, ROUND(AVG(temp9), 2) AS temp9, ROUND(AVG(temp10), 2) AS temp10";
}
else {
  $columns = "ts, temp0, temp1";
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
    $newSql = "SELECT ts, AVG(temp0) AS temp0, AVG(temp1) AS temp1 FROM ( " . $newSql . " ) AS result";
    // ask database
    $result = mysql_query($newSql);
    $sqlsRun[] = $newSql;
    // handle the result
    while($row = mysql_fetch_array($result)) {
      $valuesDisplayed++;
      // print values
      echo "['{$row['ts']}', {$row['temp0']}, {$row['temp1']}]";
      echo ",\n";
    }
  }
}
// if we don't limit the number of points
else {
  // read result
  while($row = mysql_fetch_array($result)) {
    $valuesDisplayed++;
    echo "['{$row['ts']}', {$row['temp0']}, {$row['temp1']}]";
    echo ",\n";
  }
}

// close connection to mysql
mysql_close($db_con);
?>
]);
var options = {
<?php
// print how we want to output this
echo "title: '" . $_GET['table'] . " - Temps " . $selection;
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
