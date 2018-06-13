<?php # - logout.php

// This is the logout page for this site.


require_once ('includes/config.inc.php');

$script= 'logout.php';
$page_title = 'Logout';

include ('includes/header.html');


// If  no first_name sesion variable exists, redirect the user:


if (!isset($_SESSION['first_name'])) {
	
	$url = BASE_URL . 'login.php'; // Define the url
	
	ob_end_clean(); // Delete the buffer.
	
	
	header ("Location: $url");
	
	exit(); // Quit the script.
	
} else { // Logout the user.

$_SESSION = array(); // Destroy the variables.


session_destroy(); // Destroy the session itself

setcookie (session_name(), '', time()-300);  // Destroy the cookie.

$url = BASE_URL;

$url .= "login.php?logout=true&host={$_GET['host']}"; 

// Define the url
	
	ob_end_clean(); // Delete the buffer.
	
	
	header ("Location: $url");
	
	exit(); // Quit the script.
}


?>