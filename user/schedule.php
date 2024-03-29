<?php
session_start();
?>

<?php
if(!isset($_SESSION['sess_username'])) {
  header("location: ../index.html");
  exit();
}

?>

<?php

$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$net_id= $_SESSION['sess_username'];

// Create connection
$conn = mysqli_connect($servername, $dbusername, $dbpassword,'project');

$result = mysqli_query($conn,"select term_id from term_code where status = 1");

if ($result && mysqli_num_rows($result) > 0) {
	while($row = mysqli_fetch_assoc($result)){
		$term_id = $row["term_id"];
	}
}

$result = mysqli_query($conn,"SELECT c.c_id as id, c.c_name as name, c.descr as descr, t.timings as time, CASE when t.status = '1' THEN 'Enrolled' ELSE 'No longer Offered!' END as status FROM course_details c,user_courses_enrolled u, course_term_assignment t WHERE u.net_id = '$net_id' and u.c_id = c.c_id and u.c_id = t.c_id and u.estatus != 'c' and t.term_id = '$term_id'");
$json = array();

if ($result && mysqli_num_rows($result) > 0) {
	
	 while ($row = mysqli_fetch_assoc($result))
        {
		 $json[] = $row; 
        }
	$response = array(
		"data" => $json
	);

	echo json_encode($response);
} else {
    $json = array();
	$response = array(
		"data" => $json
	);
	echo json_encode($response);
}

mysqli_close($conn);

?>
       