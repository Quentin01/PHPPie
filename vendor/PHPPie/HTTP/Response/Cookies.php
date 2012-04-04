<?php

/*
 * Class Cookie for Response
 * Created on 04/04/12 at 13:28
 */

namespace PHPPie\HTTP\Response;

class Cookies {
	protected $cookies = array();
	
	public function __construct() {}
	
	public function add($name, $cookie) {
		if(is_array($cookie))
			$this->cookies[$name] = $cookie;
		else
			$this->cookies[$name] = array('value' => $cookie);
	}
	
	public function remove($name) {
		setcookie($name);
		
		if(isset($this->cookies[$name]))
			unset($this->cookies[$name]);
	}
	
	public function send() {
		foreach($this->cookies as $name => $cookie)
		{
			setcookie(
				$name, 
				(isset($cookie['value'])) ? $cookie['value'] : "", 
				(isset($cookie['expire'])) ? $cookie['expire'] : 0, 
				(isset($cookie['path'])) ? $cookie['path'] : "/", 
				(isset($cookie['domain'])) ? $cookie['domain'] : \PHPPie\Core\StaticContainer::getService('http.request')->getHost(), 
				(isset($cookie['secure'])) ? $cookie['secure'] : false, 
				(isset($cookie['httponly'])) ? $cookie['httponly'] : false
			);
		}
	}
}
