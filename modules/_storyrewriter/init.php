<?php

$MODULE = array(
	'getContent' => function() {
		$path = post('path');
		$FILE = new File($path);
		if ($FILE->type == 'folder')
			throw new Exception("C'est un dossier ! '$path'");
		return array(
			'info' => $FILE->toArray()
		);
	},
	
	'rewrite' => function() {
		$path = post('path');
		if (!$content = post('content'))
			throw new Exception("Attention, contenu vide...");
		$FILE = new File($path);
		if ($FILE->type == 'folder')
			throw new Exception("C'est un dossier ! '$path'");
		$FILE->write($content);
		return array(
			'info' => $FILE->toArray()
		);
	}
);