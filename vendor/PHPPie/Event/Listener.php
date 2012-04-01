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
			return;
		
		if(method_exists($this, $methodName))
		{
			$eventsInUse[$name] = true;
			
			$reflectionMethod = new \ReflectionMethod($this, $methodName);
			$reflectionMethod->invokeArgs($this, $parameters);
			
			$eventsInUse[$name] = false;
		}
		else
		{
			if(isset($this->events[$name]))
			{
				$eventsInUse[$name] = true;
				
				$reflectionMethod = new \ReflectionMethod($this, $this->events[$name]);
				$reflectionMethod->invokeArgs($this, $parameters);
				
				$eventsInUse[$name] = false;
			}
		}
	}
}
