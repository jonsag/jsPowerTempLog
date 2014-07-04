<html>
  <head>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
   google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawChart);
function drawChart() {

  var data = new google.visualization.DataTable();
  data.addColumn('Time', 'ts');
  data.addColumn('R1', 'currentAverageR1');
  data.addColumn('S2', 'currentAverageS2');
  data.addColumn('T3', 'currentAverageT3');

  <?php
  include ('config.php');
  
  // query MySQL and put results into array $results
  
  // connect to mysql
  if (!$db_con) {
    die('Could not connect: ' . mysql_error());
  }
  
  // select database
  mysql_select_db($db_name) or die(mysql_error());
  

  $sql = "SELECT * FROM powerLog";

  $query = mysql_query($sql);


  while($row = mysql_fetch_array($query)) {
    echo "data.addRow(['{$row['ts']}', {$row['currentAverageR1']}, {$row['currentAverageS2']}, {$row['currentAverageT3']}]);";
    echo "\n";
  }
  
  // close connection to mysql
  mysql_close($db_con);
  
  ?>

  var options = {
  title: 'Average currents'
  };

  var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
  chart.draw(data, options);
}
    </script>
  </head>
  <body>
    <div id="chart_div" style="width: 1024px; height: 768px;"></div>
  </body>
</html>