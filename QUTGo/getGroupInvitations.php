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
* getGroupInvitations
* 
* Returns a JSON object containing a success/failure notification if the friendship request has been sent
* 
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $userid - Integer variable containing the sending user's ID
* 
* @return (JSON) $groups - JSON encoded String variable containing a success/failure message
*/
function getGroupInvitations($connect, $userid){	
	/*
		SELECT membership.group_id, user_group.name
		FROM membership
		INNER JOIN user_group
		ON membership.group_id = user_group.group_id
		WHERE user = '$userid'
		AND type = 'pending'
	*/
	$sql = "SELECT membership.group_id, user_group.name FROM membership INNER JOIN user_group ON membership.group_id = user_group.group_id WHERE user = '$userid' AND type = 'pending'";
	
	/* Run the query */
	$result = mysqli_query($connect, $sql);
	
	/* Push query results into array */
	$groups = array();
	while($group = mysqli_fetch_array($result)){
		$groups[] = $group;
	}
	
	/* Encode array as json */
	$groups = json_encode($groups);
	
	/* Return json */
	echo '{"result":' . $groups . '}';
}
?>