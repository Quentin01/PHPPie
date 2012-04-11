<?php

/*
 * Class Event Listener
 * Created on 01/04/12 at 12:37
 */

namespace PHPPie\Event;

class Listener {
	protected $events = array();
	protected $eventsInUse = array();
	
	public function event($name, $parameters)
	{
		$methodName = 'on' . ucFirst($name);
		
		if(isset($eventsInUse[$name]) && $eventsInUse[$name] === true)
			return false;
		
		if(!method_exists($this, $methodName) && isset($this->events[$name]))
			$methodName = $this->events[$name];
		
		if(!method_exists($this, $methodName))
			return false;
			
		$eventsInUse[$name] = true;
			
		$reflectionMethod = new \ReflectionMethod($this, $methodName);
		$return = $reflectionMethod->invokeArgs($this, $parameters);
			
		$eventsInUse[$name] = false;
		
		return $return;
	}
}
