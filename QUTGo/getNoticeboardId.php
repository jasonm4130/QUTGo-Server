<?php
/**
* Require database connection and timezone setup code
* 
* @define (MySQLi_Connect) $connect - A MySQLi_Connect variable containing the database connection information
*/
require 'setup.php';

/* If the method, and userid are set and not empty */
if(isset($_GET['method']) && !empty($_GET['method']) && isset($_GET['block']) && !empty($_GET['block'])){
	/* If the requested method exists */
	if(function_exists($_GET['method'])){
		/* Run the method */
		$_GET['method']($connect, $_GET['block']);
	}
}

/* Close the database connection */
mysqli_close($connect);

/**
* getNoticeBoardId
* 
* Returns a JSON object containing a success/failure notification if the friendship request has been sent
* 
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $userid - Integer variable containing the notice board's ID
* 
* @return (JSON) $success - JSON encoded String variable containing a success/failure message
*/
function getNoticeBoardId($connect, $block){
	/*
		SELECT noticeboard_id
		FROM noticeboard
		WHERE block LIKE '$block'
		LIMIT 1
	*/
	$sql = "SELECT noticeboard_id FROM noticeboard WHERE block LIKE '$block' LIMIT 1";
	
	/* Run the query */
	$result = mysqli_query($connect, $sql);
	
	/* Push query results into array */
	$messages = array();
	while($message = mysqli_fetch_array($result)){
		$messages[] = $message;
	}
	
	/* Encode array as json */
	$messages = json_encode($messages);
	
	/* Return json */
	echo '{"result":' . $messages . '}';
}
?>