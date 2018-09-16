<?php
/**
* Require database connection credentials
*
* @define HOST - String containing the database's host name
* @define PORT - String containing the database's host port
* @define USERNAME - String containing the database's login username
* @define PASSWORD - String containing the database's login password
* @define DATABASE - String containing the database's name
*/
/* Require database credentials */
require 'credentials.php';

/* Enable different origin access */
header('Access-Control-Allow-Origin: *');

/* Connect to the database */
$connect = mysqli_connect(HOST, USERNAME, PASSWORD, DATABASE, PORT);
mysqli_query($connect, "SET time_zone = '+10:00'");

/* If there is an error connecting to the database */
if(mysqli_errno($connect)){
	/* Return the error */
	echo "Failed to connect to the database: " . mysqli_connect_error();
}
?>
