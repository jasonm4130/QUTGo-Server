<?php
/**
* Require database connection and timezone setup code
* 
* @define (MySQLi_Connect) $connect - A MySQLi_Connect variable containing the database connection information
*/
require 'setup.php';

/* If the method, and userid are set and not empty */
if(isset($_GET['method']) && !empty($_GET['method']) && isset($_GET['block']) && !empty($_GET['block']) && isset($_GET['userid']) && !empty($_GET['userid']) && isset($_GET['message']) && !empty($_GET['message'])){
	/* If the requested method exists */
	if(function_exists($_GET['method'])){
		/* Run the method */
		$_GET['method']($connect, $_GET['block'], $_GET['userid'], $_GET['message']);
	}
}

/* Close the database connection */
mysqli_close($connect);

/**
* addNewNoticeboardMessage
* 
* Returns a JSON object containing a success/failure notification if the message has been added
* 
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $noticeboardid - Integer variable containing the noticeboard's ID
* @param (Integer) $userid - Integer variable containing the user's ID
* @param (String) $message - String variable containing the user's message
* 
* @return (JSON) $success - JSON encoded Integer containing the number of challenges completed
*/
function addNewNoticeboardMessage($connect, $block, $userid, $message){
	$noticeboardid = mysqli_fetch_array(mysqli_query($connect, "SELECT noticeboard_id FROM noticeboard WHERE block LIKE '$block' LIMIT 1"))[0];
	
	/*
		INSERT INTO message (noticeboard, user, message, time)
		VALUES ('$noticeboardid', '$userid', '$message', NOW())
	*/
	$sql = "INSERT INTO message (noticeboard, user, message, time) VALUES ('$noticeboardid', '$userid', '$message', NOW())";
	
	/* Run the query */
	$result = mysqli_query($connect, $sql);
	
	/* Check the number of modified rows */
	$rows = mysqli_affected_rows($connect);

	/* Set the result */
	if($rows == 1){
		$success = "Messaged added succesfully";
	} else {
		$success = "Error message not added";
	}
	
	/* Encode array as json */
	$success = json_encode($success);
	
	/* Return json */
	echo '{"result":' . $success . '}';
}
?>