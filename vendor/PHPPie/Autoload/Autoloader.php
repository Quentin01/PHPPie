<?php

/*
 * Class loading objects that are not found by PHP
 * Created on 23/10/2011 at 9:58
 */

namespace PHPPie\Autoload;

class Autoloader {

    protected $namespaceFallbacks = array();
    protected $prefixFallbacks = array();
    protected $namespaces = array();
    protected $prefixes = array();

    public function __construct() {
        
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
        if ($file = $this->findFile($class)) {
            require $file;
        }
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
        return false;
    }

}

?>
