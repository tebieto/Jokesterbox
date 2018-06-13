<?php #send_people.php

// This script allows a logged in user to follow people // in people page

// Include the configuration file


require_once ('includes/config.inc.php');

$script = 'send_people.php';
$start= 0;
$display=1;
$host = 'people.php';

// we cant include header

ob_start();

// Initialize a session:

require_once("includes/sessions.php");

session_start();

// Regenerate session ID:

//session_regenerate_id();

// Require dbc connection

require_once(MYSQL);

// validate session
 
 jb_validate_session($host);

// If no user_id session variable exists, redirect the user:


 if (!isset($_SESSION['user_id'])  
	 OR !isset($_GET['pid']) OR !isset($_GET['ssid'])) {

 $url = BASE_URL . 'login.php?host=';

 $url .= "$host"; // Define the URL.
 
 
 ob_end_clean(); // Delete the buffer.
 
 header("Location: $url");

 exit(); // Quit the script.

 }

 
 
 $person = $_GET['pid'];
 $author = $_SESSION['user_id'];
 $country = $_SESSION['country'];
 $dbc=$dba;
 
 if (is_numeric($person)) {
	 
	$q = "SELECT follow_id FROM follows 
 
	WHERE follower_id=$author AND master_id=$person 
	
	LIMIT 1";
	
	$r = mysqli_query ($dbc, $q) or
 
trigger_error("Query: $q\n<br />MySQLError: "

 . mysqli_error($dbc));
	
if (mysqli_num_rows($r)==0) {
	// You have not followed
	 
 $q1 = "INSERT INTO follows 
 (master_id, follower_id, country, follow_date)
 
 VALUES ($person, $author, '$country', UTC_TIMESTAMP())";
	
	
 $r1 = mysqli_query ($dbc, $q1) or
 
trigger_error("Query: $q1\n<br />MySQLError: "

 . mysqli_error($dbc));
 
 } else {
	 
	 // You have followed
 
 $q = " DELETE FROM follows
 
	 WHERE follower_id=$author AND master_id=$person 
	 
	 LIMIT 1";
	
 $r = mysqli_query ($dbc, $q) or
 
trigger_error("Query: $q\n<br />MySQLError: "

 . mysqli_error($dbc));
 } 
 }
 


	include ('includes/display_people.inc.php');
 

mysqli_close($dbc);



?>