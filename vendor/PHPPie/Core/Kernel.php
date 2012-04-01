<?php

/*
 * Core class of Framework
 * Created on 25/10/11 at 11:17
 */

namespace PHPPie\Core;
use \PHPPie\Event\Handler as EventHandler;

class Kernel implements KernelInterface {
    public $dev;
    public $debug;
    public $dirFrontController;
    
    public $container;
    public $autoloader;
    
    protected $pathViews = array();
    
    public function __construct($dirFrontController, \PHPPie\Autoload\Autoloader $autoloader, \PHPPie\Cache\CacheInterface $cacheManager, $dev = false)
    {
		\PHPPie\Exception\Handler::initialize($this, $dev);
		
        $this->dev = $dev;
        $this->debug = $this->dev;
        
        $this->dirFrontController = $dirFrontController;

		$this->autoloader = $autoloader;
        $this->container = new Container($this, $this->autoloader, $cacheManager);
    }
    
    public function run()
    {
        $request = $this->container->getService('http.request');        
        $routingURI = substr($request->getURI(), strlen(dirname($request->server->offsetGet('SCRIPT_NAME'))));
        
        EventHandler::fireEvent('getRoutingURI', array(&$routingURI));
        
        $route = $this->container->getService('router')->resolve($routingURI);
        
        if($route === false)
        {
			EventHandler::fireEvent('routeNotFound', array(&$routingURI));

			if(($pos = strpos($routingURI, '/web/')) !== false && file_exists(($pathfile = $this->dirFrontController . substr($routingURI, $pos))))
			{
				EventHandler::fireEvent('assetFileFound', array(&$routingURI, &$pathfile));
				
				$response = $this->container->getService('http.response');
				$finfo = new \finfo(FILEINFO_MIME);
				
				$response->setContent(file_get_contents($pathfile));
				
				if(!$response->hasHeader('Content-Type'))
					$response->setHeader('Content-Type', $finfo->file($pathfile));
				
				$response->send();
			}
			else
			{
				EventHandler::fireEvent('assetFileNotFound', array(&$routingURI));
				throw new \PHPPie\Exception\Exception('Route not found for this URI : '.$routingURI, 404);
			}
		}
		else
		{
			$parameters = $route->getParameters();
			
			if(!isset($parameters['_controller']) && !isset($parameters['_view']))
				throw new \PHPPie\Exception\Exception('No controller defined for this route : '.$routingURI, 404);
			
			if(!isset($parameters['_action']) && isset($parameters['_controller']))
			{
				$data = $this->findControllerAndAction($parameters['_controller']);
				$parameters['_controller'] = $data['controller'];
				$parameters['_action'] = $data['action'];
			}
			
			EventHandler::fireEvent('controllerAndActionDefined', array(&$parameters['_controller'], &$parameters['_action']));
			
			$request->get->append($parameters);
			
			if(isset($parameters['_view']))
			{
				$view = $parameters['_view'];
				
				if(isset($parameters['_controller']))
					$view = str_replace('\\', DIRECTORY_SEPARATOR, $parameters['_controller']) . DIRECTORY_SEPARATOR . $parameters['_view'];
					
				EventHandler::fireEvent('viewDefined', array(&$view));
					
				$response = $this->container->getService('http.response');
				$response->setContent($this->container->getService('view', $view)->render());
			}
			else
			{
				$response = $this->executeController($parameters['_controller'], $parameters['_action']);
			}
			
			$response->send();
		}
    }
    
    public function findControllerAndAction($string)
    {
		$data = explode(':', $string);
			
		return array(
			'controller' => $data[0],
			'action' => (isset($data[1])) ? $data[1] : "index",
		);
	}
	
