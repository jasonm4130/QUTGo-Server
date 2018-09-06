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
* getUserGroups
* 
* Returns a JSON object containing a success/failure notification if the friendship request has been sent
* 
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $userid - Integer variable containing the sending user's ID
* 
* @return (JSON) $users - JSON encoded String variable containing a success/failure message
*/
function getUserGroups($connect, $userid){	
	/*
		SELECT user_group.group_id, user_group.name, COUNT(*) AS num_members
		FROM user_group
		INNER JOIN membership
		ON user_group.group_id = membership.group_id
		WHERE user_group.group_id IN (
			SELECT group_id
			FROM membership
			WHERE user = '$userid'
			AND type = 'admin'
		)
		AND (type = 'admin'
		OR type = 'member')
		GROUP BY group_id
	*/
	$sql = "SELECT user_group.group_id, user_group.name, COUNT(*) AS num_members FROM user_group INNER JOIN membership ON user_group.group_id = membership.group_id WHERE user_group.group_id IN (SELECT group_id FROM membership WHERE user = '$userid' AND type = 'admin') AND (type = 'admin' OR type = 'member') GROUP BY group_id";
	
	/* Run the query */
	$result = mysqli_query($connect, $sql);
	
	/* Push query results into array */
	$admin_groups = array();
	while($group = mysqli_fetch_array($result)){
		$admin_groups[] = $group;
	}
	
	/*
		SELECT user_group.group_id, user_group.name, COUNT(*) AS num_members
		FROM user_group
		INNER JOIN membership
		ON user_group.group_id = membership.group_id
		WHERE user_group.group_id IN (
			SELECT group_id
			FROM membership
			WHERE user = '$userid'
			AND type = 'member'
		)
		AND (type = 'admin'
		OR type = 'member')
		GROUP BY group_id
	*/
	$sql = "SELECT user_group.group_id, user_group.name, COUNT(*) AS num_members FROM user_group INNER JOIN membership ON user_group.group_id = membership.group_id WHERE user_group.group_id IN (SELECT group_id FROM membership WHERE user = '$userid' AND type = 'member') AND (type = 'admin' OR type = 'member') GROUP BY group_id";
	
	/* Run the query */
	$result = mysqli_query($connect, $sql);
	
	/* Push query results into array */
	$member_groups = array();
	while($group = mysqli_fetch_array($result)){
		$member_groups[] = $group;
	}
	
	/* Encode array as json */
	$admin_groups = json_encode($admin_groups);
	$member_groups = json_encode($member_groups);
	
	/* Return json */
	echo '{"result":{"admin_groups":' . $admin_groups . ',"member_groups":' . $member_groups . '}}';
}
?>