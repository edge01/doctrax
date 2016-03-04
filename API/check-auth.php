<?php
include("../config/connection.php");
include("../config/auth.php");
// include("../config/authi.php");

$data = json_decode(file_get_contents("php://input"));

$id = mysql_real_escape_string($data->id);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "DELETE FROM `users` WHERE `user_id`='".$id."'"	;

$output = Array();

if ($conn->query($sql) === TRUE) {
	$output['success'] = true;
	$output['message'] = "Schedules deleted!";
} else {
	$output['success'] = false;
	$output['message'] = $conn->error;
}

echo json_encode ($output);

$conn->close();


?>