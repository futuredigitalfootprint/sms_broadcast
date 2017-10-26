<!DOCTYPE html>
<html>
<body>
<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);
$configContents  = file_get_contents('***');
$configJson = json_decode($configContents);
?>
</body>
</html>
