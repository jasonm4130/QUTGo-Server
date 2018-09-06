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
* getGroupMemberInfo
* 
* Returns a JSON object containing a success/failure notification if the friendship request has been sent
* 
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $userid - Integer variable containing the user's ID
* @param (Integer) $groupid - Integer variable containing the group's ID
* 
* @return (JSON) $users - JSON encoded String variable containing a success/failure message
*/
function getGroupMemberInfo($connect, $userid, $groupid){	
	/*
		SELECT user_id, first_name, last_name, email, url
		FROM user
		INNER JOIN membership
		ON user.user_id = membership.user
		WHERE group_id = '$groupid'
		AND (type = 'admin'
		OR type = 'member')
		ORDER BY first_name, last_name ASC
	*/
	$sql = "SELECT user_id, first_name, last_name, email, url FROM user INNER JOIN membership ON user.user_id = membership.user WHERE group_id = '$groupid' AND (type = 'admin' OR type = 'member') ORDER BY first_name, last_name ASC";
	
	/* Run the query */
	$result = mysqli_query($connect, $sql);
	
	/* Push query results into array */
	$user_info = array();
	while($user = mysqli_fetch_array($result)){
		$user_info[] = $user;
	}
	
	/*
		SELECT IF(type = 'admin', 'true', 'false') AS is_admin
		FROM membership
		WHERE group_id = '$groupid'
		AND user = '$userid'
	*/
	$sql = "SELECT IF(type = 'admin', 'true', 'false') AS is_admin FROM membership WHERE group_id = '$groupid' AND user = '$userid'";
	
	/* Run the query */
	$result = mysqli_query($connect, $sql);
	
	/* Fetch result */
	$is_admin = mysqli_fetch_array($result);
	
	/* Encode array as json */
	$user_info = json_encode($user_info);
	$is_admin = json_encode($is_admin);
	
	/* Return json */
	echo '{"result":{"user_info":' . $user_info . ', "is_admin":' . $is_admin . '}}';
}
?>