<?php # - login.php

// This is the login page for this site.


require_once ('includes/config.inc.php');

$page_title = 'Login';

$script = 'login.php';

if (isset($_GET['host'])) {
				
	$host = $_GET['host'];
				
			} else {
				
				$host = 'stories.php';
				
			}

include ('includes/header.html');

if (isset($_SESSION['first_name'])) {

 $_SESSION = array(); // Destroy the variables.


	session_destroy(); // Destroy the session itself
 
	setcookie (session_name(), '', time()-300);  
	
	// Destroy the cookie.
	
	$url = BASE_URL . 'login.php'; // Define the URL.
 
 
 ob_end_clean(); // Delete the buffer.

 header("Location: $url");

 exit(); // Quit the script.

 }

if (isset($_POST['submitted'])) {
	if (isset($_POST['host'])) {
	$host = $_POST['host'];
	}
 
require_once(MYSQL); 

$slave=FALSE;
if($slave){
	$x=rand(1,1);
	if ($x==1) {
	$dbc=$dba1;
	}else {
	$dbc=$dba;	
	}
} else {
$dbc=$dba;
}
	
	// Validate the email address:
	
	if (!empty($_POST['email'])) {
		
		$e = mysqli_real_escape_string  ($dbc, $_POST['email']);
		
	} else {
		
		$e = FALSE;
		
		$we= ' <p class="error">You forgot to enter your email address!</p>';
		
	}
	
	// Validate the password:
	
	if(!empty($_POST['pass'])) {
		
		$p = mysqli_real_escape_string($dbc, $_POST['pass']);
		
	} else {
		
		$p = FALSE;
		
		$wp= '<p class="error">You forgot to enter your password!</p>';
		
	}
	
	if ($e && $p) { // If everything is OK.
	
	
	$url = BASE_URL;
	
	$url .= "$host"; // Define the URL
	
	
	// Query the database:
	
	
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
			
			$timeout = time() + 28400;
			
			$_SESSION['timeout'] = $timeout;
			
			$_SESSION['agent'] = 
			
			md5($_SERVER['HTTP_USER_AGENT']);
			
			
			
			mysqli_free_result($r);
			
			mysqli_close($dbc);
			
			ob_end_clean(); // Delete the buffer
			
			header("Location: $url");
			
			exit(); // Quit the script.
			
			
			
		} else {
			
			// Maybe its a username:
			
			$q = "SELECT user_id, user_key, first_name, country, user_level FROM users 
	
		WHERE (nick_name='$e' AND user_pass=SHA1('$p'))";
		
		$r = mysqli_query ($dbc, $q) or 
		
		trigger_error ("Querry: $q\n<br />MySQL 
		
		Error: " . mysqli_error($dbc));
		
		if (@mysqli_num_rows ($r) == 1) {
			
			// A match was made.
			
			// Register the values and redirect.
			
			$_SESSION = mysqli_fetch_array($r, MYSQLI_ASSOC);
			
			$expire = time() + 360000;
			
			$_SESSION['expire'] =  $expire;
			
			$timeout = time() + 28400;
			
			$_SESSION['timeout'] = $timeout;
			
			$_SESSION['agent'] = 
			
			md5($_SERVER['HTTP_USER_AGENT']);
			
			
			
			mysqli_free_result($r);
			
			mysqli_close($dbc);
			
			
			ob_end_clean(); // Delete the buffer
			
			header("Location: $url");
			
			exit(); // Quit the script.
			
			
		} else {
			
			// Maybe its a phone
			
			$q = "SELECT user_id, user_key, first_name, country, user_level FROM users 
	
		WHERE (phone='$e' AND user_pass=SHA1('$p'))";
		
		$r = mysqli_query ($dbc, $q) or 
		
		trigger_error ("Querry: $q\n<br />MySQL 
		
		Error: " . mysqli_error($dbc));
		
		if (@mysqli_num_rows ($r) == 1) {
			
			// A match was made.
			
			// Register the values and redirect.
			
			$_SESSION = mysqli_fetch_array($r, MYSQLI_ASSOC);
			
			$expire = time() + 360000;
			
			$_SESSION['expire'] =  $expire;
			
			$timeout = time() + 28400;
			
			$_SESSION['timeout'] = $timeout;
			
			$_SESSION['agent'] = 
			
			md5($_SERVER['HTTP_USER_AGENT']);
			
			
			
			mysqli_free_result($r);
			
			mysqli_close($dbc);
			
			
			ob_end_clean(); // Delete the buffer
			
			header("Location: $url");
			
			exit(); // Quit the script.
			
			
}  else {
			
			// No Match was made.
			
			$wm = '<p class="error">Invalid email address or password. 
			
					Create a new account or retrieve your password if you lost it.</p>';
					
		}}}
		
} else {
		
		// if everything wasn't OK.
		
	$wd= '<p class="error">Please try again.</p>';

	}

mysqli_close ($dbc);

} // End of Submit conditional.

if (isset($_GET['logout'])) {
	
	echo '<h3 class="notice"></br> Login to continue...</h3>';
	
}

?>

	<div class="form" align="center">
	
	<h1>Login</h1>
	
	<p><b>Share the funny videos on your device on jokesterbox.</b></p>
	
	<form action ="login.php" method="post">
	
	<fieldset>
	<?php if(isset($wd)) echo $wd; ?>
	<?php if(isset($wm)) echo $wm; ?>
	<p><b>Email or Username<br /></b> <input type="text" name="email" size="20" maxlength="40" 
	
	value="<?php if (isset($_POST['email'])) 
		
	echo $_POST['email']; ?>"  /></p>
	<?php if(isset($we)) echo $we; ?>
	<p><b>Password<br /></b><input type="password" name="pass" size="20" maxlength="20" /></p>
	<?php if(isset($wp)) echo $wp; ?>
	<div align="center"><input class="submit" type="submit" name="submit" value="Login" /></div>
	
	<input type="hidden" name="host" 
	
	
	value="<?php echo $host; ?>" />
	
	<input type="hidden" name="submitted" value="TRUE" />
	
	</fieldset>
	
	</form>
	<p><a href="register.php" title="login">Register a new Account.</a></p>
	
	<p><a href="register.php" title="login">I lost my Password.</a></p>
	</div>
	
	<?php // Include the html footer
	echo'<div align="center" id="footer"> &copy; 2017 Jokesterbox';
	include ('includes/footer.html');
	
	?>