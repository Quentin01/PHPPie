<?php

namespace PHPPie\Event\Listeners;

class Firewall extends \PHPPie\Event\Listener {
	protected $routes = array();
	protected $controllers = array();
	
	public function onControllerAndActionDefined(&$route, &$controller, &$action, &$view) {
		foreach($this->routes as $name => $parameters) {
			if(preg_match('#^' . str_replace('*', '(.*)', $name) . '$#', $route->name)) {
				if($this->callCallBack($parameters['callback'])) {
					$this->applyParameters($route, $controller, $action, $view, $parameters['parameters']);
				}
			}
		}
		
		foreach($this->controllers as $name => $parameters) {
			if(preg_match('#^' . str_replace('*', '(.*)', $name) . '$#', $controller)) {
				if($this->callCallBack($parameters['callback'])) {
					$this->applyParameters($route, $controller, $action, $view, $parameters['parameters']);
				}
			}
		}
	}
	
	public function setRoute($name, $callback, array $parameters = array()) {
		$this->routes[$name]['parameters'] = $parameters;
		$this->routes[$name]['callback'] = $callback;
		return $this;
	}
	
	public function setController($name, $callback, array $parameters = array()) {
		$this->controllers[$name]['parameters'] = $parameters;
		$this->controllers[$name]['callback'] = $callback;
		return $this;
	}
	
	protected function callCallBack($callback) {
		return call_user_func($callback);
	}
	
	protected function applyParameters(&$route, &$controller, &$action, &$view, $parameters) {	
		foreach($parameters as $key => $value) {
			if(is_callable($value)) {
				$parameters[$key] = call_user_func($value);
			}
		}
		
		if(isset($parameters['controller']) && !isset($parameters['action'])) {
			$data = \PHPPie\Core\StaticContainer::getService('kernel')->findControllerAndAction($parameters['controller']);
			$controller = $data['controller'];
			$action = $data['action'];
			
			$view = "";
		} elseif(isset($parameters['controller']) && isset($parameters['action'])) {
			$controller = $parameters['controller'];
			$action = $parameters['action'];
			
			$view = "";
		}
		
		if(isset($parameters['redirect'])) {
			if(is_string($parameters['redirect'])) {
				\PHPPie\Core\StaticContainer::getService('http.response')->redirect(\PHPPie\Core\StaticContainer::getService('router')->getURI($parameters['redirect']));
				exit();
			} elseif(is_array($parameters['redirect'])) {
				PHPPie\Core\StaticContainer::getService('http.response')->redirect(\PHPPie\Core\StaticContainer::getService('router')->getURI($parameters['redirect']['name'], $parameters['redirect']['slugs']));
				exit();
			}
		}
	}
}
