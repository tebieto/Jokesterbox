<?php #  - profile.php


// This page will act mainly as the user's profile

// All post authored by this user will be placed here

// This is the page where a user can also upload videos and 

// share stories

// Include the configuration file


require_once ('includes/config.inc.php');

// Define some important variables

$start = 0;

if (isset($_GET['s'])){
	$start = $_GET['s'];
}

$display = 5;

$script = 'profile.php';
if (isset($_GET['uid'])) {
$host = "profile.php?uid={$_GET['uid']}";
} else if(isset($_GET['username'])) {
$host = "profile.php?username={$_GET['username']}";

} else{
$host = "profile.php?s=$start";
}
// Include the header file

ob_start();

// Initialize a session:

require_once("includes/sessions.php");

session_start();

// Regenerate session ID:

//session_regenerate_id();

// Require dbc connection
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
if ( isset($_GET['username'])) {
	$username = $_GET['username'];
	if (preg_match ('/^\w{2,20}$/', $username)) {	
		$username = $username;
	} else {
	$username= NULL;
	}	
	$q = "SELECT user_id AS u FROM users WHERE 
	nick_name = '$username'";
	
	
	$r = mysqli_query ($dbc, $q) or
 
trigger_error("Query: $q\n<br />MySQLError: "

 . mysqli_error($dbc));
 
 if (mysqli_num_rows($r) == 1) {
	 $row = mysqli_fetch_array($r, MYSQLI_ASSOC);
	 $user = $row['u'];
	
} else {
	$user= NULL;
}
}
	
if ((isset($_GET['uid']) OR  isset($user)) AND isset($_SESSION['user_id']))  {
	
	if (isset($_GET['uid'])) {
	$user = $_GET['uid'];	
	}
	if ($user != $_SESSION['user_id']) {
	$q = "SELECT first_name AS n FROM users WHERE 
	user_id = $user";
	
	$r = mysqli_query ($dbc, $q) or
 
trigger_error("Query: $q\n<br />MySQLError: "

 . mysqli_error($dbc));
 
 if (mysqli_num_rows($r) == 1) {
	 $row = mysqli_fetch_array($r, MYSQLI_ASSOC);
	$profile = "{$row['n']}'s Profile";
}
	} else {
	$profile = 'Your Profile';
}

$page_title = "$profile";
} else {
	$page_title = 'Your Profile';
}
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
 
 // Determine who is viewing this page
 
 $me = $view = FALSE;
 
 if (isset($_GET['uid']) OR isset($_GET['username'])) {
	 
	  $view = $user;
	  
	  if ($view == $_SESSION['user_id']) {
		  
		  $me = $_SESSION['user_id'];
		  
		  $view = $me;
	  }
	  
 } else {
	 
	 $me = $_SESSION['user_id'];

	$view = $me;
}


if ($view) {
	
	// include the cp and pictures
	
	echo '<div align="center" class="cover">';
	
	include ('includes/display_cp_pp.inc.php');
	
	echo'</div>';
	
	// include share form
echo '<a name="begin"></a>';	
	echo '<div align="center" class="shares">';
	
	// Include watch videos
	
	include ('includes/dh_shares.inc.php');
	
	echo '</div>';
	
// NOW LETS HAVE SOME FUN

// FROM HERE ON, We Watch videos.

// This codes will display videos based on page 

// This codes will also be used in stories.php

echo '<div align="center" class="videos">';

	include ('includes/jb_display_videos.inc.php');
 
echo '</div>';

mysqli_close($dbc);
	
} // END of view IF

?>

<?php 
include ('includes/footer.html');

?>