<?php
/**
* Require database connection and timezone setup code
* 
* @define (MySQLi_Connect) $connect - A MySQLi_Connect variable containing the database connection information
*/
require 'setup.php';

/* If the method, name or email are set and not empty */
if(isset($_GET['method']) && !empty($_GET['method'])){
	/* If the requested method exists */
	if(function_exists($_GET['method'])){
		/* If searching for user by name */
		if($_GET['method'] == 'searchForUserByName'){
			/* If the name is set */
			if(isset($_GET['name']) && !empty($_GET['name'])){
				/* Run the method */
				$_GET['method']($connect, $_GET['userid'], $_GET['name']);
			}
		
		/* If searching for user by email */
		} else if( $_GET['method'] == 'searchForUserByEmail'){
			/* If the name is set */
			if(isset($_GET['email']) && !empty($_GET['email'])){
				/* Run the method */
				$_GET['method']($connect, $_GET['userid'], $_GET['email']);
			}
		}
	}
}

/* Close the database connection */
mysqli_close($connect);

/**
* searchForUserByName
* 
* Returns a JSON object containing a list of the users who match the search criteria
* 
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $userid - Integer variable containing the searching user's ID
* @param (String) $name - String variable containing the first name paramater for searching
* 
* @return (JSON) $users - JSON encoded String variable containing the user's ID, first name, last name, email and google account profile image URL
*/
function searchForUserByName($connect, $userid, $name){
	/* Check if two names have been entered */
	if(strpos($name, " ") !== false){
		/* Split the names */
		$names = explode(" ", $name);
		/* Assign name variables */
		$firstname = $names[0];
		$lastname = $names[1];
		
	/* If only one name entered */
	} else {
		/* Assign the same name to both name variables */
		$firstname = $name;
		$lastname = $name;
	}
	
	/* Check if name variable hold values */
	if($firstname != ""){
		/* Concatonate wildcard for search */
		$firstname .= "%";
	}
	/* Check if name variable hold values */
	if($lastname != ""){
		/* Concatonate wildcard for search */
		$lastname .= "%";
	}
	
	/*
		SELECT searched_users.user_id, searched_users.first_name, searched_users.last_name, searched_users.email, searched_users.url, IFNULL(users_friends.type, 'not friends') AS type
		FROM (
			SELECT user.user_id, user.first_name, user.last_name, user.email, user.url
			FROM user
			WHERE first_name LIKE '$firstname'
			OR last_name LIKE '$lastname'
			AND user_id != '$userid'
		)searched_users
		LEFT JOIN (
			(
				SELECT relationship.user_one AS user_id, type
				FROM relationship
				WHERE user_two = '$userid'
			)
			UNION
			(
				SELECT relationship.user_two AS user_id, type
				FROM relationship
				WHERE user_one = '$userid'
			)
		)users_friends
		ON searched_users.user_id = users_friends.user_id
	*/
	$sql = "SELECT searched_users.user_id, searched_users.first_name, searched_users.last_name, searched_users.email, searched_users.url, IFNULL(users_friends.type, 'not friends') AS type FROM (SELECT user.user_id, user.first_name, user.last_name, user.email, user.url FROM user WHERE first_name LIKE '$firstname' OR last_name LIKE '$lastname' AND user_id != '$userid')searched_users LEFT JOIN ((SELECT relationship.user_one AS user_id, type FROM relationship WHERE user_two = '$userid')UNION(SELECT relationship.user_two AS user_id, type FROM relationship WHERE user_one = '$userid'))users_friends ON searched_users.user_id = users_friends.user_id";
	
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

/**
* searchForUserByEmail
* 
* Returns a JSON object containing a list of the users who match the search criteria
* 
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $userid - Integer variable containing the searching user's ID
* @param (String) $email - String variable containing the email address paramater for searching
* 
* @return (JSON) $users - JSON encoded String variable containing the user's ID, first name, last name, email and google account profile image URL
*/
function searchForUserByEmail($connect, $userid, $email){
	/*
		SELECT searched_users.user_id, searched_users.first_name, searched_users.last_name, searched_users.email, searched_users.url, IFNULL(users_friends.type, 'not friends') AS type
		FROM (
			SELECT user.user_id, user.first_name, user.last_name, user.email, user.url
			FROM user
			WHERE email LIKE '$email'
			AND user_id != '$userid'
		)searched_users
		LEFT JOIN (
			(
				SELECT relationship.user_one AS user_id, type
				FROM relationship
				WHERE user_two = '$userid'
			)
			UNION
			(
				SELECT relationship.user_two AS user_id, type
				FROM relationship
				WHERE user_one = '$userid'
			)
		)users_friends
		ON searched_users.user_id = users_friends.user_id
	*/
	$sql = "SELECT searched_users.user_id, searched_users.first_name, searched_users.last_name, searched_users.email, searched_users.url, IFNULL(users_friends.type, 'not friends') AS type FROM (SELECT user.user_id, user.first_name, user.last_name, user.email, user.url FROM user WHERE email LIKE '$email' AND user_id != '$userid')searched_users LEFT JOIN ((SELECT relationship.user_one AS user_id, type FROM relationship WHERE user_two = '$userid')UNION(SELECT relationship.user_two AS user_id, type FROM relationship WHERE user_one = '$userid'))users_friends ON searched_users.user_id = users_friends.user_id";
	
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