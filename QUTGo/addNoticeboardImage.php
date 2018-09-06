<?php
/**
* Require database connection and timezone setup code
* 
* @define (MySQLi_Connect) $connect - A MySQLi_Connect variable containing the database connection information
*/
require 'setup.php';

/* Add the image */
$image = saveImage();
addNoticeboardImage($connect, $_POST['block'], $_POST['userid'], $image);

/* Close the database connection */
mysqli_close($connect);

/**
* addNoticeboardImage
* 
* Returns a JSON object containing a success/failure notification if the message has been added
* 
* @param (MySQLi_Connect) $connect - MySQLi_Connect variable containing the database connection information
* @param (Integer) $noticeboardid - Integer variable containing the noticeboard's ID
* @param (Integer) $userid - Integer variable containing the user's ID
* @param (String) $image - String variable containing the local address of the uploaded image
* 
* @return (JSON) $success - JSON encoded Integer containing the number of challenges completed
*/
function addNoticeboardImage($connect, $block, $userid, $image){
	$noticeboardid = mysqli_fetch_array(mysqli_query($connect, "SELECT noticeboard_id FROM noticeboard WHERE block LIKE '$block' LIMIT 1"))[0];
	
	/*
		INSERT INTO message (noticeboard, user, image, time)
		VALUES ('$noticeboardid', '$userid', '$image', NOW())
	*/
	$sql = "INSERT INTO message (noticeboard, user, image, time) VALUES ('$noticeboardid', '$userid', '$image', NOW())";
	
	/* Run the query */
	$result = mysqli_query($connect, $sql);
	
	/* Check the number of modified rows */
	$rows = mysqli_affected_rows($connect);

	/* Set the result */
	if($rows == 1){
		$success = "Image added succesfully";
	} else {
		$success = "Error image not added";
	}
	
	/* Encode array as json */
	$success = json_encode($success);
	
	/* Return json */
	echo '{"result":' . $success . '}';
}

/**
* saveImage
* 
* Helper function that saves the image from the post request to the webserver.
* 
* @return (String) $image - String variable containing the local address of the uploaded image
*/
function saveImage(){
	/* Generate a new file name */
	$temp = explode(".", $_FILES["uploaded_file"]["name"]);
	$newfilename = round(microtime(true)) . '.' . end($temp);
	
	/* Setup file path */
	$file_path =  "images/" . $newfilename;
	
	/* Save image to webserver */
	move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $file_path);
	
	/* Return the file path */
	return $file_path;
}
?>