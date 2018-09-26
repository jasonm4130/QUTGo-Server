<?php
/**
* Require database connection and timezone setup code
* 
* @define (MySQLi_Connect) $connect - A MySQLi_Connect variable containing the database connection information
*/
require 'setup.php';

/* If the method, userid and type are set and not empty */
if(isset($_GET['method']) && !empty($_GET['method']) && isset($_GET['userid']) && !empty($_GET['userid']) && isset($_GET['type']) && !empty($_GET['type'])){
	/* If the requested method exists */
	if(function_exists($_GET['method'])){
		/* Run the method */
		$_GET['method']($connect, $_GET['userid'], $_GET['type']);
	}
}

/* Close the database connection */
mysqli_close($connect);

/**
* getFriendsList
* 
* Returns a JSON object containing a list of the user's IDs whom currently have accepted friendships with the specified user
* 
* @pre (String) $type - Contains exactly "friends" or "pending"
* 
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $userid - Integer variable containing the requested user's ID
* @param (String) $type - String variable containing the type of relationship requested
* 
* @return (JSON) $users - JSON encoded String variable containing the user's ID, first name, last name, email and google account profile image URL
*/
function getFriendsList($connect, $userid, $type){
	/* If request is for currently accepted friends set the sql appropriately */
	if($type == "friends"){
		/*	
		SELECT user.user_id, user.first_name, user.last_name, user.email, user.url, friends_table.timestamp
		FROM (
			SELECT derived_table.user_two AS friend_id, derived_table.timestamp
			FROM(
				(
					SELECT user_one, user_two, timestamp
					FROM relationship
					WHERE user_one = '$userid'
					AND type = '$type'
				)
				UNION
				(
					SELECT user_two AS user_one, user_one AS user_two, timestamp
					FROM relationship
					WHERE user_two = '$userid'
					AND type = '$type'
				)
			)derived_table
		)friends_table
		INNER JOIN user
		ON friends_table.friend_id = user.user_id
		ORDER BY first_name, last_name ASC
		*/
		$sql = "SELECT user.user_id, user.first_name, user.last_name, user.email, user.url, friends_table.timestamp FROM (SELECT derived_table.user_two AS friend_id, derived_table.timestamp FROM((SELECT user_one, user_two, timestamp FROM relationship WHERE user_one = '$userid' AND type = '$type'))derived_table)friends_table INNER JOIN user ON friends_table.friend_id = user.user_id ORDER BY first_name, last_name ASC";
		
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
		
	/* If request is for currently pending friends set the sql appropriately */
	} else if($type == "pending"){
		/*
		Query for retrieving friend information when the specified userid is the user who sent the request
		
		SELECT user.user_id, user.first_name, user.last_name, user.email, user.url, derived_table.timestamp
		FROM(
			SELECT user_one, user_two, timestamp
			FROM relationship
			WHERE user_one = '$userid'
			AND type = '$type'
			)derived_table
		INNER JOIN user
		ON derived_table.user_two = user.user_id
		ORDER BY first_name, last_name ASC
		*/
		$sql = "SELECT user.user_id, user.first_name, user.last_name, user.email, user.url, derived_table.timestamp FROM(SELECT user_one, user_two, timestamp FROM relationship WHERE user_one = '$userid' AND type = '$type')derived_table INNER JOIN user ON derived_table.user_two = user.user_id ORDER BY first_name, last_name ASC";
		
		/* Run the query */
		$result = mysqli_query($connect, $sql);
		
		/* Push query results into array */
		$user_is_sender = array();
		while($user = mysqli_fetch_array($result)){
			$user_is_sender[] = $user;
		}
		
		/* Encode array as json */
		$user_is_sender = json_encode($user_is_sender);
		
		/*
		Query for retrieving friend information when the specified userid is the user who received the request
		
		SELECT user.user_id, user.first_name, user.last_name, user.email, user.url, derived_table.timestamp
		FROM(
			SELECT user_one, user_two, timestamp
			FROM relationship
			WHERE user_two = '$userid'
			AND type = '$type'
			)derived_table
		INNER JOIN user
		ON derived_table.user_one = user.user_id
		ORDER BY first_name, last_name ASC
		*/
		$sql = "SELECT user.user_id, user.first_name, user.last_name, user.email, user.url, derived_table.timestamp FROM(SELECT user_one, user_two, timestamp FROM relationship WHERE user_two = '$userid' AND type = '$type')derived_table INNER JOIN user ON derived_table.user_one = user.user_id ORDER BY first_name, last_name ASC";
		
		/* Run the query */
		$result = mysqli_query($connect, $sql);
		
		/* Push query results into array */
		$user_is_receiver = array();
		while($user = mysqli_fetch_array($result)){
			$user_is_receiver[] = $user;
		}
		
		/* Encode array as json */
		$user_is_receiver = json_encode($user_is_receiver);
		
		/* Return json */
		echo '{"result":{"to":' . $user_is_sender . ',"from":' . $user_is_receiver . '}}';
	}
}
?>
