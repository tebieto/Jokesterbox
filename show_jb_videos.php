<?php # - show_image.php

// This pages retrieves and show videos.

// Flag variables:

$video = FALSE;


$vname = (!empty($_GET['vname'])) ? $_GET['vname'] : 'JBV';

// Check for a video value in the url:

if (isset($_GET['video'])) {
	
		
	// Full video path
	
	$video = '../../jbfs/uploads/videos/shares/' . $_GET['video'];
		
	
	// Check that the video exists and is a file:
	
	if (!file_exists($video) || (!is_file($video))) {
		
		$video = FALSE;
		
	}
}	

// If there was a problem, use the default image:

if (!$video) {
	
	echo 'video not available.';
	
} else{


	// Get the video information:
	
	$mime = mime_content_type($video);
	
	$fs = filesize($video);
	
	header ("content-type: $mime\n");
	
	header ("content-disposition: inline; filename=\"$vname\"\n");
	
	header ("content-length: $fs\n");
	
	// Send the file:
	
	readfile ($video);
	
	
	
}
	
	?>