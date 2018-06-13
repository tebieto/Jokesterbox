<?php
if (isset($_SESSION['country'])) {
$c=$_SESSION['country'];
}

if(isset($_GET['shared'])){
	$dbc=$dba;
	
} else {
	
if (isset($storykey) && $storykey!=FALSE){
$dbc=$dbc;	
} else {
	
$sharded=FALSE;
if (isset ($_GET['kkid']) AND $sharded==TRUE){
	$shard = $_GET['kkid'];
	if ($shard=='b') {	
	$slave=FALSE;
	if ($slave) {
	$x=rand(1,1);
	if ($x==1) {
	$dbc=$dbb1;
	}else {
	$dbc=$dbb;	
	}

	} else {
	$dbc= $dbb;
	}	
	} else {
	$slave=FALSE;
	if($slave) {
	$y=rand(1,1);
	if ($y==1) {
	$dbc=$dba1;
	}else {
	$dbc=$dba;	
	}	
	} else {
	$dbc=$dba;	
	}
	
	}
	$y=rand(1,1);
	if ($y==1) {
	$dbc=$dba1;
	}else {
	$dbc=$dba;	
	}	
	} else{
		
	$slave=FALSE;
	if($slave) {
	$y=rand(1,1);
	if ($y==1) {
	$dbc=$dba1;
	}else {
	$dbc=$dba;	
	}	
	} else {
	$dbc=$dba;
}
	}
}
}
if (isset ($_SESSION['user_id'])) {
$u = $_SESSION['user_id'];
}
if (isset ($storyid) AND isset($delpost) AND isset($_GET['sid'])) {

	
	$q = "SELECT s.story_id, s.story_key, s.post_id, s.author_id, s.sponsored, s.endorsed, s.target 
	
		FROM stories AS s
        
        WHERE s.story_id=$storyid 
		AND s.story_key=$storykey
		
		ORDER BY order_date DESC
		
		LIMIT 1" ;

	$qkey = "KEY" . md5($q);
		
} else {

if (isset($story) AND isset($_GET['story'])) {

	
	$q = "SELECT s.story_id, s.story_key, s.post_id, s.author_id, s.sponsored, s.endorsed, s.target 
	
		FROM stories AS s
        
        WHERE s.story_id=$story
		AND s.story_key=$storykey
		
		ORDER BY order_date DESC
		
		LIMIT 1" ;
		
	$qkey = "KEY" . md5($q);	
	
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

$qc = "SELECT s.story_id, s.story_key, s.order_date as d
	
		FROM stories as s LEFT JOIN users as u

		ON (s.author_id = u.user_id)

		WHERE u.nick_name LIKE '$query%'

		OR u.first_name LIKE '$query%'

		OR u.middle_name LIKE '$query%'

		OR u.last_name LIKE '$query%'
		
		GROUP BY (s.story_id) ORDER BY d DESC
		" ;
		
 
    $rc = mysqli_query ($dbc, $qc) or
 
trigger_error("Query: $qc\n<br />MySQLError: "

 . mysqli_error($dbc));
 
 $records = mysqli_num_rows($rc);
 
 
		

if ($start>$records) {
$start = 0;
}


$q = "SELECT s.story_id, s.story_key, s.post_id, s.author_id, s.sponsored, s.endorsed, s.target, u.order_date as d
	
		FROM stories as s LEFT JOIN users as u

		ON (s.author_id = u.user_id)

		WHERE u.nick_name LIKE '$query%'

		OR u.first_name LIKE '$query%'

		OR u.middle_name LIKE '$query%'

		OR u.last_name LIKE '$query%'
		
		GROUP BY (s.story_id) ORDER BY d DESC
		
		LIMIT $start, $display" ;
		
	$qkey = "KEY" . md5($q);
	
} else {

if (isset($_GET['sid']) AND isset($story)) {
	
	$q = "SELECT s.story_id, s.story_key, s.post_id, s.author_id, s.sponsored, s.endorsed, s.target 
	
		FROM stories AS s
        
        WHERE s.story_id=$story
		
		AND s.story_key=$storykey
		
		ORDER BY order_date DESC
		
		LIMIT 1" ;
		
		$qkey = "KEY" . md5($q);
		
} else {
	
if ($script == 'stories.php') {
	
	$q= "SELECT country from users 
GROUP BY(country) ORDER BY COUNT(country) DESC LIMIT 3";

$r = mysqli_query ($dba, $q) or
 
trigger_error("Query: $q\n<br />MySQLError: "

 . mysqli_error($dba));
 
 if(mysqli_num_rows($r)>0) {

	$hund= 100;

	$c = $_SESSION['country'];
	 
	$country = array();
	
	$num = mysqli_num_rows($r);
	
	for ($i=0; $i<$num; ++$i) {
		$row= mysqli_fetch_array($r, MYSQLI_ASSOC);
	$country[$i] = $row['country'];
	}
	if ($num==1) {
	$country1 = $country[0];
	$qc = "SELECT s.story_id
	
		FROM stories AS s  
        
        WHERE 
		
		s.author_id=$u
        
        OR
		
		s.target='$c' 
		
		OR
		
		s.target='$country1'
		
		GROUP BY (s.story_id) ORDER BY order_date DESC
		LIMIT $start, $hund";
		
		$qckey = "KEY" . md5($qc);
		
	}else if ($num==2) {
	$country1= $country[0];
	$country2= $country[1];
	
	$qc = "SELECT s.story_id
	
		FROM stories AS s  
        
        WHERE 
		
		s.author_id=$u
        
        OR
		s.
		target='$c' 
		
		OR
		
		s.target='$country1'
		
		OR
		
		s.target='$country2'
		
		GROUP BY (s.story_id) ORDER BY order_date DESC
		LIMIT $start, $hund";
		
		$qckey = "KEY" . md5($qc);
	
	}else if ($num==3) {
	$country1= $country[0];
	$country2= $country[1];
	$country3= $country[2];
	
	$qc = "SELECT s.story_id
	
		FROM stories AS s  
        
        WHERE 
		
		s.author_id=$u
        
        OR
		
		s.target='$c' 
		
		OR
		
		s.target='$country1'
		
		OR
		
		s.target='$country2'
		
		OR
		
		s.target='$country3'
		
		GROUP BY (s.story_id) ORDER BY order_date DESC
		LIMIT $start, $hund";
		
		$qckey = "KEY" . md5($qc);
	}
 



    $rc = mysqli_query ($dbc, $qc) or
 
trigger_error("Query: $qc\n<br />MySQLError: "

 . mysqli_error($dbc));
 
 $records = mysqli_num_rows($rc);
 

	
if ($start>$records) {
$start = 0;
}	

if ($num==1) {
	$country1 = $country[0];
	$q = "SELECT s.story_id,  s.story_key, s.post_id, s.author_id, s.sponsored, s.endorsed, s.target 
	
		FROM stories AS s 
		
		WHERE 
		
		s.author_id=$u
        
        OR
		
		s.target='$c' 
		
		OR
		
		s.target='$country1'
		
		GROUP BY (s.story_id) ORDER BY order_date DESC
		
		LIMIT $start, $hund";
		
		$qkey = "KEY" . md5($q);
		
	}else if ($num==2) {
	$country1= $country[0];
	$country2= $country[1];
	$q = "SELECT s.story_id,  s.story_key, s.post_id, s.author_id, s.sponsored, s.endorsed, s.target 
	
		FROM stories AS s 
		
		WHERE 
		
		s.author_id=$u
        
        OR
		
		s.target='$c' 
		
		OR
		
		s.target='$country1'
		
		OR
		
		s.target='$country2'
		
		GROUP BY (s.story_id) ORDER BY order_date DESC
		
		LIMIT $start, $hund";
		
		$qkey = "KEY" . md5($q);
		
	} else if ($num==3) {
	$country1= $country[0];
	$country2= $country[1];
	$country3= $country[2];
	$q = "SELECT s.story_id,  s.story_key, s.post_id, s.author_id, s.sponsored, s.endorsed, s.target 
	
		FROM stories AS s 
		
		WHERE 
		
		s.author_id=$u
        
        OR
		
		s.target='$c' 
		
		OR
		
		s.target='$country1'
		
		OR
		
		s.target='$country2'
		
		OR
		
		s.target='$country3'
		
		GROUP BY (s.story_id) ORDER BY order_date DESC
		
		LIMIT $start, $hund";
		
		$qkey = "KEY" . md5($q);
	}
 }
} else {
	
	$qc = "SELECT s.story_id
	
		FROM stories AS s
        
        WHERE s.author_id=$view 
		
		GROUP BY (s.story_id) ORDER BY order_date DESC
		" ;
		
	$qckey = "KEY" . md5($qc);
	
    $rc = mysqli_query ($dbc, $qc) or
 
trigger_error("Query: $qc\n<br />MySQLError: "

 . mysqli_error($dbc));
 
 $records = mysqli_num_rows($rc);
 
	
	if ($start>$records) {
$start = 0;
}
 
	$q = "SELECT s.story_id, s.story_key, s.post_id, s.author_id, s.sponsored, s.endorsed, s.target 
	
		FROM stories AS s
        
        WHERE s.author_id=$view 
		
		GROUP BY (s.story_id) ORDER BY order_date DESC
		
		LIMIT $start, $display" ;
		
		$qkey = "KEY" . md5($q);
}

}
}
}
}

 $r = mysqli_query ($dbc, $q) or
 
