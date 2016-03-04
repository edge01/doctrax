<?php
ini_set('display_errors','false');

$mode = 1;

$connection = array(
	'1' => 'connection-local.php',
	'2' => 'connection-live.php',
);

include($connection[$mode]);

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>