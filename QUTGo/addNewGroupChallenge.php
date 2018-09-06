<?php
/**
* Require database connection and timezone setup code
* 
* @define (MySQLi_Connect) $connect - A MySQLi_Connect variable containing the database connection information
*/
require 'setup.php';

/* If the method, groupid, and stepgoal are set and not empty */
if(isset($_GET['method']) && !empty($_GET['method']) && isset($_GET['groupid']) && !empty($_GET['groupid']) && isset($_GET['stepgoal']) && !empty($_GET['stepgoal'])){
	/* If the requested method exists */
	if(function_exists($_GET['method'])){
		/* Run the method */
		$_GET['method']($connect, $_GET['groupid'], $_GET['stepgoal']);
	}
}

/* Close the database connection */
mysqli_close($connect);

/**
* addNewGroupChallenge
* 
* Returns a JSON object containing a success/failure notification if the user exists in the database
* 
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $groupid - Integer variable containing the group's ID
* @param (Integer) $stepgoal - Integer variable containing the challenge's step goal
* 
* @return (JSON) $user - JSON encoded success/failure message and the newly created group challenge's ID
*/
function addNewGroupChallenge($connect, $groupid, $stepgoal){
	/* Create the challenge */
	/*
		INSERT INTO group_challenge (group_id, goal_steps, creation_date)
		VALUES ('$groupid', '$stepgoal', DATE(NOW()))
	*/
	$sql = "INSERT INTO group_challenge (group_id, goal_steps, creation_date) VALUES ('$groupid', '$stepgoal', DATE(NOW()))";

	/* Run the query */
	$result = mysqli_query($connect, $sql);
	
	/* Check the number of modified rows */
	$rows = mysqli_affected_rows($connect);

	/* Set the result */
	if($rows == 1){
		$success = "Group challenge added succesfully";
	} else {
		$success = "Error group challenge not added";
	}
	
	/* Retrieve the created group challenge's ID */
	/*
		SELECT LAST_INSERT_ID() as group_challenge_id
	*/
	$sql = "SELECT LAST_INSERT_ID() as group_challenge_id";

	/* Run the query */
	$result = mysqli_query($connect, $sql);
	
	/* Fetch inserted id */
	$challenge = mysqli_fetch_array($result);
	
	/* Encode array as json */
	$success = json_encode($success);
	$challenge = json_encode($challenge);
	
	/* Return json */
	echo '{"result":{"message":' . $success . ',"data":' . $challenge . '}}';
}
?>