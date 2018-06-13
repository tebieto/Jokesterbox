<?php # - show_image.php

// This pages retrieves and shows an image.

// Flag variables:

$image = FALSE;


$name = (!empty($_GET['name'])) ? $_GET['name'] : 'jbdefault.jpg';

// Check for an image value in the url:

if ((isset($_GET['image'])) OR  isset($_GET['default'])) {
	
	if (isset($_GET['dcp']) OR isset($_GET['dpp'])) {
	
	if (isset($_GET['dcp'])) {
		
		$image = '../../jbfs/uploads/default/cover.jpg';
		
	} elseif(isset($_GET['dpp'])) {
		
		$image = '../../jbfs/uploads/default/pp.jpg';
		
	} else {
		
		$image = FALSE;
		
	}
	} else {
		
	// Full image path
	
	if (isset($_GET['image'])) {
		
		if(isset($_GET['cp'])) {
	$image = '../../jbfs/uploads/pictures/covers/' . $_GET['image'];
		} else {
			
			if(isset($_GET['pp'])) {
	$image = '../../jbfs/uploads/pictures/pps/' . $_GET['image'];
		}}
	}
	}
	
	// Check that the image exists and is a file:
	
	if (!file_exists($image) || (!is_file($image))) {
		
		$image = FALSE;
		
	}
	
}

// If there was a problem, use the default image:

if (!$image) {
	
	echo 'Image not available.';
	
} else{


	// Get the image information:
	
	$info = getimagesize($image);
	
	$fs = filesize($image);
	
	header ("content-type:{$info['mime']}\n");
	
	header ("content-disposition: inline; filename=\"$name\"\n");
	
	header ("content-length: $fs\n");
	
	// Send the file:
	
	readfile ($image);
	
}
	
	?>