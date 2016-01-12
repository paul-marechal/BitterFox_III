<?php

chdir('../'); // On se déplace à la racine
include_once 'includes/sakado.php';

// Ben voui on veut gérer les images
header("Content-type: image");

if ($thumbnail = get('thumbnail')) {
	try {
		$file = new File($thumbnail);
		echo $file->read();
	} catch (Exception $e) {
		echo file_get_contents('skin/image.png');
	}
}