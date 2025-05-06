<?php
function getDBConnection(){

$host = 'trolley.proxy.rlwy.net';
$port = 28960;
$user = 'root';
$password = 'kqfBIMplvoxUVOsjFgdzsNavORNlRGKO';
$dbname = 'railway';

// Create connection
$connection = new mysqli($host, $user, $password, $dbname, $port);

if($connection->connect_error){
    die("Error: Failed to connect to MySQL. ".$connection->connect_error);
}

return $connection;
}

?>
