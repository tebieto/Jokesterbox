<?php #check_user.php

// This script allows a logged in user to know status of // username
// Include the configuration file


require_once ('includes/config.inc.php');

$script = 'check_user.php';

$host = 'stories.php';

// we cant include header

ob_start();

// Initialize a session:

require_once("includes/sessions.php");

session_start();

// Regenerate session ID:

//session_regenerate_id();

// Require dbc connection

require_once(MYSQL);

$dbc=$dba;

// validate session
 
 //jb_validate_session($host);

// If no user_id session variable exists, redirect the user:


 /*if (!isset($_SESSION['user_id'])  
	 OR !isset($_GET['q']) OR !isset($_GET['p'])   OR !isset($_GET['ssid'])) {

 $url = BASE_URL . 'login.php?host=';

 $url .= "$host"; // Define the URL.
 
 
 ob_end_clean(); // Delete the buffer.
 
 header("Location: $url");

 exit(); // Quit the script.

 }
*/
 
 
 $n = $_GET['u'];
 $n = urldecode($n);

 
 
 if (!empty($n)) {
	 
$q = "SELECT user_id

FROM users

WHERE nick_name='$n'";
	 
 
	$r = mysqli_query ($dbc, $q) or
 
trigger_error("Query: $q\n<br />MySQLError: "

 . mysqli_error($dbc));
	
if (mysqli_num_rows($r)>0) {
	// It matched
 echo '<span class="error" id="ucheck">Username exist, choose another</span>';
} else {
 echo '<span class="success" id="ucheck">Username is Available!</span>';	
}
 }


?>