<?php
/**
* Require database connection and timezone setup code
*
* @define (MySQLi_Connect) $connect - A MySQLi_Connect variable containing the database connection information
*/
require 'setup.php';

/* If the method, userid and type are set and not empty */
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
* getLeaderboardInfo
*
* Returns a JSON object containing the leaderboard information for the specified user and all groups they are a member of
*
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $userid - Integer variable containing the specified user's ID
*/
function getGroupLeaderboard($connect, $userid){
	/* Retrieve a list of groups the user is a member of */
	$groups = getGroups($connect, $userid);

	/* Instantiate the response string */
	$groups_string = "";

	/* For every group the user is a member of */
	foreach ($groups as $group) {
		/* Store the group information in local variables */
		$groupid = $group[0];
		$groupname = $group[1];

		/* Add the group information to the response string */
		$groups_string = $groups_string . "{\"0\":\"$groupid\",\"group_id\":\"$groupid\",\"1\":\"$groupname\",\"group_name\":\"$groupname\",\"2\":[";

		/* Retrieve a list of group members */
		$users = getGroupUsers($connect, $groupid);

		/* Instantiate the response string */
		$user_string = "";

		/* For every user in the group */
		foreach ($users as $user) {
			/* Retrieve the user's information */
			$userinfo = getUserInfo($connect, $user[0]);

			/* Store the user's information in local variables */
			$userid = $userinfo[0];
			$name = $userinfo[1];
			$challenges = $userinfo[2];
			$steps = $userinfo[3];
			$extra = $userinfo[4];

			/* Add the user's information to the response string */
			$user_string = $user_string . "{\"0\":\"$userid\",\"user_id\":\"$userid\",\"1\":\"$name\",\"user_name\":\"$name\",\"2\":\"$challenges\",\"completed_challenges\":\"$challenges\",\"3\":\"$steps\",\"steps\":\"$steps\",\"4\":\"$extra\",\"extra_steps\":\"$extra\"}";

			/* If the user is not the last user in the list of users, add a comma to the response string */
			if($user != end($users)){
				$user_string = $user_string . ",";
			}
		}

		/* Assemble the response string */
		$groups_string = $groups_string . $user_string;
		$groups_string = $groups_string . "],\"group_members\":[";
		$groups_string = $groups_string . $user_string;
		$groups_string = $groups_string . "]}";

		/* If the group is not the last group in the list of groups, add a comma to the response string */
		if($group != end($groups)){
			$groups_string = $groups_string . ",";
		}
	}

	/* Finalise the response string */
	$result = "{\"result\":[" . $groups_string . "]}";

	/* Return the response string */
	echo $result;
}

/**
* getFriendsLeaderboard
*
* Returns a JSON object containing the leaderboard information for the specified user and their friends
*
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $userid - Integer variable containing the specified user's ID
*/
function getFriendsLeaderboard($connect, $userid){
	/* Retrieve a list of users the user is friends with */
	$users = getFriends($connect, $userid);

	/* Instantiate the response string */
	$user_string = "";

	/* For every user the user is friends with */
	foreach($users as $user) {
		/* Retrieve the user's information */
		$userinfo = getUserInfo($connect, $user[0]);

		/* Store the user's information in local variables */
		$userid = $userinfo[0];
		$name = $userinfo[1];
		$challenges = $userinfo[2];
		$steps = $userinfo[3];
		$extra = $userinfo[4];

		/* Add the user's information to the response string */
		$user_string = $user_string . "{\"0\":\"$userid\",\"user_id\":\"$userid\",\"1\":\"$name\",\"user_name\":\"$name\",\"2\":\"$challenges\",\"completed_challenges\":\"$challenges\",\"3\":\"$steps\",\"steps\":\"$steps\",\"4\":\"$extra\",\"extra_steps\":\"$extra\"}";

		/* If the user is not the last user in the list of users, add a comma to the response string */
		if($user != end($users)){
			$user_string = $user_string . ",";
		}
	}

	/* Finalise the response string */
	$result = "{\"result\":[" . $user_string . "]}";

	/* Return the response string */
	echo $result;
}

/**
* getGroups
*
* Returns an Array object containing the groups the specified user is a member of
*
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $userid - Integer variable containing the specified user's ID
*/
function getGroups($connect, $userid){
	/*
		SELECT group_id, name
		FROM user_group
		WHERE group_id IN (
			SELECT membership.group_id
			FROM membership
			WHERE user = '$userid'
		)
	*/
	$sql = "SELECT group_id, name FROM user_group WHERE group_id IN (SELECT membership.group_id FROM membership WHERE user = '$userid')";

	/* Run the query */
	$result = mysqli_query($connect, $sql);

	/* Push query results into array */
	$groups = array();
	while($group = mysqli_fetch_array($result)){
		$groups[] = $group;
	}

	/* Return the results */
	return $groups;
}

