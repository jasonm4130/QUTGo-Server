<?php
/**
* Require database connection and timezone setup code
* 
* @define (MySQLi_Connect) $connect - A MySQLi_Connect variable containing the database connection information
*/
require 'setup.php';

/* If the method, and challengeid are set and not empty */
if(isset($_GET['method']) && !empty($_GET['method']) && isset($_GET['challengeid']) && !empty($_GET['challengeid'])){
	/* If the requested method exists */
	if(function_exists($_GET['method'])){
		/* Run the method */
		$_GET['method']($connect, $_GET['challengeid']);
	}
}

/* Close the database connection */
mysqli_close($connect);

/**
* deleteChallenge
* 
* Returns a JSON object containing a success/failure notification if the friendship request has been sent
* 
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $challengeid - Integer variable containing the group's ID
* 
* @return (JSON) $users - JSON encoded String variable containing a success/failure message
*/
function deleteChallenge($connect, $challengeid){	
	/*
		INSERT INTO dropped_challenges (challenge_id, type, challengee, challenger, block, message)
		SELECT challenge_id, type, challengee, challenger, block, message
		FROM challenge
		WHERE challenge_id = '$challengeid';
		DELETE FROM challenge
		WHERE challenge_id = '$challengeid'
	*/
	$sql = "INSERT INTO dropped_challenges (challenge_id, type, challengee, challenger, block, message) SELECT challenge_id, type, challengee, challenger, block, message FROM challenge WHERE challenge_id = '$challengeid'; DELETE FROM challenge WHERE challenge_id = '$challengeid'";
	
	/* Run the query */
	$result = mysqli_multi_query($connect, $sql);
	
	/* Chect the number of modified rows */
	$rows = mysqli_affected_rows($connect);
	
	/* Set the result */
	if($rows >= 1){
		$success = "Challenge succesfully removed";
	} else {
		$success = "Error challenge not removed";
	}
	
	/* Encode array as json */
	$success = json_encode($success);
	
	/* Return json */
	echo '{"result":' . $success . '}';
}
?>