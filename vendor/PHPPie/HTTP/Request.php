<?php

/*
 * Class Request
 * Created on 28/10/11 at 10:54
 */

namespace PHPPie\HTTP;

class Request {
 	
	protected $get    = array();
	protected $post   = array();
	protected $files  = array();
	protected $server = array();

 	public function __construct()
 	{	
		$this->get    = new Request\Get($_GET);
		$this->post   = new Request\Post($_POST);
		$this->files  = new Request\Files($_FILES);	
		$this->server = new Request\Server($_SERVER);
 	}
 	
 	public function __get($name)
 	{
		if(is_int(array_search($name, array('get', 'post', 'files', 'server'))))
			return $this->$name;
	}

 	public function getPreviousURI()
 	{
 		return $this->server['HTTP_REFERER'];
 	}

 	public function getURI()
 	{
 		return $this->server['REQUEST_URI'];
 	}
 	
 	public function getCompletURI()
 	{
		$uri = "";
		
		if($this->server['REMOTE_PORT'] != "443")
			$uri = "http:///";
		else
			$uri = "https:///";
			
		$uri .= $this->getHost();
		$uri .= '/' . $this->getURI();
		
		return str_replace('//', '/', $uri);
	}

	public function isAjaxRequest()
 	{
 		if (isset($this->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
 		{
 	 		return true;
 	 	}

 	 	else
 		{
 	 		return false;
 		}
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
