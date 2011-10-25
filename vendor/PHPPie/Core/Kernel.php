<?php

/*
 * Core class of Framework
 * Created on 25/10/11 at 11:17
 */

namespace PHPPie\Core;

class Kernel implements KernelInterface {
    protected $debug;
    protected $dirFrontController;
    
    public function __construct($dirFrontController, $debug = false, \PHPPie\Autoload\Autoloader $autoloader)
    {
        $this->debug = $debug;
        $this->dirFrontController = realpath($dirFrontController);
        
        $this->container = new Container($this, $autoloader);
    }
    
    public function run()
    {
        
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
