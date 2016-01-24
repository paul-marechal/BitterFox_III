<?php

$MODULE = array(
	'getImage' => function() {
		$path = post('path');
		$FILE = new File($path);
		if (!$FILE->isImg())
			throw new Exception("C'est pas une image ! '$path'");
		return array(
			'info' => $FILE->toArray(),
			'vtff' => 'Kilian est une pute    . :, ;:!',
		);
	}
);