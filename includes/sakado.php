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
	$realdir = _realpath($dir);
	if (empty($realdir) and !_is_dir($realdir)) return false;
	
	foreach(_scandir($realdir) as $file) {
		if ($zap and ($file == '.' or $file == '..')) continue;
		$realfile = _realpath("$realdir/$file");
		if ($filter ? $filter($realfile) : true)
			$result[] = $callback ? $callback($realfile, $file) : $realpath;
	} return $result;
}

// Petite récursive
function recurse($path, $callback) {
	$path = _realpath($path);
	foreach(_scandir($path) as $file) {
		if ($file == '.' or $file == '..') continue;
		$realfile = _realpath("$path/$file");
		if (_is_dir($realfile)) recurse($realfile, $callback);
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

// ############################################################################################ //
// 											AAAAARG
// ############################################################################################ //
// /*

function trim_exec($cmd) {
	return trim(shell_exec($cmd));
}

function _realpath($path) {
	$real = trim_exec("readlink -f \"$path\"");
	return $real ? $real : false;
}

function _sh($script, $file) {
	$real = _realpath($file);
	return trim_exec("\"./sh/$script.sh\" \"$real\"");
}

function _file_exists($file) {
	return _sh('file_exists', $file) == 'exists';
}

function _file_type($file) {
	return _sh('file_type', $file);
}

function _is_dir($file) {
	return _file_type($file) == 'dir';
}

function _is_file($file) {
	return _file_type($file) == 'file';
}

function _is_writable($file) {
	return _sh('is_writable', $file) == 'writable';
}


function _is_readable($file) {
	return _sh('is_readable', $file) == 'readable';
}

function _scandir($dir = '.') {
	$real = _realpath($dir);
	$files = trim_exec("ls -ah \"$real\"");
	if (empty($files)) return;
	
	$list = explode("\n", $files);
	foreach($list as $id => $file)
		yield $id => $file;
}

function _file_get_contents($file) {
	if (_file_type($file) != 'file') return false;
	$real = _realpath($file);
	
	return trim_exec("cat \"$real\"");
}

function _file_put_contents($file, $data) {
	global $TMP;
	
	$real = _realpath($file);
	$tmp = "$TMP/tmp";
	file_put_contents($tmp, $data);
	return trim_exec("cat \"$tmp\">\"$real\" && echo ok");
}

function _unlock($file) {
	return _chmod($file, 777);
}

function _touch($file) {
	return trim_exec("touch \"$file\" && echo ok");
}

function _mkdir($dir) {
	return trim_exec("mkdir \"$dir\" && echo ok");
}

function _unlink($file) {
	return trim_exec("unlink \"$file\" && echo ok");
}

function _chmod($file, $mod=777) {
	return trim_exec("chmod $mod \"$file\" && echo ok");
}

/**/