<?php
/**
* Require database connection and timezone setup code
* 
* @define (MySQLi_Connect) $connect - A MySQLi_Connect variable containing the database connection information
*/
require 'setup.php';

/* If the method, and userid are set and not empty */
if(isset($_GET['method']) && !empty($_GET['method']) && isset($_GET['userid']) && !empty($_GET['userid'])){
	/* If the requested method exists */
	if(function_exists($_GET['method'])){
		/* Run the method */
		$_GET['method']($connect, $_GET['userid']);
	}
}

/* Close the database connection */
mysqli_close($connect);

/**
* dailySummaryMilestones
* 
* Returns a JSON object containing a success/failure notification if the friendship request has been sent
* 
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $userid - Integer variable containing the user's ID
* 
* @return (JSON) $success - JSON encoded Integer containing the number of milestone completed
*/
function dailySummaryMilestones($connect, $userid){
	/*
		SELECT type, level, awarded_time
		FROM milestone
		WHERE user = '$userid'
		AND DATE(awarded_time) = DATE(NOW())
	*/
	$sql = "SELECT type, level, awarded_time FROM milestone WHERE user = '$userid' AND DATE(awarded_time) = DATE(NOW())";
	
	/* Run the query */
	$result = mysqli_query($connect, $sql);
	
	/* Push query results into array */
	$milestones = array();
	while($milestone = mysqli_fetch_array($result)){
		$milestones[] = $milestone;
	}
	
	/* Encode array as json */
	$milestones = json_encode($milestones);
	
	/* Return json */
	echo '{"result":' . $milestones . '}';
}
?>