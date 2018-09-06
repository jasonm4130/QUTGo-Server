<?php
/**
* checkGroupChallengeSteps
* 
* Description
* 
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $groupchallengeid - Integer variable containing the group challenges's ID
* 
* @return (String) $success - String variable containing a success or failiure message
*/
function checkGroupChallengeSteps($connect, $groupchallengeid){
	$success = "Group challenge not completed";
	
	$goal_steps = intval(mysqli_fetch_array(mysqli_query($connect, "SELECT goal_steps FROM group_challenge WHERE group_challenge_id = '$groupchallengeid' AND date(creation_date) = date(now())"))[0]);
	
	$step_contribution = intval(mysqli_fetch_array(mysqli_query($connect, "SELECT SUM(steps) AS total_contribution FROM group_challenge_contribution WHERE group_challenge_id = '$groupchallengeid'"))[0]);
	
	if($step_contribution >= $goal_steps){
		$success = "Group challenge completed";
		completeGroupChallenge($connect, $groupchallengeid);
		//addCompletedGroupChallengeSetps($connect, $groupchallengeid, $goal_steps);
	}
	
	return $success;
}

/**
* completeGroupChallenge
* 
* Description
* 
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $groupchallengeid - Integer variable containing the group challenges's ID
*/
function completeGroupChallenge($connect, $groupchallengeid){	
	/*
		UPDATE group_challenge
		SET complete = 1
		WHERE group_challenge_id = '$groupchallengeid'
	*/
	$sql = "UPDATE group_challenge SET complete = 1 WHERE group_challenge_id = '$groupchallengeid'";
	
	/* Run the query */
	mysqli_query($connect, $sql);
}

/**
* addCompletedGroupChallengeSetps
* 
* Description
* 
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $groupchallengeid - Integer variable containing the group challenges's ID
*/

// DOESN'T WORK YET

/* function addCompletedGroupChallengeSetps($connect, $groupchallengeid, $goalsteps){
	$challenge_contributers = array();
	while($contributer = mysqli_fetch_array(mysqli_query($connect, "SELECT user_id FROM group_challenge_contribution WHERE group_challenge_id = '$groupchallengeid'"))){
		$challenge_contributers[] = $contributer;
	}
	
	foreach($challenge_contributers as &$contributer){
		$steps = intval(mysqli_fetch_array(mysqli_query($connect, "SELECT steps FROM step WHERE user_id = '$$contributer' AND date(creation_date) = date(now())"))[0]);
		
		file_get_contents("sharethat.us/QUTGo/addUserSteps.php?method=addUserSteps&userid=$contributer&steps=$steps&extrasteps=$goalsteps");
	}
} */
?>