/**
* getGroupUsers
*
* Returns an Array object containing the users in a specified group
*
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $userid - Integer variable containing the specified user's ID
*/
function getGroupUsers($connect, $groupid){
	/*
		SELECT user AS user_id
		FROM membership
		WHERE group_id = '$groupid'
		AND (type = 'member'
		OR type = 'admin')
	*/
	$sql = "SELECT user AS user_id FROM membership WHERE group_id = '$groupid' AND (type = 'member' OR type = 'admin')";

	/* Run the query */
	$result = mysqli_query($connect, $sql);

	/* Push query results into array */
	$users = array();
	while($user = mysqli_fetch_array($result)){
		$users[] = $user;
	}

	/* Return the results */
	return $users;
}

/**
* getFriends
*
* Returns an Array object containing the users a specified user is friends with, and themself
*
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $userid - Integer variable containing the specified user's ID
*/
function getFriends($connect, $userid){
	/*
		SELECT user_id
		FROM (
				(
					SELECT user_one AS user_id
					FROM relationship
					WHERE user_two = '$userid'
					AND type = 'friends'
				)
				UNION
				(
					SELECT user_two AS user_id
					FROM relationship
					WHERE user_one = '$userid'
					AND type = 'friends'
				)
				UNION
				(
					SELECT user_id
					FROM user
					WHERE user_id = '$userid'
				)
			)derived_table
	*/
	$sql = "SELECT user_id FROM ((SELECT user_one AS user_id FROM relationship WHERE user_two = '$userid' AND type = 'friends')UNION(SELECT user_two AS user_id FROM relationship WHERE user_one = '$userid' AND type = 'friends')UNION(SELECT user_id FROM user WHERE user_id = '$userid'))derived_table";

	/* Run the query */
	$result = mysqli_query($connect, $sql);

	/* Push query results into array */
	$users = array();
	while($user = mysqli_fetch_array($result)){
		$users[] = $user;
	}

	/* Return the results */
	return $users;
}

/**
* getUserInfo
*
* Returns an Array object containing the leaderboard information for a specified user
*
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $userid - Integer variable containing the specified user's ID
*/
function getUserInfo($connect, $userid){
	/*
		SELECT user.user_id, CONCAT(first_name,' ',last_name) AS user_name, challenges, steps, extra_steps
		FROM user
		INNER JOIN (
			(
				SELECT derived_table_one.user_id, challenges, steps, extra_steps
				FROM
					(
						(
							SELECT IFNULL(challengee,'$userid') AS user_id, COUNT(*) AS challenges
							FROM challenge
							WHERE challengee = '$userid'
							AND DATE(completion_time) = CURDATE()
						)
						UNION
						(
							SELECT '$userid' AS user_id, '0' AS challenges
						)
						LIMIT 1
					) derived_table_one
				INNER JOIN
					(
						(
							SELECT IFNULL(user_id,'$userid') AS user_id, IFNULL(steps,0) AS steps, IFNULL(extra_steps,0) AS extra_steps
							FROM step
							WHERE user_id = '$userid'
							AND date = CURDATE()
						)
						UNION
						(
							SELECT '$userid' AS user_id, '0' AS steps, '0' AS extra_steps
						)
						LIMIT 1
					) derived_table_two
				ON derived_table_one.user_id = derived_table_two.user_id
			) derived_table
		)
		ON derived_table.user_id = user.user_id
		WHERE user.user_id = '$userid'
	*/
	$sql = "SELECT user.user_id, CONCAT(first_name,' ',last_name) AS user_name, challenges, steps, extra_steps FROM user INNER JOIN ((SELECT derived_table_one.user_id, challenges, steps, extra_steps FROM((SELECT IFNULL(challengee,'$userid') AS user_id, COUNT(*) AS challenges FROM challenge WHERE challengee = '$userid' AND DATE(completion_time) = CURDATE())UNION(SELECT '$userid' AS user_id, '0' AS challenges)LIMIT 1) derived_table_one INNER JOIN((SELECT IFNULL(user_id,'$userid') AS user_id, IFNULL(steps,0) AS steps, IFNULL(extra_steps,0) AS extra_steps FROM step WHERE user_id = '$userid' AND date = CURDATE())UNION(SELECT '$userid' AS user_id, '0' AS steps, '0' AS extra_steps)LIMIT 1) derived_table_two ON derived_table_one.user_id = derived_table_two.user_id) derived_table)ON derived_table.user_id = user.user_id WHERE user.user_id = '$userid'";

	/* Run the query */
	$result = mysqli_query($connect, $sql);

	/* Push query results into array */
	$users =  mysqli_fetch_array($result);

	/* Return the results */
	return $users;
}
?>
