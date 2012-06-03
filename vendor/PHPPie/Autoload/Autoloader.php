<?php

/*
 * Class loading objects that are not found by PHP
 * Created on 23/10/2011 at 9:58
 */

namespace PHPPie\Autoload;

class Autoloader {

    protected $namespaceFallbacks = array();
    protected $prefixFallbacks    = array();
    protected $namespaces         = array();
    protected $prefixes           = array();
    protected $directories        = array();
    
    protected $cache              = array();
    protected $cacheManager       = null;
    protected $idCache            = 'autoload.cache';

    public function __construct() {
        
    }
    
    public function enableCache(\PHPPie\Cache\CacheInterface $cacheManager)
    {
		$this->cacheManager = $cacheManager;
		
		if($this->cacheManager->exists($this->idCache))
			$this->cache = array_merge($this->cache, $this->cacheManager->get($this->idCache));
	}
	
	public function save()
	{
		$this->cacheManager->add($this->idCache, $this->cache);
	}

    public function register() {
        spl_autoload_register(array($this, 'loadClass'), true, true);
    }

    public function registerNamespaceFallbacks(array $dirs) {
        $this->namespaceFallbacks = array_merge($dirs, $this->namespaceFallbacks);
    }

    public function registerPrefixFallbacks(array $dirs) {
        $this->prefixFallbacks = array_merge($dirs, $this->prefixFallbacks);
    }
    
    public function registerDirectories(array $dirs) {
       $this->directories = array_merge($dirs, $this->directories);
    }

    public function registerNamespaces(array $namespaces) {
        foreach ($namespaces as $namespace => $locations) {
            $this->namespaces[$namespace] = (array) $locations;
        }
    }

    public function registerNamespace($namespace, $path) {
        $this->namespaces[$namespace] = (array) $path;
    }

    public function registerPrefixes(array $classes) {
        foreach ($classes as $prefix => $locations) {
            $this->prefixes[$prefix] = (array) $locations;
        }
    }

    public function registerPrefix($prefix, $paths) {
        $this->prefixes[$prefix] = (array) $paths;
    }

    public function loadClass($class) {
		if(isset($this->cache[$class]))
		{
			require_once $this->cache[$class];
            return true;
		}
		
        if ($file = $this->findFile($class)) {
			$this->cache[$class] = $file;
            require_once $file;
            return true;
        }
        return false;
    }

    public function findFile($class) {
        if ('\\' == $class[0]) {
            $class = substr($class, 1);
        }

        if (false !== $pos = strrpos($class, '\\')) {
            $namespace = substr($class, 0, $pos);
            foreach ($this->namespaces as $ns => $dirs) {
                if (0 !== strpos($namespace, $ns)) {
                    continue;
                }
                
                foreach ($dirs as $dir) {
                    $className = substr($class, $pos + 1);
                    $namespace = substr($namespace, strlen($ns) + 1);
                    $file = $dir . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR . str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
                    if (file_exists($file)) {
                        return $file;
                    }
                }
            }

            foreach ($this->namespaceFallbacks as $dir) {
                $file = $dir . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
                if (file_exists($file)) {
                    return $file;
                }
            }
        } else {
            foreach ($this->prefixes as $prefix => $dirs) {
                if (0 !== strpos($class, $prefix)) {
                    continue;
                }

                foreach ($dirs as $dir) {
                    $file = $dir . DIRECTORY_SEPARATOR . str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
                    if (file_exists($file)) {
                        return $file;
                    }

                    $file = $dir . DIRECTORY_SEPARATOR . str_replace('_', DIRECTORY_SEPARATOR, substr($class, strlen($prefix))) . '.php';
                    if (file_exists($file)) {
                        return $file;
                    }
                }
            }

            foreach ($this->prefixFallbacks as $dir) {
                $file = $dir . DIRECTORY_SEPARATOR . str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
                if (file_exists($file)) {
                    return $file;
                }
            }
        }

        foreach ($this->directories as $dir) {
			$file = $dir . DIRECTORY_SEPARATOR . $class . '.php';
			
            if (file_exists($file)) {
				return $file;
			}
			
			$file = $dir . DIRECTORY_SEPARATOR . str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
			
			if (file_exists($file)) {
				return $file;
			}
			
			$file = $dir . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
			
			if (file_exists($file)) {
				return $file;
			}
		}
        
        return false;
    }

}

?>
