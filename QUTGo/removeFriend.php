<?php
/**
* Require database connection and timezone setup code
* 
* @define (MySQLi_Connect) $connect - A MySQLi_Connect variable containing the database connection information
*/
require 'setup.php';
/* If the method, userid and groupid are set and not empty */
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
* removeGroupMember
* 
* Returns a JSON object containing a success/failure notification if the friendship request has been sent
* 
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $userid - Integer variable containing the user's ID
* @param (Integer) $groupid - Integer variable containing the group's ID
* 
* @return (JSON) $users - JSON encoded String variable containing a success/failure message
*/
function removeGroupMember($connect, $userid, $groupid){	
	/*
		DELETE FROM membership
		WHERE group_id = '$groupid'
		AND user = '$userid'
	*/
	$sql = "DELETE FROM membership WHERE group_id = '$groupid' AND user = '$userid'";
	
	/* Run the query */
	$result = mysqli_query($connect, $sql);
	
	/* Chect the number of modified rows */
	$rows = mysqli_affected_rows($connect);
	
	/* Set the result */
	if($rows == 1){
		$success = "Member removed successfully";
	} else {
		$success = "Error member not removed";
	}
	
	/* Encode array as json */
	$success = json_encode($success);
	
	/* Return json */
	echo '{"result":' . $success . '}';
}
?>
