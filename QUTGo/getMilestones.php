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
* getMilestones
* 
* Returns a JSON object containing a success/failure notification if the friendship request has been sent
* 
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $userid - Integer variable containing the sending user's ID
* 
* @return (JSON) $groups - JSON encoded String variable containing a success/failure message
*/
function getMilestones($connect, $userid){
	/*
		SELECT *
		FROM (
			SELECT user, first_name, last_name, type, level, awarded_time
			FROM milestone
			INNER JOIN user
			ON milestone.user = user.user_id
			WHERE user IN (
				SELECT user.user_id
				FROM (
					SELECT derived_table.user_two AS friend_id
					FROM(
						(
							SELECT user_one, user_two
							FROM relationship
							WHERE user_one = '$userid'
							AND type = 'friends'
						)
						UNION
						(
							SELECT user_two AS user_one, user_one AS user_two
							FROM relationship
							WHERE user_two = '$userid'
							AND type = 'friends'
						)
					)derived_table
				)friends_table
				INNER JOIN user
				ON friends_table.friend_id = user.user_id
				ORDER BY first_name, last_name ASC
			)
			OR user = '$userid'
		)milestones_table
		WHERE DATE(awarded_time) = CURDATE()
		ORDER BY awarded_time DESC
	*/
	$sql = "SELECT * FROM (SELECT user, first_name, last_name, type, level, awarded_time FROM milestone INNER JOIN user ON milestone.user = user.user_id WHERE user IN (SELECT user.user_id FROM (SELECT derived_table.user_two AS friend_id FROM((SELECT user_one, user_two FROM relationship WHERE user_one = '$userid' AND type = 'friends')UNION(SELECT user_two AS user_one, user_one AS user_two FROM relationship WHERE user_two = '$userid' AND type = 'friends'))derived_table)friends_table INNER JOIN user ON friends_table.friend_id = user.user_id ORDER BY first_name, last_name ASC)OR user = '$userid')milestones_table WHERE DATE(awarded_time) = CURDATE() ORDER BY awarded_time DESC";
	
	/* Run the query */
	$result = mysqli_query($connect, $sql);
	
	/* Push query results into array */
	$milestones = array();
	while($milestone = mysqli_fetch_array($result)){
		$milestones[] = $milestone;
	}
	
	/*
		SELECT *
		FROM (
			SELECT challenge_id, challengee AS challengee_id, challengee.first_name AS challengee_first_name, challengee.last_name AS challengee_last_name, challenger AS challenger_id, challenger.first_name AS challenger_first_name, challenger.last_name AS challenger_last_name, type, block, completion_time
			FROM challenge
			LEFT JOIN user AS challengee
			ON challenge.challengee = challengee.user_id
			LEFT JOIN user AS challenger
			ON challenge.challenger = challenger.user_id
			WHERE challengee IN(
				SELECT user.user_id
				FROM (
					SELECT derived_table.user_two AS friend_id
					FROM(
						(
							SELECT user_one, user_two
							FROM relationship
							WHERE user_one = '$userid'
							AND type = 'friends'
						)
						UNION
						(
							SELECT user_two AS user_one, user_one AS user_two
							FROM relationship
							WHERE user_two = '$userid'
							AND type = 'friends'
						)
					)derived_table
				)friends_table
				INNER JOIN user
				ON friends_table.friend_id = user.user_id
				ORDER BY first_name, last_name ASC
			)
			OR challengee = '$userid'
			OR challenger IN(
				SELECT user.user_id
				FROM (
					SELECT derived_table.user_two AS friend_id
					FROM(
						(
							SELECT user_one, user_two
							FROM relationship
							WHERE user_one = '$userid'
							AND type = 'friends'
						)
						UNION
						(
							SELECT user_two AS user_one, user_one AS user_two
							FROM relationship
							WHERE user_two = '$userid'
							AND type = 'friends'
						)
					)derived_table
				)friends_table
				INNER JOIN user
				ON friends_table.friend_id = user.user_id
				ORDER BY first_name, last_name ASC
			)
			OR challenger = '$userid'
		)challenges_table
		WHERE DATE(completion_time) = CURDATE()
		ORDER BY completion_time DESC
	*/
	$sql = "SELECT * FROM (SELECT challenge_id, challengee AS challengee_id, challengee.first_name AS challengee_first_name, challengee.last_name AS challengee_last_name, challenger AS challenger_id, challenger.first_name AS challenger_first_name, challenger.last_name AS challenger_last_name, type, block, completion_time FROM challenge LEFT JOIN user AS challengee ON challenge.challengee = challengee.user_id LEFT JOIN user AS challenger ON challenge.challenger = challenger.user_id WHERE challengee IN(SELECT user.user_id FROM (SELECT derived_table.user_two AS friend_id FROM((SELECT user_one, user_two FROM relationship WHERE user_one = '$userid' AND type = 'friends')UNION(SELECT user_two AS user_one, user_one AS user_two FROM relationship WHERE user_two = '$userid' AND type = 'friends'))derived_table)friends_table INNER JOIN user ON friends_table.friend_id = user.user_id ORDER BY first_name, last_name ASC)OR challengee = '$userid' OR challenger IN(SELECT user.user_id FROM (SELECT derived_table.user_two AS friend_id FROM((SELECT user_one, user_two FROM relationship WHERE user_one = '$userid' AND type = 'friends')UNION(SELECT user_two AS user_one, user_one AS user_two FROM relationship WHERE user_two = '$userid' AND type = 'friends'))derived_table)friends_table INNER JOIN user ON friends_table.friend_id = user.user_id ORDER BY first_name, last_name ASC)OR challenger = '$userid')challenges_table WHERE DATE(completion_time) = CURDATE() ORDER BY completion_time DESC";
	
	/* Run the query */
	$result = mysqli_query($connect, $sql);
	
	/* Push query results into array */
	$challenges = array();
	while($challenge = mysqli_fetch_array($result)){
		$challenges[] = $challenge;
	}
	
	/* Encode array as json */
	$milestones = json_encode($milestones);
	$challenges = json_encode($challenges);
	
	/* Return json */
	echo '{"result":{"milestones":' . $milestones . ',"challenges":' . $challenges . '}}' ;
}
?>