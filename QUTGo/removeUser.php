<?php
/**
* Require database connection and timezone setup code
* 
* @define (MySQLi_Connect) $connect - A MySQLi_Connect variable containing the database connection information
*/
require 'setup.php';

/* If the method, and userid are set and not empty */
if(isset($_GET['method']) && !empty($_GET['method']) && isset($_GET['userid']) && !empty($_GET['userid'])){
	/* If the requested method exists */
	if(function_exists($_GET['method'])){
		/* Run the method */
		$_GET['method']($connect, $_GET['userid']);
	}
}

/* Close the database connection */
mysqli_close($connect);

/**
* removeUser
* 
* Returns a JSON object containing a success/failure notification if the user exists in the database
* 
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $userid - Integer variable containing the user's ID
* 
* @return (JSON) $user - JSON encoded String variable containing the user's ID
*/
function removeUser($connect, $userid){
	/*
		INSERT INTO removed_user (user_id, google_id, first_name, last_name, email)
		SELECT user_id, google_id, first_name, last_name, email
		FROM user
		WHERE user_id = '$userid';
		UPDATE user
		SET google_id = NULL, first_name = NULL, last_name = NULL, email = NULL
		WHERE user_id = '$userid'
		
	*/
	$sql = "INSERT INTO removed_user (user_id, google_id, first_name, last_name, email) SELECT user_id, google_id, first_name, last_name, email FROM user WHERE user_id = '$userid'; UPDATE user SET google_id = NULL, first_name = NULL, last_name = NULL, email = NULL WHERE user_id = '$userid'";
	
	/* Run the query */
	$result = mysqli_multi_query($connect, $sql);
	
	/* Check the number of modified rows */
	$rows = mysqli_affected_rows($connect);

	/* Set the result */
	if($rows == 1){
		$success = "User removed succesfully";
	} else {
		$success = "Error user not removed";
	}
	
	/* Encode array as json */
	$success = json_encode($success);
	
	/* Return json */
	echo '{"result":' . $success . '}';
}
?>