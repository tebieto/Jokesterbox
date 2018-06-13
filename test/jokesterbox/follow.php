<?php # follow.php

// This script allows a logged in user to follow another user

// Include the configuration file


require_once ('includes/config.inc.php');

$script = 'follow.php';

$host = 'profile.php';

// we cant include header

ob_start();

// Initialize a session:

require_once("includes/sessions.php");

session_start();

// Regenerate session ID:

//session_regenerate_id();

// Require dbc connection

require_once(MYSQL);
$dbc=$dba;
// validate session
 
 jb_validate_session($host);

// If no user_id session variable exists, redirect the user:


 if (!isset($_SESSION['user_id']) OR !isset($_GET['uid']) OR !isset($_GET['ssid'])) {

 $url = BASE_URL . 'login.php?host=';

 $url .= "$host"; // Define the URL.
 
 
 ob_end_clean(); // Delete the buffer.
 
 header("Location: $url");

 exit(); // Quit the script.

 }
 
 
 $mm = $_GET['uid'];
 $ff = $_SESSION['user_id'];
 $country = $_SESSION['country'];
 $dbc=$dba;
 $q = "SELECT follow_id FROM follows 
 
	WHERE master_id=$mm AND follower_id=$ff 
	
	LIMIT 1";
	
	$r = mysqli_query ($dbc, $q) or
 
trigger_error("Query: $q\n<br />MySQLError: "

 . mysqli_error($dbc));
	
if (mysqli_num_rows($r)==0) {
	// is not following, follow
 
 $q = "INSERT INTO follows (master_id, follower_id, country, follow_date)
 
	VALUES ($mm, $ff, '$country', UTC_TIMESTAMP())";
	
	
 $r = mysqli_query ($dbc, $q) or
 
trigger_error("Query: $q\n<br />MySQLError: "

 . mysqli_error($dbc));
	
if (mysqli_affected_rows($dbc)==1) {
	
$q = "SELECT follow_id FROM follows
	 WHERE follower_id={$_SESSION['user_id']} 
	 AND master_id=$mm LIMIT 1";
	 
	 $r = mysqli_query ($dbc, $q) or
 
trigger_error("Query: $q\n<br />MySQLError: "

 . mysqli_error($dbc));
 
 if(mysqli_num_rows($r) == 0) {
	 // I am not following 
	 
	 $action = 'follow.php?';
	 
	 $uid = $mm;
	 
	 $value = 'follow';
	 
	 $onclick = "sendFollow('$action', $uid)";
	 
 } else {
	 // I am following
	 
	 $action = 'unfollow.php?';
	 
	 $uid = $mm;
	 
	 $value = 'unfollow';
	 
	 $onclick = "sendFollow('$action', $uid)";
 } // End of num rows IF
 
 echo '<div id="follower">';
 echo "<button type=\"button\" id=\"fbutton\"
 onclick=\"$onclick\">$value</button>";
		
	 } // End of affected rows IF
	 
	 // I want to know the followers of this user
	 
	 $q = "SELECT COUNT(master_id) as following FROM follows
			WHERE follower_id=$mm LIMIT 1";
	$r = mysqli_query ($dbc, $q) or
	trigger_error("Query: $q\n<br />MySQLError: "
	. mysqli_error($dbc));
	
	if (mysqli_num_rows($r) == 1) {
		// Match was made
		$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
		
		if($row['following'] == 1) {
		$people = 'person'; 
		} else {
			$people = 'people';
		}
		echo "<p><b>Following:</b> 
		<a href=\"people.php?fid=$mm\">";
		echo $row['following'];
		echo "</a> $people";

		} // end of num rows IF to know followers
	
	// I want to know those this user is following
	
	
	 $q = "SELECT COUNT(follower_id) as followers FROM follows
			WHERE master_id=$mm LIMIT 1";
	$r = mysqli_query ($dbc, $q) or
	trigger_error("Query: $q\n<br />MySQLError: "
	. mysqli_error($dbc));
	
	if (mysqli_num_rows($r) == 1) {
		// Match was made
		$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
		
		if($row['followers'] == 1) {
		$people = 'person'; 
		} else {
			$people = 'people';
		}
		
		echo "<b> Followers:</b> 
		<a href=\"people.php?ffid=$mm\">";
		
		echo $row['followers'];
		echo "</a> $people";
		echo '</p>';
	
	} // End of num rows IF to know following
	// I want to retrieve the username and about me

$qa = "SELECT nick_name, about_me FROM users WHERE user_id='$mm'";
	
	$ra = mysqli_query ($dbc, $qa) or 
	
	trigger_error("Query: $qa\n<br />MySQL Error: ". mysqli_error($dbc));
	
	if (mysqli_num_rows($ra) == 1 ){
		$row = mysqli_fetch_array($ra, MYSQLI_ASSOC);
		$about = $row['about_me'];
		$username= $row['nick_name'];
		
echo "<b>url:</b> <small> http://www.jokesterbox.com/$username</small>";
echo "<p>$about</p></div>";	
	}
	
} // End of Num Rows IF to know if you are already   //following user
?>