<?php

/*
 * Class Exception Handler
 * Created on 08/02/12 at 14:25
 */

namespace PHPPie\Exception;

class Handler {
	public static $debug = false;
	public static $kernel = null;
	
	public static function initialize(\PHPPie\Core\KernelInterface &$kernel, $debug)
	{
		self::$debug = $debug;
		self::$kernel = $kernel;
		
        set_error_handler(array(__CLASS__, 'errorHandler'));
        set_exception_handler(array(__CLASS__, 'exceptionHandler'));
    }

	public static function errorHandler($errno, $errstr, $errfile, $errline) {
		$exception = new \PHPPie\Exception\Exception($errstr);
		$exception->file = $errfile;
		$exception->line = $errline;
		
		throw $exception;
    }
    
    public static function exceptionHandler(\PHPPie\Exception\Exception $exception) {
		$response = self::$kernel->container->getService('http.response');
		
		if(!is_null($exception->statusCode)) $response->setStatusCode($exception->statusCode);
		$response->setContent($exception);
		
		$response->send();
		exit();
    }
}
