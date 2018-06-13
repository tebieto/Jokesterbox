<?php #check_search.php

// This script allows a logged in user to know status of // search
// Include the configuration file


require_once ('includes/config.inc.php');

$script = 'check_search.php';

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

$dbc=$dba;


// validate session
 
 jb_validate_session($host);

// If no user_id session variable exists, redirect the user:


 /*if (!isset($_SESSION['user_id'])  
	 OR !isset($_GET['q']) OR !isset($_GET['p'])   OR !isset($_GET['ssid'])) {

 $url = BASE_URL . 'login.php?host=';

 $url .= "$host"; // Define the URL.
 
 
 ob_end_clean(); // Delete the buffer.
 
 header("Location: $url");

 exit(); // Quit the script.

 }
*/
 
 $page = $_GET['p'];
 $query = $_GET['q'];
 $query = urldecode($query);
 $query = $query;
 $u = $_SESSION['user_id'];
 
if (preg_match ('/^\w{2,20}$/', $query)) { 
 if (!empty($query) && !empty($page)) {
	 
	 if ($page == 'stories.php')  {
	 
	$q = "SELECT story_id, user_id

FROM stories as s LEFT JOIN users as u

ON (s.author_id = u.user_id)

WHERE u.nick_name LIKE '$query%'

OR u.first_name LIKE '$query%'

OR u.middle_name LIKE '$query%'

OR u.last_name LIKE '$query%' 
";

$people = 'video';
	 } else {

$q = "SELECT user_id

FROM users as u

WHERE u.nick_name LIKE '$query%'

OR u.first_name LIKE '$query'

OR u.middle_name LIKE '$query'

OR u.last_name LIKE '$query' ";

$people = 'person';
	 }
	

 
	$r = mysqli_query ($dbc, $q) or
 
trigger_error("Query: $q\n<br />MySQLError: "

 . mysqli_error($dbc));
	
if (mysqli_num_rows($r)>0 && strlen($query)>1) {
	// It matched
	
	$num = mysqli_num_rows($r);
	
	if ($num > 1) {
		$was = 'were';
		if ($people == 'person') {
		$people = 'people';
		} else {
			$people = 'videos';
		}
	} else {
		$was = 'was';
	}
		
	
	
	echo '<span id="describe">';
	echo "$num $people $was matched.";
	echo '</span>';

} else {
	if (strlen($query)>2){
	echo '<span id="describe">';
	echo "No match found";
	echo '</span>';
	}
}
 }
} else {
echo '<span id="describe">';
	echo "No match found";
	echo '</span>';
}	
mysqli_close($dbc);
	


?>