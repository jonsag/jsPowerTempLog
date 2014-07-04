<?php

///// turn off error reporting
error_reporting (E_ALL ^ E_NOTICE);

///// include configuration file
include ('config.php');
include ('functions.php');

///// debug argument
if ($argv[1] == "") {
  $debug = 0;
}
else {
  $debug = $argv[1];
}

if ($_GET['debug']) {
  $debug = 1;
}

///// poll argument
if ($argv[2] == "") {
  $poll = 0;
}
else {
  $poll = $argv[2];
}

if ($_GET['poll']) {
  $poll = 1;
}

///// event argument
if ($_GET['event'] != "") {
  $event = $_GET['event'];
}

if ($argv[3] != "") {
  $event = $argv[3];
}

$thisTime =  date('Y\-m\-d\ H\:i\:s');

///// get web page
//$html = file_get_contents($url);
$html = file($tempUrl);

///// how many lines in this file
$numLines = count($html);

if ($debug) {
  echo "Webpage " . $url . "<br>\nconsists of " . $numLines . " lines";
  lf();
  echo "-----------------------------------------------------";
  lf();
}

///// process each line
for ($i = 0; $i < $numLines; $i++) {
  // use trim to remove the carriage return and/or line feed character
  // at the end of line
  $line = trim($html[$i]);
  
  ///// show webpage with line numbers 
  if ($debug) {
    echo "#" . $i . " . " . $line . "";
    lf();
  }

  ///// find temp values
  $numberOfTemps = count($dallasTemps);
  for ($s = 0; $s <= $numberOfTemps; $s++) {
    if (preg_match('/(' . $tempValueMatch . '' . $s . ': )(.*)/', $line)) {
      preg_match('/(' . $tempValueMatch . '' . $s . ': )(.*)/', $line, $matches);
      $tempValue[$s] = $matches[2];
      $tempValue[$s] = substr($tempValue[$s], 0, -6);
      $tempValue[$s] = substr($tempValue[$s],0,strrpos($tempValue[$s],'C'));
      $tempValue[$s] = str_replace(' ', '', $tempValue[$s]);
    }
  }
}

if ($debug) {
  echo "-----------------------------------------------------";
  lf();
}

echo "This servers time stamp: " . $thisTime;
lf();

for ($s = 0; $s <= $numberOfTemps; $s++) {
  if ($tempValue[$s] != "") {
    echo "tempValue" . $s . ": " . $tempValue[$s];
    lf();
  }
}

if ($event != "") {
  echo "event: " . $event;
  lf();
}

if ($poll) {
  
  echo "Writing to MySQL...";
  lf();
  
  if (!$db_con) {
    die('Could not connect: ' . mysql_error());
  }
  
  mysql_select_db($db_name);
  
  $sql = "INSERT INTO tempLog (temp00, temp01, temp02, temp03, temp04, temp05, temp06, temp07, temp08, temp09, temp10, event)
   VALUES (
   '$tempValue[0]',
   '$tempValue[1]',
   '$tempValue[2]',
   '$tempValue[3]',
   '$tempValue[4]',
   '$tempValue[5]',
   '$tempValue[6]',
   '$tempValue[7]',
   '$tempValue[8]',
   '$tempValue[9]',
   '$tempValue[10]', 
   '$event')";
  
  $result = mysql_query($sql);
  
  if ($result) {
    echo "OK";
    lf();
    echo "Resetting after poll";
    lf();
    file($tempPollReset);      
  }
  else {
    die('Invalid query: ' . mysql_error());
  }
  
  mysql_close($db_con);
}
?>
