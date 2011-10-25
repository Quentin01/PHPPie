<?php

/*
 * Class container of dependency Injection
 * Created on 25/10/11 at 11:17
 */

namespace PHPPie\Core;

class Container { 
    protected $servicesFile;
    
    protected $parameters = array();
    protected $services = array();
    
    public function __construct(KernelInterface $kernel)
    {
        $this->servicesFile = new \PHPPie\File\XML($kernel->getPathConfig().DIRECTORY_SEPARATOR.'services.xml');
        
        $xml = $this->servicesFile->readData();
        
        $parameters = $xml->parameters;
        if(!empty($parameters))
        {
            foreach($parameters->children() as $parameter)
            {
                $this->addParameter((string) $parameter->attributes()->key, (string) $parameter);
            }
        }
        
        echo print_r($this->parameters);
    }
    
    public function addParameter($key, $value)
    {
        $this->parameters[$key] = $value;
    }
    
    public function hasParameter($key)
    {
        return isset($this->parameters[$key]);
    }
    
    public function getParameter($key)
    {
        if($this->hasParameter($key))
            return $parameters[$key];
        else {
            throw new \Exception('Parameter '.$key.' doesn\'t exists');
        }
    }
}
?>
