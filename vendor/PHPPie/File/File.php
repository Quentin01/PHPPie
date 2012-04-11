<?php

/*
 * Class files manager
 * Created on 23/10/11 at 09:34
 */
 
namespace PHPPie\File;
 
abstract class File {
	protected $path;
	
	public static function create($path, $contents = null)
	{
		$file = new static($path, true);
		
		if(!is_null($contents))
		{
			if(is_string($contents))
				$file->setContents($contents);
			else
				$file->writeData($contents);
		}
			
		return $file;
	}
	
	public function __construct($path, $create = false)
	{
		$this->path = realpath($path);
		
		if(empty($this->path))
			$this->path = $path;
		
		if(!$this->exists() && $create)
			$this->setContents('');
	}
	
	public function __get($name)
	{
		$methodName = "get" . ucfirst($name);
		
		if(method_exists($this, $methodName))
			return $this->$methodName();
		else
			return null;
	}
	
	public function __tostring() {
        return $this->getPath();
    }
	
	public function exists()
	{
		return file_exists($this->path);
	}
	
	public function getSize()
	{
		if($this->exists())
			return filesize($this->path);
		else
			return null;
	}
	
	public function getPath()
	{
		return $this->path;
	}
	
	public function getName()
	{
		return basename($this->path);
	}
	
	public function getExtension()
	{
		return substr(strrchr(basename($this->getName()), '.'), 1);
	}
	
	public function getContents()
	{
		if($this->exists())
			return file_get_contents($this->path);
		else
			return null;
	}
	
	public function getTime()
	{
		if($this->exists())
			return filemtime($this->path);
		else
			return null;
	}
	
	public function setContents($contents)
	{
		$return = file_put_contents($this->path, $contents);
		$this->path = realpath($this->path);
		return $return;
	}
	
	public function del()
	{
		return unlink($this->path);
	}
	
	abstract public function readData();
    abstract public function writeData($data);
}

