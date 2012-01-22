<?php

/*
 * Class routing URI
 * Created on 26/10/2011 at 13:49
 */

namespace PHPPie\Routing;

class Router {
    protected $kernel;
    protected $routes = array();
    protected $idCache = 'router.cache';
    
    public function __construct(\PHPPie\Core\KernelInterface $kernel)
    {
        $this->kernel = $kernel;
        
        if($this->kernel->container->getService('cache')->isFresh($this->idCache, $this->kernel->getPathConfig().DIRECTORY_SEPARATOR.'routing.yml'))
		{
			$data = $this->kernel->container->getService('cache')->get($this->idCache);
			
			foreach($data as $name => $routeData)
            {
				$this->routes[$name] = new Route($routeData['pattern'], $routeData['defaults'], $routeData['requirements'], $routeData['patternRegexp'], $routeData['tokens']);
			}
		}
		else
		{
			$this->loadRoutes();
			$data = array();
			
			foreach($this->routes as $name => $route)
            {
				$data[$name] = array(
					'pattern' => $route->pattern,
					'defaults' => $route->defaults,
					'requirements' => $route->requirements,
					'patternRegexp' => $route->patternRegexp,
					'tokens' => $route->tokens,
				);
			}
			
			$this->kernel->container->getService('cache')->add($this->idCache, $data);
		}
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
                
                if(!isset($data['defaults']))
                {
                    $data['defaults'] = array();
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
