<?php

/*
 * Controller MVC of Framework
 * Created on 07/03/12 at 19:06
 */

namespace PHPPie\MVC;

class Controller {
	protected $kernel;
	
	public function __construct(\PHPPie\Core\KernelInterface $kernel)
	{
		$this->kernel = $kernel;
	}
	
	public function __runAction($controller, $action)
	{
		if(!is_callable(array($this, $action)))
            throw new \PHPPie\Exception\Exception('Action '.$action.' doesn\'t exists in the controller '.$controller.'', 'PHPPie\MVC\Controller', '__runAction');
		
		return $this->$action();
	}
	
	public function __get($name)
	{
		return $this->get($name);
	}
	
	public function get($name)
	{
		$parameters = func_get_args();
		array_shift($parameters);
		
		if(isset($this->$name))
			return $this->$name;
			
		if($this->kernel->container->hasService($name))
		{
			$reflectionMethod = new \ReflectionMethod($this->kernel->container, 'getService');
			return $reflectionMethod->invokeArgs($this->kernel->container, array_merge(array($name), $parameters));
		}
			
		if($this->kernel->container->hasParameter($name))
			return $this->kernel->container->getParameter($name);
			
		if(isset($this->kernel->$name))
			return $this->kernel->$name;
			
		return null;
	}
}
?>
