<?php
include("../config/connection.php");
include("../config/auth.php");
$data = json_decode(file_get_contents("php://input"));
$mto = mysql_real_escape_string($data->mto);
$msub = mysql_real_escape_string($data->msub);
$mmes = mysql_real_escape_string($data->mmes);
$msender = mysql_real_escape_string($data->msender);
$name=mysql_real_escape_string($data->mto);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$office = getUserIdByName($name);
if($office){
$sql = "INSERT INTO `mail`(`sender`, `receiver`, `office`, `subject`, `message`)VALUES('".$msender."','".$office."','".$mto."','".$msub."','".$mmes."')";

$output = Array();

if ($conn->query($sql) === TRUE) {
	$output['success'] = true;
	$output['message'] = "Mail has been added.";
} else {
	$output['success'] = false;
	$output['message'] = $conn->error;
}
}
else{
	$output['fail'] = true;
	$output['message'] = "No user found in this office!";
}

echo json_encode ($output);

$conn->close();

function getUserIdByName($name){
 		
				global $conn;

		if ($conn->connect_error) {
		    die("Connection failed: " . $conn->connect_error);
		} 
 
		$sql = "SELECT `username` FROM `users` WHERE `office`='".$name."'";
		$result = $conn->query($sql);
		$user = Array();
		
		if ($result ->num_rows > 0) {
		    while($row = $result -> fetch_assoc()) {
    			$user[] = $row;
		    }
		} 

	
		if( count($user) > 0 ){
			//if name was found, return its id
					$result_id = $user[0]['username'];
			// echo "Existing Schedule (".$result_id .") ";

		}
		return $result_id;
	}
	

?>