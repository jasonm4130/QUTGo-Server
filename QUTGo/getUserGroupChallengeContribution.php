<?php
/**
* Require database connection and timezone setup code
* 
* @define (MySQLi_Connect) $connect - A MySQLi_Connect variable containing the database connection information
*/
require 'setup.php';

/* If the method, and userid are set and not empty */
if(isset($_GET['method']) && !empty($_GET['method']) && isset($_GET['userid']) && !empty($_GET['userid']) && isset($_GET['groupchallengeid']) && !empty($_GET['groupchallengeid'])){
	/* If the requested method exists */
	if(function_exists($_GET['method'])){
		/* Run the method */
		$_GET['method']($connect, $_GET['userid'], $_GET['groupchallengeid']);
	}
}

/* Close the database connection */
mysqli_close($connect);

/**
* getUserGroupChallengeContribution
* 
* Returns a JSON object containing a success/failure notification if the friendship request has been sent
* 
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $userid - Integer variable containing the user's ID
* @param (Integer) $groupchallengeid - Integer variable containing the group challenge's ID
* 
* @return (JSON) $success - JSON encoded Integer containing the number of milestone completed
*/
function getUserGroupChallengeContribution($connect, $userid, $groupchallengeid){
	/*
		SELECT IFNULL
		(
			(
				SELECT steps
				FROM group_challenge_contribution
				WHERE user_id = '$userid'
				AND group_challenge_id = '$groupchallengeid'
			),'0'
		) AS contribution
	*/
	$sql = "SELECT IFNULL((SELECT steps FROM group_challenge_contribution WHERE user_id = '$userid' AND group_challenge_id = '$groupchallengeid'),'0') AS contribution";
	
	/* Run the query */
	$contribution = intval(mysqli_fetch_array(mysqli_query($connect, $sql))[0]);
	
	/* Encode array as json */
	$contribution = json_encode($contribution);
	
	/* Return json */
	echo '{"result":' . $contribution . '}';
}
?>