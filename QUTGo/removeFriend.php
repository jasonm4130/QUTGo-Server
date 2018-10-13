<?php
/**
* Author: Luke Goeree (26.09.2018)
*
* Require database connection and timezone setup code
*
* @define (MySQLi_Connect) $connect - A MySQLi_Connect variable containing the database connection information
*/
require 'setup.php';
/* If the method and userid are set and not empty */
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
* removeFriend
*
* Returns a JSON object containing a success/failure notification if the friendship removal request has been sent
*
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $useridone - Integer variable containing the first user's ID
* @param (Integer) $useridtwo - Integer variable containing the second user's ID
*
* @return (JSON) $users - JSON encoded String variable containing a success/failure message
*/
function removeFriend($connect, $useridone, $useridtwo){
	/*
	SQL Query for removing friendship lines from relationship database
	Removes friendships regardless of who is listed as user_one and user_two in the database

		DELETE FROM relationship
		WHERE (relationship.user_one = '$useridone' AND relationship.user_two = '$useridtwo')
			OR (relationship.user_one = '$useridtwo' AND relationship.user_two = '$useridone')
	*/
	$sql = "DELETE FROM relationship WHERE (relationship.user_one = '$useridone' AND relationship.user_two = '$useridtwo') OR (relationship.user_one = '$useridtwo' AND relationship.user_two = '$useridone')";

	/* Run the query */
	$result = mysqli_query($connect, $sql);

	/* Chect the number of modified rows */
	$rows = mysqli_affected_rows($connect);

	/* Set the result */
	if($rows == 1){
		$success = "Friend removed successfully";
	} else {
		$success = "Error friend not removed";
	}

	/* Encode array as json */
	$success = json_encode($success);

	/* Return json */
	echo '{"result":' . $success . '}';
}
?>
