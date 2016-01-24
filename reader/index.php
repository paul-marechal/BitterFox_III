<?php

chdir('../'); // On se déplace à la racine
include_once 'includes/sakado.php';

if ($thumbnail = get('thumbnail')) {
	header("Content-type: image");
	
	try {
		$file = new File($thumbnail);
		echo $file->read();
	} catch (Exception $e) {
		echo file_get_contents('skin/image.png');
	}
	
} else if ($read = get('read')) {
	
	// try {
		$file = new File($read);
		echo $file->read();
	// } catch (Exception $e) {}
}