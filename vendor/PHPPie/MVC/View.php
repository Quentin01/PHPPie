<?php

/*
 * View class of Framework
 * Created on 10/03/12 at 19:30
 */

namespace PHPPie\MVC;

abstract class View {
	protected $kernel;
	protected $pathFile;
	protected $variables = array();
	
	public function __construct(\PHPPie\Core\KernelInterface $kernel, $pathfile = null)
	{
		$this->kernel = $kernel;
		$this->setPathfile($pathfile);
	}
	
	public function setPathfile($pathfile = null)
	{
		$this->pathFile = (is_string($pathfile) || is_null($pathfile)) ? $pathfile : (string) $pathfile;
		
		if(!is_null($this->pathFile) && !file_exists($this->getRealPathfile()))
			throw new \PHPPie\Exception\Exception('The view '.$this->getRealPathfile().' doesn\'t exists', 'PHPPie\MVC\View', 'setPathfile');
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
	
	
	abstract public function getRealPathfile();
	abstract public function render();
}
