<?php
class File {
	private $_path;
	
	private $_type;
	private $_size;
	
	private $_write;
	private $_read;
	
	// Types des images courantes
	public static $imgTypes = array('png', 'gif', 'jpg', 'bmp');
		
	// Type des musiques courantes
	public static $mscTypes = array('mp3', 'wav', 'flv', 'wma', 'ogg');
	
	// Création d'un fichier
	public static function create($path, $chmod=777) {
		$err = @_touch($path) === false;
		_chmod($path, $chmod);
		return $err;
	}
	
	// Suppression
	public static function delete($path) {
		if (_is_dir($path)) return false;
		$err = !@_unlink($path);
		return $err;
	}
	
	// Déverrouillage
	public static function unlock($path) {
		return !@_chmod($path, 777);
	}
	
	// Est-ce que le fichier existe ?
	public function exists() {return file_exists($this->_path);}
	
	// El Portugesh
	public function __construct($path) {
		if (_file_exists($path)) {
			/*$name = basename($path);
			if ($name == '.'
			 or $name == '..') { // Les deux fichiers zarbis
				throw new Exception("Le fichier est zarb: '$name'");
			// Sinon on fait normalement
			} else*/
			$this->_path = _realpath($path);
		} else throw new Exception("Le fichier n'existe pas: $path");
		
		$this->_write = $this->isWritable();
		$this->_read = $this->isReadable();
		
		if ($this->isFolder()) $this->_type = 'folder';
		elseif ($this->isFile()) $this->_type = 'file';
		else $this->_type = 'unknown';
	}
	
	// Equivalent get_file_contents
	public function read() {
		return @_file_get_contents($this->_path);
	}
	
	// Equivalent file_put_contents
	public function write($data) {
		return @_file_put_contents($this->_path, $data);
	}
	
	// Tests
	public function isWritable(){return _is_writable($this->_path);}
	public function isReadable(){return _is_readable($this->_path);}
	public function isFolder()	{return _is_dir($this->_path);}
	public function isFile()	{return !_is_dir($this->_path) and _is_file($this->_path);}
	public function isImg()		{return in_array($this->ext(), File::$imgTypes);}
	public function isMusic()	{return in_array($this->ext(), File::$mscTypes);}
	public function isUnknown()	{return !$this->isFile();}
	
	// On récupère l'image en elle même
	public function thumbnail() {
		if ($this->isReadable() and $this->isImg())
			 return './lakarte/?thumbnail='.urlencode($this->_path);
		else return './skin/'.$this->_type.'.png';
	}
	
	// Get basename
	public function base() {
		return basename($this->_path);
	}
	
	// Get extension
	public function ext() {
		return pathinfo($this->_path, PATHINFO_EXTENSION);
	}
	
	// Get size
	public function size() {
		return $this->isFolder() ? 0 : filesize($this->_path);
	}
	
	// Get owner
	public function owner() {
		return posix_getpwuid(fileowner($this->_path));
	}
	
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
	
	// On envoie le fichier
	public function toArray() {
		return array(
			'path' => $this->_path,
			'type' => $this->_type,
			'name' => $this->base(),
			'ext' => $this->ext(),
			// 'size' => $this->size(),
			'size' => 'eheh',
			// 'owner' => $this->owner(),
			'owner' => 'not implemented',
			'w' => $this->isWritable(),
			'r' => $this->isReadable(),
		);
	}
	
	// ToString \o/
	public function __toString() {
		return $this->base().": '$_path [size:$_size]\n'";
	}
}