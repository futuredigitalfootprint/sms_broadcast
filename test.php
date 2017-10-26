<!DOCTYPE html>
<html>
<body>
<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);
$configContents  = file_get_contents('http://futuredigital.co.uk/form/config.json');
$configJson = json_decode($configContents);
$key = $configJson->pin;
$pin = "5486";
var_dump($pin == $key);
?>
</body>
</html>