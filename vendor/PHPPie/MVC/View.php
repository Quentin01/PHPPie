<?php

/*
 * View class of Framework
 * Created on 10/03/12 at 19:30
 */

namespace PHPPie\MVC;

abstract class View {
	protected static $kernel = null;
	protected $pathFile;
	protected $variables = array();
	
	public function __construct(\PHPPie\Core\KernelInterface $kernel, $pathfile = null)
	{
		self::$kernel = $kernel;
		$this->setPathfile($pathfile);
	}
	
	public function setPathfile($pathfile = null)
	{
		$this->pathFile = (is_string($pathfile) || is_null($pathfile)) ? $pathfile : (string) $pathfile;
		
		if(!is_null($this->pathFile) && $this->viewExists($this->pathFile) === false) 
		{
			throw new \PHPPie\Exception\Exception('The view '.$this->pathFile.' doesn\'t exists', 'PHPPie\MVC\View', 'setPathfile');
		}
	}
	
	public function getPathfile()
	{
		return $this->pathFile;
	}
	
	public function addVariables($variables)
	{
		$this->variables = array_merge($variables, $this->variables);
	}
	
	public function addVariable($name, $value)
	{
		$this->variables[$name] = $value;
	}
	
	public function delVariable($name)
	{
		if(isset($this->variables[$name]))
		{
			unset($this->variables[$name]);
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function viewExists($pathfile = null)
	{
		if(is_null($pathfile))
			$pathfile = $this->pathFile;
		
		$paths = $this->getRealPathfile($pathfile);
		foreach($paths as $path)
		{
			if(file_exists($path))
				return $path;
		}
		
		return false;
	}
	
	public function getRealPathfile($pathfile = null)
	{
		if(is_null($pathfile))
			$pathfile = $this->pathFile;
			
		$paths = self::$kernel->getPathViews();
		foreach($paths as &$path)
		{
			$path .= DIRECTORY_SEPARATOR . $pathfile . $this->getExtensionFile();
		}
		return $paths;
	}
	
	abstract public function render();
	abstract public function getExtensionFile();
}
