<?php
include("../config/connection.php");
// include("../config/auth.php");
$data = json_decode(file_get_contents("php://input"));
$output = Array('success'=>true, 'doxs'=>null);
// $tracking = mysql_real_escape_string($data->trackingNo);
$search_string = getSearchString();

$sql = "SELECT * FROM doxs WHERE 1 " . $search_string;

$result = $conn->query($sql);

$trackingNo = Array();
if ($result->num_rows > 0) {
   
    while($row = $result->fetch_assoc()) {
      
		
   $trackingNo[$row["trackingNo"]] = $row;
    }
} else {
	$output['success'] = false;
    $output['message'] = "No result found!";
}
$output['doxs'] = $trackingNo;

echo json_encode ($output);

$conn->close();

function getSearchString(){
	$search_string = '';
	if(isset($_GET['search']) && $_GET['search'] != ''){
		$search_term = $_GET['search'];
		$search_string .= "AND ( ";
		$search_string .= "trackingNo LIKE '%" . $search_term . "%' ";
		$search_string .= ") ";
	}

	return $search_string;
}

?>