<html>
<head>
<title>JS Weather Station</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=7" />
</head>
<body>
<h1><center>JS Weather Station</center></h1>
<center>- part of jsPowerTempLog</center>
<br>

<?php
   include ('config.php');
include ('functions/functions.php');
include "classes/php_serial.class.php";

$poll = "false";
$debug = "false";

if(isset($_GET['poll'])) {
  $poll = "true";
  lf();
}

if(isset($_GET['debug'])) {
  $debug = "true";
  lf();
}

if ( $debug == "true" ) {
  echo "Debug: " . $debug;
  lf();
  echo "Poll: " . $poll;
  dlf();
}

$counter1 = 0;
$counter2 = 0;
$startOK = 0;
$endOK = 0;
$noValues = 0;
$trys = 0;

define("PORT","/dev/ttyUSB0");
$serial = new phpSerial;
$serial->deviceSet(PORT);
$serial->confBaudRate(9600);
$serial->confParity("none");
$serial->confCharacterLength(8);
$serial->confStopBits(1);
$serial->confFlowControl("none");

// open serial
$serial->deviceOpen();

if ( $debug == "true" || $poll != "true" ) {
  $string1 = array("pollStart", $weatherWindDirMatch, $weatherWindDirDegMatch, $weatherAvgWindDirDegMatch, $weatherWindSpeedMatch, $weatherAverageWindSpeedMatch, $weatherRainSinceLastMatch, "pollEnd");
  
  if ( $debug == "true" ) {
    echo "Identification string: ";
    lf();
    print_r($string1);
    dlf();
    echo "Answer string: ";
    lf();
  }

  while ( $noValues != 8 ) {
    $trys++;
    if ( $trys > $maxTrys - 1 ) {
      echo "Tried " . $trys . " times";
      lf();
      echo "Giving up...";
      dlf();
      break;
    }
    // write p to serial
    $serial->sendMessage("p");
    
    // read answer
    $read = $serial->readPort();

    // split string to array
    $string2 = explode(",", $read);
    
    // count values in string
    $noValues = (count($string2));
    if ( $debug == "true" ) {
      print_r($string2);
      lf();
      echo "Received " . $noValues . " values";
      lf();
    }
  }

  for ($counter1; $counter1 <= 7; $counter1++) {
    if ($string2[$counter1] == $weatherPollStartMatch) { 
      $startOK = 1;
      if ( $debug == "true" && $startOK == 1 ) {
	lf();
	echo "Start OK";
      }
      dlf();
    }
    else if (substr($string2[$counter1], 0, 7) == $weatherPollEndMatch) {
      $endOK = 1;
      if ( $debug == "true" && $endOK == 1 ) {
	lf();
        echo "End OK";
      } 
      dlf();
    }
    else {
      echo $string1[$counter1] . $string2[$counter1];
      lf();
    }
  }
  
  lf();
  
  if ($startOK == 1 && $endOK == 1) {
    echo "OK";
    lf();
    echo "Recieved full message";
  }
  else {
    echo "Error";
    lf();
    echo "Recieved incomplete message";
  }
  dlf();
}

if ($poll == "true") {
  echo "Resetting values...";
  lf();
  $trys = 0;
  while ( $resetOK != 1) {
    $trys++;
    if ( $trys > $maxTrys - 1 ) {
      echo "Tried " . $trys . " times";
      lf();
      echo "Giving up...";
      dlf();
      break;
    }
    // write r to serial
    $serial->sendMessage("r");
    // read answer
    $read = $serial->readPort();
    
    $string3 = array("Values", "reset");
    // split string to array
    $string4 = explode(" ", $read);
    if ($debug == "true" ) {
      echo "Answer string: ";
      lf();
      print_r($string4);
      lf();
    }
    if($string3[0] == substr($string4[0], 0, 6) && $string3[1] == substr($string4[1], 0, 5)) {
      echo "Reset OK";
      lf();
      $resetOK = 1;
    }
    else {
      echo "Failed to reset";
      lf();
      $resetOK = 0;
    }
  }
}

// close device
$serial->deviceClose();

?>

</body>
</html>