	public function executeController($controllerName, $action)
	{
		EventHandler::fireEvent('executeController', array(&$controllerName, &$action));
		
		if(!$this->autoloader->loadClass($controllerName))
            throw new \PHPPie\Exception\Exception('Controller '.$controllerName.' doesn\'t exists', 404);
            
        $reflectionClass = new \ReflectionClass($controllerName);
        $controller = $reflectionClass->newInstanceArgs(array($this));
        
        $returnController = $controller->__runAction($controllerName, $action);
        $defaultView = str_replace('\\', DIRECTORY_SEPARATOR, $controllerName) . DIRECTORY_SEPARATOR . $action;
        
        if(is_string($returnController))
		{
			// $returnController is the view pathfile
			
			EventHandler::fireEvent('viewDefined', array(&$returnController));
			
			$response = $this->container->getService('http.response');
			$response->setContent($this->container->getService('view', $returnController)->render());
		}
		elseif(get_class($returnController) == $this->container->getParameter('http.response.class'))
		{
			// $returnController is the http response
			
			$response = $returnController;
		}
		elseif(get_class($returnController) == $this->container->getParameter('view.class'))
		{
			// $returnController is the view
			
			if(is_null($returnController->getPathfile()))
				$returnController->setPathfile($defaultView);
			
			EventHandler::fireEvent('viewClassDefined', array(&$returnController));
			
			$response = $this->container->getService('http.response');
			$response->setContent($returnController->render());
		}
		elseif(is_null($returnController) || $returnController === false)
		{
			// $returnController is null, the default view is used ( {controller}/{action} )
			
			EventHandler::fireEvent('viewDefined', array(&$defaultView));
			
			$response = $this->container->getService('http.response');
			$response->setContent($this->container->getService('view', $defaultView)->render());
		}
		else
		{
			throw new \PHPPie\Exception\Exception('The controller '.$controllerName.' has returned a wrong value with the action '.$action);
		}
		
		EventHandler::fireEvent('responseDefined', array(&$response));
		return $response;
	}
    
    public function getPathApp($real = true)
    {
        return (($real) ? realpath($this->dirFrontController) : $this->dirFrontController).DIRECTORY_SEPARATOR.'app';
    }
    
    public function getPathConfig($real = true)
    {
        return $this->getPathApp($real).DIRECTORY_SEPARATOR.'config';
    }
    
    public function getPathCache($real = true)
    {
        return $this->getPathApp($real).DIRECTORY_SEPARATOR.'cache';
    }
    
     public function getPathControllers($real = true)
    {
        return $this->getPathApp($real).DIRECTORY_SEPARATOR.'controllers';
    }
    
    public function getPathModels($real = true)
    {
        return $this->getPathApp($real).DIRECTORY_SEPARATOR.'models';
    }
    
    public function getPathViews($real = true)
    {
        return array_merge(array(
			$this->getPathApp($real).DIRECTORY_SEPARATOR.'views'
		), $this->pathViews);
    }
    
    public function addPathViews($path)
    {
		$this->pathViews[] = $path;
	}
    
    public function getPathWeb($real = true)
    {
        return (($real) ? realpath($this->dirFrontController) : $this->dirFrontController).DIRECTORY_SEPARATOR.'web';
    }
    
    public function getPathCss($real = true)
    {
        return $this->getPathWeb($real).DIRECTORY_SEPARATOR.'css';
    }
    
    public function getPathImages($real = true)
    {
        return $this->getPathWeb($real).DIRECTORY_SEPARATOR.'images';
    }
    
    public function getPathJs($real = true)
    {
        return $this->getPathWeb($real).DIRECTORY_SEPARATOR.'js';
    }
    
    public function getContainerParameters()
    {
        return array(
            'pathApp' => $this->getPathApp(),
            'pathWeb' => $this->getPathWeb(),
            'pathCache' => $this->getPathCache(),
            'pathConfig' => $this->getPathConfig(),
            'pathControllers' => $this->getPathControllers(),
            'pathModels' => $this->getPathModels(),
            'pathViews' => $this->getPathViews(),
            'pathCss' => $this->getPathCss(),
            'pathImages' => $this->getPathImages(),
            'pathJs' => $this->getPathJs(),
            'pathRoot' => $this->dirFrontController,
        );
    }
}
?>
