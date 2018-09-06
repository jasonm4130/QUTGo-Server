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
* getUserChallenges
* 
* Returns a JSON object containing a success/failure notification if the friendship request has been sent
* 
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $userid - Integer variable containing the user's ID
* 
* @return (JSON) $success - JSON encoded String variable containing a success/failure message
*/
function getUserChallenges($connect, $userid){
	/*
		SELECT challenge_id, CONCAT(user.first_name, ' ', user.last_name) AS challenger_name, type, block, message
		FROM challenge
		LEFT JOIN user
		ON challenge.challenger = user.user_id
		WHERE challengee = '$userid'
		AND completion_time IS NULL
	*/
	/*
		AND type != 'go'
	*/
	$sql = "SELECT challenge_id, CONCAT(user.first_name, ' ', user.last_name) AS challenger_name, type, block, message FROM challenge LEFT JOIN user ON challenge.challenger = user.user_id WHERE challengee = '$userid' AND completion_time IS NULL";
	
	/* Run the query */
	$result = mysqli_query($connect, $sql);
	
	/* Push query results into array */
	$challenges = array();
	while($challenge = mysqli_fetch_array($result)){
		$challenges[] = $challenge;
	}
	
	/* Encode array as json */
	$challenges = json_encode($challenges);
	
	/* Return json */
	echo '{"result":' . $challenges . '}';
}
?>