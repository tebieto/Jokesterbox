<?php #send_like.php

// This script allows a logged in user to like a post

// Include the configuration file


require_once ('includes/config.inc.php');

$script = 'send_like.php';

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

// validate session
 
 jb_validate_session($host);

// If no user_id session variable exists, redirect the user:


 if (!isset($_SESSION['user_id'])  
	 OR !isset($_GET['pid']) OR !isset($_GET['sid'])   OR !isset($_GET['ssid'])) {

 $url = BASE_URL . 'login.php?host=';

 $url .= "$host"; // Define the URL.
 
 
 ob_end_clean(); // Delete the buffer.
 
 header("Location: $url");

 exit(); // Quit the script.

 }

 
 $post = $_GET['pid'];
 $story = $_GET['sid'];
 $author = $_SESSION['user_id'];
 $storykey= $_GET['sk'];
 if($storykey==2017) {
	 $dbc=$dba;
 }else if($storykey==2018){
	$dbc=$dbb;
 } else {
	 $storykey==FALSE;
 }
 if (($storykey) && is_numeric($post) && is_numeric($story)) {
	 
	$q = "SELECT like_id FROM likes 
 
	WHERE author_id=$author AND post_id=$post 
	
	LIMIT 1";
	
	$r = mysqli_query ($dbc, $q) or
 
trigger_error("Query: $q\n<br />MySQLError: "

 . mysqli_error($dbc));
	
if (mysqli_num_rows($r)==0) {
	// User has not liked
	 
 $q1 = "INSERT INTO likes 
 (author_id, post_id, like_date)
 
 VALUES ($author, $post, UTC_TIMESTAMP())";
	
	
 $r1 = mysqli_query ($dbc, $q1) or
 
trigger_error("Query: $q1\n<br />MySQLError: "

 . mysqli_error($dbc));
 
 } else {
	 
	 // user has liked, unlike
 
 $q = " DELETE FROM likes
 
	 WHERE author_id=$author AND post_id=$post 
	 
	 LIMIT 1";
	
 $r = mysqli_query ($dbc, $q) or
 
trigger_error("Query: $q\n<br />MySQLError: "

 . mysqli_error($dbc));
 } 
 }
 


	include ('includes/jb_display_videos.inc.php');
 

mysqli_close($dbc);



?>