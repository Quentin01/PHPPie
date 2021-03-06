<?php

/*
 * Class Request
 * Created on 28/10/11 at 10:54
 */

namespace PHPPie\HTTP;

class Request implements \ArrayAccess{
 	
	protected $get     = array();
	protected $post    = array();
	protected $files   = array();
	protected $server  = array();
	protected $cookies = array();

 	public function __construct()
 	{	
		$this->get     = new Request\Get($_GET);
		$this->post    = new Request\Post($_POST);
		$this->files   = new Request\Files($_FILES);	
		$this->server  = new Request\Server($_SERVER);
		$this->cookies = new Request\Cookies($_COOKIE);
 	}
 	
 	public function __get($name)
 	{
		if($this->offsetExists($name))
			return $this->offsetGet($name);
	}
	
	public function offsetGet($name)
	{
		if($name == "session" && isset($_SESSION))
			return $_SESSION;
			
		if($this->offsetExists($name))
			return $this->$name;
	}
	
	public function offsetExists($name)
	{
		if($name == "session" && isset($_SESSION))
			return true;
			
		return (in_array($name, array('get', 'post', 'files', 'server', 'cookies')));
	}
	
	public function offsetSet($name, $value)
	{
		throw new \PHPPie\Exception\Exception('Read only', 'PHPPie\HTTP\Request', 'offsetSet');
	}
	
	public function offsetUnset($name)
	{
		throw new \PHPPie\Exception\Exception('Read only', 'PHPPie\HTTP\Request', 'offsetUnset');
	}

 	public function getPreviousURI()
 	{
 		return $this->server['HTTP_REFERER'];
 	}

 	public function getURI()
 	{
 		return $this->server['REQUEST_URI'];
 	}
 	
 	public function getRedirectURI()
 	{
		return $this->server['REDIRECT_URL'];
	}
 	
 	public function getCompletURI($page = true)
 	{
		if($this->server['REMOTE_PORT'] != "443")
			$uri = "http:///";
		else
			$uri = "https:///";
			
		$uri .= $this->getHost();
		
		if($uri[strlen($uri) - 1] !== '/')
			$uri .= '/';
		
		if($page)
			$uri .= $this->getRedirectURI();
		
		return str_replace('//', '/', $uri);
	}

	public function isAjaxRequest()
 	{
 		return (isset($this->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
 	}

 	public function getPort()
 	{
 		return $this->server['REMOTE_PORT'];
 	}

	public function getHost()
 	{
  		return $this->server['HTTP_HOST'];
 	}

 	public function getProtocol()
 	{
 		return $this->server['SERVER_PROTOCOL'];
 	}

 	public function getUserAgent()
 	{
 		return $this->server['HTTP_USER_AGENT'];
 	}

 	public function getIp()
 	{
		if(isset($this->server['HTTP_X_FORWARDED_FOR'])){ return $this->server['HTTP_X_FORWARDED_FOR']; }
		elseif(isset($this->server['HTTP_CLIENT_IP'])){ return $this->server['HTTP_CLIENT_IP']; }
 		else{ return $this->server['REMOTE_ADDR']; }
 	}
 }
