<?php #story.php

// This page displays story specific videos



require_once ('includes/config.inc.php');

// Define some important variables

$start = 0;

if (isset($_GET['s'])){
	$start = $_GET['s'];
	if ($start < 0 OR !is_numeric($start)) {
	$start = 0;
	}
}

$display = 1;

$script = 'story.php';

$host = 'stories.php';

// Include the header file

$page_title = 'Funny Video, Hahahahahaha ';

include ('includes/header.html');

// validate session
 
 //jb_validate_session($host);

// If no user_id session variable exists, redirect the user:

/*
 if (!isset($_SESSION['user_id']) && !isset($_GET['story'])) {

 $url = BASE_URL . 'login.php?host=';

 $url .= "$host"; // Define the URL.
 
 
 ob_end_clean(); // Delete the buffer.
 
 header("Location: $url");

 exit(); // Quit the script.

 }

 */
 require_once(MYSQL);
 
 if (isset($_SESSION['user_id'])) {
 $me = $u = $_SESSION['user_id'];
 }
 $story = $_GET['story'];
 $comment = TRUE;
$storykey= $_GET['sk'];
 if($storykey==2017) {
	 $dbc=$dba;
 }else if($storykey==2018){
	$dbc=$dbb;
 } else {
	 $storykey==FALSE;
 }
 
// This codes will display videos and comments 

echo '<div align="center" class="videos">';

	include ('includes/jb_display_videos.inc.php');
 
echo '</div>';

mysqli_close($dbc);
	
	
	

?>

<?php 

include ('includes/footer.html');

?>