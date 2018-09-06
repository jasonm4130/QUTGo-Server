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
* @define (Function) checkMilestoneEligibility($connect, $userid, $type) - A function that checks for milestone eligibility and awards milestones if the user is eligible (For use after challenge completion)
*/
require 'addNewMilestone.php';

/* If the method, and challengeid are set and not empty */
if(isset($_GET['method']) && !empty($_GET['method']) && isset($_GET['challengeid']) && !empty($_GET['challengeid'])){
	/* If the requested method exists */
	if(function_exists($_GET['method'])){
		/* Run the method */
		$_GET['method']($connect, $_GET['challengeid']);
	}
}

/* Fetch the challenge id from the get request data */
$challengeid = $_GET['challengeid'];

/* Fetch the userid from the completed challenge */
$userid = intval(mysqli_fetch_array(mysqli_query($connect, "SELECT challengee AS userid FROM challenge WHERE challenge_id = '$challengeid'"))[0]);

/* Fetch the challenge type from the completed challenge */
$type = mysqli_fetch_array(mysqli_query($connect, "SELECT type AS userid FROM challenge WHERE challenge_id = '$challengeid'"))[0];

/* Check for milestone eligibility and award eligible milestone */
checkMilestoneEligibility($connect, $userid, $type);

/* Close the database connection */
mysqli_close($connect);

/**
* completeChallenge
* 
* Returns a JSON object containing a success/failure notification if the friendship request has been sent
* 
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $challengeid - Integer variable containing the completed challenge's ID
* 
* @return (JSON) $success - JSON encoded String variable containing a success/failure message
*/
function completeChallenge($connect, $challengeid){
	/*
		UPDATE challenge
		SET completion_time = NOW()
		WHERE challenge_id = '$challengeid'
	*/
	$sql = "UPDATE challenge SET completion_time = NOW() WHERE challenge_id = '$challengeid'";
	
	/* Run the query */
	$result = mysqli_query($connect, $sql);
	
	/* Chect the number of modified rows */
	$rows = mysqli_affected_rows($connect);
	
	/* Set the result */
	if($rows >= 1){
		$success = "Challenge succesfully completed";
	} else {
		$success = "Error challenge not completed";
	}
	
	/* Return json */
	echo '{"result":"' . $success . '"}';
}
?>