<?php

function loadModules() {
	
	// Code HTML d'importation
	$scripts = '';
	$styles = '';
	
	// Liste des modules
	ls('modules', function($modpath, $modfile)
		use (&$scripts, &$styles) {
		
		// Fichiers de scripts/styles
		$modscripts = "$modpath/scripts";
		$modstyles = "$modpath/styles";
		
		// echo "$modscripts<br/>";
		
		// Chargement des scripts
		ls($modscripts, function($subpath, $subfile)
			use (&$scripts, $modfile) {
			$scripts .= "<script src='./modules/$modfile/scripts/$subfile'></script>";
		}, 'is_file');
		
		// Chargement des styles
		ls($modstyles, function($subpath, $subfile)
			use (&$styles, $modfile) {
			$styles .= "<link rel='stylesheet' type='text/css' href='./modules/$modfile/styles/$subfile'/>";
		}, 'is_file');
		
	}, 'is_dir');
	
	// On retourne tout
	return array(
		'styles' => $styles."\n",
		'scripts' => $scripts."\n"
	);
}

$init = false;
if ($module = get('m')) {
	$init = file_exists($modinit = "modules/_$module/init.php") ? 'm' : null;
} elseif ($module = get('n')) {
	$init = file_exists($modinit = "modules/_$module/jnjt.php") ? 'n' : null;
}

if ($init) {
		
	// Petit système REST
	include_once $modinit;
	
	// Si une action est définie
	if (!$action = post('action'))
		$action = 'default';
	
	// Si on demande un module en json
	if ($init == 'm') {
		header('Content-Type: application/json');
		try {
			echo json_encode($MODULE[$action]());
		} catch (Exception $e) {
			echo json_encode(array(
				'error' => true,
				'data' => $e->getMessage()
			));
		}
	
	// Si on demande un module yolo
	} elseif ($init == 'n') {
		try {
			echo $MODULE[$action]();
		} catch (Exception $e) {
			$err = $e->getMessage();
			echo "<div class='php-error'>$err</div>";
		}
	}
	
} else {
	$APPNAME = basename(realpath('.')).' as "'.get_current_user().'"';
	include_once "views/home.php";
} exit();

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