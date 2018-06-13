<?php # Script 16.11 - change_password.php


 // This page allows a logged-in user to change their phone number.

require_once ('includes/config.inc.php');


 $page_title = 'Edit Your Phone Number';
 
 $script = 'settings.php';
 
 $host = 'change_phone.php';

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

 
 
 $q = "SELECT phone
	
	FROM users 
	
	WHERE  user_id={$_SESSION['user_id']}";
		
		
	$r = mysqli_query ($dbc, $q) or 
	
	trigger_error("Query: $q\n<br />MySQL Error: ". mysqli_error($dbc));
	
	if (@mysqli_num_rows($r) == 1 ){
		
		// About me retrieved, assign it to a variable
		
		while ($row = mysqli_fetch_array ($r, MYSQLI_ASSOC)) {
			
			$eph = $row['phone'];
		}
	}
 
 if (isset($_POST['submitted'])) {
 

 // Assume nothing 
 
 $trimmed = array_map('trim', $_POST);
 
 $ph = FALSE;
 
 	// Check for a phone number:
	
if (is_numeric ($trimmed['phone_number'])) {
	
	if (strlen($trimmed['phone_number']) < 10 
	OR
	(strlen($trimmed['phone_number']) > 11)){
		$wph = '<p class="error">Invalid phone number.<br />Ignore country codes.</p>';
	} else {
	
	$ph = mysqli_real_escape_string ($dbc, $trimmed['phone_number']);
	
	$q = "SELECT user_id FROM users WHERE phone='$ph'";
	
	$r = mysqli_query ($dbc, $q) or 
	
	trigger_error("Query: $q\n<br />MySQL Error: ". mysqli_error($dbc));
	
	if (mysqli_num_rows($r) > 0 ){
		
		$ph = FALSE;
		
		$wph= '<p class="error">Phone Number has been taken, Try another one.</p>';
		
	}
	}
	
} else {
	
	$wph = '<p class="error">Phone Number must be numeric.</p>';
	$ph = FALSE;
}

 if ($ph) { // If everything's OK.

 // Make the query.
 
 $q = "UPDATE users SET phone='$ph'

 WHERE user_id={$_SESSION['user_id']} LIMIT 1";
 
 $r = mysqli_query ($dbc, $q) or
 
trigger_error("Query: $q\n<br />MySQLError: "

 . mysqli_error($dbc));
 
 if (mysqli_affected_rows($dbc) == 1) {

 // If it ran OK.
 // Check if number exist in phones table
 
 $q = "SELECT phone_id FROM phones 
 
 WHERE number=$ph and author_id={$_SESSION['user_id']}";
 
 $r = mysqli_query ($dbc, $q) or
 
trigger_error("Query: $q\n<br />MySQLError: "

 . mysqli_error($dbc));
 
 if (mysqli_num_rows($r) == 0) {
 
 // Make another query to the phones table. 
 
 $q = "INSERT INTO phones (author_id, number, phone_date) 
	
	VALUES ('{$_SESSION['user_id']}', '$ph', UTC_TIMESTAMP() )";
 
 $r = mysqli_query ($dbc, $q) or
 
trigger_error("Query: $q\n<br />MySQLError: "

 . mysqli_error($dbc));
 
 }
 

 // Send an email, if desired.

 echo '<h3>Your phone number has been updated.</h3>';

  mysqli_close($dbc); // Close the database connection.

  include ('includes/footer.html'); //Include the HTML footer.
  
  $url = BASE_URL . 'settings.php?updated=true'; // Define the URL.
 
 
 ob_end_clean(); // Delete the buffer.

 header("Location: $url");

  exit();
  
 } else { // If it did not run OK.

 $retype = '<p class="error">No update was made, clear, retype and try again.</p>';

 } // End of main affected IF.

 } else { // Failed the validation test.
$retry = '<p class="error">Please try again.</p>';
 }

mysqli_close($dbc); // Close the database connection.

 } // End of the main Submit conditional.

 ?>
 <div class="form" align="center">

 <h1>Edit Phone Number</h1>

 <form action="change_phone.php" method="post">
 
 <fieldset>
 
 <?php if(isset($wph)) echo $wph; 
 
 if(isset($retry)) echo $retry; 
 
 if(isset($retype)) echo $retype;?>

<p><b>Phone Number</b><br /><input type="text" name="phone_number" size="15" maxlength="15" value="

<?php if (isset($eph))
echo $eph; ?>"/></p>

 <div align="center"><input class="submit" type="submit" name="submit" 
 value="Change" /></div>
 <input type="hidden" name="submitted" value="TRUE" />
 
 </fieldset>

 </form>
</div>
 <?php
 include ('includes/footer.html');
 ?>