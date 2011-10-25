<?php

/*
 * Core class of Framework
 * Created on 25/10/11 at 11:17
 */

namespace PHPPie\Core;

class Kernel implements KernelInterface {
    protected $debug;
    protected $dirFrontController;
    
    public function __construct($dirFrontController, $debug = false)
    {
        $this->debug = $debug;
        $this->dirFrontController = realpath($dirFrontController);
        
        $this->container = new Container($this);
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
}
?>
