<?php # activate.php

// This page activates the user's account.


require_once ('includes/config.inc.php');

$page_title = 'Activate Your Account';

$script = 'activate.php';

$host = 'activate.php';

include ('includes/header.html');

// Validate $_GET['x'] and $_GET['y']:

$s = $x = $y = FALSE;

if (isset($_GET['x']) && preg_match ('/^[\w.-]+@[\w.-]+\.[A-Za-z]{2,6}$/', $_GET['x']) ) {
	
	$x = $_GET['x'];
	
}

if (isset ($_GET['y']) && (strlen($_GET['y'])) == 32 ) {
	
	$y = $_GET['y'];
	
}

if (isset ($_POST['submitted']) && (isset($_POST['email'])) ) {
	
	$s = TRUE;
	
}


// If $x, $y and $s aren't correct, redirect the user.

if (($x && $y) OR $s) {
	
	if (!isset($_SESSION['first_name'])) {

 $url = BASE_URL . 'login.php?redirected=1'; // Define the URL.
 
 
 ob_end_clean(); // Delete the buffer.

 header("Location: $url");

 exit(); // Quit the script.

 }
 require_once(MYSQL);
$slave=FALSE;
if($slave){
	$xx=rand(1,1);
	if ($xx==1) {
	$dbc=$dba1;
	}else {
	$dbc=$dba;	
	}
} else {
$dbc=$dba;
}

 if ($s) {
	 // Submitted resend form
	 
	 $q = "SELECT email, active FROM users 
	
		WHERE user_id={$_SESSION['user_id']}
		
		LIMIT 1";
		
		$r = mysqli_query ($dbc, $q) or 
		
		trigger_error ("Querry: $q\n<br />MySQL 
		
		Error: " . mysqli_error($dbc));
		
		if (@mysqli_num_rows ($r) == 1) {
			
			// A match was made.
			
	 while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
		 
	 // Send the email:
		
		$body = "Thank you for using jokesterbox.
		
		To verify your account, please click on this new link:\n\n";
		
		$body .= "<p><a href=\"";
		$body .=  BASE_URL . 'activate.php?x='.urlencode($row['email']). "&y={$row['active']}";
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
	 }	
		include  ('includes/footer.html'); 
		
		// Include the Footer

		exit (); // Quit the script
		
		} else { // No record
		
		$url = BASE_URL . 'login.php?r'; // Define the URL.
 
 
 ob_end_clean(); // Delete the buffer.

 header("Location: $url");

 exit(); // Quit the script.

		} // End of num_rows IF. 
		//we cant help the user any further
 }
if ($x && $y) {
	
	
	// Check if account has not been activated
	
	
	$q = "SELECT email, active FROM users 
	
		WHERE user_id={$_SESSION['user_id']}
		
		LIMIT 1";
		
		$r = mysqli_query ($dbc, $q) or 
		
		trigger_error ("Querry: $q\n<br />MySQL 
		
		Error: " . mysqli_error($dbc));
		
		if (@mysqli_num_rows ($r) == 1) {
			
			// A match was made.
			
	 while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
		 
	if (strlen($row['active']) == 32) {
	
		switchwrite($key);
		if ($key==2017) {
	require_once(MYSQL1);
	} elseif ($key==2018) {
		require_once(MYSQL2);
	}elseif ($key==2019) {
		require_once(MYSQL3);
	}
	
	// it has not been activated, Update table
	
	$q1 = "UPDATE users SET active=NULL 
	
	WHERE (user_id='{$_SESSION['user_id']}'
	
	AND  active='" .mysqli_real_escape_string($dbc, $y) ."'
	
	AND  email='" .mysqli_real_escape_string($dbc, $x) ."'
	)
	
	LIMIT 1";
	
	
	$r1 = mysqli_query ($dbc, $q1) or 
	
	trigger_error ("Query: $q\n<br />MySQL Error: " 
	
	.mysqli_error ($dbc));
	
	
	if (mysqli_affected_rows($dbc) == 1) {
		
		// it ran OK... Save email in emails table
		
		$x = mysqli_real_escape_string($dbc, $x) ;
		
		$q1 = "INSERT INTO emails (author_id, address, email_date) 
	
	VALUES ('{$_SESSION['user_id']}', '$x', UTC_TIMESTAMP() )";
 
 $r1 = mysqli_query ($dbc, $q1) or
 
trigger_error("Query: $q\n<br />MySQLError: "

 . mysqli_error($dbc));
 
		// Print a customized message:
		
		echo '<h3 class="success">Your account has been activated.</h3>';
		
		
	mysqli_close ($dbc); // close database connection
	
		$url = BASE_URL . 'settings.php?activated=true'; 
		
		// Define the URL.
 
 
 ob_end_clean(); // Delete the buffer.

 header("Location: $url");

 exit(); // Quit the script.

		
	} else { // If it did not run ok:
		
		$error = '<p class="error">Your account could not be activated, make sure you are using the exact link sent to your email or request another link below.</p>';	

echo '
<div class="form" align="center">

 <h1>Resend Activation Link</h1>

 <form action="activate.php" method="post">
 
 <fieldset>	';
 
 echo $error;
 
 echo '<p> <input type="submit" name="submit" value="Resend Link" /></p>
 <input type="hidden" name="submitted" value="TRUE" />
 <input type="hidden" name="email" value="TRUE" />
 </fieldset></form></div>
 ';
 
	}
		
	mysqli_close($dbc);
	
} else { // Account is already activated, redirect.

$url = BASE_URL . 'settings.php?activated=truth'; 

// Define the URL:

ob_end_clean(); // Delete the buffer.

header("Location: $url");

exit(); // Quit the script.

} // End of strlen IF.

	} // End of while IF
	
		} // End of Num_row IF
	
} // End of $x and $y IF...

} else { // You cannot be on this page...

$url = BASE_URL . 'login.php'; // Define the URL.
 
 
 ob_end_clean(); // Delete the buffer.

 header("Location: $url");

 exit(); // Quit the script.
 
} // End of $x $y $s IF

include ('includes/footer.html');



?>

