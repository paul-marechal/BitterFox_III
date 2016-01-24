<?php

$MODULE = array(
	'default' => function() {
		$path = get('path');
		$FILE = new File($path);
		$file = $FILE->base();
		
		header("Content-Type: application/x-please-download-me");
		header("Content-Disposition: attachment; filename=\"$file\"");
		return $FILE->read();
	}
);