<?php

$MODULE = array(
	'script' => function() {
		$path = post('path');
		$FILE = new File($path);
		if ($FILE->ext() != 'sh') throw new Exception('Not a .sh');
		$script = $FILE->path;
		return shell_exec("\"$script\"");
		// return shell_exec("ps -x");
	}
);