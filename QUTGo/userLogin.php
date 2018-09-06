<?php
/**
* Require database connection and timezone setup code
* 
* @define (MySQLi_Connect) $connect - A MySQLi_Connect variable containing the database connection information
*/
require 'setup.php';

/* If the method, and googleid are set and not empty */
if(isset($_GET['method']) && !empty($_GET['method']) && isset($_GET['googleid']) && !empty($_GET['googleid'])){
	/* If the requested method exists */
	if(function_exists($_GET['method'])){
		/* Run the method */
		$_GET['method']($connect, $_GET['googleid']);
	}
}

/* Close the database connection */
mysqli_close($connect);

/**
* userLogin
* 
* Returns a JSON object containing a success/failure notification if the user exists in the database
* 
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (String) $googleid - String variable containing the user's googleid
* 
* @return (JSON) $user - JSON encoded String variable containing the user's ID
*/
function userLogin($connect, $googleid){
	/*
		SELECT user_id
		FROM user
		WHERE google_id = '$googleid'
	*/
	$sql = "SELECT user_id FROM user WHERE google_id = '$googleid'";
	
	/* Run the query */
	$result = mysqli_query($connect, $sql);
	
	/* Fetch number of results */
	$rows = mysqli_num_rows($result);
	
	/* Fetch the result */
	$row = mysqli_fetch_array($result);	
	
	/* Encode array as json */
	$user = json_encode($row);
	
	/* If at least one result is returned, return json */
	if($rows >= 1){
		echo '{"result":' . $user . '}';
	} else {
		echo '{"result":{"0":null,"user_id":null}}';
	}
}
?>