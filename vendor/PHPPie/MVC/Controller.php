<?php

/*
 * Controller MVC of Framework
 * Created on 07/03/12 at 19:06
 */

namespace PHPPie\MVC;

class Controller {
	public function __construct()
	{
		
	}
	
	public function __runAction($controller, $action)
	{
		if(!is_callable(array($this, $action)))
            throw new \PHPPie\Exception\Exception('Action '.$action.' doesn\'t exists in the controller '.$controller);
		
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
			
		if(\PHPPie\Core\StaticContainer::hasService($name))
		{
			$reflectionMethod = new \ReflectionMethod(\PHPPie\Core\StaticContainer::$instance, 'getService');
			return $reflectionMethod->invokeArgs(\PHPPie\Core\StaticContainer::$instance, array_merge(array($name), $parameters));
		}
			
		if(\PHPPie\Core\StaticContainer::hasParameter($name))
			return \PHPPie\Core\StaticContainer::getParameter($name);
			
		$kernel = \PHPPie\Core\StaticContainer::getService('kernel');
		
		if(isset($kernel->$name))
			return $kernel->$name;
			
		return null;
	}
}
?>
