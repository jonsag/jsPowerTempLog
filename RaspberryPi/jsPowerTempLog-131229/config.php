<?php

date_default_timezone_set('Europe/Stockholm');
//setlocale(LC_ALL, 'en_US');

// Connections Parameters
$db_host = "localhost";
$db_name = "powerTempLog";
$username = "arduino";
$password = "arduinopass";

$db_con = mysql_connect($db_host,$username,$password);
$connection_string = mysql_select_db($db_name);

// Connection
//mysql_connect($db_host,$username,$password);
//mysql_select_db($db_name);

$powerUrl = 'http://arduino01';
$powerPollReset = 'http://arduino01/?pollReset';

$tempUrl = 'http://localhost/jsPowerTempLog/temperature.php';
$tempPollReset = 'http://localhost/jsPowerTempLog/tempPollReset.php';

$currentTimeMatch = "Current time: ";
// $lastSyncMatch = "";
// $skewMatch = "";
$unixTimeMatch = "Unix time: ";
// $loopTimeMatch = "";
$currentValueMatch = "Current phase ";
$currentAverageValueMatch = "Current average phase ";
$tempValueMatch = "Temperature sensor ";

$pulsesLastIntervalMatch = "Pulses last interval: ";
$pulsesMatch = "Pulses since last poll: ";

$dallasTemps = array("28-000003c359ac", "28-000003c37731");
$dallasPlace = array("Ute", "Acktank");

?>
