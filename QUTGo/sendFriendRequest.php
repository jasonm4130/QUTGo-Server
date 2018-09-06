<?php
/**
* Require database connection and timezone setup code
* 
* @define (MySQLi_Connect) $connect - A MySQLi_Connect variable containing the database connection information
*/
require 'setup.php';

/* If the method, senderid, and receiverid are set and not empty */
if(isset($_GET['method']) && !empty($_GET['method']) && isset($_GET['senderid']) && !empty($_GET['senderid']) && isset($_GET['receiverid']) && !empty($_GET['receiverid'])){
	/* If the requested method exists */
	if(function_exists($_GET['method'])){
		/* Run the method */
		$_GET['method']($connect, $_GET['senderid'], $_GET['receiverid']);
	}
}

/* Close the database connection */
mysqli_close($connect);

/**
* sendFriendRequest
* 
* Returns a JSON object containing a success/failure notification if the friendship request has been sent
* 
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $senderid - Integer variable containing the sending user's ID
* @param (Integer) $receiverid - Integer variable containing the receiving user's ID
* 
* @return (JSON) $users - JSON encoded String variable containing a success/failure message
*/
function sendFriendRequest($connect, $senderid, $receiverid){
	/*
		INSERT INTO relationship (user_one, user_two, type, timestamp)
		VALUES ('$senderid', '$receiverid', 'pending', NOW())
	*/
	$sql = "INSERT INTO relationship (user_one, user_two, type, timestamp) VALUES ('$senderid', '$receiverid', 'pending', NOW())";

	/* Run the query */
	$result = mysqli_query($connect, $sql);

	/* Chect the number of modified rows */
	$rows = mysqli_affected_rows($connect);

	/* Set the result */
	if($rows == 1){
		$success = "Friendship request sent succesfully";
	} else {
		$success = "Error friendship request not sent";
	}

	/* Encode array as json */
	$success = json_encode($success);

	/* Return json */
	echo '{"result":' . $success . '}';
}
?>