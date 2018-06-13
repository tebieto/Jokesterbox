<?php # Script 16.11 - change_password.php


 // This page allows a logged-in user to change their about me.

require_once ('includes/config.inc.php');


 $page_title = 'Edit Your About Me';
 
 $script = 'settings.php';
 
 $host = 'change_about.php';

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

 // Check for existing about me
 
require_once(MYSQL);

$dbc=$dba;

 
 
 $q = "SELECT about_me
	
	FROM users 
	
	WHERE  user_id={$_SESSION['user_id']}";
		
		
	$r = mysqli_query ($dbc, $q) or 
	
	trigger_error("Query: $q\n<br />MySQL Error: ". mysqli_error($dbc));
	
	if (@mysqli_num_rows($r) == 1 ){
		
		// About me retrieved, assign it to a variable
		
		while ($row = mysqli_fetch_array ($r, MYSQLI_ASSOC)) {
			
			$eam = $row['about_me'];
		}
	}
 
 if (isset($_POST['submitted'])) {
 

 // Assume nothing 
 
 $am = FALSE;
 
 // Check for about me:
  
  if(!empty($_POST['about'])) {
	 if (strlen($_POST['about']) < 100) {
	 
 $am = mysqli_real_escape_string($dbc, trim($_POST['about']));
 
 $am = htmlentities($am);

	 } else { $wam= '<p class="error">Your about me 
	 is too long.</p>';
	 }
  }else {
	  $am = NULL;
  }

 if (!isset($wam)) { // If everything's OK.

 // Make the query.
 
 $q = "UPDATE users SET about_me='$am'

 WHERE user_id={$_SESSION['user_id']} LIMIT 1";
 
 $r = mysqli_query ($dbc, $q) or
 
trigger_error("Query: $q\n<br />MySQLError: "

 . mysqli_error($dbc));
 
 if ((mysqli_affected_rows($dbc) == 1) 
	 OR mysqli_affected_rows($dbc) == 0) {

 // If it ran OK.

 // Send an email, if desired.

 echo '<h3>Your about me has been updated.</h3>';

  mysqli_close($dbc); // Close the database connection.

  include ('includes/footer.html'); //Include the HTML footer.
  
  $url = BASE_URL . 'settings.php?updated=true'; // Define the URL.
 
 
 ob_end_clean(); // Delete the buffer.

 header("Location: $url");

  exit();

 } else { // If it did not run OK.

 $retype = '<p class="error">No update was made, clear, re-type and try again.</p>';

 }

 } else { // Failed the validation test.
$retry = '<p class="error">Please try again.</p>';
 }

mysqli_close($dbc); // Close the database connection.

 } // End of the main Submit conditional.

 ?>
 <div class="form" align="center">

 <h1>Edit About Me</h1>

 <form id="about" action="change_about.php" method="post">
 
 <fieldset>
 
 <?php if(isset($wam)) echo $wam; 
 
 if(isset($retry)) echo $retry;
if(isset($retype)) echo $retype; ?>

<p><b>About Me</b><br /> <textarea name="about" cols="25" rows="5">

<?php if (isset($eam))
echo $eam; ?></textarea></p>
 

 <div align="center"><input class="submit" type="submit" name="submit" 
 value="Edit" /></div>
 <input type="hidden" name="submitted" value="TRUE" />
 
 </fieldset>

 </form>
</div>
 <?php
 include ('includes/footer.html');
 ?>