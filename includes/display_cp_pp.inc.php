<?php
	
	// query databse for profile picture and cover photo:
	
	$q = "SELECT CONCAT_WS(' ', u.first_name, u.middle_name, u.last_name) AS name, CONCAT_WS ('.', p.picture_id, p.extension) AS pp, p.picture_name AS ppname,
CONCAT_WS ('.', c.cover_id, c.extension) as cp, c.cover_name as cpname
	FROM users AS u LEFT JOIN pictures AS p
	ON (u.profile_pic=p.picture_id) LEFT JOIN covers as c
    ON (u.cover_pic=c.cover_id)
	WHERE u.user_id=$view LIMIT 1;";
	
	$r = mysqli_query ($dbc, $q) or 
	
	trigger_error("Query: $q\n<br />MySQL Error: ". mysqli_error($dbc));
	
	if (@mysqli_num_rows($r) == 1 ){

	// Match was made...
	
	$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
	
	$cp = $pp = FALSE;
	
	if(!empty($row['cp'])) {
		$cp = $row['cp'];
		$cpname= $row['cpname'];
	}
		
	
	if (!empty($row['pp'])) {
		$pp = $row['pp'];
		$ppname= $row['ppname'];
	}
	
	if ($cp) {
		
		// Set cp
		
	 $cp = "show_jb_image.php?cp=true&image=JBC_$cp&name=$cpname";
		
	} else {
		
		// Use default site cp
		
		$cp = 'show_jb_image.php?default=true&dcp=default';
	}
	
	if ($pp) {
		
		// Set pp
		
		$pp = "show_jb_image.php?pp=true&image=JBP_$pp&name=$ppname";
		
	} else {
		
		// Use default site pp
		
		$pp = 'show_jb_image.php?default=true&dpp=default';
	}
	
} else {
	
	$url = BASE_URL . 'settings.php?redirected=true'; 
	// Defined the URL.
 
 
 ob_end_clean(); // Delete the buffer.

 header("Location: $url");

  exit();
}

$name = $row['name'];
echo "<img id=\"cp\" src=\"$cp\" alt=\"$name\" 

     width=\"400\" height=\"200\" /></br>
	
	<img id=\"pp\" src=\"$pp\" alt=\"$name\" 

     width=\"100\" height=\"100\" /><br /><br /><br /><br />";
	 echo '<div id="pd">';
	 echo "<h1>{$row['name']}</h1>";
	 
	 if ($me==FALSE){
		 
		 $q = "SELECT follow_id FROM follows
	 WHERE master_id={$_SESSION['user_id']} 
	 AND follower_id=$view LIMIT 1";
	 
	 $r = mysqli_query ($dbc, $q) or
 
trigger_error("Query: $q\n<br />MySQLError: "

 . mysqli_error($dbc));
 
 if(mysqli_num_rows($r) == 1) {
	 // You are following me
	 
	 echo "<p>This profile is following you.</p>";
	 
 } else {
	 
	 echo '<p>This profile is not following you.</p>';
	 
 }
	 
		 
		 $q = "SELECT follow_id FROM follows
	 WHERE follower_id={$_SESSION['user_id']} 
	 AND master_id=$view LIMIT 1";
	 
	 $r = mysqli_query ($dbc, $q) or
 
trigger_error("Query: $q\n<br />MySQLError: "

 . mysqli_error($dbc));
 
 if(mysqli_num_rows($r) == 0) {
	 // I am not following 
	 
	 $action = 'follow.php?';
	 
	 $uid = $view;
	 
	 $value = 'follow';
	 
	 $onclick = "sendFollow('$action', $uid)";
	 
 } else {
	 // I am following
	 
	 $action = 'unfollow.php?';
	 
	 $uid = $view;
	 
	 $value = 'unfollow';
	 
	 $onclick = "sendFollow('$action', $uid)";
 }
 
 echo '<div id="follower">';
 echo "<button class=\"$value\" type=\"button\" 
 id=\"fbutton\" onclick=\"$onclick\">$value</button>";
		
	 } // End of false me IF
	 
	 // I want to know the followers of this user
	 
	 $q = "SELECT COUNT(master_id) as following FROM follows
			WHERE follower_id=$view LIMIT 1";
	$r = mysqli_query ($dbc, $q) or
	trigger_error("Query: $q\n<br />MySQLError: "
	. mysqli_error($dbc));
	
	if (mysqli_num_rows($r) == 1) {
		// Match was made
		$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
		
		if($row['following'] == 1) {
		$people = 'person'; 
		} else {
			$people = 'people';
		}
		echo "<p><b>Following:</b> 
		<a href=\"people.php?fid=$view\">";
		echo $row['following'];
		echo "</a> $people";
	}
	
	// I want to know those this user is following
	
	
	 $q = "SELECT COUNT(follower_id) as followers FROM follows
			WHERE master_id=$view LIMIT 1";
	$r = mysqli_query ($dbc, $q) or
	trigger_error("Query: $q\n<br />MySQLError: "
	. mysqli_error($dbc));
	
	if (mysqli_num_rows($r) == 1) {
		// Match was made
		$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
		
		if($row['followers'] == 1) {
		$people = 'person'; 
		} else {
			$people = 'people';
		}
		
		echo "<b> Followers:</b> 
		<a href=\"people.php?ffid=$view\">";
		
		echo $row['followers'];
		echo "</a> $people";
		echo '</p>';
	}
	
