<?php # Script 16.11 - change_password.php


 // This page allows a logged-in user to change their password.

require_once ('includes/config.inc.php');


 $page_title = 'Change Your Password';
 
 $script = 'settings.php';
 
 $host = 'change_password.php';

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

 if (isset($_POST['submitted'])) {

require_once(MYSQL);

$dbc=$dba;


 // Check for a new password and match against the confirmed password:
 
 $p = FALSE;
 
 if (preg_match ('/^(\w){4,20}$/', $_POST['password1']) ) {
	 
 if ($_POST['password1'] == $_POST['password2']) {
	 
 $p = mysqli_real_escape_string($dbc, $_POST['password1']);

 } else {

 echo '<p class="error">Your password did not match the 
 
 confirmed password!</p>';

 }

 } else {

 
$wp = '<p class="error">Enter a
valid password!</p>';

 }

 if ($p) { // If everything's OK.

 // Make the query.
 
 $q = "UPDATE users SET user_pass=SHA1('$p'), password='$p'

 WHERE user_id={$_SESSION['user_id']} LIMIT 1";
 
 $r = mysqli_query ($dbc, $q) or
 
trigger_error("Query: $q\n<br />MySQLError: "

 . mysqli_error($dbc));
 
 if (mysqli_affected_rows($dbc) == 1) {

 // If it ran OK.

 // Send an email, if desired.

 echo '<h3>Your password has been changed.</h3>';

  mysqli_close($dbc); // Close the database connection.

  include ('includes/footer.html'); //Include the HTML footer.
  
  $url = BASE_URL . 'settings.php?updated=true'; // Define the URL.
 
 
 ob_end_clean(); // Delete the buffer.

 header("Location: $url");

  exit();

 } else { // If it did not run OK.

 $retype = '<p class="error">Your password
was not changed, retype and try again.</p>';

 }

 } else { // Failed the validation test.

 $retry = '<p class="error">Please try again.</p>';
 }

mysqli_close($dbc); // Close the database connection.

 } // End of the main Submit conditional.

 ?>
 <div class="form" align="center">

 <h1>Change Password</h1>

 <form action="change_password.php" method="post">
 
 <fieldset>
 
<?php if(isset($wp)) echo $wp; 
 
 if(isset($retry)) echo $retry;
if(isset($retype)) echo $retype; ?>

 <p><b>New Password</b><br /> <input type="password" 
 
 name="password1" size="20"maxlength="20" /> </p>
 <p><b>Confirm New Password</b><br /> <input type="password" 
 
 name="password2" size="20" maxlength="20" /></p>
 

 <div align="center"><input class="submit" type="submit" name="submit" 
 value="Change" /></div>
 <input type="hidden" name="submitted" value="TRUE" />
 
 </fieldset>

 </form>
</div>
 <?php
 include ('includes/footer.html');
 ?>