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
* getStepCount
* 
* Returns a JSON object containing a success/failure notification if the user exists in the database
* 
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $userid - Integer variable containing the user's ID
* 
* @return (JSON) $user - JSON encoded String variable containing the user's ID
*/
function getStepCount($connect, $userid){
	/*
		SELECT steps, extra_steps
		FROM step
		WHERE user_id = '$userid'
		AND date = date(now())
	*/
	$sql = "SELECT steps, extra_steps FROM step WHERE user_id = '$userid' AND date = date(now())";
	
	/* Run the query */
	$result = mysqli_query($connect, $sql);
	
	/* Fetch the result */
	$row = mysqli_fetch_array($result);	
	
	/* Encode array as json */
	$steps = json_encode($row);
	
	/* Return json */
	echo '{"result":' . $steps . '}';
}
?>