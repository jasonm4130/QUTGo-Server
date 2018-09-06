<?php
/**
* Require database connection and timezone setup code
* 
* @define (MySQLi_Connect) $connect - A MySQLi_Connect variable containing the database connection information
*/
require 'setup.php';

/**
* Require milestone check and allocation code
* 
* @define (Function) checkGroupChallengeSteps($connect, $groupchallengeid) - A function that checks if a group challenge has been completed and then updates the database accordingly
*/
require 'completeGroupChallenge.php';

/* If the method, and userid are set and not empty */
if(isset($_GET['method']) && !empty($_GET['method']) && isset($_GET['userid']) && !empty($_GET['userid']) && isset($_GET['groupchallengeid']) && isset($_GET['groupchallengeid']) && isset($_GET['steps']) && isset($_GET['steps'])){
	/* If the requested method exists */
	if(function_exists($_GET['method'])){
		/* Run the method */
		$_GET['method']($connect, $_GET['userid'], $_GET['groupchallengeid'], $_GET['steps']);
	}
}

/* Fetch the group challenge id from the get request data */
$groupchallengeid = $_GET['groupchallengeid'];

/* Check for group challenge completion eligibility and complete the challenge accordingly */
checkGroupChallengeSteps($connect, $groupchallengeid);

/* Close the database connection */
mysqli_close($connect);

/**
* addGroupChallengeSteps
* 
* Returns a JSON object containing a success/failure notification if the user exists in the database
* 
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $userid - Integer variable containing the user's ID
* @param (Integer) $groupchallengeid - Integer variable containing the group challenge's ID
* @param (Integer) $steps - Integer variable containing the user's step contribution
* 
* @return (JSON) $user - JSON encoded String variable containing the user's ID
*/
function addGroupChallengeSteps($connect, $userid, $groupchallengeid, $steps){
	/* Check if the user already has a step entry for the challenge */
	if(userHasStepEntry($connect, $userid, $groupchallengeid)){
		/* If the user has a current step entry, update the step entry */
		/*
			UPDATE group_challenge_contribution
			SET steps = '$steps'
			WHERE user_id = '$userid'
			AND group_challenge_id = '$groupchallengeid'
		*/
		$sql = "UPDATE group_challenge_contribution SET steps = steps + '$steps' WHERE user_id = '$userid' AND group_challenge_id = '$groupchallengeid'";
	} else {
		/* If the user does not have a current step entry, add a step entry */
		/*
			INSERT INTO group_challenge_contribution (user_id, group_challenge_id, steps)
			VALUES ('$userid', '$groupchallengeid', '$steps')
		*/
		$sql = "INSERT INTO group_challenge_contribution (user_id, group_challenge_id, steps) VALUES ('$userid', '$groupchallengeid', '$steps')";
	}
	
	/* Run the query */
	$result = mysqli_query($connect, $sql);
	
	/* Check the number of modified rows */
	$rows = mysqli_affected_rows($connect);

	/* Set the result */
	if($rows == 1){
		$success = "Steps updated succesfully";
	} else {
		$success = "Error steps not updated";
	}
	
	/* Encode array as json */
	$success = json_encode($success);
	
	/* Return json */
	echo '{"result":' . $success . '}';
}

/**
* userHasStepEntry
* 
* Returns a Boolean denoting the presence within the database of an existing steps entry for the current date
* 
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $userid - Integer variable containing the user's ID
* @param (Integer) $groupchallengeid - Integer variable containing the group challenge's ID
* 
* @return (BOOL) - Returns true if the user has a steps entry for the current date, false if they do not
*/
function userHasStepEntry($connect, $userid, $groupchallengeid){
	/*
		SELECT *
		FROM group_challenge_contribution
		WHERE user_id = '$userid'
		AND group_challenge_id = '$groupchallengeid'
	*/	
	$sql = "SELECT * FROM group_challenge_contribution WHERE user_id = '$userid' AND group_challenge_id = '$groupchallengeid'";
	
	/* Run the query */
	$result = mysqli_query($connect, $sql);
	
	/* Fetch number of results */
	$rows = mysqli_num_rows($result);
	
	if($rows >= 1){
		return true;
	} else {
		return false;
	}
}
?>