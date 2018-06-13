<?php #check_email.php

// This script allows a logged in user to know status of // email
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
 
 
 $m = $_GET['m'];
 $m = urldecode($m);

 
 
 if (!empty($m)) {
	 
$q = "SELECT user_id

FROM users

WHERE email='$m'";
	 
 
	$r = mysqli_query ($dbc, $q) or
 
trigger_error("Query: $q\n<br />MySQLError: "

 . mysqli_error($dbc));
	
if (mysqli_num_rows($r)>0) {
	// It matched
 echo '<span class="error" id="ucheck">Email address already exist, choose another.</span>';
} else {
 echo '<span class="success" id="ucheck">Email address is Available!</span>';	
}
 }


?>