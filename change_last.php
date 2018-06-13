<?php # Script 16.11 - change_password.php


 // This page allows a logged-in user to change their last_name.

require_once ('includes/config.inc.php');


 $page_title = 'Edit Your Last Name';
 
 $script = 'settings.php';
 
 $host = 'change_last.php';

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

 // Check for existing last_name
 
require_once(MYSQL);

$dbc=$dba;

 
 $q = "SELECT last_name
	
	FROM users 
	
	WHERE  user_id={$_SESSION['user_id']}";
		
		
	$r = mysqli_query ($dbc, $q) or 
	
	trigger_error("Query: $q\n<br />MySQL Error: ". mysqli_error($dbc));
	
	if (@mysqli_num_rows($r) == 1 ){
		
		// About me retrieved, assign it to a variable
		
		while ($row = mysqli_fetch_array ($r, MYSQLI_ASSOC)) {
			
			$eln = $row['last_name'];
		}
	}
 
 if (isset($_POST['submitted'])) {
 

 // Assume nothing 
 
 $trimmed = array_map('trim', $_POST);
 
 $ln = FALSE;
 
 	// Check for a last_name:
	
if (preg_match ('/^[A-Z\'.-]{2,40}$/i', $trimmed['last_name'])) {
	
	$ln = mysqli_real_escape_string ($dbc, $trimmed['last_name']);
	
	
} else {
	
	$wln = '<p class="error">Enter a valid Last Name.</p>';
	
}

 if ($ln) { // If everything's OK.

 // Make the query.
 
 $q = "UPDATE users SET last_name='$ln'

 WHERE user_id={$_SESSION['user_id']} LIMIT 1";
 
 $r = mysqli_query ($dbc, $q) or
 
trigger_error("Query: $q\n<br />MySQLError: "

 . mysqli_error($dbc));
 
 if (mysqli_affected_rows($dbc) == 1) {

 // If it ran OK.

 // Send an email, if desired.

 echo '<h3>Your last name has been updated.</h3>';

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

 <h1>Edit Last Name</h1>

 <form action="change_last.php" method="post">
 
 <fieldset>
 
 <?php if(isset($wln)) echo $wln; 
 
 if(isset($retry)) echo $retry; 
 
 if(isset($retype)) echo $retype;?>

<p><b>Last Name</b><br /><input type="text" name="last_name" size="25" maxlength="40" value="

<?php if (isset($eln))
echo $eln; ?>"/></p>

 <div align="center"><input class="submit" type="submit" name="submit" 
 value="Edit" /></div>
 <input type="hidden" name="submitted" value="TRUE" />
 
 </fieldset>

 </form>
</div>
 <?php
 include ('includes/footer.html');
 ?>