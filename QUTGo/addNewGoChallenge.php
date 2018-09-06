<?php
/**
* Require database connection and timezone setup code
* 
* @define (MySQLi_Connect) $connect - A MySQLi_Connect variable containing the database connection information
*/
require 'setup.php';

/* If the method, userid, and block are set and not empty */
if(isset($_GET['method']) && !empty($_GET['method']) && isset($_GET['userid']) && !empty($_GET['userid']) && isset($_GET['block']) && !empty($_GET['block'])){
	/* If the requested method exists */
	if(function_exists($_GET['method'])){
		/* Run the method */
		$_GET['method']($connect, $_GET['userid'], $_GET['block']);
	}
}

/* Close the database connection */
mysqli_close($connect);

/**
* addNewGoChallenge
* 
* Returns a JSON object containing a success/failure notification if the friendship request has been sent
* 
* @pre (String) $block - Must contain exactly: 'a','b','c','d','e','f','g','h','j','m','n','o','p','q','r','s','u','v','w','x','y','z'
* 
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $chellengee - Integer variable containing the sending user's ID
* @param (String) $block - String variable containing the challege's block
* 
* @return (JSON) $success - JSON encoded String variable containing a success/failure message.
*/
function addNewGoChallenge($connect, $challengee, $block){
	/* This function is designed specifically to add 'go' type challenges, as such this is hard-coded here. */
	$type = "go";
	
	/*
		INSERT INTO challenge (challengee, type, block)
		VALUES ('$challengee', '$type', '$block')
	*/
	$sql = "INSERT INTO challenge (challengee, type, block) VALUES ('$challengee', '$type', '$block')";
	
	/* Run the query */
	$result = mysqli_query($connect, $sql);
	
	/* Chect the number of modified rows */
	$rows = mysqli_affected_rows($connect);
	
	/* Set the result */
	if($rows >= 1){
		$success = "Challenge succesfully added";
	} else {
		$success = "Error challenge not added";
	}
	
	/* Retrieve the created challenge's ID */
	/*
		SELECT LAST_INSERT_ID() as challenge_id
	*/
	$sql = "SELECT LAST_INSERT_ID() as challenge_id";
	
	/* Run the query */
	$result = mysqli_query($connect, $sql);
	
	/* Push query results into array */
	$challenge_id = mysqli_fetch_array($result);
	
	/* Encode array as json */
	$challenge_id = json_encode($challenge_id);
	
	/* Return json */
	echo '{"result":{"message":"' . $success . '", "challenge_info":' . $challenge_id . '}}' ;
}
?>