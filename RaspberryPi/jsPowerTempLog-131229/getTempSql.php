<?php

if (!isset($selected)) {
    $selected = false;
  }
  
if (!isset($sql)) {
      $sql = "start_value";
    }

///// catch attributes
if (isset($_GET['time'])) {
  $timeSelection = $_GET['time'];
}

/////
///// query mysql
/////
///// last number of months, days, hours
if (isset($_GET['years'])) {
  $selected = true;
  $years = $_GET['years'];
  $sql = "SELECT * FROM tempLog WHERE DATE_SUB(CURDATE(),INTERVAL $years YEAR) <= ts";
  $selection = "last " . $years . " years";
}

if (isset($_GET['months'])) {
  $selected = true;
  $months = $_GET['months'];
  $sql = "SELECT * FROM tempLog WHERE DATE_SUB(CURDATE(),INTERVAL $months MONTH) <= ts";
  $selection = "last " . $months . " months";
}

if (isset($_GET['weeks'])) {
  $selected = true;
  $weeks = $_GET['weeks'];
  $sql = "SELECT * FROM tempLog WHERE DATE_SUB(CURDATE(),INTERVAL $weeks WEEK) <= ts";
  $selection = "last " . $weeks . " weeks";
}


if (isset($_GET['days'])) {
  $selected = true;
  $days = $_GET['days'];
  $sql = "SELECT * FROM tempLog WHERE DATE_SUB(CURDATE(),INTERVAL $days DAY) <= ts";
  $selection = "last " . $days . " days";
}

if (isset($_GET['hours'])) {
  $selected = true;
  $hours = $_GET['hours'];
  $sql = "SELECT * FROM tempLog WHERE DATE_SUB(NOW(),INTERVAL $hours HOUR) <= ts";
  $selection = "last " . $hours . " hours";
}

///// this year month, week, yesterday
if (isset($_GET['this'])) {
  $selected = true;
  if ($_GET['this'] == "year") {
    $sql = "SELECT * FROM tempLog WHERE YEAR(ts) = YEAR(CURDATE())";
    $selection = "this year";
  }
  if ($_GET['this'] == "month") {
    $sql = "SELECT * FROM tempLog WHERE MONTH(ts) = MONTH(CURDATE())";
    $selection = "this month";
  }
  if ($_GET['this'] == "week") {
    $sql = "SELECT * FROM tempLog WHERE WEEK(ts) = WEEK(CURDATE())";
    $selection = "this week";
  }
  if ($_GET['this'] == "day") {
    $sql = "SELECT * FROM tempLog WHERE DATE(ts) = CURDATE()";
    $selection = "today";
  }
}

///// last year month, week, yesterday
if (isset($_GET['last'])) {
  $selected = true;
  if ($_GET['last'] == "year") {
    $sql = "SELECT * FROM tempLog WHERE YEAR(ts) = YEAR(CURDATE()) - 1";
    $selection = "last year";
  }
  if ($_GET['last'] == "month") {
    $sql = "SELECT * FROM tempLog WHERE MONTH(ts) = MONTH(CURDATE()) - 1";
    $selection = "last month";
  }
  if ($_GET['last'] == "week") {
    $sql = "SELECT * FROM tempLog WHERE WEEK(ts) = WEEK(CURDATE()) - 1";
    $selection = "last week";
  }
  if ($_GET['last'] == "day") {
    $sql = "SELECT * FROM tempLog WHERE DATE(ts) = CURDATE() - 1";
    $selection = "yesterday";
  }
}

///// date interval
if (isset($_GET['start']) && isset($_GET['end'])) {
  $selected = true;
  $start=$_GET['start'];
  $end=$_GET['end'];
  $sql = "SELECT * FROM tempLog WHERE DATE(ts) BETWEEN '$start' AND '$end'";
  $selection = "between " . $start . " and " . $end . "";
}

///// if nothing selected above
if (!$selected) {
  $sql = "SELECT * FROM tempLog";
  $selection = "since start";
}

return $sql;

?>
