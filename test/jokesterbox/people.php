<?php #people.php

// This script will either display people from a 
// user's country based on relevance or search for any user //on records. 

require_once ('includes/config.inc.php');

// Define some important variables

$start = 0;

if (isset($_GET['s'])){
	$start = $_GET['s'];
	if ($start < 0 OR !is_numeric($start)) {
	$start = 0;
	}
}

$display = 15;

$script = 'people.php';

$host = "people.php?s=$start";

// Include the header file

$page_title = 'People';

include ('includes/header.html');

// validate session
 
jb_validate_session($host);

// If no user_id session variable exists, redirect the user:


 if (!isset($_SESSION['user_id'])) {

 $url = BASE_URL . 'login.php?host=';

 $url .= "$host"; // Define the URL.
  
 ob_end_clean(); // Delete the buffer.
 
 header("Location: $url");

 exit(); // Quit the script.

 }
 require_once(MYSQL);
$slave=FALSE;
if($slave){
	$x=rand(1,1);
	if ($x==1) {
	$dbc=$dba1;
	}else {
	$dbc=$dba;	
	}
} else {
$dbc=$dba;
}
 
 echo '<div class="people">';

	include ('includes/display_people.inc.php');
 
echo '</div>';

mysqli_close($dbc);
?>