<?php
/**
* Require database connection and timezone setup code
* 
* @define (MySQLi_Connect) $connect - A MySQLi_Connect variable containing the database connection information
*/
require 'setup.php';

/* If the method, challengee, challenger, block, and type are set and not empty */
if(isset($_GET['method']) && !empty($_GET['method']) && isset($_GET['challengee']) && !empty($_GET['challengee']) && isset($_GET['challenger']) && !empty($_GET['challenger']) && isset($_GET['block']) && !empty($_GET['block']) && isset($_GET['type']) && !empty($_GET['type'])){
	/* If the requested method exists */
	if(function_exists($_GET['method'])){
		/* Run the method */
		/* @ symbol = error suppersion on $_GET['message'] to avoid unecessary error logging when a message is not included. */
		$_GET['method']($connect, $_GET['challengee'], $_GET['challenger'], $_GET['block'], $_GET['type'], @$_GET['message']);
	}
}

/* Close the database connection */
mysqli_close($connect);

/**
* addNewFriendChallenge
* 
* Returns a JSON object containing a success/failure notification if the friendship request has been sent
* 
* @pre (String) $block - Must contain exactly: 'a','b','c','d','e','f','g','h','j','m','n','o','p','q','r','s','u','v','w','x','y','z'
* @pre (String) $type - Must contain exactly: 'friend', 'coop'
* 
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $challengee - Integer variable containing the receiving user's ID
* @param (Integer) $challenger - Integer variable containing the sending user's ID
* @param (String) $block - String variable containing the challege's block
* @param (String) $type - String variable containing type of challenge
* @param (Optional, String) $message - Optional string variable containing the user's message
* 
* @return (JSON) $success - JSON encoded String variable containing a success/failure message
*/
function addNewFriendChallenge($connect, $challengee, $challenger, $block, $type, $message){
	/* Check if optional paramater message is set, and adjust SQL accordingly */
	if(empty($message)){
		/*
			INSERT INTO challenge (challengee, challenger, block, type)
			VALUES ('$challengee', '$challenger', '$block', '$type')
		*/
		$sql = "INSERT INTO challenge (challengee, challenger, block, type) VALUES ('$challengee', '$challenger', '$block', '$type')";
	} else {
		/*
			INSERT INTO challenge (challengee, challenger, block, type, message)
			VALUES ('$challengee', '$challenger', '$block', '$type', '$message')
		*/
		$sql = "INSERT INTO challenge (challengee, challenger, block, type, message) VALUES ('$challengee', '$challenger', '$block', '$type', '$message')";
	}
	
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
	
	/* Return json */
	echo '{"result":"' . $success . '"}';
}
?>