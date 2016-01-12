<?php
function __autoload($class) {include_once "model/$class.php";}

// Arg
function get($field) {return isset($_GET[$field]) ? $_GET[$field] : null;}
function post($field) {return isset($_POST[$field]) ? $_POST[$field] : null;}

function ls($dir, $callback=null) {
	$result = array();
	$realdir = realpath($dir);
	foreach(scandir($realdir) as $file) {
		$realfile = realpath("$realdir/$file");
		$result[] = $callback ? $callback($realfile, $file) : $realpath;
	} return $result;
}

// Hop contrainte facile
function constrain($x, $min, $max) {
	return ($x < $min) ? $min : (($max < $x) ? $max : $x);
}

// Mici le net
function grab_dump($var) {
    ob_start();
    var_dump($var);
    return ob_get_clean();
}