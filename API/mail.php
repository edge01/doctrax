<?php
include("../config/connection.php");
include("../config/auth.php");
$data = json_decode(file_get_contents("php://input"));
$output = Array('success'=>true, 'mail'=>null);

$sql = "SELECT * FROM `mail` WHERE `receiver` ='{$username}'";

$result = $conn->query($sql);

$mail = Array();
if ($result->num_rows > 0) {
   
    while($row = $result->fetch_assoc()) {
      
		
   $mail[$row["mesID"]] = $row;
    }
} else {
    //echo "0 results";
}
$output['mail'] = $mail;

echo json_encode ($output);

$conn->close();


?>