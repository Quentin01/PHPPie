<?php

/*
 * Class container of dependency Injection
 * Created on 25/10/11 at 11:17
 */

namespace PHPPie\Core;

class Container { 
    protected $idCache          = 'services.cache';
    
    protected $parameters       = array();
    protected $services         = array();
    protected $registerServices = array();
    
    public function __construct(KernelInterface $kernel, \PHPPie\Autoload\Autoloader $autoloader, \PHPPie\Cache\CacheInterface $cacheManager)
    {
        $this->addService('kernel', 'PHPPie\Core\Kernel', true, array());
        $this->registerServices['kernel'] = $kernel;
        
        $this->addService('cache', 'PHPPie\Cache', true, array());
        $this->registerServices['cache'] = $cacheManager;
        
        $this->addService('autoloader', 'PHPPie\Autoload\Autoloader', true, array());
        $this->registerServices['autoloader'] = $autoloader;
        
        if($cacheManager->isFresh($this->idCache, $this->getService('kernel')->getPathConfig().DIRECTORY_SEPARATOR.'services.xml'))
		{
			$data = $cacheManager->get($this->idCache);
			
			$this->parameters = $data['parameters'];
			$this->services = $data['services'];
		}
		else
		{
			$this->parseFile();
		}
    }
    
    protected function parseFile()
    {
        $servicesFile = new \PHPPie\File\XML($this->getService('kernel')->getPathConfig().DIRECTORY_SEPARATOR.'services.xml');
        $xml = $servicesFile->readData();
        
        $parameters = $xml->parameters;
        if(!empty($parameters))
        {
            foreach($parameters->children() as $parameter)
            {
                $this->addParameter((string) $parameter->attributes()->key, (string) $parameter);
            }
        }
        
        $services = $xml->services;
        if(!empty($services))
        {
            foreach($services->children() as $service)
            {
                $attributes = $service->attributes();
                
                $arguments = array();
                foreach($service->children() as $argument)
                {
                    $arguments[] = (string) $argument;
                }
                
                $this->addService((string) $attributes->id, (string) $attributes->class, (empty($attributes->shared) || $attributes->shared === "true"), $arguments);
            }
        }
        
        $this->getService('cache')->add($this->idCache, array(
			'parameters' => $this->parameters,
			'services' => $this->services,
        ));
    }
    
    public function addParameter($key, $value)
    {
        $this->parameters[trim($key)] = trim($value);
    }
    
    public function hasParameter($key)
    {
        return isset($this->parameters[trim($key)]);
    }
    
    public function getParameter($key)
    {
        if($key[0] === "%")
        {
            $key = substr($key, 1, strlen($key) - 2);
        }
        
        if($key[0] !== "@")
        {
            $key = trim($key);
            if($this->hasParameter($key))
            {
                return $this->parameters[$key];
            }
            else 
            {
                if(0 !== $pos = strpos($key, '.'))
                {
                    $nameService = substr($key, 0, $pos);
                    if($this->hasService($nameService))
                    {
                        $service = $this->getService($nameService);
                        if(method_exists($service, 'getContainerParameters') && !$this->services[$nameService]['hasGetParameters'])
                        {
                            $parameters = $service->getContainerParameters();
                            foreach($parameters as $keyParameter => $valueParameter)
                            {
                                $this->addParameter($nameService.'.'.$keyParameter, $valueParameter);
                            }
                            $this->services[$nameService]['hasGetParameters'] = true;

                            return $this->getParameter($key);
                        }
                    }
                }
                   
                throw new \PHPPie\Exception\Exception('Parameter '.$key.' doesn\'t exists', 'PHPPie\Core\Container', 'getParameter');
            }
        }
        else
        {
            $key = trim(substr($key, 1));
            
            if($this->hasService($key))
                return $this->getService($key);
            else
                throw new \PHPPie\Exception\Exception('Service '.$key.' doesn\'t exists', 'PHPPie\Core\Container', 'getParameter');
        }
    }
    
    public function addService($id, $class, $shared, $arguments = array())
    {
        $this->services[trim($id)] = array(
            'class' => $class,
            'shared' => $shared,
            'arguments' => $arguments,
            'hasGetParameters' => false,
        );
    }
    
    public function hasService($id)
    {
        return isset($this->services[trim($id)]);
    }
    
    protected function hasRegisterService($id)
    {
        return isset($this->registerServices[trim($id)]);
    }
    
    public function getService($id)
    {
        $id = trim($id);
        if($this->hasService($id))
        {
            if($this->services[$id]['shared'])
            {
                if(!$this->hasRegisterService($id))
                    $this->registerService($id);
                
                return $this->registerServices[$id];
            }
            else
            {
                return $this->constructService($id);
            }
        }
        else 
        {
            throw new \PHPPie\Exception\Exception('Service '.$id.' doesn\'t exists', 'PHPPie\Core\Container', 'getService');
        }
    }
    
    protected function constructService($id)
    {
        $service = $this->services[trim($id)];
        
        if($service['class'][0] === "%")
        {
            $service['class'] = $this->getParameter($service['class']);
        }
        
        if($service['class'][0] !== "\\")
        {
            $service['class'] = "\\".$service['class'];
        }
        
        foreach($service['arguments'] as &$argument)
        {
            if($argument[0] === "%" || $argument[0] === "@")
            {
                $argument = $this->getParameter($argument);
            }
        }
        
        if(!$this->getService('autoloader')->loadClass($service['class']))
            throw new \PHPPie\Exception\Exception('Class '.$service['class'].' doesn\'t exists', 'PHPPie\Core\Container', 'constructService');
        
        $reflectionClass = new \ReflectionClass($service['class']);
        return $reflectionClass->newInstanceArgs($service['arguments']);
    }
    
    protected function registerService($id)
    {
        $id = trim($id);
        $this->registerServices[$id] = $this->constructService($id);
    }
}
?>
