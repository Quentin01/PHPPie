<?php

/*
 * Core class of Framework
 * Created on 25/10/11 at 11:17
 */

namespace PHPPie\Core;

class Kernel implements KernelInterface {
    protected $debug;
    protected $dirFrontController;
    
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
			$parameters['_action'] = (isset($data[1])) ? $data[1] : null;
		}
        
        $request->addGet($parameters);
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
