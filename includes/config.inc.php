<?php # - config.inc.php
$mem = new Memcached();
$mem->addServer("127.0.0.1", 11211);


/* This script:

* - define constants and settings

* - dictates how errors are handled

* - defines useful functions

*/

// Document who created this site, when, why, etc.



// **********************************************//

// **************** SETTINGS ********************//


// Flag variables for site status:

define('LIVE', TRUE);


// Admin contact address:

define('EMAIL', 'InsertRealAddressHere');


// Site URL (base for all redirections):

define('BASE_URL', 'http://www.jokesterbox.com/');

// Adjust the time zone for PHP 5.1 and greater:

date_default_timezone_set ('US/Eastern');

// Location of the MySQL connection script:



function switchwrite($year) {
	
$year= $year;

if ($year==2017) {
	
define ('MYSQL1', '../../jbdb/jb_connect.php');


} else if($year == 2018) {
	
	define ('MYSQL2', '../../jbdb/jb_connect.php');

} else if($year == 2019) {
	
	define ('MYSQL3', '../../jbdb/jb_connect.php');
}

}




function switchread($year) {
	
$year= $year;

if ($year==2017) {
	
define ('MYSQL1a', '../../jbdb/jb_connect.php');


} else if($year == 2018) {
	
	define ('MYSQL2a', '../../jbdb/jb_connect.php');

} else if($year == 2019) {
	
	define ('MYSQL3a', '../../jbdb/jb_connect.php');
	
}

}

define ('MYSQL', '../../jbdb/jb_connect.php');
// ******************* SETTINGS ********************//

// *************************************************//


//*********************************************//

//**************** ERROR MANAGEMENT ***********//


// Create the error handler:


function my_error_handler (
$e_number, $e_message, $e_file, $e_line, $e_vars) {
	
	// Build the error message
	
	
	$message = "<p>An error occoured in script '$e_file' on 
	
	line $e_line: $e_message\n<br />";
	
	
	// Add the date and time:
	
	
	$message .= "Date/Time: "  . date('n-j-Y H:i:s') . "\n<br />";
	
	
	// Append $e_vars to the message:
	
	$message .= "<pre>". print_r($e_vars, 1) . "</pre>\n</p>";
	
if (!LIVE) { // Development (print the error).
	
	
		echo '<div id="Errors">'. $message .'</div><br />';
	
	} 
	
	else {  // Dont show the error:
	
		// Send an email to the admin:
	
		mail(EMAIL, 'Site Error!', $message, 'From: email@example.com');
	
		// Only print an error message if the error isn't a notice:
	
		if ($e_number != E_NOTICE) {
		
			echo '<div id="Error">A system error occoured. We appologize 
		
			for the inconveniences.</div><br />';
		
		}
	
	} // END of !LIVE IF.
	
	
} // End of my_error_handler() definition.


// USE my error handler

set_error_handler ('my_error_handler');



// **************** ERROR MANAGEMENT **********************//

// *******************************************************//


// *******************************************************//

// ****************** IMPORTANT FUNCTIONS ****************//b
			
function write_date($now, $then) {

$day = $now - $then;

if ($day < 60) {
$shout = '<small>shared just now.</small>';
} else if ($day >= 60 AND $day < 120) {
$shout = '<small>shared one min ago.</small>';
} else if($day >= 120 AND $day < 3600) {
$day = $day / 60;

$day = round($day);

if ($day == 1) {
$es = NULL;	
} else {
$es = 's';	
}	

$shout = "<small>shared $day min$es ago.</small>";
} else if ($day >= 3600 AND $day < 86400) {
$day = $day/60;
$day = $day/60;
$day  = round($day);

if ($day == 1) {
$es = NULL;	
} else {
$es = 's';	
}

$shout = "<small>shared $day hr$es ago.</small>";
	
} else if ($day >= 86400 AND $day < 604800) {

$day = $day/3600;
$day = $day/24;	
$day = round($day);
if ($day == 1) {
$es = NULL;	
} else {
$es = 's';	
}
$shout = "<small>shared $day day$es ago.</small>";
} else if ($day >= 604800 AND $day < 2419200){
$day = $day/3600;
$day = $day/24;
$day = $day/7;

$day = round($day);
if ($day == 1) {
$es = NULL;	
} else {
$es = 's';	
}
$shout = "<small>shared $day week$es ago.</small>";
} else if ($day >= 2419200 AND $day < 29030400) {
$day = $day/2419200;

$day = round($day);
if ($day == 1) {
$es = NULL;	
} else {
$es = 's';	
}
$shout = "<small>shared $day month$es ago.</small>";
} else if ($day >= 29030400) {
$day = $day/29030400;
$day = round($day);
if ($day == 1) {
$es = NULL;	
} else {
$es = 's';	
}
$shout = "<small>shared $day year$es ago.</small>";
}
return $shout;
}

function jb_validate_session($host) {
	
	$url = BASE_URL;

    $url .=	"login.php?logout=true&host=$host"; 
	
	// Define the url
	
	if (time() > ($_SESSION['expire'])) {
		
		$_SESSION = array(); // Destroy the variables.


	session_destroy(); // Destroy the session itself

	setcookie (session_name(), '', time()-14200);  // Destroy the cookie.

	
	
	ob_end_clean(); // Delete the buffer.
	
	
	header ("Location: $url");
	
	exit(); // Quit the script.
	
	} 
	
	if (time() < ($_SESSION['timeout'])) {
		
		$_SESSION['timeout'] = time() + 28400;
		
	}
	
	if  (time() > ($_SESSION['timeout'])) {

	$_SESSION = array(); // Destroy the variables.


	session_destroy(); // Destroy the session itself

	setcookie (session_name(), '', time()-14200);  // Destroy the cookie.

	
	ob_end_clean(); // Delete the buffer.
	
	
	header ("Location: $url");
	
	exit(); // Quit the script.
	
	
	}
	
	if  (!isset($_SESSION['agent']) 
		
	OR 
	
	($_SESSION['agent'] != md5($_SERVER['HTTP_USER_AGENT'] ))) {

	$_SESSION = array(); // Destroy the variables.


	session_destroy(); // Destroy the session itself

	setcookie (session_name(), '', time()-100200);  // Destroy the cookie.
	
	
	ob_end_clean(); // Delete the buffer.
	
	
	header ("Location: $url");
	
	exit(); // Quit the script.
	
	}
}

// ****************** IMPORTANT FUNCTIONS ******************//

//**********************************************************//

?>