<?php

$u = $_SESSION['user_id'];
 $country = $_SESSION['country'];
 
if (isset($_GET['lid']) OR isset($_GET['llid']) AND (isset($_GET['pid']) AND $_GET['pid'] > 0) AND (isset($_GET['ss']) AND $_GET['ss'] > 0)) {
	if (isset ($_GET['lid'])) {
		$lid = $_GET['lid'];
		if (is_numeric($lid) && $lid > 0) {
			$lid = $lid;
			$pid = $_GET['pid'];
			$s = $_GET['ss'];
	} else {
		$lid = NULL;
	}
	$qc = "SELECT user_id
	
		FROM users AS u LEFT JOIN pictures AS p
		
		ON (u.profile_pic=p.picture_id) 
		
		LEFT JOIN likes as l 
		
		ON (u.user_id=l.author_id)
		
		WHERE l.post_id=$lid
		
		GROUP BY (u.user_id) ";
$rc = mysqli_query ($dbc, $qc) or
 
trigger_error("Query: $qc\n<br />MySQLError: "

 . mysqli_error($dbc));	
 
 $records = mysqli_num_rows($rc);

if ($start>$records) {
$start = 0;
}

$q = "SELECT user_id, CONCAT_WS(' ', u.first_name, u.last_name) AS name, CONCAT_WS('.', p.picture_id, p.extension) as pp, p.picture_name as pname
	
		FROM users AS u LEFT JOIN pictures AS p
		
		ON (u.profile_pic=p.picture_id) 
		
		LEFT JOIN likes as l 
		
		ON (u.user_id=l.author_id)
		
		WHERE l.post_id=$lid
		
		GROUP BY (u.user_id) ORDER BY u.order_date DESC
		
		LIMIT $start, $display";
	
	} else {
		$llid = $_GET['llid'];
		if (is_numeric($llid) && $llid > 0) {
			$llid = $llid;
			$pid = $_GET['pid'];
			$s = $_GET['ss'];
	} else {
		$llid = NULL;
	}
	$qc = "SELECT user_id
	
		FROM users AS u LEFT JOIN pictures AS p
		
		ON (u.profile_pic=p.picture_id) 
		
		LEFT JOIN funny as f 
		
		ON (u.user_id=f.author_id)
		
		WHERE f.post_id=$llid
		
		GROUP BY (u.user_id) ";
$rc = mysqli_query ($dbc, $qc) or
 
trigger_error("Query: $qc\n<br />MySQLError: "

 . mysqli_error($dbc));	
 
 $records = mysqli_num_rows($rc);

if ($start>$records) {
$start = 0;
}

$q = "SELECT user_id, CONCAT_WS(' ', u.first_name, 		u.last_name) AS name, CONCAT_WS('.', p.picture_id, p.extension) as pp, p.picture_name as pname
	
		FROM users AS u LEFT JOIN pictures AS p
		
		ON (u.profile_pic=p.picture_id) 
		
		LEFT JOIN funny as f 
		
		ON (u.user_id=f.author_id)
		
		WHERE f.post_id=$llid
		
		GROUP BY (u.user_id) ORDER BY u.order_date DESC
		
		LIMIT $start, $display";
	
	}
	
	$qp = "SELECT CONCAT_WS(' ', first_name, last_name) as name FROM users 
	WHERE user_id=$pid";
	$rp = mysqli_query ($dbc, $qp) or
 
trigger_error("Query: $qp\n<br />MySQLError: "

 . mysqli_error($dbc));	

 if (mysqli_num_rows($rp) > 0) {
	 while($rowp = mysqli_fetch_array($rp, MYSQLI_ASSOC)){
	$pidname = $rowp['name'];
	 }
 }
} else{
if (isset($_GET['fid']) OR isset($_GET['ffid'])) {
	if (isset ($_GET['fid'])) {
		$fid = $_GET['fid'];
		if (is_numeric($fid) && $fid > 0) {
			$fid = $fid;
			$pid = $fid;
	} else {
		$fid = NULL;
	}
	$qc = "SELECT user_id
	
		FROM users AS u LEFT JOIN pictures AS p
		
		ON (u.profile_pic=p.picture_id) 
		
		LEFT JOIN follows as f 
		
		ON (u.user_id=f.master_id)
		
		WHERE f.follower_id=$fid
		
		GROUP BY (u.user_id) ";
$rc = mysqli_query ($dbc, $qc) or
 
trigger_error("Query: $qc\n<br />MySQLError: "

 . mysqli_error($dbc));	
 
 $records = mysqli_num_rows($rc);

if ($start>$records) {
$start = 0;
}

$q = "SELECT user_id, CONCAT_WS(' ', u.first_name, u.last_name) AS name, CONCAT_WS('.', p.picture_id, p.extension) as pp, p.picture_name as pname
	
		FROM users AS u LEFT JOIN pictures AS p
		
		ON (u.profile_pic=p.picture_id) 
		
		LEFT JOIN follows as f 
		
		ON (u.user_id=f.master_id)
		
		WHERE f.follower_id=$fid
		
		GROUP BY (u.user_id) ORDER BY u.order_date DESC
		
		LIMIT $start, $display";
	
	} else {
		$ffid = $_GET['ffid'];
		if (is_numeric($ffid) && $ffid > 0) {
			$ffid = $ffid;
			$pid = $ffid;
	} else {
		$ffid = NULL;
	}
	$qc = "SELECT user_id
	
		FROM users AS u LEFT JOIN pictures AS p
		
		ON (u.profile_pic=p.picture_id) 
		
		LEFT JOIN follows as f 
		
		ON (u.user_id=f.follower_id)
		
		WHERE f.master_id=$ffid
		
		GROUP BY (u.user_id) ";
$rc = mysqli_query ($dbc, $qc) or
 
trigger_error("Query: $qc\n<br />MySQLError: "

 . mysqli_error($dbc));	
 
 $records = mysqli_num_rows($rc);

if ($start>$records) {
$start = 0;
}

$q = "SELECT user_id, CONCAT_WS(' ', u.first_name, 		u.last_name) AS name, CONCAT_WS('.', p.picture_id, p.extension) as pp, p.picture_name as pname
	
		FROM users AS u LEFT JOIN pictures AS p
		
		ON (u.profile_pic=p.picture_id) 
		
		LEFT JOIN follows as f 
		
		ON (u.user_id=f.follower_id)
		
		WHERE f.master_id=$ffid
		
		GROUP BY (u.user_id) ORDER BY u.order_date DESC
		
		LIMIT $start, $display";
	
	}
	
	$qp = "SELECT CONCAT_WS(' ', first_name, last_name) as name FROM users 
	WHERE user_id=$pid";
	$rp = mysqli_query ($dbc, $qp) or
 
trigger_error("Query: $qp\n<br />MySQLError: "

 . mysqli_error($dbc));	

 if (mysqli_num_rows($rp) > 0) {
	 while($rowp = mysqli_fetch_array($rp, MYSQLI_ASSOC)){
	$pidname = $rowp['name'];
	 }
 }
} else {
if (isset($_POST['query']) OR isset($_GET['query']) ) {
if (isset( $_POST['query'])) {
	$query = $_POST['query'];
} else {
	$query = $_GET['query'];
}
$query = urldecode($query);
$query = htmlentities($query);

if (empty($query)) {
	$query = NULL;
}

$qc = "SELECT user_id
	
		FROM users AS u LEFT JOIN pictures AS p
		
		ON (u.profile_pic=p.picture_id) 
		
		WHERE u.nick_name LIKE '$query%'

		OR u.first_name LIKE '$query'

		OR u.middle_name LIKE '$query'

		OR u.last_name LIKE '$query' 
		
		GROUP BY (u.user_id) ";
$rc = mysqli_query ($dbc, $qc) or
 
trigger_error("Query: $qc\n<br />MySQLError: "

 . mysqli_error($dbc));	
 
 $records = mysqli_num_rows($rc);

if ($start>$records) {
$start = 0;
}

$q = "SELECT user_id, CONCAT_WS(' ', u.first_name, u.last_name) AS name, CONCAT_WS('.', p.picture_id, p.extension) as pp, p.picture_name as pname
	
		FROM users AS u LEFT JOIN pictures AS p
		
		ON (u.profile_pic=p.picture_id) 
		
		WHERE u.nick_name LIKE '$query%'

		OR u.first_name LIKE '$query'

		OR u.middle_name LIKE '$query'

		OR u.last_name LIKE '$query' 
		
		GROUP BY (u.user_id) ORDER BY u.order_date DESC
		
		LIMIT $start, $display";
} else {
 if (isset($_GET['pid']) AND isset($person)) {
	 
	 $pid = $_GET['pid'];
	 
	$q = "SELECT user_id, CONCAT_WS(' ', u.first_name, u.last_name) AS name, CONCAT_WS('.', p.picture_id, p.extension) as pp, p.picture_name as pname
	
		FROM users AS u LEFT JOIN pictures AS p
		
		ON (u.profile_pic=p.picture_id) 
		
        
        WHERE u.user_id =$pid
		
		LIMIT 1" ; 
 } else {
 $q = "SELECT u.user_id, u.order_date as d
	
		FROM users as u 
		
		LEFT JOIN stories as s
		
		ON (u.user_id=s.author_id)
        
        WHERE country='$country' 
		AND user_id !=$u
		
		GROUP BY (user_id) ORDER BY d DESC
		
		LIMIT 20" ;
 		
 $r = mysqli_query ($dbc, $q) or
 
trigger_error("Query: $q\n<br />MySQLError: "

 . mysqli_error($dbc));
 
 if (mysqli_num_rows($r) > 0) {
	 
	 $qc = "SELECT user_id
	
		FROM users AS u LEFT JOIN pictures AS p
		
		ON (u.profile_pic=p.picture_id) 
        
		LEFT JOIN stories as s
		
		ON (u.user_id=s.author_id)
		
        WHERE u.country='$country' 
		
		AND u.user_id !=$u
		
		GROUP BY (u.user_id) " ;
		
		$rc = mysqli_query ($dbc, $qc) or
 
trigger_error("Query: $qc\n<br />MySQLError: "

 . mysqli_error($dbc));	
 
 $records = mysqli_num_rows($rc);

if ($start>$records) {
$start = 0;
}

$q = "SELECT user_id, CONCAT_WS(' ', u.first_name, u.last_name) AS name, CONCAT_WS('.', p.picture_id, p.extension) as pp, p.picture_name as pname, u.order_date as d
	
		FROM users AS u LEFT JOIN pictures AS p
		
		ON (u.profile_pic=p.picture_id) 
		
		LEFT JOIN stories as s
		
		ON (u.user_id=s.author_id)
        
        WHERE u.country='$country' 
		
		AND u.user_id !=$u
		
		GROUP BY (u.user_id) ORDER BY d DESC
		
		LIMIT $start, $display" ;
 } else {
	 
	  $qc = "SELECT user_id
	
		FROM users AS u LEFT JOIN pictures AS p
		
		ON (u.profile_pic=p.picture_id) 
        
		LEFT JOIN stories as s
		
		ON (u.user_id=s.author_id)
		
        WHERE u.country='Nigeria' 
		
		AND u.user_id !=$u
		
		GROUP BY (u.user_id) " ;
		
		$rc = mysqli_query ($dbc, $qc) or
 
trigger_error("Query: $qc\n<br />MySQLError: "

 . mysqli_error($dbc));	
 
 $records = mysqli_num_rows($rc);

if ($start>$records) {
$start = 0;
}
		
	 $q = "SELECT user_id, CONCAT_WS(' ', u.first_name, u.last_name) AS name, CONCAT_WS('.', p.picture_id, p.extension) as pp, p.picture_name as pname, u.order_date as d
	
		FROM users AS u LEFT JOIN pictures AS p
		
		ON (u.profile_pic=p.picture_id) 
		
		LEFT JOIN stories as s
		
		ON (u.user_id=s.author_id)
        
        WHERE u.country='Nigeria' 
		
		AND u.user_id !=$u
		
		GROUP BY (u.user_id) ORDER BY d DESC
		
		LIMIT $start, $display" ;
 }
 }
}
}
}
if (isset($pidname)) {
	if (isset($fid)) {
echo "<b>People <a href=\"profile.php?uid=$fid\">$pidname</a> is following</b>";
	} else if(isset($ffid)) {
echo "<b>People following <a href=\"profile.php?uid=$ffid\">$pidname</a></b>";
	} else if(isset($lid)){
echo "<b>People who liked this <a href=\"story.php?story=$s\">$pidname's video.</a></b>";
	} else {
echo "<b>People who laughed after viewing this <a href=\"story.php?story=$s\">$pidname's video.</a></b>";
	}	
}
 $r = mysqli_query ($dbc, $q) or
 