trigger_error("Query: $q\n<br />MySQLError: "

 . mysqli_error($dbc));
 
 if (mysqli_num_rows($r) > 0) {
	 
	 
 if (mysqli_num_rows($r) < 100 
 && !isset($story) && !isset($delpost)) {
	 echo '<p class="note"><br />Share funny videos. Follow People or search a username to watch their videos.<br />
	 </p>';
 }
	 
	 
	 while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC) ) {
	
	$story = $row['story_id'];
	$post = $row['post_id'];
	$author = $row['author_id'];
	$sponsored = $row['sponsored'];
	$endorsed = $row['endorsed'];
	$target = $row['target'];
	if (isset ($_SESSION['user_id'])) {
	$checkblock = $_SESSION['user_id'];
	}
	$storykey=$row['story_key'];
	
	$ifollow=FALSE;
	$nblock=FALSE;
if (isset ($checkblock)) {	
	$q1 = "SELECT block_id FROM blocks 
 
	WHERE author_id=$checkblock AND post_id=$post 
	
	LIMIT 1";
	
	$r1 = mysqli_query ($dbc, $q1) or
 
trigger_error("Query: $q1\n<br />MySQLError: "

 . mysqli_error($dbc));
	
if (mysqli_num_rows($r1)==0) {
	$nblock=TRUE;
}


$q1 = "SELECT follow_id FROM follows 
 
	WHERE follower_id=$checkblock AND master_id=$author 
	
	LIMIT 1";
	
	$r1 = mysqli_query ($dba, $q1) or
 
trigger_error("Query: $q1\n<br />MySQLError: "

 . mysqli_error($dba));
	
if (mysqli_num_rows($r1)==1) {
	$ifollow=TRUE;
}
} else {
$nblock=TRUE;
$ifollow=TRUE;	
	
}
$shuffle= rand(1,2);

if(((($ifollow==TRUE && $sponsored==0) OR ($target==$c && $shuffle==2) )
OR $script=='profile.php' OR $author==$u OR isset($_GET['sk']) OR isset($query) OR ($sponsored==1 && $target==$c)) AND $nblock==TRUE ){
	// User has not blocked this video, carry on
	if(isset($urecord)){
	$urecord=$urecord+1;	
	} else {
	$urecord=1;
	}
	
	if ($urecord<=5) {
	$q1 = "SELECT author_id, post_key, body, video_id, post_date, UTC_TIMESTAMP() as now
	FROM posts
	WHERE post_id=$post 
	
	LIMIT 1";
	if (!isset($_SESSION['user_id'])){
		$u=1;
	}
	$querykey = "$u" . md5($q1);
	
	
	$r1 = mysqli_query ($dbc, $q1) or
 
trigger_error("Query: $q1\n<br />MySQLError: "

 . mysqli_error($dbc));
 
	 
	 $row1 = mysqli_fetch_array($r1, MYSQLI_ASSOC);
	 
	 $author1 = $row1['author_id'];
	 $caption = $row1['body'];
	 $video = $row1['video_id'];
	 $date = $row1['post_date'];
	 $now = $row1['now'];
	 $postkey= $row1['post_key'];
	 
	 
	 $q1 = "SELECT  CONCAT_WS(' ', first_name, middle_name, last_name) as name
	
	FROM  users
	
	WHERE user_id=$author1 LIMIT 1";
	
	$querykey = "$u" . md5($q1);
	
	
	$r1 = mysqli_query ($dba, $q1) or
 
trigger_error("Query: $q1\n<br />MySQLError: "

 . mysqli_error($dba));
 if (mysqli_num_rows($r1) == 1) {
	 
	 $row1 = mysqli_fetch_array($r1, MYSQLI_ASSOC);
	 $name1=$row1['name'];
 
	}
if ($storykey==2017) {	 
	 $qms =  "SELECT u.user_id as n, CONCAT_WS(' ', u.first_name, u.last_name) as name 
	 FROM funny as l LEFT JOIN follows as f
 ON (l.author_id=f.master_id) LEFT JOIN users as u 
 ON  (u.user_id=l.author_id)
 
 WHERE l.post_id=$post AND f.follower_id=$u
 
 AND l.author_id != '$author1' 
 
 ORDER BY l.lol_date DESC LIMIT 1";
 
 $rms = mysqli_query ($dba, $qms) or
 
trigger_error("Query: $qms\n<br />MySQLError: "

 . mysqli_error($dba));
 
 if (mysqli_num_rows($rms) > 0) {
	 $rowms = mysqli_fetch_array($rms, MYSQLI_ASSOC);
	 $msa = $rowms['name'];
	 $msan = $rowms['n'];
	 $mmsg = "<a href=\"profile.php?uid=$msan\">$msa</a> laughed after watching this video.";
 } else {
	 $qms =  "SELECT u.user_id as n, CONCAT_WS(' ', u.first_name, u.last_name) as name 
	 FROM comments as c LEFT JOIN follows as f
 ON (c.author_id=f.master_id) LEFT JOIN users as u 
 ON  (u.user_id=c.author_id)
 
 WHERE c.post_id=$post AND f.follower_id=$u
 
 ORDER BY c.comment_date DESC LIMIT 1";
 
 $rms = mysqli_query ($dba, $qms) or
 
trigger_error("Query: $qms\n<br />MySQLError: "

 . mysqli_error($dba));
 
 if (mysqli_num_rows($rms) > 0) {
	 $rowms = mysqli_fetch_array($rms, MYSQLI_ASSOC);
	 $msa = $rowms['name'];
	 $msan = $rowms['n'];
	 $mmsg = "<a href=\"profile.php?uid=$msan\">$msa</a> commented on this video.";
 } else {
	 $qms =  "SELECT u.user_id as n, CONCAT_WS(' ', u.first_name, u.last_name) as name 
	 FROM likes as l LEFT JOIN follows as f
 ON (l.author_id=f.master_id) LEFT JOIN users as u 
 ON  (u.user_id=l.author_id)
 
 WHERE l.post_id=$post AND f.follower_id=$u
 
 ORDER BY l.like_date DESC LIMIT 1";
 
 $rms = mysqli_query ($dba, $qms) or
 
trigger_error("Query: $qms\n<br />MySQLError: "

 . mysqli_error($dba));
 
 if (mysqli_num_rows($rms) > 0) {
	 $rowms = mysqli_fetch_array($rms, MYSQLI_ASSOC);
	 $msa = $rowms['name'];
	 $msan = $rowms['n'];
	 $mmsg = "<a href=\"profile.php?uid=$msan\">$msa</a> like this video.";
	 
 } else {
	 $mmsg= FALSE;
 }
 }
 }
 }
	 
	 if ($sponsored == 1) {
		 $msg1 = '<b>sponsored</b>';
	 } else {
		 if ($endorsed == 1) {
	$qms =  "SELECT CONCAT_WS(' ', u.first_name, u.last_name) as name 
	
	 FROM users as u
 
	WHERE u.user_id=$author LIMIT 1";
 
 $rms = mysqli_query ($dba, $qms) or
 
trigger_error("Query: $qms\n<br />MySQLError: "

 . mysqli_error($dba));
 
 if (mysqli_num_rows($rms)>0) {
	 $rowen = mysqli_fetch_array($rms, MYSQLI_ASSOC);
	 $endorser = $rowen['name'];		 
			 $msg1 = "<b>via</b> <a href=\"profile.php?uid=$author\">$endorser</a>";
		 } 
	 } else {
			 $msg1 = NULL;
		 }
	 }
	 
	 
	  
	  $qv = "SELECT video_name, extension, CONCAT_WS('.', video_id, extension) as vlink FROM videos 
	  WHERE video_id=$video LIMIT 1";
	   
	   $rv= mysqli_query ($dbc, $qv) or
 
trigger_error("Query: $qv\n<br />MySQLError: "

 . mysqli_error($dbc));
 
		$rowv = mysqli_fetch_array($rv, MYSQLI_ASSOC);
		
		$vname = $rowv['video_name'];
		$vlink = $rowv['vlink'];
		$ext = $rowv['extension'];
	
	if ($ext=='mp4'){
		$vtype= 'video/mp4';
	}else if($ext=='ogg') {
		$vtype = 'video/ogg';
	}else if($ext=='avi') {
		$vtype = 'video/avi';
	}else if($ext=='webm') {
		$vtype = 'video/webm';
	}else{
		$vtype = 'video/mp4';
	}
		
	 echo "<div class=\"video\" id=\"$story\">";
	 if ($script== 'stories.php' OR $script== 'profile.php' OR $script== 'story.php') {
	
	 echo " <div class=\"fb-share-button\" data-href=\"https://jokesterbox.com/story.php?sk=$storykey&amp;story=$story\""; 
	 
	 echo ' data-layout="button_count" data-size="small" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" ';
	 echo " href=\"https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fjokesterbox.com%2Fstory.php%3Fsk%3D$storykey%26story%3D$story&amp;src=sdkpreparse\">Share</a></div> ";
	 
	  if ($mmsg) {
		 echo "<br />$mmsg";
	 }
	 
	 echo "<p class=\"author\">
	 <a href=\"profile.php?uid=$author1\">$name1</a> $msg1
	  </p>";
	  echo "<p class=\"caption\">$caption</p>";
	  
	  $vsource = "show_jb_videos.php?vname=$vname&video=JBV_$vlink";
	  echo '<span class="vid">';
	  echo "<video id=\"this$story\"   class=\"video-js vjs-default-skin vjs-big-play-centered\" 
	  
	  controls preload=\"auto\" autoplay height=\"200\"  width=\"350\">
	  <source src=\"$vsource\" type=\"$vtype\">
	  <p>
		Cannot load this video, Try again with Chrome Browser Or Upgrade your browser .
		</p>
		</video></span>";
	

	}
echo "<div id=\"jbb$story\">";	
		$ql = "SELECT lol_id FROM funny 
		
		WHERE post_id=$post AND author_id=$u
		
		LIMIT 1";
		
		$rl = mysqli_query ($dbc, $ql) or
 
trigger_error("Query: $ql\n<br />MySQLError: "

 . mysqli_error($dbc));
 
 if (mysqli_num_rows($rl) == 1) {
	 $laugh = 'lol';
	 $laughid='unlol';
	 $action = 'unlol.php';
 } else {
	 $laughid='lol';
	 $laugh = 'lol';
	 $action = 'lol.php';
 }
 
 $qli = "SELECT like_id FROM likes 
		
		WHERE post_id=$post AND author_id=$u
		
		LIMIT 1";
		
		$rli = mysqli_query ($dbc, $qli) or
 
trigger_error("Query: $qli\n<br />MySQLError: "

 . mysqli_error($dbc));
 
 if (mysqli_num_rows($rli) == 1) {
	 $liked = 'unlike';
	 $action1 = 'unlike.php';
 } else {
	 $liked = 'like';
	 $action1 = 'like.php';
 }
 
  $qen = "SELECT endorsed_id FROM endorsed 
		
		WHERE post_id=$post AND author_id=$u
		
		LIMIT 1";
		
		$ren = mysqli_query ($dbc, $qen) or
 
trigger_error("Query: $qen\n<br />MySQLError: "

 . mysqli_error($dbc));
 
 if (mysqli_num_rows($ren) == 1) {
	 $endorse = 'shared';
	 $endorsed='endorsed';
 } else {
	 $endorse = 'share';
 }
 
 $qls = "SELECT like_id FROM likes 
 
 WHERE post_id=$post";
 
 $rls = mysqli_query ($dbc, $qls) or
 
trigger_error("Query: $qls\n<br />MySQLError: "

 . mysqli_error($dbc));
 
 if (mysqli_num_rows($rls) > 0) {
	 $likes = mysqli_num_rows($rls);
 } else {
	 $likes = FALSE;
 }
 
 $qlls = "SELECT lol_id FROM funny 
 
 WHERE post_id=$post";
 
 $rlls = mysqli_query ($dbc, $qlls) or
 
trigger_error("Query: $qlls\n<br />MySQLError: "

 . mysqli_error($dbc));
 
 if (mysqli_num_rows($rlls) > 0) {
	 $lols = mysqli_num_rows($rlls);
 } else {
	 $lols = FALSE;
 }
 
  $qcc = "SELECT comment_id FROM comments 
 
 WHERE post_id=$post";
 
 $rcc = mysqli_query ($dbc, $qcc) or
 
trigger_error("Query: $qcc\n<br />MySQLError: "

 . mysqli_error($dbc));
 
 if (mysqli_num_rows($rcc) > 0) {
	 $pcc = mysqli_num_rows($rcc);
 } else {
	 $pcc = FALSE;
 }
 
 $val= 'this.value';
 $conclick= "sendComment($story, $storykey, $post, $val)";
 $lonclick= "sendLike($story, $storykey, $post)";
 $llonclick= "sendLol ($story, $storykey, $post, $author1)";
 $enonclick= "sendEndorse($story, $storykey, $post)";
echo '<span="likelol">';
 if ($lols) {
	if ($lols == 1) {
$lol = '<b>lol</b>';
	} else {
$lol = '<b>lols</b>';
	}

echo "&nbsp;<a href=\"people.php?pid=$author1&llid=$post&ss=$story&ssk=$storykey\" target=\"_blank\">$lols</a> $lol";
	
 } 
 if ($likes) {
	if ($likes == 1) {
$like = '<b>like</b>';
	} else {
$like = '<b>likes</b>';
	}	
echo "&nbsp;&nbsp;<a href=\"people.php?pid=$author1&lid=$post&ss=$story&ssk=$storykey\" target=\"_blank\">$likes</a> $like";
 }
 
 $then=strtotime($date);
 $now = strtotime($now);
 $time = write_date($now, $then);
 echo "&nbsp;&nbsp;&nbsp;&nbsp;$time";
 echo '</span>';
 if (isset($_SESSION['user_id'])) {
 echo '<br /><span class="vbuttons">';
		
 echo "<button type=\"button\" class=\"$laughid\"
 onclick=\"$llonclick\">$laugh</button>
		
		<button type=\"button\" class=\"$liked\"
 onclick=\"$lonclick\">$liked</button>
		
		<button type=\"button\" class=\"$endorsed\"
 onclick=\"$enonclick\">$endorse</button>";
 
 if(($author1 != $u) AND ($author != $u)) {
 if (isset($block) && $block == 'confirm') {
	 $bonclick= "sendBlock($story, $storykey, $post, 'confirmed')";
echo	"<button type=\"button\" class=\"delete\" 
onclick=\"$bonclick\">confirm</button><br />";
 echo '</span>';
 } else {
	 $bonclick= "sendBlock ($story, $storykey, $post, 'confirm')";
echo	"<button type=\"button\" class=\"delete\" 
 onclick=\"$bonclick\">block</button><br />";
 echo '</span>';
 
 }
 } else {
	if (isset($delpost) && $delpost == 'confirm') {
	 $donclick= "postDel($story, $storykey, $post, 'confirmed')";
echo	"<button type=\"button\" class=\"delete\" 
onclick=\"$donclick\">confirm</button><br />";
 echo '</span>';
 } else {
	 $donclick= "postDel ($story,$storykey, $post, 'confirm')";
echo	"<button type=\"button\" class=\"delete\" 
 onclick=\"$donclick\">delete</button><br />";
 echo '</span>'; 
 }
 }
 
echo "<br /><span class=\"comment\">";

echo '<span id="commented">';
if ($pcc) {
	if ($pcc == 1) {
		$comments = 'comment';
	} else {
		$comments = 'comments';
	}
	if (!isset($comment)) {
	echo "View the
	<a href=\"story.php?sk=$storykey&story=$story\" target=\"_blank\">$pcc $comments</a> on this video.<br /></span>";
	} else {
		
	$qsc = "SELECT c.comment_id, c.comment_date as date, c.body, c.author_id 
	FROM comments AS c 
        WHERE c.post_id=$post 
		
		GROUP BY (c.comment_id) ORDER BY date ASC
		
		LIMIT 20
		" ;
	$rsc = mysqli_query ($dbc, $qsc) or
 
	trigger_error("Query: $qsc\n<br />MySQLError: "

	. mysqli_error($dbc));
	
	if (mysqli_num_rows($rsc)>0) {
		
	while ($rowsc = mysqli_fetch_array($rsc, MYSQLI_ASSOC)) {
	$sc = $rowsc['body'];
	$sci = $rowsc['comment_id'];
	$scai = $rowsc['author_id'];
	$scd = $rowsc['date'];
	
	$qn = "SELECT CONCAT_WS(' ', u.first_name, u.middle_name, u.last_name) as name 
	FROM users AS u
        WHERE u.user_id=$scai 
		LIMIT 1
		" ;
	$rn = mysqli_query ($dba, $qn) or
 
	trigger_error("Query: $qn\n<br />MySQLError: "

	. mysqli_error($dba));
	
	if (mysqli_num_rows($rn)==1) {
		
$rown = mysqli_fetch_array($rn, MYSQLI_ASSOC); 
	$sca = $rown['name'];
	}
		
	 
	echo "<div class=\"comments\"><div class=\"comment\" id=\"$sci\">";
	echo "<span class=\"sca\"><a href=\"profile.php?uid=$scai\">$sca</a></span><br />
	
		<span class=\"sc\">$sc</span><br /> ";
		
		if (($scai == $u) OR $author1 == $u) {
		
			$delete = 'delete';
			$sendelete = 
			"delDel($sci, $storykey, 'confirm')";
		
	echo	"<button type=\"button\" class=\"cdelete\" 
 onclick=\"$sendelete\">$delete</button><br /><br />";
	}
	echo '</div><div>';
	
		
	}
		
	}
		
	}
}
echo "<input type=\"text\" size=\"25\" maxlength=\"200\" placeholder=\"Type a comment\" onChange=\"$conclick\"/>
 <button type=\"button\" class=\"like\">comment</button></span>";
	  
	 echo '</div></div><br />';
	 
 
 
 }
}} }
 } else {
	 
 }
 
 if (($script == 'stories.php') OR 
 ($script == 'profile.php')){
	 if (isset($query)){
	$query = urlencode($query);
	$q= "&query=$query";
	 } else {
		 $q=NULL;
	 }
	if($script=='profile.php'){ 
	$puid="&uid=$view"; 
	 } else {
		 $puid=NULL;
		 }
	
 if (isset($records)) {
	 $point = $records - $display;
	 if (!isset($query) &&  $script=='stories.php'){
	$point1= ceil($records/4	); 
 $s= rand(0, $point1); 
 
 
	 $s= $s+5;
	 
	 if($s==$start){
		 if ($s==0){
		 $s= $s + 1;
		 
		 } else {
			 
			$s= $s - 1; 
		 }
		
	 }
	 
	 
	 
	 
	 
 }
	

	 $begin=TRUE;

	 if (isset($query) OR $script=='profile.php' OR $begin==FALSE){
		 $s = $start + $display;
		 }
	 if ($start < $point) {

			 if($urecord==1){
			$lucky='the video';
			 }else{
				  if ($urecord>5){
			$urecord=5;	 
			 }
				 
			$lucky="those $urecord videos";
				 
			 }
			 
			
			if (isset($urecord)) {
			 $lucky= "<p class=\"success\">Lucky you. We hope $lucky made you laugh out loud.
			 CLICK 'Get Lucky' and follow people to catch their videos.</p>";
			$show='More Videos'; 
		 } else {
			$lucky="<p class=\"error\"><b>We could not catch any video, Follow or Search for people/profiles with videos. CLICK 'VIDEOS' for random new videos by people you follow. If you think you are following enough people click 'Feel Lucky', else click 'Get Lucky' to follow more people.</b></p>";
			$show="#$s Feel Lucky"; 
		 }
if(isset($lucky)) {
	if(!isset($query) && $script=='stories.php'){
		echo $lucky;
		
		}
}
echo '<p id="next" class ="video">';
	 
echo "<a href=\"$script?s=$s$q$puid&#begin\">$show</a>
<a href=\"people.php\"> Get Lucky</a></p>";
} else {

			 if($urecord==1){
			$vvv= 'video';	 
			 }else{
				 $vvv='videos';
			 }
			 if ($urecord>5){
			$urecord=5;	 
			 }

			if (isset($urecord)) {
			 $lucky= "<p class=\"success\">$urecord $vvv. CLICK 'Get Lucky' and follow people to catch their videos.</p>";
			$show='More Videos'; 
		 }else {
		
			$lucky="<p class=\"error\"><b>We could not catch any video, follow people to watch their videos. If you think you are following enough people CLICK 'VIDEOS' for random new videos by people you follow, else click 'Get Lucky' to follow more people.</b></p>";
			$show="#$s Feel Lucky";
						
		 }
if(isset($lucky)) {
	if(!isset($query) && $script=='stories.php'){echo $lucky;}
}

	echo '<p id="back" class ="video">';	 
echo "<a href=\"people.php\">Get Lucky...</a>
</p>";
	
}
 }

if((isset($query ) OR $script=='profile.php') && !isset($urecord)) {
$lucky= "<p class=\"error\"><b>We could not catch any video, Share your own videos or follow people to watch their videos. CLICK'VIDEOS' for random new videos by people you follow.</b></p>";
 echo  $lucky;
}	
 }
 

echo"	<script>
             window.onload=function(){

var videos = document.getElementsByTagName(\"video\"),
    fraction = 0.9;

    function checkScroll() {

for(var i = 0; i < videos.length; i++) {

    var video = videos[i];

    var x = video.offsetLeft, y = video.offsetTop, w = video.offsetWidth, h = video.offsetHeight, r = x + w, //right
        b = y + h, //bottom
        visibleX, visibleY, visible;

        visibleX = Math.max(0, Math.min(w, window.pageXOffset + window.innerWidth - x, r - window.pageXOffset));
        visibleY = Math.max(0, Math.min(h, window.pageYOffset + window.innerHeight - y, b - window.pageYOffset));

        visible = visibleX * visibleY / (w * h);

        if (visible > fraction) {
            video.play();
        } else {
            video.pause();
        }

}

}



  window.addEventListener('scroll', checkScroll, false);
    window.addEventListener('resize', checkScroll, false);
}

        </script>";
 ?>