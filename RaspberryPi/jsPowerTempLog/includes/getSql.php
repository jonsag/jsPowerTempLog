<?php

function getSQL($get, $columns) {

  if (!isset($selected)) {
    $selected = false;
  }
  
  if (!isset($sql)) {
    $sql = "start_value";
  }
  
  ///// catch attributes
  if (isset($get['time'])) {
    $timeSelection = $get['time'];
  }
  
  if (isset($get['table'])) {
    $table = $get['table'];
  }
  
  /////
  ///// query mysql
  /////
  ///// last number of months, days, hours
  if (isset($get['years'])) {
    $selected = true;
    $years = $get['years'];
    $sql = "SELECT $columns FROM $table WHERE DATE_SUB(CURDATE(),INTERVAL $years YEAR) <= ts";
    $selection = "last " . $years . " years";
  }
  
  if (isset($get['months'])) {
    $selected = true;
    $months = $get['months'];
    $sql = "SELECT $columns FROM $table WHERE DATE_SUB(CURDATE(),INTERVAL $months MONTH) <= ts";
    $selection = "last " . $months . " months";
  }
  
  if (isset($get['weeks'])) {
    $selected = true;
    $weeks = $get['weeks'];
    $sql = "SELECT $columns FROM $table WHERE DATE_SUB(CURDATE(),INTERVAL $weeks WEEK) <= ts";
    $selection = "last " . $weeks . " weeks";
  }
  
  
  if (isset($get['days'])) {
    $selected = true;
    $days = $get['days'];
    $sql = "SELECT $columns FROM $table WHERE DATE_SUB(CURDATE(),INTERVAL $days DAY) <= ts";
    $selection = "last " . $days . " days";
  }
  
  if (isset($get['hours'])) {
    $selected = true;
    $hours = $get['hours'];
    $sql = "SELECT $columns FROM $table WHERE DATE_SUB(NOW(),INTERVAL $hours HOUR) <= ts";
    $selection = "last " . $hours . " hours";
  }
  
  ///// this year month, week, yesterday
  if (isset($get['this'])) {
    $selected = true;
    if ($get['this'] == "year") {
      $sql = "SELECT $columns FROM $table WHERE YEAR(ts) = YEAR(CURDATE())";
      $selection = "this year";
    }
    if ($get['this'] == "month") {
      $sql = "SELECT $columns FROM $table WHERE MONTH(ts) = MONTH(CURDATE())";
      $selection = "this month";
    }
    if ($get['this'] == "week") {
      $sql = "SELECT $columns FROM $table WHERE WEEK(ts) = WEEK(CURDATE())";
      $selection = "this week";
    }
    if ($get['this'] == "day") {
      $sql = "SELECT $columns FROM $table WHERE DATE(ts) = CURDATE()";
      $selection = "today";
    }
  }
  
  ///// last year month, week, yesterday
  if (isset($get['last'])) {
    $selected = true;
    if ($get['last'] == "year") {
      $sql = "SELECT $columns FROM $table WHERE YEAR(ts) = YEAR(CURDATE()) - 1";
      $selection = "last year";
    }
    if ($get['last'] == "month") {
      $sql = "SELECT $columns FROM $table WHERE MONTH(ts) = MONTH(CURDATE()) - 1";
      $selection = "last month";
    }
    if ($get['last'] == "week") {
      $sql = "SELECT $columns FROM $table WHERE WEEK(ts) = WEEK(CURDATE()) - 1";
      $selection = "last week";
    }
    if ($get['last'] == "day") {
      $sql = "SELECT $columns FROM $table WHERE DATE(ts) = CURDATE() - 1";
      $selection = "yesterday";
    }
  }
  
  ///// date interval
  if (isset($get['start']) && isset($get['end'])) {
    $selected = true;
    $start=$get['start'];
    $end=$get['end'];
    $sql = "SELECT $columns FROM $table WHERE DATE(ts) BETWEEN '$start' AND '$end'";
    $selection = "between " . $start . " and " . $end . "";
  }
  
  ///// if nothing selected above
  if (!$selected) {
    $sql = "SELECT $columns FROM $table";
    $selection = "since start";
  }
  
  if(isset($get['groupBy'])) {
    if ($get['groupBy'] == "hour") {
      $sql = $sql . " GROUP BY YEAR(ts), MONTH(ts), DAY(ts), HOUR(ts)";
    }
    else if ($get['groupBy'] == "day") {
      $sql = $sql . " GROUP BY YEAR(ts), MONTH(ts), DAY(ts)";
    }
    else if ($get['groupBy'] == "week") {
      $sql = $sql . " GROUP BY YEAR(ts), WEEK(ts)";
    }
    else if ($get['groupBy'] == "month") {
      $sql = $sql . " GROUP BY YEAR(ts), MONTH(ts)";
    }
    else if ($get['groupBy'] == "year") {
      $sql = $sql . " GROUP BY YEAR(ts)";
    }
  }

  $answer[0] = $sql;
  $answer[1] = $selection;

  return $answer;
}

?>
