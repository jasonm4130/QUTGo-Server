<?php
/**
* Require database connection and timezone setup code
* 
* @define (MySQLi_Connect) $connect - A MySQLi_Connect variable containing the database connection information
*/
require 'setup.php';

/* If the method, googleid, firstname, lastname, and email are set and not empty */
if(isset($_GET['method']) && !empty($_GET['method']) && isset($_GET['googleid']) && !empty($_GET['googleid']) && isset($_GET['firstname']) && !empty($_GET['firstname']) && isset($_GET['lastname']) && !empty($_GET['lastname']) && isset($_GET['email']) && !empty($_GET['email'])){
	/* If the requested method exists */
	if(function_exists($_GET['method'])){
		/* Run the method */
		if(isset($_GET['url']) && !empty($_GET['url'])){
			$_GET['method']($connect, $_GET['googleid'], $_GET['firstname'], $_GET['lastname'], $_GET['email'], $_GET['url']);
		} else {
			$_GET['method']($connect, $_GET['googleid'], $_GET['firstname'], $_GET['lastname'], $_GET['email'], "");
		}
	}
}

/* Close the database connection */
mysqli_close($connect);

/**
* addNewUser
* 
* Returns a JSON object containing a success/failure notification if the user exists in the database
* 
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (String) $googleid - String variable containing the user's googleid
* @param (String) $firstname - String variable containing the user's first name
* @param (String) $lastname - String variable containing the user's last name
* @param (String) $email - String variable containing the user's email address
* @param (String) $url - String variable containing the user's profile image url
* 
* @return (JSON) $user - JSON encoded String variable containing a success message
*/
function addNewUser($connect, $googleid, $firstname, $lastname, $email, $url){
	if(empty($url)){
		/*
			INSERT INTO user (google_id, first_name, last_name, email)
			VALUES ('$googleid','$firstname','$lastname','$email')
		*/
		$sql = "INSERT INTO user (google_id, first_name, last_name, email) VALUES ('$googleid','$firstname','$lastname','$email')";
	} else {
		/*
			INSERT INTO user (google_id, first_name, last_name, email, url)
			VALUES ('$googleid','$firstname','$lastname','$email','$url')
		*/
		$sql = "INSERT INTO user (google_id, first_name, last_name, email, url) VALUES ('$googleid','$firstname','$lastname','$email','$url')";
	}
	
	/* Run the query */
	$result = mysqli_query($connect, $sql);
	
	/* Chect the number of modified rows */
	$rows = mysqli_affected_rows($connect);
	
	/* Set the result */
	if($rows == 1){
		$success = "New user added succesfully";
	} else {
		$success = "Error new user not added";
	}
	
	/* Retrieve the created user's ID */
	/*
		SELECT LAST_INSERT_ID()
	*/
	$sql = "SELECT LAST_INSERT_ID() as user_id";

	/* Run the query */
	$result = mysqli_query($connect, $sql);
	
	/* Fetch inserted id */
	$user = mysqli_fetch_array($result);
	
	/* Encode array as json */
	$success = json_encode($success);
	$user = json_encode($user);
	
	/* Return json */
	echo '{"result":{"message":' . $success . ',"data":' . $user . '}}';
}
?>