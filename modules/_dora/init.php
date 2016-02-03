<?php

$MODULE = array(
	'getFiles' => function() {
		$path = post('path');
		$FOLDER = new Folder($path);
		return array(
			'files' => $FOLDER->getFiles(function($file) use ($FOLDER) {
				return $file->path != $FOLDER->path;
			}, function($file) {
				return $file->toArray();
			}),
			'info' => $FOLDER->toFileArray()
		);
	},
	
	'newFile' => function() {
		$path = post('path');
		return array(
			'error' => File::create($path)
		);
	},
	
	'newFolder' => function() {
		$path = post('path');
		return array(
			'error' => Folder::create($path)
		);
	},
	
	'delete' => function() {
		$path = post('path');
		$err = _is_dir($path) ? Folder::delete($path) : File::delete($path);
		return array(
			'error' => $err
		);
	},
	
	'unlock' => function() {
		$path = post('path');
		return array(
			'error' => File::unlock($path)
		);
	}
);