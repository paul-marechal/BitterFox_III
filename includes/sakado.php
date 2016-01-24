<?php
// Autoloader...
function __autoload($class) {include_once "models/$class.php";}

function reload($path='.') {
	header("Location: $path");
	exit();
}

// Arg...
function get($field) {return isset($_GET[$field]) ? $_GET[$field] : null;}
function post($field) {return isset($_POST[$field]) ? $_POST[$field] : null;}

// Listing d'un dossier et extraction chemin/fichier
// args: directory, callback, filter, ignorePoints?
function ls($dir, $callback=null, $filter=null, $zap=true) {
	$result = array();
	$realdir = realpath($dir);
	if (empty($realdir) and !is_dir($realdir)) return false;
	
	foreach(scandir($realdir) as $file) {
		if ($zap and ($file == '.' or $file == '..')) continue;
		$realfile = realpath("$realdir/$file");
		if ($filter ? $filter($realfile) : true)
			$result[] = $callback ? $callback($realfile, $file) : $realpath;
	} return $result;
}

// Petite récursive
function recurse($path, $callback) {
	$path = realpath($path);
	foreach(scandir($path) as $file) {
		if ($file == '.' or $file == '..') continue;
		$realfile = realpath("$path/$file");
		if (is_dir($realfile)) recurse($realfile, $callback);
		else $callback($realfile);
	} $callback($path);
}

// Hop contrainte facile
function constrain($x, $min=null, $max=null) {
	if (!is_null($min)) $x = ($x < $min) ? $min : $x;
	if (!is_null($max)) $x = ($x > $max) ? $max : $x;
	return $x;
}

// Mici le net
function grab_dump($var) {
    ob_start();
    var_dump($var);
    return ob_get_clean();
}