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
* dailySummaryChallenges
* 
* Returns a JSON object containing a success/failure notification if the friendship request has been sent
* 
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $userid - Integer variable containing the user's ID
* 
* @return (JSON) $success - JSON encoded Integer containing the number of challenges completed
*/
function dailySummaryChallenges($connect, $userid){
	$completed_challenges = intval(mysqli_fetch_array(mysqli_query($connect, "SELECT COUNT(*) AS num_challenges FROM challenge WHERE challengee = '$userid' AND DATE(completion_time) = DATE(NOW())"))[0]);
	
	/* Return json */
	echo '{"result":' . $completed_challenges . '}';
}
?>