<?php # register.php

// This is the registration page for this site.


require_once ('includes/config.inc.php');

$script = 'register.php';

if (isset($_GET['host'])) {
				
	$host = $_GET['host'];
				
			} else {
				
				$host = 'stories.php';
				
			}

$page_title = 'Register';

include ('includes/header.html');

if (isset($_SESSION['first_name'])) {

 $_SESSION = array(); // Destroy the variables.


	session_destroy(); // Destroy the session itself
 
	setcookie (session_name(), '', time()-300);  
	
	// Destroy the cookie.
	
	$url = BASE_URL . 'register.php'; // Define the URL.
 
 
 ob_end_clean(); // Delete the buffer.

 header("Location: $url");

 exit(); // Quit the script.

 }



if (isset($_POST['submitted'])) {
	
	// Handle the form:
	
	require_once (MYSQL);
	
	$dbc=$dba;	
		
	
	// Trim all the incoming data:
	
	$trimmed = array_map('trim', $_POST);
	
	// Assume invalid values:
	
	$c = $fn = $ln = $nn = $e = $p = FALSE;
	
	
	// Check for a country:
	
if ($trimmed['country']) {
	
	$c = mysqli_real_escape_string ($dbc, $trimmed['country']);
	
	
} else {
	
	$wc='<p class="error">Please enter a valid name of Country</p>';
	
}
	
	// Check for a first_name:
	
if (preg_match ('/^[A-Z\'.-]{2,20}$/i', $trimmed['first_name'])) {
	
	$fn = mysqli_real_escape_string ($dbc, $trimmed['first_name']);
	
	
} else {
	
	$wf= '<p class="error">Please enter your first name!</p>';
	
}


// Check for a last name:

if(preg_match ('/^[A-Z \'.-]{2,40}$/i', $trimmed['last_name'])) {
	
	$ln = mysqli_real_escape_string ($dbc, $trimmed['last_name']);
	
} else {
	
	$wl= '<p class="error">Please enter your last name!</p>';
	
}

// Check for a Nick Name:

if (preg_match ('/^\w{2,20}$/', $trimmed['nick_name'])) {
	
	$nn = mysqli_real_escape_string ($dbc, $trimmed['nick_name']);
	
	$q = "SELECT user_id FROM users WHERE nick_name='$nn'";
	
	$r = mysqli_query ($dbc, $q) or 
	
	trigger_error("Query: $q\n<br />MySQL Error: ". mysqli_error($dbc));
	
	if (mysqli_num_rows($r) > 0 ){
		
		$nn = FALSE;
		
		$wn= '<p class="error">Username has been taken, Try another one.</p>';
		
	} else {

	$gn=TRUE;

}
		
		
} else {
	
	$wu ='<p class="error">Username must contain alphanumerics and underscores only.</p>';
	
}


// Check for an email address:


if (preg_match ('/^[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}$/', $trimmed['email'])) {
	
	$e = mysqli_real_escape_string ($dbc, $trimmed['email']);
	
	// Make sure the email address is available:
	
	$q = "SELECT user_id FROM users WHERE email='$e'";
	
	$r = mysqli_query ($dbc, $q) or 
	
	trigger_error("Query: $q\n<br />MySQL Error: ". mysqli_error($dbc));
	
	if (mysqli_num_rows($r) > 0 ){
		
		// Not Available
		
		$we = '<p class="error">email has already been registered, Retrieve your password if you forgot it.</p>'; 
	
	} else {

	$ge=TRUE;


}
	
} else {
	
	$ie= '<p class="error">Please enter a valid email address!</p>';
	
}

// Check for a password and match against the confirmed password:

if (preg_match ('/^\w{6,20}$/', $trimmed['password1'])) {
	
	if ($trimmed['password1'] == $trimmed['password2']) {
		
		$p = mysqli_real_escape_string ($dbc, $trimmed['password1']);
		
	} else {
		
		$wp= '<p class="error">Your password did not match the confirmed 
		
		password!</p>';
		
	}
	
}else {
	
	$ip = '<p class="error">Please enter a valid password!</p>';
	
}

if ($c && $fn && $ln && $nn && $e && $p && $gn && $ge) {
	
	// Everything is Ok...
	
	
	
	// Create the activation code:
	
	$a = md5(uniqid(rand(), true));
	
	$key = 2017;
	
	// Add the user to the database:
	
	$q = "INSERT INTO users (reg_date, user_key, country, email, nick_name, user_pass, password, first_name, last_name, active) 
	
	VALUES (UTC_TIMESTAMP(), $key, '$c', '$e', '$nn', SHA1('$p'), '$p', '$fn', '$ln', '$a' )";
	
	$r = mysqli_query ($dbc, $q) or
	
	trigger_error("Query: $q\n<br />MySQL Error: ". mysqli_error($dbc));
	
	if (mysqli_affected_rows($dbc) == 1) {
		
		// If it ran OK.
		
		// Send the email:
		
		$body = "Thank you for registering at jokesterbox.com.
		
		To verify your account, please click on this link:\n\n";
		
		$body .= "<p><a href=\"";
		$body .=  BASE_URL . 'activate.php?x='.urlencode($e). "&y=$a";
		$body .= "\">This is your activation Link</a></p>";

		/* This will be  used on a Live Site:
		
		mail($trimmed['email'], 'Registration Confirmation', $body, 
		
		'From: admin@sitename.com');
		
		*/
		
		// Finish the page:
		
		echo '<h3 class="success">Thank you for registering at Jokesterbox! A confirmation email has been sent to your Email address.
		
		Please click on the link in that email in order to verify your 
		account.</h3>';
		
		echo "<p>$body</p>";
		
		include  ('includes/footer.html'); 
		
		// Include the Footer

// Query the database again to log the user in:
	
	
	$q = "SELECT user_id, user_key, first_name, country, user_level FROM users 
	
		WHERE (email='$e' AND user_pass=SHA1('$p'))";
		
		$r = mysqli_query ($dbc, $q) or 
		
		trigger_error ("Querry: $q\n<br />MySQL 
		
		Error: " . mysqli_error($dbc));
		
		if (@mysqli_num_rows ($r) == 1) {
			
			// A match was made.
			
			// Register the values and redirect.
			
			$_SESSION = mysqli_fetch_array($r, MYSQLI_ASSOC);
			
			$expire = time() + 360000;
			
			$_SESSION['expire'] =  $expire;
			
			$timeout = time() + 14200;
			
			$_SESSION['timeout'] = $timeout;
			
			$_SESSION['agent'] = 
			
			md5($_SERVER['HTTP_USER_AGENT']);
			
		
			$url = BASE_URL; 
			
			$url .= "$host"; //Define the host
			
			mysqli_free_result($r);
			
			mysqli_close($dbc);
			
			ob_end_clean(); // Delete the buffer
			
			header("Location: $url");
			
		exit(); // Stop the script here.
		
		} 
	   exit();	
	} else { // If it did not run OK.
	
	$cr = '<p class="errors">You could not be registered due to a system error. 
	
	We apologize for any inconvenience.</p>';
	
	}
	

} else { // If one of the data tests failed.

$wd= '<p class="error">Please re-enter your passwords and try again.</p>';

}

mysqli_close($dbc);

} // End of the main Submit conditional.



?>


<div class="form" align="center">
<h1>Sign-up</h1>
<p><b>Share the funny videos on your device on jokesterbox.</b></p>

<?php if(isset($cr)) echo $cr; 
	if(isset($wd)) echo $wd;
?>

<form action="register.php" method="post">

<fieldset>

<p><b>Country<br /></b>
<select name="country"><option value="Nigeria">Nigeria</option>
 
<?php 
foreach ($countries as $mycountry) {

if (isset($_POST['country']) && $_POST['country']==$mycountry ){
$selected =  'selected=\"selected\"';
}else {
$selected = NULL;
}
echo "<option value= \"$mycountry\" $selected>$mycountry</option>";
}	
?>
</select></p>

<?php if(isset($wc)) echo $wc; ?>

<p><b>First Name<br /></b> <input type="text" name="first_name" 

size="20" maxlength="20" value="<?php if (isset($trimmed['first_name']))
	
echo $trimmed['first_name']; ?>" /></p>

<?php if(isset($wf)) echo $wf; ?>
<p><b>Last Name<br /> </b> <input type="text" name="last_name"

 size="20" maxlength="40" value="<?php if (isset($trimmed['last_name'])) 
	 
 echo $trimmed['last_name']; ?>" /></p>
 <?php if(isset($wl)) echo $wl; ?>
 
 <p><b>Username<br />  </b> <input type="text" name="nick_name"

 onchange="checkUser(this.value)" size="20" maxlength="40" value="<?php if (isset($trimmed['nick_name'])) 
	 
 echo $trimmed['nick_name']; ?>" /></p>
 <p id="ucheck"></p>
 <?php if(isset($wu)) echo $wu; ?>
 <p><b>Email Address<br /> </b> <input type="text" name="email" onchange="checkMail(this.value)" 
 
 size="20" maxlength="80" value="<?php if (isset($trimmed['email'])) 
	 
 echo $trimmed['email']; ?> "/> </p>
 <p id="mcheck"><p>
 <?php if(isset($we)) echo $we; ?>
	<?php if(isset($ie)) echo $ie; ?>
 
 <p><b>Password<br /> </b> <input type="password" name="password1" 
 
 size="20" maxlength="20" /><br /></p>
 <?php if(isset($wp)) echo $wp; ?>
 <?php if(isset($ip)) echo $ip; ?>
 <p><b>Confirm Password<br /> </b><input type="password" name="password2" 
 
 size="20" maxlength="20" /></p> 
 <small>by registering, you accept our</small>
 <p id="privacy"><a target="_blank" href="terms.php">Terms and Policies</a></p>
 
 <div align="center"><input class="submit" type="submit" name="submit" value="Register" /><div>
 
 <input type="hidden" name="submitted" value="TRUE" />
 
 </fieldset>
 </form>
 <p><a href="login.php" title="login">I aready have an account.</a></p>
 </div>
 
 <?php // Include the HTML footer.
  echo'<div align="left" id="footer"> &copy; 2017 Jokesterbox'; 
 include ('includes/footer.html');
 
 ?>