<?php

/*
 * Class routing URI
 * Created on 26/10/2011 at 13:49
 */

namespace PHPPie\Routing;

class Router {
    protected $kernel;
    protected $routes = array();
    
    public function __construct(\PHPPie\Core\KernelInterface $kernel)
    {
        $this->kernel = $kernel;
        $this->loadRoutes();
    }
    
    protected function loadRoutes()
    {
        $file = new \PHPPie\File\Yaml($this->kernel->getPathConfig().DIRECTORY_SEPARATOR.'routing.yml');
        $routesData = $file->readData();
        
        if(is_array($routesData))
        {
            foreach($routesData as $name => $data)
            {
                if(!isset($data['requirements']))
                {
                    $data['requirements'] = array();
                }

                $this->routes[$name] = new Route($data['pattern'], $data['defaults'], $data['requirements']);
            }
        }
    }
    
    public function resolve($uri)
    {
        foreach($this->routes as $name => $route)
        {
            if($route->check($uri))
            {
                $route->setDefaultURI($uri);
                return $route;
            }
        }
        return false;
    }
}
?>
