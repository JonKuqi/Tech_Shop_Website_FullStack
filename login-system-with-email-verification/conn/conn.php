<?php 
//kredencialet

define('DB_HOST','localhost:3307');
define('DB_USER','Jon');
define('DB_PASS','1234');
define('DB_NAME','techshopdatabase');

try
{
$conn = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER, DB_PASS,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
}
catch (PDOException $e)
{
exit("Error: " . $e->getMessage());
}


?>
