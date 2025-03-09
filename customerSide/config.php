<?php
define('DB_HOST','localhost');
define('DB_USER','root'); 
define('DB_PASS','');
define('DB_NAME','restaurantDB');

//Connection
$link = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);

//Check
if($link->connect_error){ 
die('Connection Failed'.$link->connect_error);
}
?>
