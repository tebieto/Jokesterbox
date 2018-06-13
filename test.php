<?php

require_once ('includes/config.inc.php');

switchread(2017);

require_once(MYSQL1a);

$q= "SELECT country from users 
GROUP BY(country) ORDER BY COUNT(country) DESC LIMIT 3";

$r = mysqli_query ($dbc, $q) or
 
trigger_error("Query: $q\n<br />MySQLError: "

 . mysqli_error($dbc));
 
 if(mysqli_num_rows($r)>0) {

	 
	 
	$country = array();
	
	$num = mysqli_num_rows($r);
	
	for ($i=0; $i<$num; ++$i) {
		$row= mysqli_fetch_array($r, MYSQLI_ASSOC);
	$country[$i] = $row['country'];
	}
	if ($num==1) {
	$country1 = $country[0];
	}else if ($num==2) {
	$country1= $country[0];
	$country2= $country[1];
	
	}else if ($num==3) {
	$country1= $country[0];
	$country2= $country[1];
	$country3= $country[2];
	}
	 
	 echo "$country1, $country2, $country3";
	 
	 
	 
	 
	 
 }




?>