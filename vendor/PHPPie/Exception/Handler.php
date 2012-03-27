<?php

/*
 * Class Exception Handler
 * Created on 08/02/12 at 14:25
 */

namespace PHPPie\Exception;

class Handler {
	public static function initialize()
	{	
        set_error_handler(array(__CLASS__, 'errorHandler'));
        set_exception_handler(array(__CLASS__, 'exceptionHandler'));
        set_exception_handler(array(__CLASS__, 'nativeExceptionHandler'));
    }

	public static function errorHandler($errno, $errstr, $errfile, $errline) {
		$exception = new \PHPPie\Exception\Exception($errstr);
		$exception->file = $errfile;
		$exception->line = $errline;
		
		static::exceptionHandler($exception);
    }
    
    public static function exceptionHandler(\PHPPie\Exception\Exception $exception) {
		ob_get_clean();
		
		$response = \PHPPie\Core\StaticContainer::getService('http.response');
		$response->setStatusCode($exception->statusCode);
		
		$view = \PHPPie\Core\StaticContainer::getService('view');
		
		if($view->viewExists('errors/' . $exception->statusCode) || $view->viewExists('error')
		{
			if($view->viewExists('errors/' . $exception->statusCode))
				$view->setPathfile('errors/' . $exception->statusCode);
			else
				$view->setPathfile('error');
				
			$view->addVariable('message', (string) $exception);
			$response->setContent($view->render());
		}
		else
		{
			$response->setContent((string) $exception);
		}
		
		$response->send();
		exit();
    }
    
    public static function nativeExceptionHandler(\Exception $exception) {
		$newException = new \PHPPie\Exception\Exception($exception->getMessage());
		$newException->trace = $exception->getTraceAsString();
		$newException->file = $exception->getFile();
		$newException->line = $exception->getLine();
		
		static::exceptionHandler($newException);
    }
}
