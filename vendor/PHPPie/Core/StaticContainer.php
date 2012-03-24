<?php

/*
 * Class static of container of dependency Injection
 * Created on 25/03/12 at 00:22
 */

namespace PHPPie\Core;

class StaticContainer { 
	public static $instance = null;
	
	public static function __callStatic($name, $parameters)
	{
		if(!method_exists(self::$instance, $name))
			throw new \PHPPie\Exception\Exception('Container doesn\'t contains a method called  ' . $name, 'PHPPie\Core\StaticContainer', '__callstatic');
			
		$reflectionMethod = new \ReflectionMethod(self::$instance, $name);
		return $reflectionMethod->invokeArgs(self::$instance, $parameters);
	}
}
