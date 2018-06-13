<?php # Script 16.11 - change_password.php


 // This page allows a logged-in user to change their email address.

require_once ('includes/config.inc.php');


 $page_title = 'Change Your Email Address';
 
 $script = 'settings.php';
 
 $host = 'change_email.php';

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

 // Check for existing email
 
require_once(MYSQL);

$dbc=$dba;

 
 
 $q = "SELECT email
	
	FROM users 
	
	WHERE  user_id={$_SESSION['user_id']}";
		
		
	$r = mysqli_query ($dbc, $q) or 
	
	trigger_error("Query: $q\n<br />MySQL Error: ". mysqli_error($dbc));
	
	if (@mysqli_num_rows($r) == 1 ){
		
		// About me retrieved, assign it to a variable
		
		while ($row = mysqli_fetch_array ($r, MYSQLI_ASSOC)) {
			
			$ee = $row['email'];
		}
	}
 
 if (isset($_POST['submitted'])) {
 

 // Assume nothing 
 
 $trimmed = array_map('trim', $_POST);
 
 $e = FALSE;
 
 	// Check for an email address:
	
if (preg_match ('/^[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}$/', $trimmed['email'])) {
	
	$e = mysqli_real_escape_string ($dbc, $trimmed['email']);
	
	$q = "SELECT user_id FROM users WHERE email='$e'";
	
	$r = mysqli_query ($dbc, $q) or 
	
	trigger_error("Query: $q\n<br />MySQL Error: ". mysqli_error($dbc));
	
	if (mysqli_num_rows($r) > 0 ){
		
		$e = FALSE;
		
		$we= '<p class="error">Email address has been taken, Try another one.</p>';
		
	}
} else {
	
	$we = '<p class="error">Enter a valid  email address.</p>';
	$e = FALSE;
}

 if ($e) { // If everything's OK.
 
 // Create the activation code:
	
	$a = md5(uniqid(rand(), true));

 // Make the query.
 
 $q = "UPDATE users SET email='$e'

 WHERE user_id={$_SESSION['user_id']} LIMIT 1";
 
 $r = mysqli_query ($dbc, $q) or
 
trigger_error("Query: $q\n<br />MySQLError: "

 . mysqli_error($dbc));
 
 if (mysqli_affected_rows($dbc) == 1) {

 // If it ran OK.
 
 // add email and activation code again.
 
 $q = "UPDATE users SET email='$e', active='$a'

 WHERE user_id={$_SESSION['user_id']} LIMIT 1";
 
 $r = mysqli_query ($dbc, $q) or
 
trigger_error("Query: $q\n<br />MySQLError: "

 . mysqli_error($dbc));
 
 if (mysqli_affected_rows($dbc) == 1) {
 

 // Send an email, if desired.

 echo '<h3>Your email has been updated.</h3>';

  mysqli_close($dbc); // Close the database connection.

  include ('includes/footer.html'); //Include the HTML footer.
  
  $url = BASE_URL . 'settings.php?updated=true'; // Define the URL.
 
 
 ob_end_clean(); // Delete the buffer.

 header("Location: $url");

  exit();
  
 }

 } else { // If first IF did not run OK.

 $retype = '<p class="error">No update was made, clear, retype and try again.</p>';

 }

 } else { // Failed the validation test.
$retry = '<p class="error">Please try again.</p>';
 }

mysqli_close($dbc); // Close the database connection.

 } // End of the main Submit conditional.

 ?>
 <div class="form" align="center">

 <h1>Change Email Address</h1>

 <form action="change_email.php" method="post">
 
 <fieldset>
 
 <?php if(isset($we)) echo $we; 
 
 if(isset($retry)) echo $retry; 
 
 if(isset($retype)) echo $retype;?>

<p><b>Email Address</b><br /><input type="text" name="email" size="25" maxlength="60" value="

<?php if (isset($ee))
echo $ee; ?>"/></p>

 <div align="center"><input class="submit" type="submit" name="submit" 
 value="Change" /></div>
 <input type="hidden" name="submitted" value="TRUE" />
 
 </fieldset>

 </form>
</div>
 <?php
 include ('includes/footer.html');
 ?>