// I want to retrieve the username and about me

$qa = "SELECT nick_name, about_me FROM users WHERE user_id='$view'";
	
	$ra = mysqli_query ($dbc, $qa) or 
	
	trigger_error("Query: $qa\n<br />MySQL Error: ". mysqli_error($dbc));
	
	if (mysqli_num_rows($ra) == 1 ){
		$row = mysqli_fetch_array($ra, MYSQLI_ASSOC);
		$about = $row['about_me'];
		$username= $row['nick_name'];
		
echo "<small>jokesterbox.com/$username</small>";
echo "<p>$about</p></div>";	
	}

if ($me){
	  /* <form class="ppcp" id="pcp" action="profile.php" method="post">
		<input type="submit" name="cover" value="Change Cover" />
		</form> */
	 echo'	<form class="ppcp" action="profile.php" method="post">
		<input type="submit" name="pp" value="Change Photo" />
		</form>';
		
		if (isset($_GET['change'])) {
			$message = '<p class="success">Change Was Succesful.</p>';
		}
		
		
		if (isset($_POST['pp']) OR isset($_POST['cover'])) {
			
		if (isset($_POST['pp'])) {	
		
		$corp = 'pp';
		
			echo '<div class="ppcp"><h1>Upload Profile Picture</h1>';
		} else {
			if (isset($_POST['cover'])) {	
			
			$corp = 'cover';
			
			echo '<div class="ppcp"><h1>Upload Cover Photo</h1>';
		}}
		
		
if (isset($_POST['upload'])) {
	
	$errors = array();
	$success = array();
			$i = FALSE;
			
			$ext = FALSE;
			
// Check for an image:

if (is_uploaded_file ($_FILES['image']['tmp_name'])) {
	
	$allowed = array ('image/pjpeg',
 'image/jpeg', 'image/jpeg',
 'image/JPG', 'image/X-PNG',
 'image/PNG', 'image/png',
 'image/x-png');
if (in_array($_FILES['image']['type'], $allowed)) {
	
	if ((($_FILES['image']['size']) < 5120000) 

		AND (($_FILES['image']['size']) > 2000) ) {
	
	// Create a temporary file name:
	
	if (isset($_POST['pp'])){
	$temp = '../../jbfs/uploads/pictures/pps' .  md5($_FILES['image']['name']);
	} else {
		if (isset($_POST['cover'])){
	$temp = '../../jbfs/uploads/pictures/covers' .  md5($_FILES['image']['name']);
	}}
	
	// Move the file over:
	
	if (move_uploaded_file($_FILES['image']['tmp_name'], $temp)) {
		
		$success[] = '<p class="success">The file has been uploaded!</p>';
		
		// Set the $i varaiable to the image's name:
		
		$i = $_FILES['image']['name'];
		
		$type_jpg = array ('image/pjpeg',
 'image/jpeg', 'image/jpeg',
 'image/JPG');
 
		$type_png = array ('image/X-PNG',
 'image/PNG', 'image/png',
 'image/x-png');
		
		if (in_array($_FILES['image']['type'], $type_jpg)) {
			
			$ext = 'jpg';
			
		} elseif (in_array($_FILES['image']['type'], $type_png)) {
			
			$ext = 'png';
			
		} else {
			
			$ext = FALSE;
			
		}
		
	} else { // Couldn't move the file over.
	
	$errors[] = 'The file could not be moved.';
	
	$temp = $_FILES['image']['tmp_name'];
	
	} // End of move uploaded IF.
	
		} else {
			
			$errors[] = '<p class="error">Image should be between 100kb - 3mb large. Try again.</p>';
			
		}
	
} else {
	
	$errors[] = '<p class="error">PNG and JPG is prefered, try again.</p>'; 
	
} // End of in array IF
	
} else { // No uploaded file.

$errors[] = '<p class="error">No file was uploaded, try again with a smaller image.</p>';

$temp = NULL;

} // End of is uploaded IF
	
if ($i && $ext && empty($error)) {// Everything's OK.

// Add the image to the database:

	$u = $_SESSION['user_id'];

if (isset ($_POST['pp'])) {

$q = 'INSERT INTO pictures (author_id, picture_name, extension) VALUES (?, ?, ?)';

}  else {
	
	if (isset ($_POST['cover'])) {

$q = 'INSERT INTO covers (author_id, cover_name, extension) VALUES (?, ?, ?)';

}}
	
$stmt = mysqli_prepare($dbc, $q);

mysqli_stmt_bind_param($stmt, 'iss', $u, $i, $ext);

mysqli_stmt_execute($stmt);

// Check the result...

if (mysqli_stmt_affected_rows($stmt) == 1){
	
	// Print a message
	
	$success[] = '<p class="success">Your Change Was Effected Successfully.</p>';
	
	// Get the ID:
	
	$id = mysqli_stmt_insert_id($stmt); // Get the print ID.
	
	if (isset ($_POST['pp'])) {
		
		// UPDATE the insert time with UTC_TIMESTAMP
		
		$q = "UPDATE pictures SET picture_date=UTC_TIMESTAMP()

 WHERE picture_id=$id LIMIT 1";
 
 $r = mysqli_query ($dbc, $q) or
 
trigger_error("Query: $q\n<br />MySQLError: "

 . mysqli_error($dbc));
		
		// update users profile pic
		
	$q = "UPDATE users SET profile_pic='$id'

	WHERE user_id={$_SESSION['user_id']} LIMIT 1";
	
	
	// Rename the image	
	
	rename ($temp, "../../jbfs/uploads/pictures/pps/JBP_$id.$ext");
	
	} else {
		if (isset ($_POST['cover'])) {
			
			// Update insert time to UTC_TIMESTAMP
		
		$q = "UPDATE covers SET cover_date=UTC_TIMESTAMP()

		WHERE picture_id=$id LIMIT 1";
 
 $r = mysqli_query ($dbc, $q) or
 
trigger_error("Query: $q\n<br />MySQLError: "

 . mysqli_error($dbc));
 
 // Update users cover_pic
			
		$q = "UPDATE users SET cover_pic='$id'

		WHERE user_id={$_SESSION['user_id']} LIMIT 1";
		
		// Rename the image:
	
	rename ($temp, "../../jbfs/uploads/pictures/covers/JBC_$id.$ext");
	
	} }
	
	$r = mysqli_query ($dbc, $q) or
 
trigger_error("Query: $q\n<br />MySQLError: "

 . mysqli_error($dbc));
 
 if (mysqli_affected_rows($dbc) == 1) {

 // If it ran OK.
 
	// Clear $_POST
	
	$_POST = array();
	
	//redirect to home affresh
	
	$url = BASE_URL . 'profile.php?change=true'; // Define the URL.
 
 
 ob_end_clean(); // Delete the buffer.

 header("Location: $url");

 exit(); // Quit the script.
	
 } else {
	 
	 $errors[] = '<p class="error">Try again</p>';
	 
 }
	
} else { // Error!

$error[] = '<p class="error">No change was made,  Try again.</p>';

}

mysqli_stmt_close($stmt);

} // End of $i, $ext and $errors IF.

// Delete the uploaded file if it still exists:

if (isset($temp) && file_exists ($temp) && is_file($temp) ) {
	
	unlink ($temp);
	
}	
	
mysqli_close($dbc);

}

	if (!empty($errors)){
		
		foreach($errors as $msg){
			echo "$msg<br />";
		}
	}

    echo' <form enctype="multipart/form-data" action="profile.php" method="post" media="all">

	<input type="hidden" name="MAX_FILE_SIZE" value="62333111" />
	
	<p><b>Image:</b> <input type="file" name="image" /></p>
	
	<p><input type="submit" name="upload" value="Upload"/></p><br />';
	
	echo "<input type=\"hidden\" name=\"$corp\" value=\"TRUE\" /></form></div>";
echo '</div>';
include ('includes/footer.html');
exit;	
		} // End if $_POST cover or pp IF.
		
	
	
} // End of me IF

?>