trigger_error("Query: $q\n<br />MySQLError: "

 . mysqli_error($dbc));
 
 
 if (mysqli_num_rows($r) > 0) {
	 
	 while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
	$id = $row['user_id'];
	$name = $row['name'];
	$pp = $row['pp'];
	$ppname = $row['pname'];
	if (!empty($pp)) {
	$pp = "show_jb_image.php?pp=true&image=JBP_$pp&name=$ppname";
	} else {
		$pp = 'show_jb_image.php?default=true&dpp=default';
	}
	
	$qf = "SELECT follow_id FROM follows
	 WHERE follower_id=$u 
	 AND master_id=$id LIMIT 1";
	 
	 $rf = mysqli_query ($dbc, $qf) or
 
trigger_error("Query: $qf\n<br />MySQLError: "

 . mysqli_error($dbc));
 
 if(mysqli_num_rows($rf) == 1) {
	 // I am following
$follow = 'unfollow';
 } else {
$follow = 'follow';
 }	 
	
	$qff = "SELECT COUNT(follow_id) AS followers
  	FROM follows
	
	WHERE master_id=$id 
	LIMIT 1";
	 
	 $rff = mysqli_query ($dbc, $qff) or
 
trigger_error("Query: $qff\n<br />MySQLError: "

 . mysqli_error($dbc));
 
 if(mysqli_num_rows($rff) == 1) {
	while($row=mysqli_fetch_array($rff, MYSQLI_ASSOC) ) {
		$followers = $row['followers'];
	if ($followers==1) {
			$ff = 'follower';
		} else {
			$ff = 'followers';
		}	
	}
 }
 
 
	
	$qvv = "SELECT COUNT(post_id) AS videos
  	FROM posts
	WHERE author_id=$id 
	LIMIT 1";
	 
	 $rvv = mysqli_query ($dbc, $qvv) or
 
trigger_error("Query: $qvv\n<br />MySQLError: "

 . mysqli_error($dbc));
 
	 if(mysqli_num_rows($rvv) == 1) {
	while($row=mysqli_fetch_array($rvv, MYSQLI_ASSOC) ) {
		$videos = $row['videos'];
		if ($videos==1) {
			$vv = 'video';
		} else {
			$vv = 'videos';
		}
	}
 }
 $ponclick = "sendPerson($id)";
 
echo "<div class=\"person\" id=\"$id\">";

echo "<br /><img id=\"ppp\" src=\"$pp\" 

alt=\"$name\" width=\"30\" height=\"30\" />";
echo "<a href=\"profile.php?uid=$id\">$name</a>\n";
if ($id!=$u) {
echo "<button type=\"button\" onclick=\"$ponclick\" class=\"$follow\">$follow</button><br />";

echo " They have $videos $vv and $followers $ff <br />";}

echo '</div>';	
	 }

 } else {
	 if(isset($query)) {
		 echo '<p class="nomatch">No match was found.</p>';
	 }
 }

 if (($script == 'people.php')) {
	 if (isset($query)){
	$query = urlencode($query);
	$q= "&query=$query";
	 } else {
		 $q=NULL;
	 }
	 if (isset($fid) ){
	$fid= "&fid=$fid";
	 } else {
		 $fid=NULL;
	 }
	 if (isset($ffid) ){
	$ffid= "&ffid=$ffid";
	 } else {
		 $ffid=NULL;
	 }
	 if (isset($lid) ){
	$lid= "&lid=$lid&ss=$s&pid=$pid";
	 } else {
		 $lid=NULL;
	 }
	 if (isset($llid) ){
	$llid= "&llid=$llid&ss=$s&pid=$pid";
	 } else {
		 $llid=NULL;
	 }
 
 if (isset($records) AND $records > $display) {
	 $point = $records - $display;
	 $s = $start + $display;
	 if ($start < $point) {
echo '<p class ="next">';	 
echo "<a href=\"$script?s=$s$fid$ffid$q$lid$llid\">More People</a>
<a href=\"#ourTop\">Top</a></p>";
} else {
	echo '<p class ="next">';	 
echo "<a href=\"$script\">Start Again...</a>
<a href=\"#ourTop\"> Top</a></p>";

}
 }	 
 }
 ?>