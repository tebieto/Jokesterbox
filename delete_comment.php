<?php #delete_comment.php

// This script allows an authorized user to delete a //comment

// Include the configuration file


require_once ('includes/config.inc.php');

$script = 'delete_comment.php';

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
	 OR !isset($_GET['cid']) OR !isset($_GET['confirm']) 
 OR !isset($_GET['ssid']))  {

 $url = BASE_URL . 'login.php?host=';

 $url .= "$host"; // Define the URL.
 
 
 ob_end_clean(); // Delete the buffer.
 
 header("Location: $url");

 exit(); // Quit the script.

 }

 
 $comment = $_GET['cid'];
 $confirm = $_GET['confirm'];
 $u = $_SESSION['user_id'];
 $storykey= $_GET['sk'];
 if($storykey==2017) {
	 $dbc=$dba;
 }else if($storykey==2018){
	$dbc=$dbb;
 } else {
	 $storykey==FALSE;
 }
 if (($storykey) && is_numeric($comment)) {
	 
	 if ($confirm == 'confirmed') {
	 
	$q = " DELETE FROM comments
 
	 WHERE comment_id=$comment
	 
	 LIMIT 1";
	
 $r = mysqli_query ($dbc, $q) or
 
trigger_error("Query: $q\n<br />MySQLError: "

 . mysqli_error($dbc));
	 } else {
		 if ($confirm== 'confirm') {
			 
$qsc = "SELECT c.comment_id, c.comment_date as date, c.body, c.author_id 
	FROM comments AS c 
        WHERE c.comment_id=$comment 
		
		LIMIT 1
		" ;
	$rsc = mysqli_query ($dbc, $qsc) or
 
	trigger_error("Query: $qsc\n<br />MySQLError: "

	. mysqli_error($dbc));
	
	if (mysqli_num_rows($rsc)>0) {
		
	while ($rowsc = mysqli_fetch_array($rsc, MYSQLI_ASSOC)) {
	$sc = $rowsc['body'];
	$sci = $rowsc['comment_id'];
	$scai = $rowsc['author_id'];
	$scd = $rowsc['date'];
	
	$qn = "SELECT CONCAT_WS(' ', u.first_name, u.middle_name, u.last_name) as name 
	FROM users AS u
        WHERE u.user_id=$scai 
		LIMIT 1
		" ;
	$rn = mysqli_query ($dba, $qn) or
 
	trigger_error("Query: $qn\n<br />MySQLError: "

	. mysqli_error($dba));
	
	if (mysqli_num_rows($rn)==1) {
		
$rown = mysqli_fetch_array($rn, MYSQLI_ASSOC); 
	$sca = $rown['name'];
	}
	echo "<div class=\"comment\" id=\"$sci\">";
	echo "<span class=\"sca\"><a href=\"profile.php?uid=$scai\">$sca</a></span><br />
	
		<span class=\"sc\">$sc</span><br /> ";
		
		if (($scai == $u) OR $author1 == $u) {
		
			$delete = 'confirm';
			$sendelete = "delDel($sci, $storykey, 'confirmed')";
		
	echo	"<button type=\"button\" class=\"cdelete\" 
 onclick=\"$sendelete\">$delete</button><br /><br />";
	}
	echo '<div>';
	
		
	}
		
	}
			 
		 }
	 }
 }

 

mysqli_close($dbc);



?>