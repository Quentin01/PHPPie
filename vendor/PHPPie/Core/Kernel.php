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
    
    public function __construct($dirFrontController, \PHPPie\Autoload\Autoloader $autoloader, \PHPPie\Cache\CacheInterface $cacheManager, $debug = false)
    {
		\PHPPie\Exception\Handler::initialize($this, $debug);
		
        $this->debug = $debug;
        $this->dirFrontController = realpath($dirFrontController);

		$this->autoloader = $autoloader;
        $this->container = new Container($this, $this->autoloader, $cacheManager);
    }
    
    public function run()
    {
        $request = $this->container->getService('http.request');
        $route = $this->container->getService('router')->resolve($request->getURI());
        
        if($route === false)
			throw new \PHPPie\Exception\Exception('Route not found for this URI : '.$request->getURI(), 'PHPPie\Core\Kernel', 'run', 404);
		
        $parameters = $route->getParameters();
                
        if(!isset($parameters['_controller']))
			throw new \PHPPie\Exception\Exception('No controller defined for this route : '.$request->getURI(), 'PHPPie\Core\Kernel', 'run', 404);
        
		if(!isset($parameters['_action']))
		{
			$data = explode(':', $parameters['_controller']);
			$parameters['_controller'] = $data[0];
			$parameters['_action'] = (isset($data[1])) ? $data[1] : "index";
		}
        
        $request->addGet($parameters);
        
        if(!$this->autoloader->loadClass($parameters['_controller']))
            throw new \PHPPie\Exception\Exception('Controller '.$parameters['_controller'].' doesn\'t exists', 'PHPPie\Core\Kernel', 'run');
            
        $reflectionClass = new \ReflectionClass($parameters['_controller']);
        $controller = $reflectionClass->newInstanceArgs(array($this));
        
        $returnController = $controller->__runAction($parameters['_controller'], $parameters['_action']);
        $defaultView = str_replace('\\', DIRECTORY_SEPARATOR, $parameters['_controller']) . DIRECTORY_SEPARATOR . $parameters['_action'];
        
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
			throw new \PHPPie\Exception\Exception('The controller '.$parameters['_controller'].' has returned a wrong value with the action '.$parameters['_action'].'', 'PHPPie\Core\Kernel', 'run');
		}
		
		$response->send();
    }
    
    public function getPathApp()
    {
        return $this->dirFrontController.DIRECTORY_SEPARATOR.'app';
    }
    
    public function getPathConfig()
    {
        return $this->getPathApp().DIRECTORY_SEPARATOR.'config';
    }
    
    public function getPathCache()
    {
        return $this->getPathApp().DIRECTORY_SEPARATOR.'cache';
    }
    
     public function getPathControllers()
    {
        return $this->getPathApp().DIRECTORY_SEPARATOR.'controllers';
    }
    
    public function getPathModels()
    {
        return $this->getPathApp().DIRECTORY_SEPARATOR.'models';
    }
    
    public function getPathViews()
    {
        return $this->getPathApp().DIRECTORY_SEPARATOR.'views';
    }
    
    public function getPathWeb()
    {
        return $this->dirFrontController.DIRECTORY_SEPARATOR.'web';
    }
    
    public function getPathCss()
    {
        return $this->getPathWeb().DIRECTORY_SEPARATOR.'css';
    }
    
    public function getPathImages()
    {
        return $this->getPathWeb().DIRECTORY_SEPARATOR.'images';
    }
    
    public function getPathJs()
    {
        return $this->getPathWeb().DIRECTORY_SEPARATOR.'js';
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
