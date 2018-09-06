<?php
/**
* Require database connection and timezone setup code
* 
* @define (MySQLi_Connect) $connect - A MySQLi_Connect variable containing the database connection information
*/
require 'setup.php';

/* If the method, groupid, and stepgoal are set and not empty */
if(isset($_GET['method']) && !empty($_GET['method']) && isset($_GET['groupid']) && !empty($_GET['groupid'])){
	/* If the requested method exists */
	if(function_exists($_GET['method'])){
		/* Run the method */
		$_GET['method']($connect, $_GET['groupid']);
	}
}

/* Close the database connection */
mysqli_close($connect);

/**
* checkActiveGroupChallenge
* 
* Returns a JSON object containing a success/failure notification if the user exists in the database
* 
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $groupid - Integer variable containing the group's ID
* 
* @return (JSON) $user - JSON encoded success/failure message and the newly created group challenge's ID
*/
function checkActiveGroupChallenge($connect, $groupid){
	/*
		SELECT group_challenge_id, goal_steps
		FROM group_challenge
		WHERE group_id = '$groupid'
		AND creation_date = DATE(NOW())
		AND complete = 0
	*/
	$sql = "SELECT group_challenge_id, goal_steps FROM group_challenge WHERE group_id = '$groupid' AND creation_date = DATE(NOW()) AND complete = 0";

	/* Run the query */
	$result = mysqli_query($connect, $sql);
	
	/* Check the number of results */
	$row = mysqli_num_rows($result);
	
	/* Set the result */
	$challenge_exists = false;
	if($row >= 1){
		$success = "Group challenge exists";
		$challenge_exists = true;
	} else {
		$success = "No group challenge exists";
	}
	
	/* Initialise variable for later use */
	$challenge_info;
	
	/* If a group challenge exists */
	if($challenge_exists){
		/* Fetch the appropriate step contribution data */
		/* Fetch the group challenge id */
		$challenge_info = mysqli_fetch_array($result);
		
		/*
			SELECT group_challenge_contribution.user_id, first_name, last_name, steps
			FROM group_challenge_contribution
			INNER JOIN user
			ON group_challenge_contribution.user_id = user.user_id
			WHERE group_challenge_id = '$challenge_info[0]'
		*/
		$sql = "SELECT group_challenge_contribution.user_id, first_name, last_name, steps FROM group_challenge_contribution INNER JOIN user ON group_challenge_contribution.user_id = user.user_id WHERE group_challenge_id = '$challenge_info[0]'";
		
		/* Run the query */
		$result = mysqli_query($connect, $sql);
		
		/* Push query results into array */
		$user_info = array();
		while($user = mysqli_fetch_array($result)){
			$user_info[] = $user;
		}
	}
	
	/* Encode array as json */
	$success = json_encode($success);
	if($challenge_exists){
		$user_info = json_encode($user_info);
	}
	
	/* Return json */
	if($challenge_exists){
		echo '{"result":{"message":' . $success . ',"data":' . $user_info . ',"step_goal":' . $challenge_info[1] . '}}';
	} else {
		echo '{"result":' . $success . '}';
	}
}
?>