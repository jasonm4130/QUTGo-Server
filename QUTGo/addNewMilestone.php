<?php
/* Define the level increments for milestones */
define("STEP_INCREMENT", 5000);
define("EXTRA_STEP_INCREMENT", 500);
define("GO_CHALLENGE_INCREMENT", 5);
define("FRIEND_CHALLENGE_INCREMENT", 5);

/**
* checkMilestoneEligibility
* 
* Description
* 
* @pre (String) $type - Contains exactly "step", "extra_step", "challenge", or "friend_challenge"
* 
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $userid - Integer variable containing the user's ID
* @param (String) $type - String variable containing the milestone type to check for
* 
* @return (String) $success - String variable containing a success or failiure message
*/
function checkMilestoneEligibility($connect, $userid, $type){
	/* Set the default value of the return message */
	$success = "Error new milestone not added";
	
	/* Check the type of milestone and process the request accordingly */
	switch ($type) {
		/* If the milestone type to check is step */
		case "step":
			/* Fetch the number of steps the user has accumulated today */
			$steps = intval(mysqli_fetch_array(mysqli_query($connect, "SELECT steps FROM step WHERE user_id = '$userid' AND date = date(now())"))[0]);
			
			/* Fetch the highest milestone the user has achieved in this field today */
			$highest_milestone = intval(mysqli_fetch_array(mysqli_query($connect, "SELECT COUNT(*) as level FROM milestone WHERE user = '$userid' AND type = 'step' AND date(awarded_time) = date(now())"))[0]);
			
			/* If the current step count is greater than step count required for the next level of milestone */
			if(($steps >= ((STEP_INCREMENT * $highest_milestone)) + STEP_INCREMENT)){
				/* Add the new milestone */
				$success = addNewMilestone($connect, $userid, $type, ($highest_milestone + 1));
			}
			break;
		/* If the milestone type to check is extra step */
		case "extra_step":
			/* Fetch the number of extra steps the user has accumulated today */
			$extra_steps = intval(mysqli_fetch_array(mysqli_query($connect, "SELECT extra_steps FROM step WHERE user_id = '$userid' AND date = date(now())"))[0]);
			
			/* Fetch the highest milestone the user has achieved in this field today */
			$highest_milestone = intval(mysqli_fetch_array(mysqli_query($connect, "SELECT COUNT(*) as level FROM milestone WHERE user = '$userid' AND type = 'extra_step' AND date(awarded_time) = date(now())"))[0]);
			
			/* If the current extra step count is greater than extra step count required for the next level of milestone */
			if(($extra_steps >= ((EXTRA_STEP_INCREMENT * $highest_milestone)) + EXTRA_STEP_INCREMENT)){			
				/* Add the new milestone */
				$success = addNewMilestone($connect, $userid, $type, ($highest_milestone + 1));
			}
			break;
		/* If the milestone type to check is go challenge */
		case "go":
			/* Fetch the number of go challenges the user has completed today */
			$go_challenges = intval(mysqli_fetch_array(mysqli_query($connect, "SELECT COUNT(*) AS challenges FROM challenge WHERE challengee = '$userid' AND type = 'go' AND DATE(completion_time) = (DATE(now()))"))[0]);
			
			/* Fetch the highest milestone the user has achieved in this field today */
			$highest_milestone = intval(mysqli_fetch_array(mysqli_query($connect, "SELECT COUNT(*) as level FROM milestone WHERE user = '$userid' AND type = 'challenge' AND date(awarded_time) = date(now())"))[0]);
			
			/* If the current go challenge count is greater than go challenge count required for the next level of milestone */
			if(($go_challenges >= ((GO_CHALLENGE_INCREMENT * $highest_milestone)) + GO_CHALLENGE_INCREMENT)){
				/* Add the new milestone */
				$success = addNewMilestone($connect, $userid, "challenge", ($highest_milestone + 1));
			}
			break;
		/* If the milestone type to check is friend challenge */
		case "friend":
			/* Fetch the number of friend challenges the user has completed today */
			$friend_challenges = intval(mysqli_fetch_array(mysqli_query($connect, "SELECT COUNT(*) AS challenges FROM challenge WHERE challengee = '$userid' AND type = 'friend' AND DATE(completion_time) = (DATE(now()))"))[0]);
			
			/* Fetch the highest milestone the user has achieved in this field today */
			$highest_milestone = intval(mysqli_fetch_array(mysqli_query($connect, "SELECT COUNT(*) as level FROM milestone WHERE user = '$userid' AND type = 'friend_challenge' AND date(awarded_time) = date(now())"))[0]);
			
			/* If the current friend challenge count is greater than friend challenge count required for the next level of milestone */
			if(($friend_challenges >= ((FRIEND_CHALLENGE_INCREMENT * $highest_milestone)) + FRIEND_CHALLENGE_INCREMENT)){
				/* Add the new milestone */
				$success = addNewMilestone($connect, $userid, "friend_challenge", ($highest_milestone + 1));
			}
			break;
	}
	
	/* Return the success or failure message */
	return $success;
}

/**
* addNewMilestone
* 
* Description
* 
* @pre (String) $type - Contains exactly "step", "extra_step", "challenge", or "friend_challenge"
* 
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $userid - Integer variable containing the user's ID
* @param (String) $type - String variable containing the milestone's type
* @param (Integer) $level - Integer variable containing the milestone's level
* 
* @return (String) $success - String variable containing a success or failiure message
*/
function addNewMilestone($connect, $userid, $type, $level){
	/* If the level of milestone awarded exceeds the maximum milestone possible do nothing */
	if($level >= 6){
		return "Error maximum milestone already achieved";
	} else {
		/*
			INSERT INTO milestone (user, type, level, awarded_time)
			VALUES ('$userid', '$type', '$level', NOW())
		*/
		$sql = "INSERT INTO milestone (user, type, level, awarded_time) VALUES ('$userid', '$type', '$level', NOW())";
		
		/* Run the query */
		$result = mysqli_query($connect, $sql);
		
		/* Chect the number of modified rows */
		$rows = mysqli_affected_rows($connect);
		
		/* Set the result */
		if($rows == 1){
			$success = "New milestone added succesfully";
		} else {
			$success = "Error new milestone not added";
		}
	}
}
?>