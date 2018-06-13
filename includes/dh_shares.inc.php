<?php # - display and handle shares

$dbc=$dba;

if ($me) {
		
	if (isset($_POST['share'])) {
	
	$errors = array();
	$success = array();
			$v = FALSE;
			
			$ext = FALSE;
			
// Check for a video:

if (is_uploaded_file ($_FILES['video']['tmp_name'])) {
	
	$allowed = array ('video/mp4',
 'video/ogg', 'video/3gp',
 'video/webm', 'video/avi');
if (in_array($_FILES['video']['type'], $allowed)) {
	
	if ((($_FILES['video']['size']) < 51200000) 

		AND (($_FILES['video']['size']) > 512000) ) {
	
	// Create a temporary file name:
	
	$temp = '../../jbfs/uploads/videos/shares' .  md5($_FILES['video']['name']);
	
	// Move the file over:
	
	if (move_uploaded_file($_FILES['video']['tmp_name'], $temp)) {
		
		$success[] = '<p class="success">The file has been uploaded!</p>';
		
		// Check for and validate caption:
		
		if (!empty($_POST['caption'])) {
			
		$cn = trim($_POST['caption']);
		
		if (strlen($cn) < 100) {
			
		$cn = mysqli_real_escape_string($dbc, $cn);
		
		} else {
			$cn = NULL;
		}
		} else {
			$cn = NULL;
		}
		
		// Set the $v varaiable to the videos's name:
		
		$v = $_FILES['video']['name'];
		
		$type_mp4 = array ('video/mp4');
 
		$type_ogg = array ('video/ogg');
		
		$type_3gp = array ('video/3gp');
		
		$type_webm = array ('video/webm');
		
		if (in_array($_FILES['video']['type'], $type_mp4)) {
			
			$ext = 'mp4';
			
		} elseif (in_array($_FILES['video']['type'], $type_ogg)) {
			
			$ext = 'ogg';
			
		} elseif (in_array($_FILES['video']['type'], $type_3gp)) {
			
			$ext = '3gp';
			
		} elseif (in_array($_FILES['video']['type'], $type_avi)) {
			
			$ext = 'avi';
			
		} else {
			
			$ext = FALSE;
			
		}
		
	} else { // Couldn't move the file over.
	
	$errors[] = 'The file could not be moved.';
	
	$temp = $_FILES['image']['tmp_name'];
	
	} // End of move uploaded IF.
	
		} else {
			
			$errors[] = '<p class="error">Video should be between 1mb - 15mb large. Try again.</p>';
			
		}
	
} else {
	
	$errors[] = '<p class="error">MP4 video is prefered, try again.</p>'; 
	
} // End of in array IF
	
} else { // No uploaded file.

$errors[] = '<p class="error">No file was uploaded, MP4 video is required.</p>';

$temp = NULL;

} // End of is uploaded IF
	
if ($v && $ext && empty($error)) {// Everything's OK.

// Add the video to the database:

	$u = $_SESSION['user_id'];
	$c = $_SESSION['country'];
	$sts = 0;
	$isf = 0;


$q = 'INSERT INTO videos (author_id, video_name, extension) VALUES (?, ?, ?)';

$stmt = mysqli_prepare($dbc, $q);

mysqli_stmt_bind_param($stmt, 'iss', $u, $v, $ext);

mysqli_stmt_execute($stmt);

// Check the result...

if (mysqli_stmt_affected_rows($stmt) == 1){
	
	// Print a message
	
	$success[] = '<p class="success">Your Change Was Effected Successfully.</p>';
	
	// Get the VID:
	
	$vid = mysqli_stmt_insert_id($stmt); // Get the video ID.
	
	// UPDATE the video insert time with UTC_TIMESTAMP
		
		$q = "UPDATE videos SET video_date=UTC_TIMESTAMP()

		WHERE video_id=$vid LIMIT 1";
 
 $r = mysqli_query ($dbc, $q) or
 
trigger_error("Query: $q\n<br />MySQLError: "

 . mysqli_error($dbc));
 
		
 // Insert video, caption and other details to post table
 
 $key = 2017;
 		
	$q1 = 'INSERT INTO posts (author_id, body, video_id, target, 
	
	 status, is_funny) 
	 
	 VALUES(?, ?, ?, ?, ?, ?)';
	 
	 $stmt1 = mysqli_prepare($dbc, $q1);

mysqli_stmt_bind_param($stmt1, 'ssisii', $u, $cn, $vid, $c, $sts, $isf);

mysqli_stmt_execute($stmt1);

// Check the result...

if (mysqli_stmt_affected_rows($stmt1) == 1){
	
	// Print a message
	
	$success[] = '<p class="success">Your Change Was Effected Successfully.</p>';
	
	// Get the PID:
	
	$pid = mysqli_stmt_insert_id($stmt1); // Get the post ID.
	
	// UPDATE the POST insert time with UTC_TIMESTAMP
		
		$q = "UPDATE posts SET post_date=UTC_TIMESTAMP(),
		
		order_date=UTC_TIMESTAMP(),
		
		post_key=$key

		WHERE post_id=$pid LIMIT 1";
 
 $r = mysqli_query ($dbc, $q) or
 
trigger_error("Query: $q\n<br />MySQLError: "

 . mysqli_error($dbc));
 
 if (mysqli_affected_rows($dbc) == 1) {
	 
 // INSERT POST INTO STORY TABLE:
	
$q = "INSERT INTO stories(story_date, story_key, target, post_id, author_id,
	sponsored, endorsed, order_date)
	VALUES(UTC_TIMESTAMP(), 2017, '$c', '$pid', '$u', 0, 0, UTC_TIMESTAMP() )";
 
 $r = mysqli_query ($dbc, $q) or
 
trigger_error("Query: $q\n<br />MySQLError: "

 . mysqli_error($dbc));
 
 if (mysqli_affected_rows($dbc) == 1) {
	 
	 // If it ran OK.
 	
	// Rename the video:
	
 rename ($temp, "../../jbfs/uploads/videos/shares/JBV_$vid.$ext");
	
	
	// reset user order date:
	
	$q = "UPDATE users SET order_date = UTC_TIMESTAMP()
	
		WHERE user_id=$u ";
		
	$r = mysqli_query ($dbc, $q) or
 
trigger_error("Query: $q\n<br />MySQLError: "

 . mysqli_error($dbc));
		
	// Clear $_POST
	
	$_POST = array();
	
	//redirect to home affresh
	
	if ($script == 'stories.php') {
		
		$url = BASE_URL . 'stories.php?shared=true'; 
		
	} else {
		
	$url = BASE_URL . 'profile.php?shared=true'; // Define the URL.
	
	}
 
 ob_end_clean(); // Delete the buffer.

 header("Location: $url");

 exit(); // Quit the script.
	
 } else {
	 
	 $errors[] = '<p class="error">Try again</p>';
	 
 }
 }
 mysqli_stmt_close(stmt1);
	
} else { // Error!

$errors[] = '<p class="error">Try again.</p>';

} // END of stmt1 IF

mysqli_stmt_close($stmt);

} else {

$errors[] = '<p class="error">No change was made, Try again</p>';

}	// End of stmt IF

} // End of $v, $ext and $errors IF.

// Delete the uploaded file if it still exists:

if (isset($temp) && file_exists ($temp) && is_file($temp) ) {
	
	unlink ($temp);
	
}	

}

if ($script == 'stories.php') {
	$actions = 'stories.php';
} else {
	$actions = 'profile.php';
}
		
	echo '<div class="share"><h4>Share the funny videos in your device:</h4>';
	
	echo "<form enctype=\"multipart/form-data\" action=\"$actions\" method=\"post\" media=\"all\">";

	echo '<p><input type="hidden" name="MAX_FILE_SIZE" value="62333111" /></p>
	
	<p><b>Video:</b> <input type="file" name="video" /></p>';
	
	if (!empty($errors)){
		
		foreach($errors as $msg){
			echo "$msg";
		}
	}
	if (isset($_GET['shared'])) {
		echo '<p class="success">Shared!</p>';
	}
	
	echo '
	
	<p><input name="caption" placeholder="Say something..."/>';

	 if (isset($_POST['caption'])) echo $_POST['caption'];
	
	echo '
	
		<input id="sp" type="submit" name="share" value="upload" />
		</p></form></div>';
	
	




	} // End of me IF

?>