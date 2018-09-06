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
* getFriendsNotInGroup
* 
* Returns a JSON object containing a list of the user's IDs whom currently have accepted friendships with the specified user
* 
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $userid - Integer variable containing the requested user's ID
* @param (Integer) $groupid - Integer variable containing the group's ID
* 
* @return (JSON) $users - JSON encoded String variable containing the user's ID, first name, last name, email and google account profile image URL
*/
function getFriendsNotInGroup($connect, $userid, $groupid){
	/*	
	SELECT user.user_id, user.first_name, user.last_name, user.email, user.url, friends_table.timestamp
	FROM (
		SELECT derived_table.user_two AS friend_id, derived_table.timestamp
		FROM(
			(
				SELECT user_one, user_two, timestamp
				FROM relationship
				WHERE user_one = '$userid'
				AND type = 'friends'
			)
			UNION
			(
				SELECT user_two AS user_one, user_one AS user_two, timestamp
				FROM relationship
				WHERE user_two = '$userid'
				AND type = 'friends'
			)
		)derived_table
	)friends_table
	INNER JOIN user
	ON friends_table.friend_id = user.user_id
	WHERE friends_table.friend_id NOT IN(
		SELECT user
		FROM membership
		WHERE group_id = '$groupid'
	)
	ORDER BY first_name, last_name ASC
	*/
	$sql = "SELECT user.user_id, user.first_name, user.last_name, user.email, user.url, friends_table.timestamp FROM (SELECT derived_table.user_two AS friend_id, derived_table.timestamp FROM((SELECT user_one, user_two, timestamp FROM relationship WHERE user_one = '$userid' AND type = 'friends')UNION(SELECT user_two AS user_one, user_one AS user_two, timestamp FROM relationship WHERE user_two = '$userid' AND type = 'friends'))derived_table)friends_table INNER JOIN user ON friends_table.friend_id = user.user_id WHERE friends_table.friend_id NOT IN(SELECT user FROM membership WHERE group_id = '$groupid') ORDER BY first_name, last_name ASC";
	
	/* Run the query */
	$result = mysqli_query($connect, $sql);
	
	/* Push query results into array */
	$users = array();
	while($user = mysqli_fetch_array($result)){
		$users[] = $user;
	}
	
	/* Encode array as json */
	$users = json_encode($users);
	
	/* Return json */
	echo '{"result":' . $users . '}';
}
?>