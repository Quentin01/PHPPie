<?php

/*
 * Class routing URI
 * Created on 26/10/2011 at 13:49
 */

namespace PHPPie\Routing;

class Router {
    protected $routes  = array();
    protected $idCache = 'router.cache';
    
    public function __construct()
    {
        if(\PHPPie\Core\StaticContainer::getService('cache')->isFresh($this->idCache, \PHPPie\Core\StaticContainer::getService('kernel')->getPathConfig().DIRECTORY_SEPARATOR.'routing.yml'))
		{
			$data = \PHPPie\Core\StaticContainer::getService('cache')->get($this->idCache);
			
			foreach($data as $name => $routeData)
            {
				$this->routes[$name] = new Route($this, $routeData['pattern'], $routeData['defaults'], $routeData['requirements'], $routeData['patternRegexp'], $routeData['tokens']);
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
			
			\PHPPie\Core\StaticContainer::getService('cache')->add($this->idCache, $data);
		}
    }
    
    protected function loadRoutes()
    {
        $file = new \PHPPie\File\Yaml(\PHPPie\Core\StaticContainer::getService('kernel')->getPathConfig().DIRECTORY_SEPARATOR.'routing.yml');
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

                $this->routes[$name] = new Route($this, $data['pattern'], $data['defaults'], $data['requirements']);
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
    
    public function getURI($name, $slugs = array())
    {
		if(isset($this->routes[$name]))
			return $this->routes[$name]->getURI($slugs);
		else
			return false;
	}
}
?>
