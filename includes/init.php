<?php

function loadModules() {
	ls('modules', function($modpath, $modfile) {
		$modscripts = "$modpath/scripts";
		$modstyles = "$modpath/"
		ls($modscripts, function($subpath, $subfile) {
		});
	});
}

if ($module = get$('m')) {
	if (file_exists($modinit = "modules/_$module/init.php"))
		include_once $modinit;
	else exit();
} else {
	include_once "views/home.php";
}

/* JS

function copy(files) {
	var sync = {"total": files.length, "count": 0};
	$.each(files, function(i, file) {
		sync.total += 1;
		$.post(caca, function(data) {
			// Ca marche
			//...
			sync.count += 1;
		}).fail(function(data) {
			// Ca marche pas
			sync.count +=1;
		});
	})
}*/