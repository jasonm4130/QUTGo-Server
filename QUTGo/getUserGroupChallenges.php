<?php
/**
* Require database connection and timezone setup code
* 
* @define (MySQLi_Connect) $connect - A MySQLi_Connect variable containing the database connection information
*/
require 'setup.php';

/* If the method, userid, and groupid are set and not empty */
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
* getUserGroupChallenges
* 
* Returns a JSON object containing a success/failure notification if the friendship request has been sent
* 
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $userid - Integer variable containing the user's ID
* 
* @return (JSON) $users - JSON encoded String variable containing a success/failure message
*/
function getUserGroupChallenges($connect, $userid){	
	/*
		SELECT name AS group_name, group_challenge.group_id, group_challenge_id
		FROM group_challenge
		INNER JOIN user_group
		ON group_challenge.group_id = user_group.group_id
		WHERE group_challenge.group_id IN
		(
			SELECT group_id
			FROM membership
			WHERE user = '$userid'
		)
		AND creation_date = date(now())
		AND complete = 0
	*/
	$sql = "SELECT name AS group_name, group_challenge.group_id, group_challenge_id FROM group_challenge INNER JOIN user_group ON group_challenge.group_id = user_group.group_id WHERE group_challenge.group_id IN (SELECT group_id FROM membership WHERE user = '$userid') AND creation_date = date(now()) AND complete = 0";
	
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