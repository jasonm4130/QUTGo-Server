<?php
/**
* Require database connection and timezone setup code
* 
* @define (MySQLi_Connect) $connect - A MySQLi_Connect variable containing the database connection information
*/
require 'setup.php';

/* If the method, adminid and groupname are set and not empty */
if(isset($_GET['method']) && !empty($_GET['method']) && isset($_GET['adminid']) && !empty($_GET['adminid']) && isset($_GET['groupname']) && !empty($_GET['groupname'])){
	/* If the requested method exists */
	if(function_exists($_GET['method'])){
		/* Run the method */
		$_GET['method']($connect, $_GET['adminid'], $_GET['groupname']);
	}
}

/* Close the database connection */
mysqli_close($connect);

/**
* createGroup
* 
* Returns a JSON object containing a success/failure notification if the friendship request has been sent
* 
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $adminid - Integer variable containing the sending user's ID
* @param (Integer) $groupname - String variable containing the group's name
* 
* @return (JSON) $users - JSON encoded String variable containing a success/failure message
*/
function createGroup($connect, $adminid, $groupname){
	/* Create the group */
	/*
		INSERT INTO user_group (name)
		VALUES ('$groupname')
	*/
	$sql = "INSERT INTO user_group (name) VALUES ('$groupname')";

	/* Run the query */
	$result = mysqli_query($connect, $sql);
	
	/* Retrieve the created group's ID */
	/*
		SELECT LAST_INSERT_ID()
	*/
	$sql = "SELECT LAST_INSERT_ID() as group_id";

	/* Run the query */
	$result = mysqli_query($connect, $sql);
	
	/* Fetch inserted id */
	$group = mysqli_fetch_array($result);
	
	/* Add the specified admin to the group as an admin */
	/*
		INSERT INTO membership (user, type, group_id)
		VALUES ('$adminid', 'admin', '$group')
	*/
	$sql = "INSERT INTO membership (user, type, group_id) VALUES ('$adminid', 'admin', '$group[0]')";
	
	/* Run the query */
	$result = mysqli_query($connect, $sql);
	
	/* Check the number of modified rows */
	$rows = mysqli_affected_rows($connect);

	/* Set the result */
	if($rows == 1){
		$success = "Group created succesfully";
	} else {
		$success = "Error group not created";
	}

	/* Encode array as json */
	$success = json_encode($success);
	$group = json_encode($group);

	/* Return json */
	echo '{"result":{"message":' . $success . ',"data":' . $group . '}}';
}
?>