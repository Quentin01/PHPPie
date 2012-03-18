<?php

/*
 * Core class of Framework
 * Created on 25/10/11 at 11:17
 */

namespace PHPPie\Core;

class Kernel implements KernelInterface {
    public $debug;
    public $dirFrontController;
    
    public $container;
    public $autoloader;
    
    protected $routingURI = null;
    
    public function __construct($dirFrontController, \PHPPie\Autoload\Autoloader $autoloader, \PHPPie\Cache\CacheInterface $cacheManager, $debug = false)
    {
		\PHPPie\Exception\Handler::initialize($this, $debug);
		
        $this->debug = $debug;
        $this->dirFrontController = $dirFrontController;

		$this->autoloader = $autoloader;
        $this->container = new Container($this, $this->autoloader, $cacheManager);
        
        $request = $this->container->getService('http.request');
        $this->routingURI = substr($request->getURI(), strlen(substr($dirFrontController, strlen($request->server->offsetGet('DOCUMENT_ROOT')))));
    }
    
    public function run()
    {
        $request = $this->container->getService('http.request');        
        $route = $this->container->getService('router')->resolve($this->routingURI);
        
        if($route === false)
        {
			if(($pos = strpos($this->routingURI, '/web/')) !== false && file_exists(($pathfile = $this->dirFrontController . substr($this->routingURI, $pos))))
			{
				$response = $this->container->getService('http.response');
				$finfo = new \finfo(FILEINFO_MIME);
				
				$response->setContent(file_get_contents($pathfile));
				$response->setHeader('Content-Type', $finfo->file($pathfile));
				
				$response->send();
			}
			else
			{
				throw new \PHPPie\Exception\Exception('Route not found for this URI : '.$this->routingURI, 'PHPPie\Core\Kernel', 'run', 404);
			}
		}
		else
		{
			$parameters = $route->getParameters();
					
			if(!isset($parameters['_controller']))
				throw new \PHPPie\Exception\Exception('No controller defined for this route : '.$this->routingURI, 'PHPPie\Core\Kernel', 'run', 404);
			
			if(!isset($parameters['_action']))
			{
				$data = $this->findControllerAndAction($parameters['_controller']);
				$parameters['_controller'] = $data['controller'];
				$parameters['_action'] = $data['action'];
			}
			
			$request->get->append($parameters);
			$this->executeController($parameters['_controller'], $parameters['_action'])->send();
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
		if(!$this->autoloader->loadClass($controllerName))
            throw new \PHPPie\Exception\Exception('Controller '.$controllerName.' doesn\'t exists', 'PHPPie\Core\Kernel', 'run');
            
        $reflectionClass = new \ReflectionClass($controllerName);
        $controller = $reflectionClass->newInstanceArgs(array($this));
        
        $returnController = $controller->__runAction($controllerName, $action);
        $defaultView = str_replace('\\', DIRECTORY_SEPARATOR, $controllerName) . DIRECTORY_SEPARATOR . $action;
        
        if(is_string($returnController))
		{
			// $returnController is the view pathfile
			
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
			
			$response = $this->container->getService('http.response');
			$response->setContent($returnController->render());
		}
		elseif(is_null($returnController) || $returnController === false)
		{
			// $returnController is null, the default view is used ( {controller}/{action} )
			
			$response = $this->container->getService('http.response');
			$response->setContent($this->container->getService('view', $defaultView)->render());
		}
		else
		{
			throw new \PHPPie\Exception\Exception('The controller '.$controllerName.' has returned a wrong value with the action '.$action.'', 'PHPPie\Core\Kernel', 'run');
		}
		
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
        return array(
			$this->getPathApp($real).DIRECTORY_SEPARATOR.'views'
		);
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
