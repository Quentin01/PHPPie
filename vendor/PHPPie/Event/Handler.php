<?php

/*
 * Class Event Handler
 * Created on 01/04/12 at 12:37
 */

namespace PHPPie\Event;

class Handler {
	protected static $eventListeners = array();
	
	protected function __construct() {}
	
	public static function attach(\PHPPie\Event\Listener $listener)
	{
		self::$eventListeners[] = $listener;
	}
	
	public static function fireEvent($name, $parameters)
	{
		foreach(self::$eventListeners as $eventListener)
		{
			if($eventListener->event($name, $parameters))
				return true;
		}
		
		return false;
	}
}
