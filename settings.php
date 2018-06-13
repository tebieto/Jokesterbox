<?php  /* This page will display all user details and create 

links to edit them. */

require_once ('includes/config.inc.php');


$script = 'settings.php';

$host = 'settings.php';

$page_title = 'Your Account Settings';

include ('includes/header.html');

// validate session
 
 jb_validate_session($host);


if (!isset($_SESSION['first_name'])) {

 $url = BASE_URL . 'login.php?host=';

 $url .= "$host"; // Define the URL.
 
 
 ob_end_clean(); // Delete the buffer.

 header("Location: $url");

 exit(); // Quit the script.

 }
 
 // If a user is logged in


if (isset($_SESSION['user_id'])) {
	
	$this_user = $_SESSION['user_id'];
	require_once(MYSQL);
	$slave=FALSE;
if($slave){
	$x=rand(1,1);
	$dbc=$dba.$x;
} else {
$dbc=$dba;
}
	
	// Query the database to get all informations to edit
	
	
	$q = "SELECT email, nick_name, password, first_name, 
	
	
	middle_name, last_name, country, about_me, phone 
	
	FROM users 
	
	WHERE  user_id={$this_user}";
		
		
	$r = mysqli_query ($dbc, $q) or 
	
	trigger_error("Query: $q\n<br />MySQL Error: ". mysqli_error($dbc));
	
	if (@mysqli_num_rows($r) == 1 ){
		
		// Details were retrieved
		
		while ($row = mysqli_fetch_array ($r, MYSQLI_ASSOC)) {
			
			// Display the infos and give edit Link
			
	echo '<div align="left">';
			
	echo " <h1> General Settings </h1>";
	
	if (isset($_GET['updated'])){
		$success = '<p class="success">Update was successful!</p>';
		}
	
	
	if (isset($_GET['activated'])){
		$success = '<p class="success">Your account was activated successfully!</p>';
		}
	if (isset($success)) echo $success;
	
	
	
	echo "<table>
		  <tr>
		 <td><b>About Me:</b>
		 </td><td>{$row['about_me']}</td>
		 <td><a href=\"change_about.php\">Edit About Me</a></td>
		 </tr>
		 
		 <tr>
		 <td><b>Country:</b></td><td>{$row['country']}</td>
		 <td><a href=\"change_country.php\">Change Country</a></td>
		 </tr>
		 <tr>
		 <td><b>Phone Number:</b></td><td>{$row['phone']}</td>
		 <td><a href=\"change_phone.php\">Edit Phone Number</a></td>
		 </tr>
	     <tr>
		 <td><b>Primary Email Address:</b></td><td>{$row['email']}</td>
		 <td><a href=\"change_email.php\">Change Email Address</a></td>
		 </tr>
		 <tr>
		 <td><b>Username:</b></td><td>{$row['nick_name']}</td>
		 <td><a href=\"change_username.php\">Change Username</a></td>
		 </tr>
		 <tr>
		 <td><b>Password:</b></td><td>{$row['password']}...***</td>
		 <td><a href=\"change_password.php\">Change Password</a></td>
		 </tr>
		 <tr>
		 <td><b>First Name:</b></td><td>{$row['first_name']}</td>
		 <td><a href=\"change_first.php\">Edit First Name</a></td>
		 </tr>
		 <tr>
		 <td><b>Middle Name:</b></td><td>{$row['middle_name']}</td>
		 <td><a href=\"change_middle.php\">Edit Middle Name</a></td>
		 </tr>
		 <tr>
		 <td><b>Last Name:</b></td><td>{$row['last_name']}</td>
		 <td><a href=\"change_last.php\">Edit Last Name</a></td>
		 </tr>
		 </table>";
		 
		}
		
		mysqli_close($dbc);
		
	} else {
		
		echo 'An Error occoured, The system could not Load your Settings, Logout and Login again to Retry.';
		
	} // End of num_rows IF
	
} // End of is submitted IF.

include ('includes/footer.html');

echo '</div>';

exit; 

?>
		 
		 