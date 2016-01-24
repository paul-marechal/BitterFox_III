<?php

class Folder {
	private $_path;
	private $_tree;
	
	// Création d'un dossier
	public static function create($path, $chmod=0777) {
		$err = @mkdir($path) === false;
		chmod($path, $chmod);
		return $err;
	}
	
	// Suppression
	public static function delete($path) {
		if (!is_dir($path)) return false;
		
		ls($path, function($subpath, $subfile) {
			if (is_dir($subpath))
				 @Folder::delete($subpath);
			else @File::delete($subpath);
		});
		
		$err = !@rmdir($path);
		return $err;
	}
	
	// Déverrouillage
	public static function unlock($path) {
		return !@chmod($path, 0777);
	}
	
	// El Portugesh
	public function __construct($path) {
		$this->setPath($path);
	}
	
	// On change de chemin
	public function setPath($path) {
		
		// Update
		if ($path == '*') $path = $this->_path;
		
		$path = realpath($path);
		if (file_exists($path) and is_dir($path)) {
			if (is_readable($path)) {
				$this->_path = $path;
				$this->_tree = scandir($this->_path);
				$this->_size = count($this->_tree);
				return $this->_path;
			} else throw new Exception("Can't read dir: $path");
		} else throw new Exception("Not a dir: $path");
	}
	
	// On prends un bon bout de machin
	public function chunk($start, $size, $filter=null, $format=null) {
		$files = array();
		
		// On s'assure que le chunk ne déborde pas de l'arborescence
		if ($this->size() < $start) throw new Exception('End of the tree');
		$size = constrain($size, 0, $this->size());
		
		// On fetch en maaasse
		for($i = $start; $i < $start + $size; $i++) {
			$file = $this->getFile($i);
			if ($filter ? $filter($file) : true) {
				if($file !== false) {
					$files[] = $format ? $format($file) : $file;
				}
			}
		}
		
		// Puis on envoie le tout
		return $files;
	}
	
	public function getFiles($filter=null, $format=null) {
		$files = array();
		
		// On fetch en maaasse
		foreach($this->_tree as $file) {
			$realpath = $this->_path."/$file";
			$FILE = new File($realpath);
			if ($filter ? $filter($FILE) : true) {
				if($file !== false) {
					$files[$file] = $format ? $format($FILE) : $FILE;
				}
			}
		}
		
		// Puis on envoie le tout
		return $files;
	}
	
	public function isWritable() {return is_writable($this->_path);}
	
	// Get basename
	public function base() {
		return basename($this->_path);
	}
	
	// Get owner
	public function owner() {
		return posix_getpwuid(fileowner($this->_path));
	}
	
	// Pour savoir hein
	function size() {
		return count($this->_tree);
	}
	
	// Pour le debug
	function dump_tree() {
		return grab_dump($this->_tree);
	}
	
	// Renvoie un fichier d'après le dossier mis en cache
	public function getFile($id) {
		if (isset($this->tree[$id]))
			 return new File($this->_path. '/'. $this->tree[$id]);
		else return false;
	}
	
	// On se balade avec un nom de fichier ou un ID
	public function navigate($file) {$this->setPath($this->_path."/$file");}
	public function navById($id) {$this->navigate($this->_tree[$id]);}
	
	// Magic getter !
	public function __get($attr) {
		$var = "_$attr";
		if (property_exists(get_class($this), $var))
			return $this->$var;
		else throw new Exception("Variable Inconnue: $attr");
	}
	
	/* Peut-être pas besoin ?
	// Magic setter !
	public function __set($attr, $value) {
		$var = "_$attr";
		if (property_exists(get_class($this), $var))
			$this->$var = $value;
		else throw new Exception('Variable Inconnue');
	}//*/
	
	public function toFile() {
		return new File($this->_path);
	}
	
	public function toFileArray() {
		return $this->toFile()->toArray();
	}
	
	// ToString \o/
	public function __toString() {
		return "$_id: '$_path\n'";
	}
}