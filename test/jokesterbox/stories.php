<?php #stories.php

// This is the most critical page on this site

// This page display posts by users by order of relevance



require_once ('includes/config.inc.php');

// Define some important variables

$start = 0;

if (isset($_GET['s'])){
	$start = $_GET['s'];
	if ($start < 0 OR !is_numeric($start)) {
	$start = 0;
	}
}

$display = 5;

$script = 'stories.php';

$host = "stories.php?s=$start";

// Include the header file

$page_title = 'Home';

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
 
 $me = $u = $_SESSION['user_id'];
 
 // include share form
	
	echo '<div class="shares" align="center" >';
	
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
	
	
	

?>

<?php 
 echo'<div align="left" id="footer"> &copy; <b>2017 Jokesterbox<b></div>'; 
include ('includes/footer.html');

?>