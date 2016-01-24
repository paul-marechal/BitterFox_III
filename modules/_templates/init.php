<?php

$MODULE = array(
	'default' => function() {
		// Retour JSON
		$templates = array();

		ls('modules', function($modpath, $module)
			use (&$templates) {
			
			ls("$modpath/templates", function($path, $file)
				use (&$templates) {
				
				$data = file_get_contents($path);
				$data = str_replace(array("\n", "\t", "\r"), '', $data);
				$templates[basename($file, '.html')] = $data;
			});
		});

		return $templates;
	}
);