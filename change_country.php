<?php # Script 16.11 - change_password.php


 // This page allows a logged-in user to change their country.

require_once ('includes/config.inc.php');


 $page_title = 'Change Your Country';
 
 $script = 'settings.php';
 
 $host = 'change_country.php';

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

 // Check for existing country
 
require_once(MYSQL);

$dbc=$dba;

 
 
 $q = "SELECT country
	
	FROM users 
	
	WHERE  user_id={$_SESSION['user_id']}";
		
		
	$r = mysqli_query ($dbc, $q) or 
	
	trigger_error("Query: $q\n<br />MySQL Error: ". mysqli_error($dbc));
	
	if (@mysqli_num_rows($r) == 1 ){
		
		// About me retrieved, assign it to a variable
		
		while ($row = mysqli_fetch_array ($r, MYSQLI_ASSOC)) {
			
			$ec = $row['country'];
		}
	}
 
 if (isset($_POST['submitted'])) {
 

 // Assume nothing 
 
 $trimmed = array_map('trim', $_POST);
 
 $c = FALSE;
 
 	// Check for a country:
	
if ($trimmed['country']) {
	
	$c = mysqli_real_escape_string ($dbc, $trimmed['country']);
	
	
} else {
	
	$wc = '<p class="error">Enter a valid country.</p>';
	
}

 if ($c) { // If everything's OK.

 // Make the query.
 
 $q = "UPDATE users SET country='$c'

 WHERE user_id={$_SESSION['user_id']} LIMIT 1";
 
 $r = mysqli_query ($dbc, $q) or
 
trigger_error("Query: $q\n<br />MySQLError: "

 . mysqli_error($dbc));
 
 if (mysqli_affected_rows($dbc) == 1) {

 // If it ran OK.

 // Send an email, if desired.

 echo '<h3>Your country has been updated.</h3>';

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

 <h1>Edit Country</h1>

 <form action="change_country.php" method="post">
 
 <fieldset>
 
 <?php if(isset($wc)) echo $wc; 
 
 if(isset($retry)) echo $retry; 
 
 if(isset($retype)) echo $retype;?>

<p><b>Country<br /></b>
<select name="country"><option>Choose Country</option>
 
<?php 
foreach ($countries as $mycountry) {

if (isset($ec) && $ec==$mycountry ){
$selected =  'selected=\"selected\"';
}else {
$selected = NULL;
}
echo "<option value= \"$mycountry\" $selected>$mycountry</option>";
}	
?>
</select></p>

 <div align="center"><input class="submit" type="submit" name="submit" 
 value="Change" /></div>
 <input type="hidden" name="submitted" value="TRUE" />
 
 </fieldset>

 </form>
</div>
 <?php
 include ('includes/footer.html');
 ?>