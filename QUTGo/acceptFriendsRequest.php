<?php
/**
* Require database connection and timezone setup code
* 
* @define (MySQLi_Connect) $connect - A MySQLi_Connect variable containing the database connection information
*/
require 'setup.php';

/* If the method, useridone, useridtwo, and response are set and not empty */
if(isset($_GET['method']) && !empty($_GET['method']) && isset($_GET['useridone']) && !empty($_GET['useridone']) && isset($_GET['useridtwo']) && !empty($_GET['useridtwo']) && isset($_GET['response']) && !empty($_GET['response'])){
	/* If the requested method exists */
	if(function_exists($_GET['method'])){
		/* Run the method */
		$_GET['method']($connect, $_GET['useridone'], $_GET['useridtwo'], $_GET['response']);
	}
}

/* Close the database connection */
mysqli_close($connect);

/**
* acceptFriendsRequest
* 
* Returns a JSON object containing a success/failure notification if the friendship request has been modified
* 
* @pre (String) $response - Contains exactly "accept" or "decline"
* 
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $useridone - Integer variable containing the first user's ID
* @param (Integer) $useridtwo - Integer variable containing the second user's ID
* @param (String) $response - String variable containing the type of response
* 
* @return (JSON) $users - JSON encoded String variable containing the user's ID, first name, last name, email and google account profile image URL
*/
function acceptFriendsRequest($connect, $useridone, $useridtwo, $response){
	/* If the request is for accepting friendship requests set sql appropriately */
	if($response == "accept"){
		/*
			UPDATE relationship
			SET type = 'friends'
			WHERE user_one = $useridone
			AND user_two = $useridtwo
			AND type = 'pending'
			OR user_one = $useridtwo
			AND user_two = $useridone
			AND type = 'pending'
		*/
		$sql = "UPDATE relationship SET type = 'friends' WHERE user_one = $useridone AND user_two = $useridtwo AND type = 'pending' OR user_one = $useridtwo AND user_two = $useridone AND type = 'pending'";
		
	/* If the request is for declining friendship requests set sql appropriately */
	} else if($response == "decline"){
		/*
			DELETE FROM relationship
			WHERE user_one = $useridone
			AND user_two = $useridtwo
			AND type = 'pending'
			OR user_one = $useridtwo
			AND user_two = $useridone
			AND type = 'pending'
		*/
		$sql = "DELETE FROM relationship WHERE user_one = $useridone AND user_two = $useridtwo AND type = 'pending' OR user_one = $useridtwo AND user_two = $useridone AND type = 'pending'";
	}
	
	/* Run the query */
	$result = mysqli_query($connect, $sql);
	
	/* Chect the number of modified rows */
	$rows = mysqli_affected_rows($connect);
	
	/* Set the result */
	if($rows == 1){
		$success = "Friendship request updated succesfully";
	} else {
		$success = "Error friendship request not updated";
	}
	
	/* Encode array as json */
	$success = json_encode($success);
	
	/* Return json */
	echo '{"result":' . $success . '}';
}
?>