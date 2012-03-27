<?php

/*
 * Class Response
 * Created on 25/01/12 at 15:28
 */

namespace PHPPie\HTTP;

class Response {
	protected $status;
	protected $statusText;
	protected $contents;
	protected $headers;
	protected $charset;
	protected $version;
	
	static public $statusTexts = array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
    );
    
    public function __construct($content = '', $status = 200, $headers = array())
    {
		$this->content = $content;
		$this->setStatusCode($status);
		$this->headers = $headers;
		$this->setCharset("UTF-8");
		$this->setProtocolVersion("1.0");
	}
	
	public function send()
	{
		if (headers_sent()) {
			return;
		}
		
		if (!$this->hasHeader('Content-Type')) {
            $this->setHeader('Content-Type', 'text/html; charset='.$this->charset);
        } elseif ('text/' === substr($this->getHeader('Content-Type'), 0, 5) && false === strpos($this->getHeader('Content-Type'), 'charset')) {
            $this->setHeader('Content-Type', $this->getHeader('Content-Type').'; charset='.$$this->charset);
        }
        
        if ($this->hasHeader('Transfer-Encoding')) {
            $this->removeHeader('Content-Length');
        }
		
		header(sprintf('HTTP/%s %s %s', $this->version, $this->statusCode, $this->statusText));
		 
		foreach ($this->headers as $name => $value) {
			header($name.': '.$value, false);
        }
        
        echo $this->content;
        
        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }
	}
	
	public function setContent($content)
    {
        $this->content = (string) $content;
    }
    
    public function getContent()
    {
		return $this->content;
	}
	
	public function setStatusCode($status)
	{
		if(!isset(self::$statusTexts[$status]))
			throw new \PHPPie\Exception\Exception('Invalid HTTP code : '.$status, 'PHPPie\HTTP\Response', 'setStatusCode');
			
		$this->statusCode = $status;
		$this->statusText = self::$statusTexts[$status];
	}
	
	public function setHeader($name, $value)
	{
		return ($this->headers[$name] = $value);
	}
	
	public function getHeader($name)
	{
		if($this->hasHeader($name))
			return $this->headers[$name];
		else
			return false;
	}
	
	public function hasHeader($name)
	{
		return isset($this->headers[$name]);
	}
	
	public function removeHeader($name)
	{
		unset($this->headers[$name]);
	}
	
	public function getStatusCode()
    {
        return $this->statusCode;
    }
    
    public function getStatusText()
    {
		return $this->statusText;
	}
    
    public function setCharset($charset)
    {
        $this->charset = $charset;
    }
    
    public function getCharset()
    {
        return $this->charset;
    }
    public function setProtocolVersion($version)
    {
        $this->version = $version;
    }

    public function getProtocolVersion()
    {
        return $this->version;
    }
}
?>
