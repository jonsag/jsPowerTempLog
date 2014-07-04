<?php
include ('functions.php');
include "classes/php_serial.class.php";

$counter1 = 0;

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

// write to serial
$serial->sendMessage("p");

// read answer
$read = $serial->readPort();

#var_dump($read);
#lf();
#echo $read[0];

$string1 = explode("#", $read);
echo $string1[0];
dlf();

$string2 = explode(" ", $read);
for ($counter1; $counter1 <= 6; $counter1++) {
  echo $counter1 . " " . $string2[$counter1];
  lf();
}

// close device
$serial->deviceClose();

?>
