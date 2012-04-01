<?php

/*
 * Class Event Listener
 * Created on 01/04/12 at 12:37
 */

namespace PHPPie\Event;

class Listener {
	protected $events = array();
	
	public function event($name, $parameters)
	{
		$methodName = 'on' . ucFirst($name);
		
		if(method_exists($this, $methodName))
		{
			$reflectionMethod = new \ReflectionMethod($this, $methodName);
			$reflectionMethod->invokeArgs($this, $parameters);
		}
		else
		{
			if(isset($this->events[$name]))
			{
				$reflectionMethod = new \ReflectionMethod($this, $this->events[$name]);
				$reflectionMethod->invokeArgs($this, $parameters);
			}
		}
	}
}
