<?php
/**
* Require database connection and timezone setup code
* 
* @define (MySQLi_Connect) $connect - A MySQLi_Connect variable containing the database connection information
*/
require 'setup.php';

/* If the method, userid, and groupid are set and not empty */
if(isset($_GET['method']) && !empty($_GET['method']) && isset($_GET['userid']) && !empty($_GET['userid']) && isset($_GET['groupid']) && !empty($_GET['groupid'])){
	/* If the requested method exists */
	if(function_exists($_GET['method'])){
		/* Run the method */
		$_GET['method']($connect, $_GET['userid'], $_GET['groupid']);
	}
}

/* Close the database connection */
mysqli_close($connect);

/**
* inviteToGroup
* 
* Returns a JSON object containing a success/failure notification if the friendship request has been sent
* 
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $userid - Integer variable containing the sending user's ID
* @param (Integer) $groupid - String variable containing the group's name
* 
* @return (JSON) $users - JSON encoded String variable containing a success/failure message
*/
function inviteToGroup($connect, $userid, $groupid){	
	/*
		INSERT INTO membership (user, type, group_id)
		VALUES ('$userid', 'pending', '$groupid')
	*/
	$sql = "INSERT INTO membership (user, type, group_id) VALUES ('$userid', 'pending', '$groupid')";
	
	/* Run the query */
	$result = mysqli_query($connect, $sql);
	
	/* Check the number of modified rows */
	$rows = mysqli_affected_rows($connect);

	/* Set the result */
	if($rows == 1){
		$success = "Group invitation sent succesfully";
	} else {
		$success = "Error group invitation not sent";
	}

	/* Encode array as json */
	$success = json_encode($success);

	/* Return json */
	echo '{"result":{"message":' . $success . '}}';
}
?>