<?php

/*
 * Class Exception Handler
 * Created on 08/02/12 at 14:25
 */

namespace PHPPie\Exception;
use \PHPPie\Event\Handler as EventHandler;

class Handler {
	public static function initialize()
	{	
        set_error_handler(array(__CLASS__, 'errorHandler'));
        set_exception_handler(array(__CLASS__, 'exceptionHandler'));
    }

	public static function errorHandler($errno, $errstr, $errfile, $errline) {
		$exception = new \PHPPie\Exception\Exception($errstr);
		$exception->file = $errfile;
		$exception->line = $errline;
		
		static::exceptionHandler($exception);
    }
    
    public static function exceptionHandler($e) {
    	if($e instanceof \PHPPie\Exception\Exception) {
			$exception = $e;
		} else {
			$exception = new \PHPPie\Exception\Exception($e->getMessage());
			$exception->trace = $exception->getTraceAsString();
			$exception->file = $exception->getFile();
			$exception->line = $exception->getLine();
		}
    
		ob_get_clean();
		
		EventHandler::fireEvent('exceptionThrown', array(&$exception));
		
		$response = \PHPPie\Core\StaticContainer::getService('http.response');
		$response->setStatusCode($exception->statusCode);
		
		$message = (\PHPPie\Core\StaticContainer::getService('kernel')->debug) ? (string) $exception : "<h1>" . (($exception->statusCode !== 200) ? $exception->statusCode . " - " : "") . $response->getStatusText() . "</h1>";
		
		$view = \PHPPie\Core\StaticContainer::getService('view');
		
		if($view->viewExists('errors/' . $exception->statusCode) || $view->viewExists('error'))
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
		\PHPPie\Core\StaticContainer::getService('autoloader')->save();
		
		exit();
    }
}
