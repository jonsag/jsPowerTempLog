<html>
<body>

<?php
include 'config.php';
include 'functions.php';

//var_dump($dallasTemps);

$numberOfTemps = 0;
$dallasAddress = array();
$temperature = array();

foreach ($dallasTemps as &$value) {
  $dallasInfo = file_get_contents('/sys/bus/w1/devices/'. $value . '/w1_slave');

  //  var_dump($dallasInfo);
  //dlf();

  $dallasAddress[$numberOfTemps] = substr( $dallasInfo, 0,strrpos( $dallasInfo, ':' ) );
  
  $temperature[$numberOfTemps] = (substr( $dallasInfo, strrpos( $dallasInfo, '=' )+1 )) / 1000;
  
  echo "Temperature sensor " . $numberOfTemps . ": " . $temperature[$numberOfTemps] . " C \t Address: " . $dallasAddress[$numberOfTemps] . "\t Place: " . $dallasPlace[$numberOfTemps];
  
  $numberOfTemps++;
  
  lf();
}

?>

</body>
</html>
