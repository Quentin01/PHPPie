<?php

/*
 * Class routing URI
 * Created on 26/10/2011 at 13:49
 */

namespace PHPPie\Routing;

class Router implements RouterInterface {
    protected $routes  = array();
    protected $urls = array();
    
    protected $idCache = 'router.cache';
    protected $idCacheURL = 'url.cache';
    
    public function __construct()
    {
		$routingFile = \PHPPie\Core\StaticContainer::getService('kernel')->getPathConfig().DIRECTORY_SEPARATOR.'routing.yml';
		
        if(\PHPPie\Core\StaticContainer::getService('cache')->isFresh($this->idCache, $routingFile))
		{
			$data = \PHPPie\Core\StaticContainer::getService('cache')->get($this->idCache);
			
			foreach($data as $name => $routeData)
            {
				$this->routes[$name] = new Route($this, $routeData['name'], $routeData['pattern'], $routeData['defaults'], $routeData['requirements'], $routeData['patternRegexp'], $routeData['tokens']);
			}
		}
		else
		{
			$this->loadRoutes();
			$data = array();
			
			foreach($this->routes as $name => $route)
            {
				$data[$name] = array(
					'name' => $name,
					'pattern' => $route->pattern,
					'defaults' => $route->defaults,
					'requirements' => $route->requirements,
					'patternRegexp' => $route->patternRegexp,
					'tokens' => $route->tokens,
				);
			}
			
			\PHPPie\Core\StaticContainer::getService('cache')->add($this->idCache, $data);
		}
		
		if(\PHPPie\Core\StaticContainer::getService('cache')->isFresh($this->idCacheURL, $routingFile))
		{
			$this->urls = \PHPPie\Core\StaticContainer::getService('cache')->get($this->idCacheURL);
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

                $this->routes[$name] = new Route($this, $name, $data['pattern'], $data['defaults'], $data['requirements']);
            }
        }
    }
    
    public function resolve($uri)
    {
		if(isset($this->urls[$uri]))
		{
			$route = $this->routes[$this->urls[$uri]];
			$route->setDefaultURI($uri);
			
			return $route;
		}
		
        foreach($this->routes as $name => $route)
        {
            if($route->check($uri))
            {
                $route->setDefaultURI($uri);
                
                $this->urls[$uri] = $name;
                \PHPPie\Core\StaticContainer::getService('cache')->add($this->idCacheURL, $this->urls);
                
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
