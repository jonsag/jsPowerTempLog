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

     if(isset($_GET['values']) && !isset($_GET['groupBy'])) {
       $values = $_GET['values'];
     }


     if(isset($_GET['groupBy'])) {
       $columns = "ts, AVG(temp0) AS temp0 , AVG(temp1) AS temp1";
       if ($_GET['groupBy'] == "hour") {
	 $groupBy = " GROUP BY HOUR(ts)";
       }
       else if ($_GET['groupBy'] == "day") {
	 $groupBy = " GROUP BY DAY(ts)";
       }
       else if ($_GET['groupBy'] == "week") {
	 $groupBy = " GROUP BY WEEK(ts)";
       }
       else if ($_GET['groupBy'] == "month") {
	 $groupBy = " GROUP BY MONTH(ts)";
       }
       else if ($_GET['groupBy'] == "year") {
	 $groupBy = " GROUP BY YEAR(ts)";
       }
       else {
	 $groupBy = "";
       }
     }
     else {
       $columns = "ts, temp0, temp1";
     }

     include ('includes/getSql.php');     

     $counter = 0;
     $valuesDisplayed = 0;
     
     // connect to mysql
     if (!$db_con) {
       die('Could not connect: ' . mysql_error());
     }
     
     // select database
     mysql_select_db($db_name) or die(mysql_error());
     
     $placeSql = "SELECT * FROM 1wireDevices WHERE deviceType='temp'";
     
     $query = mysql_query($placeSql);

     echo "['Time', ";
     
     while($row = mysql_fetch_array($query)) {
       echo "'" . $row['place'] . "',";
     }
     
     echo "],";
     
    // do the query
     $sql = $sql . $groupBy;
    $query = mysql_query($sql);
    
    // read result
    while($row = mysql_fetch_array($query)) {
      $rows++;
      echo "['{$row['ts']}', {$row['temp0']}, {$row['temp1']}]";
      echo ",\n";
    }

    // close connection to mysql
    mysql_close($db_con);
    ?>

    ]);

  var options = {

<?php

echo "title: '" . $table . " - Temps ";
echo $selection;
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
  echo "SQL = " . $sql;
?>
  </body>
</html>
