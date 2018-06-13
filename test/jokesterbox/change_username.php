<?php # Script 16.11 - change_password.php


 // This page allows a logged-in user to change their username.

require_once ('includes/config.inc.php');


 $page_title = 'Edit Your Username';
 
 $script = 'settings.php';
 
 $host = 'change_username.php';

 include ('includes/header.html');
 
 // validate session
 
 jb_validate_session($host);

// If no first_name session variable exists, redirect the user:


 if (!isset($_SESSION['first_name'])) {

 $url = BASE_URL . 'login.php?host=';

 $url .= "$host"; // Define the URL.
 
 
 ob_end_clean(); // Delete the buffer.

 header("Location: $url");

 exit(); // Quit the script.

 }

 // Check for existing username
 
require_once(MYSQL);

$dbc=$dba;

 
 
 $q = "SELECT nick_name
	
	FROM users 
	
	WHERE  user_id={$_SESSION['user_id']}";
		
		
	$r = mysqli_query ($dbc, $q) or 
	
	trigger_error("Query: $q\n<br />MySQL Error: ". mysqli_error($dbc));
	
	if (@mysqli_num_rows($r) == 1 ){
		
		// About me retrieved, assign it to a variable
		
		while ($row = mysqli_fetch_array ($r, MYSQLI_ASSOC)) {
			
			$enn = $row['nick_name'];
		}
	}
 
 if (isset($_POST['submitted'])) {
 

 // Assume nothing 
 
 $trimmed = array_map('trim', $_POST);
 
 $nn = FALSE;
 
 	// Check for a username:
	
if (preg_match ('/^\w{2,20}$/', $trimmed['nick_name'])) {
	
	$nn = mysqli_real_escape_string ($dbc, $trimmed['nick_name']);
	
$q = "SELECT user_id FROM users WHERE nick_name='$nn'";
	
	$r = mysqli_query ($dbc, $q) or 
	
	trigger_error("Query: $q\n<br />MySQL Error: ". mysqli_error($dbc));
	
	if (mysqli_num_rows($r) > 0 ){
		
		$nn = FALSE;
		
		$wn= '<p class="error">Username has been taken, Try another one.</p>';
		
	}	
	
} else {
	
	$wn = '<p class="error">Enter a valid  username.</p>';
	
	$nn = FALSE;
}

 if ($nn) { // If everything's OK.

 // Make the query.
 
 $q = "UPDATE users SET nick_name='$nn'

 WHERE user_id={$_SESSION['user_id']} LIMIT 1";
 
 $r = mysqli_query ($dbc, $q) or
 
trigger_error("Query: $q\n<br />MySQLError: "

 . mysqli_error($dbc));
 
 if (mysqli_affected_rows($dbc) == 1) {

 // If it ran OK.

 // Send an email, if desired.

 echo '<h3>Your username has been updated.</h3>';

  mysqli_close($dbc); // Close the database connection.

  include ('includes/footer.html'); //Include the HTML footer.
  
  $url = BASE_URL . 'settings.php?updated=true'; // Define the URL.
 
 
 ob_end_clean(); // Delete the buffer.

 header("Location: $url");

  exit();
  
 } else { // If it did not run OK.

 $retype = '<p class="error">No update was made, clear, retype and try again.</p>';

 }

 } else { // Failed the validation test.
$retry = '<p class="error">Please try again.</p>';
 }

mysqli_close($dbc); // Close the database connection.

 } // End of the main Submit conditional.

 ?>
 <div class="form" align="center">

 <h1>Edit Username</h1>

 <form action="change_username.php" method="post">
 
 <fieldset>
 
 <?php if(isset($wn)) echo $wn; 
 
 if(isset($retry)) echo $retry; 
 
 if(isset($retype)) echo $retype;?>

<p><b>Username</b><br /><input type="text" name="nick_name" size="25" maxlength="20" value="

<?php if (isset($enn))
echo $enn; ?>"/></p>

 <div align="center"><input class="submit" type="submit" name="submit" 
 value="Change" /></div>
 <input type="hidden" name="submitted" value="TRUE" />
 
 </fieldset>

 </form>
</div>
 <?php
 include ('includes/footer.html');
 ?>