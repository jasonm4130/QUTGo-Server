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
* @define (Function) checkMilestoneEligibility($connect, $userid, $type) - A function that checks for milestone eligibility and awards milestones if the user is eligible (For use after step allocation)
*/
require 'addNewMilestone.php';

/* If the method, and userid are set and not empty */
if(isset($_GET['method']) && !empty($_GET['method']) && isset($_GET['userid']) && !empty($_GET['userid']) && isset($_GET['steps']) && isset($_GET['extrasteps'])){
	/* If the requested method exists */
	if(function_exists($_GET['method'])){
		/* Run the method */
		$_GET['method']($connect, $_GET['userid'], $_GET['steps'], $_GET['extrasteps']);
	}
}

/* Fetch the user id from the get request data */
$userid = $_GET['userid'];

/* Check for milestone eligibility and award eligible milestone */
checkMilestoneEligibility($connect, $userid, 'step');
checkMilestoneEligibility($connect, $userid, 'extra_step');

/* Close the database connection */
mysqli_close($connect);

/**
* addUserSteps
* 
* Returns a JSON object containing a success/failure notification if the user exists in the database
* 
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $userid - Integer variable containing the user's ID
* @param (Integer) $steps - Integer variable containing the user's steps
* @param (Integer) $extrasteps - Integer variable containing the user's extra steps
* 
* @return (JSON) $user - JSON encoded String variable containing the user's ID
*/
function addUserSteps($connect, $userid, $steps, $extrasteps){
	/* Check if the user already has a step entry for current date */
	if(userHasStepEntry($connect, $userid)){
		/* If the user has a current step entry, update the step entry */
		/*
			UPDATE step
			SET steps = '$steps', extra_steps = '$extrasteps'
			WHERE user_id = '$userid'
			AND date = date(now())
		*/
		$sql = "UPDATE step SET steps = '$steps', extra_steps = '$extrasteps' WHERE user_id = '$userid' AND date = date(now())";
	} else {
		/* If the user does not have a current step entry, add a step entry */
		/*
			INSERT INTO step (user_id, steps, extra_steps, date)
			VALUES ('$userid', '$steps', '$extrasteps', date(now()))
		*/
		$sql = "INSERT INTO step (user_id, steps, extra_steps, date) VALUES ('$userid', '$steps', '$extrasteps', date(now()))";
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
* 
* @return (BOOL) - Returns true if the user has a steps entry for the current date, false if they do not
*/
function userHasStepEntry($connect, $userid){
	/*
		SELECT *
		FROM step
		WHERE user_id = '$userid'
		AND date = date(now())
	*/	
	$sql = "SELECT * FROM step WHERE user_id = '$userid' AND date = date(now())";
	
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